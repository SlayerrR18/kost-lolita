@extends('layouts.main')

@section('title', 'Manajemen Kamar')

@push('css')
<style>
    .kost-container {
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

    .kost-card {
        background: #fff;
        border-radius: 24px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        padding: 24px;
    }

    .btn-add {
        background: #1a7f5a;
        color: #fff;
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-add:hover {
        background: #156c4a;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(26,127,90,0.15);
    }

    .table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .table th {
        background: #f8fafc;
        color: #64748b;
        font-weight: 600;
        padding: 16px;
        text-align: left;
        border-bottom: 2px solid #e9eef3;
    }

    .table td {
        padding: 16px;
        border-bottom: 1px solid #e9eef3;
        vertical-align: middle;
    }

    .table tr:hover {
        background: #f1f5f9;
    }

    .status-badge {
        padding: 6px 16px;
        border-radius: 12px;
        font-size: 0.9rem;
        font-weight: 500;
    }

    .status-terisi {
        background: #dcfce7;
        color: #166534;
    }

    .status-kosong {
        background: #fee2e2;
        color: #991b1b;
    }

    .action-btn {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        margin: 0 4px;
        transition: all 0.2s;
    }

    .btn-edit {
        background: #fef3c7;
        color: #92400e;
    }

    .btn-delete {
        background: #fee2e2;
        color: #991b1b;
    }

    .room-image {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 12px;
    }

        .modal-content {
        border: none;
        border-radius: 16px;
    }

    .modal .btn {
        padding: 8px 24px;
        border-radius: 8px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    .modal .btn:hover {
        transform: translateY(-1px);
    }

    .modal .btn-secondary {
        background: #f1f5f9;
        color: #64748b;
        border: none;
    }

    .modal .btn-secondary:hover {
        background: #e2e8f0;
        color: #475569;
    }

    .modal .btn-danger {
        background: #fee2e2;
        color: #991b1b;
        border: none;
    }

    .modal .btn-danger:hover {
        background: #fecaca;
        box-shadow: 0 4px 12px rgba(153,27,27,0.1);
    }

</style>
@endpush

@section('content')
<div class="kost-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="page-title">Manajemen Kamar</h1>
        <a href="{{ route('admin.kost.create') }}" class="btn btn-add">
            <i data-feather="plus-circle" class="me-2"></i>
            Tambah Kamar
        </a>
    </div>

    <div class="kost-card">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nomor Kamar</th>
                        <th>Fasilitas</th>
                        <th>Foto</th>
                        <th>Harga</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kosts as $kost)
                    <tr>
                        <td class="fw-bold">{{ $kost->nomor_kamar }}</td>
                        <td>
                            @if(is_string($kost->fasilitas))
                                {{ implode(', ', json_decode($kost->fasilitas, true) ?? []) }}
                            @elseif(is_array($kost->fasilitas))
                                {{ implode(', ', $kost->fasilitas) }}
                            @else
                                -
                            @endif
                        </td>
                        <td>
                           @if(is_array($kost->foto) && count($kost->foto) > 0)
                                <img src="{{ asset('storage/' . $kost->foto[0]) }}" alt="Foto Kamar" class="room-image">
                            @elseif(is_string($kost->foto) && !empty($kost->foto))
                                <img src="{{ asset('storage/' . $kost->foto) }}" alt="Foto Kamar" class="room-image">
                            @else
                                <span class="text-muted">Tidak ada foto</span>
                            @endif
                        </td>
                        <td class="fw-bold">Rp {{ number_format($kost->harga, 0, ',', '.') }}</td>
                        <td>
                            <span class="status-badge {{ $kost->status == 'Terisi' ? 'status-terisi' : 'status-kosong' }}">
                                {{ $kost->status }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.kost.edit', $kost->id) }}"
                               class="action-btn btn-edit"
                               title="Edit">
                                <i data-feather="edit-2"></i>
                            </a>
                            <button type="button"
                                    class="action-btn btn-delete"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteModal{{ $kost->id }}"
                                    title="Hapus">
                                <i data-feather="trash-2"></i>
                            </button>

                            <!-- Delete Modal -->
                            <div class="modal fade" id="deleteModal{{ $kost->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-body text-center p-5">
                                            <div class="text-danger mb-4">
                                                <i data-feather="alert-circle" style="width: 64px; height: 64px;"></i>
                                            </div>
                                            <h4 class="text-danger mb-3">Hapus Kamar</h4>
                                            <p class="mb-4">Apakah Anda yakin ingin menghapus kamar nomor {{ $kost->nomor_kamar }}? Data yang sudah dihapus tidak dapat dikembalikan.</p>
                                            <form action="{{ route('admin.kost.destroy', $kost->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-secondary px-4 me-2" data-bs-dismiss="modal">
                                                    <i data-feather="x" class="me-2"></i>
                                                    Batal
                                                </button>
                                                <button type="submit" class="btn btn-danger px-4">
                                                    <i data-feather="trash-2" class="me-2"></i>
                                                    Ya, Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
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

        // Reinitialize feather icons after modal shows
        const deleteModals = document.querySelectorAll('[id^="deleteModal"]');
        deleteModals.forEach(modal => {
            modal.addEventListener('shown.bs.modal', function() {
                feather.replace();
            });
        });
    });
</script>
@endpush
