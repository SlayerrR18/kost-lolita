@extends('layouts.main')

@section('title', 'Manajemen Kamar')

@push('css')
<style>
    /* Base Styles */
    :root {
        --primary: #1a7f5a;
        --primary-light: #16c79a;
        --danger: #dc2626;
        --warning: #92400e;
        --gray-100: #f3f4f6;
        --gray-200: #e5e7eb;
        --gray-600: #4b5563;
    }

    /* Container & Layout */
    .kost-container {
        padding: 2rem;
        background: var(--gray-100);
        min-height: 100vh;
    }

    /* Enhanced Header Section */
    .page-header {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .search-filter-container {
        display: flex;
        gap: 1rem;
        margin-top: 1rem;
    }

    .search-box {
        flex: 1;
        position: relative;
    }

    .search-box i {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--gray-600);
    }

    .search-input {
        width: 100%;
        padding: 0.75rem 1rem 0.75rem 2.5rem;
        border: 1px solid var(--gray-200);
        border-radius: 8px;
        font-size: 0.95rem;
        background: #f8fafc;
        border: 2px solid #e2e8f0;
        transition: all 0.3s ease;
    }

    .search-input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(26,127,90,0.1);
    }

    .filter-box {
        display: flex;
        gap: 0.5rem;
    }

    .filter-select {
        padding: 0.75rem 1rem;
        border: 1px solid var(--gray-200);
        border-radius: 8px;
        min-width: 150px;
        background: #f8fafc;
        border: 2px solid #e2e8f0;
        transition: all 0.3s ease;
    }

    .filter-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(26,127,90,0.1);
    }

    /* Enhanced Table */
    .kost-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        overflow: hidden;
    }

    .table {
        margin: 0;
    }

    .table th {
        background: #f8fafc;
        padding: 1rem;
        font-size: 0.9rem;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .table td {
        padding: 1rem;
        vertical-align: middle;
    }

    .room-image {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 12px;
        transition: transform 0.2s;
        cursor: pointer;
    }

    .room-image:hover {
        transform: scale(1.05);
    }

    /* Enhanced Status Badges */
    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.9rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .status-terisi {
        background: #dcfce7;
        color: #166534;
    }

    .status-kosong {
        background: #fee2e2;
        color: #991b1b;
    }

    /* Improved Action Buttons */
    .action-btn {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
    }

    .action-btn:hover {
        transform: translateY(-2px);
    }

    /* Enhanced Modal */
    .delete-modal-icon {
        width: 80px;
        height: 80px;
        color: var(--danger);
        margin-bottom: 1rem;
    }

    /* Modal Styles */
    .modal-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1040;
    }

    .modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 1050;
        overflow-x: hidden;
        overflow-y: auto;
        display: none;
    }

    .modal.fade .modal-dialog {
        transform: translateY(-100px);
        transition: transform 0.3s ease-out;
    }

    .modal.show .modal-dialog {
        transform: none;
    }

    .modal-dialog {
        position: relative;
        width: auto;
        margin: 1.75rem auto;
        max-width: 500px;
        pointer-events: none;
    }

    .modal-content {
        position: relative;
        display: flex;
        flex-direction: column;
        width: 100%;
        pointer-events: auto;
        background: white;
        border: none;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        outline: 0;
    }

    .modal-body {
        position: relative;
        flex: 1 1 auto;
        padding: 2.5rem;
    }

    /* Delete Modal Specific Styles */
    .delete-modal {
        text-align: center;
    }

    .delete-icon {
        width: 70px;
        height: 70px;
        color: var(--danger);
        margin-bottom: 1.5rem;
        display: inline-block;
    }

    .modal-title {
        color: var(--danger);
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .modal-text {
        color: #64748b;
        font-size: 1rem;
        margin-bottom: 1.5rem;
    }

    .modal-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
    }

    .btn-modal {
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .btn-cancel {
        background: #f1f5f9;
        color: #64748b;
        border: none;
    }

    .btn-cancel:hover {
        background: #e2e8f0;
    }

    .btn-delete-confirm {
        background: #fee2e2;
        color: var(--danger);
        border: none;
    }

    .btn-delete-confirm:hover {
        background: #fecaca;
    }

    /* Animations */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .table tr {
        animation: fadeIn 0.3s ease-out forwards;
    }

    /* Fasilitas Tags */
    .fasilitas-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .fasilitas-tag {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.3rem 0.8rem;
        background: #f1f5f9;
        border-radius: 6px;
        font-size: 0.85rem;
        color: var(--primary);
    }

    .fasilitas-icon {
        width: 14px;
        height: 14px;
    }

    .page-title {
        font-size: 1.75rem;
        color: var(--primary);
        font-weight: 700;
        margin: 0;
    }

    .btn-add {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 4px 12px rgba(26,127,90,0.15);
    }

    .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(26,127,90,0.2);
        color: white;
    }

    /* Animation for table rows */
    .table tbody tr {
        opacity: 0;
        animation: fadeInUp 0.3s ease forwards;
    }

    .table tbody tr:nth-child(1) { animation-delay: 0.1s; }
    .table tbody tr:nth-child(2) { animation-delay: 0.2s; }
    .table tbody tr:nth-child(3) { animation-delay: 0.3s; }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (max-width: 768px) {
    .kost-container {
        padding: 1rem;
    }

    .page-header {
        padding: 1rem;
    }

    .header-content {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
    }

    .search-filter-container {
        flex-direction: column;
    }

    .filter-box {
        flex-wrap: wrap;
    }

    .table td {
        white-space: nowrap;
    }

    .fasilitas-tags {
        max-width: 200px;
        overflow-x: auto;
    }
}
</style>
@endpush

@section('content')
<div class="kost-container">
    <div class="page-header">
        <div class="header-content">
            <h1 class="page-title">Manajemen Kamar</h1>
            <a href="{{ route('admin.kost.create') }}" class="btn btn-add">
                <i data-feather="plus-circle" class="me-2"></i>
                Tambah Kamar
            </a>
        </div>

        <form method="GET" action="{{ route('admin.kost.index') }}" class="d-flex gap-2 mb-3">
            <input type="text" name="search" class="search-input" placeholder="Cari nomor kamar..." value="{{ request('search') }}">
            <select name="status" class="filter-select">
                <option value="">Semua Status</option>
                <option value="Kosong" {{ request('status') == 'Kosong' ? 'selected' : '' }}>Kosong</option>
                <option value="Terisi" {{ request('status') == 'Terisi' ? 'selected' : '' }}>Terisi</option>
            </select>
            <select name="price" class="filter-select">
                <option value="">Semua Harga</option>
                <option value="asc" {{ request('price') == 'asc' ? 'selected' : '' }}>Harga: Rendah - Tinggi</option>
                <option value="desc" {{ request('price') == 'desc' ? 'selected' : '' }}>Harga: Tinggi - Rendah</option>
            </select>
            <button type="submit" class="btn btn-primary">Terapkan</button>
        </form>
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
                        <!-- Bagian TD Fasilitas -->
                        <td>
                            @php
                                $fasilitas = is_string($kost->fasilitas) ? json_decode($kost->fasilitas, true) : $kost->fasilitas;
                                $fasilitas = is_array($fasilitas) ? $fasilitas : [];
                            @endphp
                            <div class="fasilitas-tags">
                                @foreach($fasilitas as $item)
                                    <span class="fasilitas-tag">
                                        <i data-feather="check-circle" class="fasilitas-icon"></i>
                                        {{ $item }}
                                    </span>
                                @endforeach
                            </div>
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
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Image Preview Modal -->
    <div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-0">
                    <img id="previewImage" src="" alt="Preview" class="img-fluid rounded">
                </div>
            </div>
        </div>
    </div>

    @foreach($kosts as $kost)
    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal{{ $kost->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $kost->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body delete-modal">
                    <i data-feather="alert-circle" class="delete-icon"></i>
                    <h4 class="modal-title">Hapus Kamar</h4>
                    <p class="modal-text">Apakah Anda yakin ingin menghapus kamar nomor {{ $kost->nomor_kamar }}?</p>
                    <div class="modal-buttons">
                        <button type="button" class="btn-modal btn-cancel" data-bs-dismiss="modal">
                            <i data-feather="x"></i>
                            Batal
                        </button>
                        <form action="{{ route('admin.kost.destroy', $kost->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-modal btn-delete-confirm">
                                <i data-feather="trash-2"></i>
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Feather Icons
    feather.replace();

    // Image Preview Modal
    const imagePreviewModal = new bootstrap.Modal(document.getElementById('imagePreviewModal'));

    document.querySelectorAll('.room-image').forEach(img => {
        img.addEventListener('click', function() {
            const previewImage = document.getElementById('previewImage');
            previewImage.src = this.src;
            imagePreviewModal.show();
        });
    });

    // Live Search & Filter
    const searchInput = document.querySelector('input[name="search"]');
    const statusFilter = document.querySelector('select[name="status"]');
    const priceFilter = document.querySelector('select[name="price"]');

    [searchInput, statusFilter, priceFilter].forEach(element => {
        element.addEventListener('change', function() {
            this.closest('form').submit();
        });
    });

    // Animate rows on load
    const tableRows = document.querySelectorAll('tbody tr');
    tableRows.forEach((row, index) => {
        row.style.animationDelay = `${index * 0.1}s`;
    });

    // Modal handling
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        modal.addEventListener('show.bs.modal', function() {
            document.body.style.overflow = 'hidden';
        });

        modal.addEventListener('shown.bs.modal', function() {
            feather.replace();
        });

        modal.addEventListener('hidden.bs.modal', function() {
            document.body.style.overflow = '';
        });
    });

    // Ensure modals can be closed with escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            modals.forEach(modal => {
                const bsModal = bootstrap.Modal.getInstance(modal);
                if (bsModal) bsModal.hide();
            });
        }
    });
});
</script>
@endpush
