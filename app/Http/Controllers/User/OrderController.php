<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Income;
use App\Models\Order;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;

class OrderController extends Controller
{
    public function create(Room $room)
    {
        $user = Auth::user();

        // 1. CEGAH BOOKING GANDA
        if ($user->hasActiveContract()) {
            return redirect()->route('user.contract.index')
                ->with('error', 'Anda sudah memiliki kamar aktif. Silakan kelola kontrak Anda di sini.');
        }

        return view('user.orders.create', [
            'room' => $room,
            'user' => $user,
        ]);
    }

    public function store(Request $request, Room $room)
    {
        $user = Auth::user();

        if ($user->hasActiveContract()) {
            return redirect()->route('user.contract.index')
                ->with('error', 'Gagal memproses. Anda sudah memiliki kontrak aktif.');
        }

        $request->validate([
            'full_name'     => 'required|string|max:255',
            'phone'         => 'required|string|max:30',
            'address'       => 'required|string',
            'id_number'     => 'required|string|max:50',
            'id_photo'      => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'rent_duration' => 'required|integer|min:1',
            'start_date'    => 'required|date|after_or_equal:today',
        ]);

        $idPhotoPath = $request->file('id_photo')->store('id_cards', 'public');

        $order = Order::create([
            'user_id'            => $user->id,
            'room_id'            => $room->id,
            'email'              => $user->email,
            'full_name'          => $request->full_name,
            'phone'              => $request->phone,
            'address'            => $request->address,
            'id_number'          => $request->id_number,
            'id_photo_path'      => $idPhotoPath,
            'rent_duration'      => $request->rent_duration,
            'start_date'         => $request->start_date,
            'transfer_proof_path'=> '',
            'status'             => 'pending',
        ]);

        return redirect()
            ->route('user.orders.payment', $order)
            ->with('success', 'Pesanan berhasil dibuat. Silakan lanjutkan ke pembayaran.');
    }

    public function payment(Order $order)
    {
        $user = Auth::user();

        if ($order->user_id !== $user->id) {
            abort(403);
        }

        if ($order->status !== 'pending') {
            return redirect()->route('user.orders.show', $order);
        }

        $order->load('room');

        try {
            $snapToken = $this->createMidtransSnapToken($order);
        } catch (\Throwable $exception) {
            Log::error('Midtrans Snap token generation failed for order ' . $order->id . ': ' . $exception->getMessage());

            return redirect()->route('user.orders.show', $order)
                ->with('error', 'Layanan pembayaran saat ini sedang bermasalah. Silakan coba lagi sedikit lagi atau hubungi admin.');
        }

        $snapUrl = config('midtrans.is_production')
            ? 'https://app.midtrans.com/snap/snap.js'
            : 'https://app.sandbox.midtrans.com/snap/snap.js';

        return view('user.orders.payment', compact('order', 'snapToken', 'snapUrl'));
    }

    public function completePayment(Request $request, Order $order)
    {
        $user = Auth::user();

        if ($order->user_id !== $user->id) {
            abort(403);
        }

        $this->setMidtransConfig();
        $statusResponse = Transaction::status('ORD-' . $order->id);
        $transactionStatus = data_get($statusResponse, 'transaction_status');

        if (in_array($transactionStatus, ['capture', 'settlement'])) {
            if ($order->status !== 'approved') {
                $order->update(['status' => 'approved']);

                if ($order->room) {
                    $order->room->update(['status' => 'occupied']);
                }

                $this->createIncomeForOrder($order);
            }
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            $order->update(['status' => 'rejected']);
        } else {
            $order->update(['status' => 'pending']);
        }

        return response()->json(['transaction_status' => $transactionStatus]);
    }

    private function createMidtransSnapToken(Order $order): string
    {
        $this->setMidtransConfig();

        $roomPrice = $order->room->price ?? 0;
        $totalAmount = $roomPrice * ($order->rent_duration ?? 1);

        $transactionDetails = [
            'order_id' => 'ORD-' . $order->id,
            'gross_amount' => $totalAmount,
        ];

        $customerDetails = [
            'first_name' => $order->full_name,
            'email' => $order->email,
            'phone' => $order->phone,
        ];

        $itemDetails = [
            [
                'id' => $order->room->id ?? 'room-' . $order->id,
                'price' => $totalAmount,
                'quantity' => 1,
                'name' => 'Sewa Kamar ' . ($order->room->room_number ?? 'Kost'),
            ],
        ];

        return Snap::getSnapToken([
            'transaction_details' => $transactionDetails,
            'customer_details' => $customerDetails,
            'item_details' => $itemDetails,
        ]);
    }

    private function setMidtransConfig(): void
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = filter_var(config('midtrans.is_production'), FILTER_VALIDATE_BOOLEAN);
        Config::$isSanitized = filter_var(config('midtrans.is_sanitized'), FILTER_VALIDATE_BOOLEAN);
        Config::$is3ds = filter_var(config('midtrans.is_3ds'), FILTER_VALIDATE_BOOLEAN);
    }

    private function createIncomeForOrder(Order $order): void
    {
        if (Income::where('order_id', $order->id)->exists()) {
            return;
        }

        $pricePerMonth = $order->room->price ?? 0;
        $totalAmount = $pricePerMonth * ($order->rent_duration ?? 1);

        Income::create([
            'source' => 'Sewa Kamar ' . ($order->room->room_number ?? '-'),
            'description' => 'Penyewa: ' . $order->full_name . ' | Durasi: ' . $order->rent_duration . ' bulan',
            'amount' => $totalAmount,
            'date' => now()->toDateString(),
            'category' => 'room_rent',
            'payment_method' => 'midtrans',
            'reference' => 'ORD-' . $order->id,
            'order_id' => $order->id,
            'bukti_transfer' => null,
        ]);
    }

    public function index()
    {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)
            ->with('room')
            ->latest()
            ->get();

        return view('user.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $user = Auth::user();

        if ($order->user_id !== $user->id) {
            abort(403);
        }

        return view('user.orders.show', compact('order'));
    }
}
