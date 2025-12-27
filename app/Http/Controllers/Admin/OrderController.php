<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Income;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // 1. IMPROVE INDEX: Filter agar tampil 1 data relevan per user
    public function index(Request $request)
    {
        $status = $request->query('status');

        $query = Order::with(['user', 'room'])->latest();

        if ($status) {
            $query->where('status', $status);
        } else {
            // JIKA TIDAK ADA FILTER STATUS KHUSUS:
            // Sembunyikan yang 'finished' (kontrak lama) dan 'rejected'
            // Jadi yang tampil hanya: Pending (Baru masuk) atau Approved (Sedang Kost)
            $query->whereNotIn('status', ['finished', 'rejected']);
        }

        $orders = $query->get();

        return view('admin.orders.index', compact('orders', 'status'));
    }

    // detail pesanan
    public function show(Order $order)
    {
        $order->load(['user', 'room']);

        // Ambil history pesanan lain
        $history = Order::with('room')
            ->where('user_id', $order->user_id)
            ->where('id', '!=', $order->id)
            ->orderByDesc('created_at')
            ->get();

        // HITUNG TOTAL DURASI (Akumulasi)
        // Menjumlahkan semua durasi dari order yang statusnya 'approved' atau 'finished'
        $totalDuration = Order::where('user_id', $order->user_id)
            ->whereIn('status', ['approved', 'finished'])
            ->sum('rent_duration');

        // Jika order saat ini statusnya 'approved', dia sudah termasuk di hitungan atas.
        // Jika 'pending', kita bisa menambahkannya secara visual nanti sebagai "+ X bulan".

        return view('admin.orders.show', compact('order', 'history', 'totalDuration'));
    }

    // update status pesanan (terima / tolak)
 public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status'     => 'required|in:approved,rejected',
            'admin_note' => 'nullable|string',
        ]);

        // Gunakan Transaction agar data aman
        \Illuminate\Support\Facades\DB::beginTransaction();

        try {
            // 1. Update status order saat ini
            $order->update([
                'status'     => $request->status,
                'admin_note' => $request->admin_note,
            ]);

            // =========================================================
            // LOGIKA JIKA DISETUJUI (APPROVED)
            // =========================================================
            if ($request->status === 'approved') {

                // A. Update kamar yang dipesan menjadi 'occupied'
                if ($order->room) {
                    $order->room->update(['status' => 'occupied']);
                }

                // B. Buat record Pemasukan (Income) dengan PERHITUNGAN BENAR
                // PERBAIKAN: Harga dikali Durasi
                $pricePerMonth = $order->room->price ?? 0;
                $duration      = $order->rent_duration ?? 1; // Default 1 bulan jika null
                $totalAmount   = $pricePerMonth * $duration;

                Income::create([
                    'source'         => 'Sewa Kamar ' . ($order->room->room_number ?? '-'),
                    'description'    => 'Penyewa: ' . $order->full_name . ' | Durasi: ' . $order->rent_duration . ' bulan',
                    'amount'         => $totalAmount, // Menggunakan total hasil perkalian
                    'date'           => now()->toDateString(),
                    'category'       => 'room_rent',
                    'payment_method' => 'transfer',
                    'reference'      => 'ORD-' . $order->id,
                    'order_id'       => $order->id,
                    'bukti_transfer' => $order->transfer_proof_path ?? null,
                ]);

                // C. LOGIKA PERPANJANGAN (HANDLE PARENT ORDER)
                if ($order->parent_order_id) {
                    $parent = Order::find($order->parent_order_id);

                    if ($parent) {
                        // 1. Tandai kontrak lama sebagai 'finished'
                        // (Pastikan kolom status di DB sudah diubah jadi VARCHAR, bukan ENUM)
                        $parent->update(['status' => 'finished']);

                        // 2. Jika Perpanjangan + Ganti Kamar (extension_change)
                        if ($order->type === 'extension_change') {
                            // Kosongkan kamar lama (milik parent)
                            if ($parent->room) {
                                $parent->room->update(['status' => 'available']);
                            }
                        }
                    }
                }
            }

            // =========================================================
            // LOGIKA JIKA DITOLAK (REJECTED)
            // =========================================================
            if ($request->status === 'rejected') {
                // Cek apakah kamar ini punya order lain yang aktif (approved) selain order ini
                $isOccupiedByOthers = Order::where('room_id', $order->room_id)
                    ->where('status', 'approved')
                    ->where('id', '!=', $order->id)
                    ->exists();

                // Jika tidak ada yang menempati, kembalikan jadi available
                if (!$isOccupiedByOthers && $order->room) {
                    $order->room->update(['status' => 'available']);
                }
            }

            \Illuminate\Support\Facades\DB::commit(); // Simpan perubahan permanen

            return redirect()
                ->route('admin.orders.show', $order)
                ->with('success', 'Status pesanan berhasil diperbarui.');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack(); // Batalkan jika error
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
