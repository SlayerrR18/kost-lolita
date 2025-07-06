@extends('layouts.main')

@section('title', 'Tambah Kamar')

@push('css')
<style>
    .form-container {
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

    .form-card {
        background: #fff;
        border-radius: 24px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        padding: 32px;
        max-width: 800px;
    }

    .form-group {
        margin-bottom: 24px;
    }

    .form-label {
        display: block;
        color: #64748b;
        font-weight: 500;
        margin-bottom: 8px;
    }

    .form-control {
        width: 100%;
        padding: 12px 16px;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #1a7f5a;
        box-shadow: 0 0 0 3px rgba(26,127,90,0.1);
        outline: none;
    }

    .fasilitas-container {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
    }

    .btn-add-fasilitas {
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        border: none;
        background: #1a7f5a;
        color: white;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-remove-fasilitas {
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        border: none;
        background: #fee2e2;
        color: #991b1b;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-submit {
        background: #1a7f5a;
        color: white;
        border: none;
        border-radius: 12px;
        padding: 12px 24px;
        font-weight: 500;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-submit:hover {
        background: #156c4a;
        transform: translateY(-2px);
    }

    .file-input-wrapper {
        position: relative;
        overflow: hidden;
        display: inline-block;
    }

    .file-input-wrapper input[type=file] {
        font-size: 100px;
        position: absolute;
        left: 0;
        top: 0;
        opacity: 0;
    }

    .file-input-trigger {
        display: inline-block;
        padding: 12px 20px;
        background: #f1f5f9;
        border: 1.5px dashed #cbd5e1;
        border-radius: 12px;
        color: #64748b;
        cursor: pointer;
        transition: all 0.3s ease;
    }
</style>
@endpush

@section('content')
<div class="form-container">
    <h1 class="page-title">Tambah Kamar</h1>

    <div class="form-card">
        <form action="{{ route('admin.kost.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label class="form-label" for="nomor_kamar">Nomor Kamar</label>
                <input type="text" id="nomor_kamar" name="nomor_kamar" class="form-control" required>
            </div>

            <div class="form-group">
                <label class="form-label">Fasilitas</label>
                <div id="fasilitas-list">
                    <div class="fasilitas-container">
                        <input type="text" name="fasilitas[]" class="form-control" required>
                        <button type="button" class="btn-add-fasilitas" onclick="tambahFasilitas()">
                            <i data-feather="plus"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="status">Status</label>
                <select id="status" name="status" class="form-control" required>
                    <option value="Kosong">Kosong</option>
                    <option value="Terisi">Terisi</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label" for="harga">Harga</label>
                <input type="number" id="harga" name="harga" class="form-control" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="foto">Foto Kamar</label>
                <div class="file-input-wrapper">
                    <div class="file-input-trigger">
                        <i data-feather="upload" class="me-2"></i>
                        Pilih Foto
                    </div>
                    <input type="file" id="foto" name="foto[]" accept="image/*" multiple>
                </div>
                <small class="text-muted d-block mt-2">Format: JPG, PNG</small>
            </div>

            <button type="submit" class="btn-submit">
                <i data-feather="save" class="me-2"></i>
                Simpan Kamar
            </button>
        </form>
    </div>
</div>

<script>
function tambahFasilitas() {
    const fasilitasList = document.getElementById('fasilitas-list');
    const newFasilitas = document.createElement('div');
    newFasilitas.className = 'fasilitas-container';
    newFasilitas.innerHTML = `
        <input type="text" name="fasilitas[]" class="form-control" required>
        <button type="button" class="btn-remove-fasilitas" onclick="this.parentElement.remove()">
            <i data-feather="minus"></i>
        </button>
    `;
    fasilitasList.appendChild(newFasilitas);
    feather.replace();
}

document.addEventListener('DOMContentLoaded', function() {
    feather.replace();
});
</script>
@endsection

