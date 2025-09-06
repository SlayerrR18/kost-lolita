@extends('layouts.main')
@section('title', 'Edit Kamar')

@push('css')
<style>
    /* === Palet & Umum (diselaraskan dengan desain sebelumnya) === */
    :root {
        --primary: #1a7f5a;
        --primary-2: #16c79a;
        --secondary: #f1f5f9;
        --surface: #ffffff;
        --bg: #f8fafc;
        --ink: #1e293b;
        --muted: #64748b;
        --ring: #e2e8f0;
        --success: #16a34a;
        --danger: #dc2626;
        --info: #0ea5e9;
        --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.05);
        --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.1);
        --radius-sm: 8px;
        --radius-md: 12px;
        --radius-lg: 24px;
    }

    body {
        font-family: 'Poppins', sans-serif;
    }

    /* === Layout Form === */
    .form-container {
        padding: 2rem;
        background: var(--bg);
        min-height: 100vh;
    }

    /* Header Form yang Rapi */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1.5rem;
        background: var(--surface);
        padding: 1.5rem;
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-md);
    }

    .page-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--ink);
        margin: 0;
    }

    .breadcrumb {
        background: transparent;
        margin-bottom: 0;
        padding: 0;
    }

    .breadcrumb-item a {
        color: var(--muted);
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .breadcrumb-item a:hover {
        color: var(--primary);
    }

    .breadcrumb-item.active {
        color: var(--ink);
        font-weight: 500;
    }

    .form-card {
        background: var(--surface);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-md);
        padding: 2.5rem;
        max-width: 900px;
        margin: 0 auto;
    }

    .form-section-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--ink);
        margin-bottom: 1.5rem;
        border-bottom: 1px solid var(--ring);
        padding-bottom: 0.75rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group.fasilitas {
        margin-bottom: 0;
    }

    .form-label {
        display: block;
        color: var(--muted);
        font-weight: 500;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }

    .input-field {
        position: relative;
    }

    .input-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        width: 1.125rem;
        height: 1.125rem;
        color: var(--muted);
        transition: color 0.2s ease;
        pointer-events: none;
    }

    .form-control, .form-select {
        width: 100%;
        padding: 0.75rem 1.25rem;
        border: 2px solid var(--ring);
        border-radius: var(--radius-md);
        font-size: 0.95rem;
        transition: all 0.2s ease;
        background: var(--bg);
        color: var(--ink);
    }

    .form-control[type="text"] { padding-left: 2.75rem; }
    .form-control[type="number"] { padding-left: 2.75rem; }
    .form-select { padding-left: 2.75rem; }

    .form-control::placeholder {
        color: var(--muted);
        opacity: 0.7;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(26, 127, 90, 0.1);
        outline: none;
        background: var(--surface);
    }

    .form-control:focus + .input-icon, .form-select:focus + .input-icon {
        color: var(--primary);
    }

    .fasilitas-list .fasilitas-container {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
    }

    .fasilitas-list .fasilitas-container:last-of-type {
        margin-bottom: 0;
    }

    .btn-action-icon {
        width: 38px;
        height: 38px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .btn-action-icon i {
        width: 18px;
        height: 18px;
    }

    .btn-add-fasilitas {
        background: var(--primary);
        color: white;
    }
    .btn-remove-fasilitas {
        background: var(--danger);
        color: white;
    }

    .btn-action-icon:hover {
        transform: scale(1.1);
    }

    .btn-submit {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-2) 100%);
        color: white;
        border: none;
        border-radius: var(--radius-md);
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: var(--shadow-md);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(26, 127, 90, 0.2);
    }

    .btn-cancel {
        background: var(--secondary);
        color: var(--ink);
    }

    .btn-cancel:hover {
        background: #e2e8f0;
        transform: translateY(-2px);
    }

    .file-upload-container {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .file-input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .file-input-wrapper input[type="file"] {
        position: absolute;
        top: 0;
        left: 0;
        opacity: 0;
        width: 100%;
        height: 100%;
        cursor: pointer;
    }

    .file-input-trigger {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background: var(--secondary);
        border: 1.5px dashed var(--ring);
        border-radius: var(--radius-md);
        color: var(--muted);
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .file-input-trigger:hover {
        background: #e2e8f0;
        color: var(--ink);
    }

    .photo-preview-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 1rem;
    }

    .photo-preview-item {
        width: 100px;
        height: 100px;
        border-radius: var(--radius-md);
        overflow: hidden;
        border: 1px solid var(--ring);
        position: relative;
    }

    .photo-preview-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .photo-preview-item .remove-btn {
        position: absolute;
        top: 4px;
        right: 4px;
        background: rgba(var(--danger), 0.8);
        border: none;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        cursor: pointer;
        opacity: 0;
        transition: opacity 0.2s;
    }

    .photo-preview-item:hover .remove-btn {
        opacity: 1;
    }

    @media (max-width: 768px) {
        .form-container {
            padding: 1rem;
        }
        .form-card {
            padding: 1.5rem;
        }
        .btn-submit {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
<div class="form-container">
    {{-- Header Form yang Diperbaiki --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">Edit Kamar</h1>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.kost.index') }}">Manajemen Kamar</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
        </nav>
    </div>

    @include('layouts.alert')

    <div class="form-card">
        <form action="{{ route('admin.kost.update', $kost->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-section-title">Detail Kamar</div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label" for="nomor_kamar">Nomor Kamar</label>
                        <div class="input-field">
                            <input type="text" id="nomor_kamar" name="nomor_kamar" class="form-control @error('nomor_kamar') is-invalid @enderror" value="{{ old('nomor_kamar', $kost->nomor_kamar) }}" required>
                            <i data-feather="hash" class="input-icon"></i>
                        </div>
                        @error('nomor_kamar')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label" for="harga">Harga</label>
                        <div class="input-field">
                            <input type="number" id="harga" name="harga" class="form-control @error('harga') is-invalid @enderror" value="{{ old('harga', $kost->harga) }}" required>
                            <i data-feather="dollar-sign" class="input-icon"></i>
                        </div>
                        @error('harga')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-group fasilitas">
                <label class="form-label">Fasilitas</label>
                <div id="fasilitas-list">
                    @php
                        $fasilitas = is_string($kost->fasilitas) ? json_decode($kost->fasilitas, true) : $kost->fasilitas;
                        $fasilitas = is_array($fasilitas) ? $fasilitas : [];
                        if (empty($fasilitas)) $fasilitas[] = ''; // Tambahkan satu input kosong jika tidak ada fasilitas
                    @endphp
                    @foreach($fasilitas as $item)
                        <div class="fasilitas-container">
                            <input type="text" name="fasilitas[]" class="form-control" value="{{ $item }}" placeholder="Masukkan nama fasilitas" required>
                            @if($loop->first)
                                <button type="button" class="btn-action-icon btn-add-fasilitas" onclick="tambahFasilitas(this)">
                                    <i data-feather="plus"></i>
                                </button>
                            @else
                                <button type="button" class="btn-action-icon btn-remove-fasilitas" onclick="this.closest('.fasilitas-container').remove()">
                                    <i data-feather="minus"></i>
                                </button>
                            @endif
                        </div>
                    @endforeach
                </div>
                <button type="button" class="btn-submit btn-add mt-3" onclick="tambahFasilitas(this)">
                    <i data-feather="plus"></i> Tambah Fasilitas
                </button>
                @error('fasilitas.*')
                    <div class="text-danger small mt-2 d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="status">Status</label>
                <div class="input-field">
                    <select id="status" name="status" class="form-control @error('status') is-invalid @enderror" required>
                        <option value="Kosong" {{ old('status', $kost->status) == 'Kosong' ? 'selected' : '' }}>Kosong</option>
                        <option value="Terisi" {{ old('status', $kost->status) == 'Terisi' ? 'selected' : '' }}>Terisi</option>
                    </select>
                    <i data-feather="check-circle" class="input-icon"></i>
                </div>
                @error('status')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="foto">Foto Kamar</label>
                <div class="file-upload-container">
                    <div class="file-input-wrapper">
                        <div class="file-input-trigger">
                            <i data-feather="upload" class="me-2"></i>
                            <span id="file-label-text">Pilih Foto Baru</span>
                        </div>
                        <input type="file" id="foto" name="foto[]" accept="image/*" multiple>
                    </div>
                    <small class="text-muted">Pilih minimal 1 foto. Format: JPG, PNG (maks. 5MB per file)</small>
                    <div id="photo-preview-grid" class="photo-preview-grid">
                        @if(is_array($kost->foto))
                            @foreach($kost->foto as $photo)
                                <div class="photo-preview-item">
                                    <img src="{{ asset('storage/'.$photo) }}" alt="Foto Kamar">
                                    <input type="hidden" name="existing_photos[]" value="{{ $photo }}">
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
                @error('foto')
                    <div class="text-danger small mt-2">{{ $message }}</div>
                @enderror
                @error('foto.*')
                    <div class="text-danger small mt-2">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-end gap-3 mt-4">
                <a href="{{ route('admin.kost.index') }}" class="btn-submit btn-cancel">
                    <i data-feather="x"></i> Batal
                </a>
                <button type="submit" class="btn-submit">
                    <i data-feather="save"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

@push('js')
<script>
function tambahFasilitas(button) {
    const fasilitasList = document.getElementById('fasilitas-list');
    const newFasilitas = document.createElement('div');
    newFasilitas.className = 'fasilitas-container';
    newFasilitas.innerHTML = `
        <input type="text" name="fasilitas[]" class="form-control" placeholder="Masukkan nama fasilitas" required>
        <button type="button" class="btn-action-icon btn-remove-fasilitas" onclick="this.closest('.fasilitas-container').remove()">
            <i data-feather="minus"></i>
        </button>
    `;
    fasilitasList.appendChild(newFasilitas);
    feather.replace();
}

document.addEventListener('DOMContentLoaded', function() {
    feather.replace();

    const fotoInput = document.getElementById('foto');
    const previewGrid = document.getElementById('photo-preview-grid');
    const fileLabelText = document.getElementById('file-label-text');

    if (fotoInput && previewGrid) {
        fotoInput.addEventListener('change', function(e) {
            previewGrid.innerHTML = '';
            const files = e.target.files;

            if (files.length > 0) {
                fileLabelText.textContent = `${files.length} file dipilih`;
            } else {
                fileLabelText.textContent = 'Pilih Foto Baru';
            }

            Array.from(files).forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewItem = document.createElement('div');
                    previewItem.className = 'photo-preview-item';
                    previewItem.innerHTML = `
                        <img src="${e.target.result}" alt="Preview Foto">
                        <button type="button" class="remove-btn" onclick="removePhotoPreview(this)">
                            <i data-feather="x"></i>
                        </button>
                    `;
                    previewGrid.appendChild(previewItem);
                    feather.replace();
                }
                reader.readAsDataURL(file);
            });
        });
    }

    window.removePhotoPreview = function(button) {
        button.closest('.photo-preview-item').remove();
    };
});
</script>
@endpush
@endsection
