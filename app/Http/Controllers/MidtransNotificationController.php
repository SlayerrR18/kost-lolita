<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Models\Order;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Notification;

class MidtransNotificationController extends Controller
{
    public function handle(Request $request)
    {
        $this->setMidtransConfig();

        $notification = new Notification();
        $transactionStatus = $notification->transaction_status;
        $orderIdString = $notification->order_id;

        if (!is_string($orderIdString) || !str_starts_with($orderIdString, 'ORD-')) {
            return response()->json(['error' => 'Invalid order id'], 400);
        }

        $orderId = intval(str_replace('ORD-', '', $orderIdString));
        $order = Order::with('room')->find($orderId);

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

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
        } elseif ($transactionStatus === 'pending') {
            $order->update(['status' => 'pending']);
        }

        return response()->json(['success' => true]);
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
}
