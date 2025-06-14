<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Financial;
use App\Models\Kost;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class FinancialController extends Controller
{
    public function index()
    {
        $transactions = Financial::with('kost')
            ->orderBy('tanggal_transaksi', 'desc')
            ->get();
        $kosts = Kost::all(); 

        return view('admin.financial.index', compact('transactions', 'kosts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kost_id' => 'required|exists:kosts,id',
            'nama_transaksi' => 'required|string',
            'tanggal_transaksi' => 'required|date',
            'total' => 'required|numeric',
            'status' => 'required|in:Pemasukan,Pengeluaran',
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:5120'
        ]);

        try {
            if ($request->hasFile('bukti_pembayaran')) {
                $file = $request->file('bukti_pembayaran');
                $path = $file->store('bukti-pembayaran', 'public');
                $validated['bukti_pembayaran'] = $path;
            }

            Financial::create($validated);

            return redirect()
                ->route('admin.financial.index')
                ->with('success', 'Transaksi berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(Financial $financial)
    {
        try {
            // Delete old image if exists
            if ($financial->bukti_pembayaran) {
                Storage::disk('public')->delete($financial->bukti_pembayaran);
            }

            $financial->delete();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus transaksi: ' . $e->getMessage()
            ], 500);
        }
    }

    public static function recordIncome($user, $kost)
    {
        return Financial::create([
            'kost_id' => $kost->id,
            'nama_transaksi' => 'Pembayaran Kost - ' . $user->name,
            'tanggal_transaksi' => now(),
            'total' => $kost->harga,
            'status' => 'Pemasukan',
            'bukti_transaksi' => null
        ]);
    }

    public static function getTotalIncome()
    {
        return Financial::where('status', 'Pemasukan')
                       ->sum('total');
    }

    public function confirmOrder(Order $order)
    {
        try {
            DB::beginTransaction();

            // Create financial record
            Financial::create([
                'kost_id' => $order->kost_id,
                'nama_transaksi' => 'Pembayaran Kamar ' . $order->kost->nomor_kamar,
                'tanggal_transaksi' => now(),
                'total' => $order->kost->harga * $order->duration,
                'status' => 'Pemasukan',
                'bukti_pembayaran' => $order->bukti_pembayaran // Ubah dari bukti_transaksi
            ]);

            // Update order status
            $order->update(['status' => 'confirmed']);

            // Create user account
            $user = User::create([
                'name' => $order->name,
                'email' => $order->email,
                'password' => Hash::make('password123'),
                'role' => 'user',
                'phone' => $order->phone,
                'address' => $order->alamat,
                'kost_id' => $order->kost_id
            ]);

            // Update kost status
            $order->kost->update([
                'status' => 'Terisi',
                'penghuni' => $user->id
            ]);

            // Link order to user
            $order->update(['user_id' => $user->id]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dikonfirmasi',
                'data' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'room_number' => $order->kost->nomor_kamar,
                    'duration' => $order->duration,
                    'tanggal_masuk' => $order->tanggal_masuk,
                    'tanggal_keluar' => $order->tanggal_keluar
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengkonfirmasi pesanan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function pendingOrders()
    {
        $pendingOrders = Order::where('status', 'pending')
            ->with('kost')
            ->latest()
            ->get();

        return view('admin.financial.pending-orders', compact('pendingOrders'));
    }
}
