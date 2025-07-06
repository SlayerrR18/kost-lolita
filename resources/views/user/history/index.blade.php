{{-- filepath: resources/views/user/history/index.blade.php --}}
@extends('layouts.user')

@push('css')
<style>
.history-container {
    padding: 32px;
    background: #f6f8fa;
    min-height: 100vh;
}
.page-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #1a7f5a;
    margin-bottom: 24px;
}
.history-card {
    background: #fff;
    border-radius: 24px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.05);
    padding: 24px;
}
.status-badge {
    padding: 6px 16px;
    border-radius: 12px;
    font-size: 0.9rem;
    font-weight: 500;
}
.status-pemasukan {
    background: #dcfce7;
    color: #166534;
}
.status-pengeluaran {
    background: #fee2e2;
    color: #991b1b;
}
.btn-bukti {
    padding: 6px 14px;
    border-radius: 8px;
    font-size: 0.95rem;
    border: 1px solid #1a7f5a;
    color: #1a7f5a;
    background: #f6f8fa;
    transition: all 0.2s;
}
.btn-bukti:hover {
    background: #1a7f5a;
    color: #fff;
}
.empty-state {
    padding: 24px;
}
.empty-state-icon {
    width: 32px;
    height: 32px;
    color: #94a3b8;
}
.empty-state-text {
    color: #64748b;
    margin: 0;
}
</style>
@endpush

@section('content')
<div class="history-container">
    <h1 class="page-title">Riwayat Transaksi Anda</h1>
    <div class="history-card">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>No Kamar</th>
                        <th>Nama Transaksi</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Bukti</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                    <tr>
                        <td>{{ $transaction->kost->nomor_kamar ?? '-' }}</td>
                        <td>{{ $transaction->nama_transaksi }}</td>
                        <td>{{ $transaction->tanggal_transaksi->format('d/m/Y') }}</td>
                        <td>Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
                        <td>
                            @if($transaction->bukti_pembayaran)
                                <a href="{{ asset('storage/' . $transaction->bukti_pembayaran) }}" target="_blank" class="btn btn-bukti">
                                    <i data-feather="eye"></i> Lihat
                                </a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <div class="empty-state">
                                <i data-feather="inbox" class="empty-state-icon mb-2"></i>
                                <p class="empty-state-text">Belum ada data transaksi</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        feather.replace();
    });
</script>
@endpush
