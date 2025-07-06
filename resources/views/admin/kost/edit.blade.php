@extends('layouts.main')
@section('title', 'Edit Kamar')

@section('content')
<div class="content-wrapper">
    <div class="edit-card">
        <h1 class="edit-title">Edit Kamar</h1>

        <form action="{{ route('admin.kost.update', $kost->id) }}" method="POST" enctype="multipart/form-data" class="edit-form">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="nomor_kamar">Nomor Kamar</label>
                <input type="text" id="nomor_kamar" name="nomor_kamar" value="{{ $kost->nomor_kamar }}" required>
            </div>

            <div id="fasilitas-list" class="form-group">
                <label>Fasilitas</label>
                <div class="fasilitas-container">
                    @php
                        $fasilitas = is_array($kost->fasilitas) ? $kost->fasilitas : [];
                    @endphp

                    @if(count($fasilitas) > 0)
                        @foreach($fasilitas as $item)
                            <div class="fasilitas-item">
                                <input type="text" name="fasilitas[]" value="{{ $item }}" required>
                                @if(!$loop->first)
                                    <button type="button" class="btn-remove" onclick="this.parentNode.remove()">
                                        <i data-feather="minus-circle"></i>
                                    </button>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <div class="fasilitas-item">
                            <input type="text" name="fasilitas[]" required>
                        </div>
                    @endif
                </div>
                <button type="button" class="btn-add" onclick="tambahFasilitas()">
                    <i data-feather="plus-circle"></i> Tambah Fasilitas
                </button>
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status" required>
                    <option value="Kosong" {{ $kost->status == 'Kosong' ? 'selected' : '' }}>Kosong</option>
                    <option value="Terisi" {{ $kost->status == 'Terisi' ? 'selected' : '' }}>Terisi</option>
                </select>
            </div>

            <div class="form-group">
                <label for="harga">Harga</label>
                <input type="number" id="harga" name="harga" value="{{ $kost->harga }}" required>
            </div>

            <div class="form-group">
                <label for="foto">Foto Kamar</label>
                <div class="foto-container">
                    <div class="custom-file-upload">
                        <input type="file" id="foto" name="foto[]" accept="image/*" multiple>
                        <label for="foto" class="file-label">
                            <i data-feather="upload"></i>
                            <span>Pilih Foto Baru</span>
                        </label>
                    </div>
                    @if($kost->foto)
                        <div class="foto-preview">
                            <img src="{{ asset('storage/' . $kost->foto) }}" alt="Foto Kamar">
                        </div>
                    @endif
                </div>
            </div>

            <button type="submit" class="btn-submit">
                <i data-feather="save"></i> Update Kamar
            </button>
        </form>
    </div>
</div>

@push('css')
<style>
.content-wrapper {
    flex: 1;
    min-height: 100vh;
    padding: 2rem;
    background: #f8fafc;
}

.edit-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
    padding: 2rem;
    max-width: 800px;
    margin: 0 auto;
}

.edit-title {
    font-size: 1.875rem;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 2rem;
}

.edit-form {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.form-group label {
    font-weight: 500;
    color: #475569;
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    outline: none;
    transition: border-color 0.2s;
}

.form-group input:focus,
.form-group select:focus {
    border-color: #1a7f5a;
}

.fasilitas-container {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.fasilitas-item {
    display: flex;
    gap: 0.5rem;
    align-items: center;
}

.fasilitas-item input {
    flex: 1;
}

.btn-add,
.btn-remove {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-add {
    background: #1a7f5a;
    color: white;
}

.btn-remove {
    background: #ef4444;
    color: white;
    padding: 0.5rem;
}

.btn-add:hover {
    background: #15664a;
}

.btn-remove:hover {
    background: #dc2626;
}

.foto-container {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.foto-preview {
    width: 300px;
    height: 200px;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
}

.foto-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s;
}

.foto-preview:hover img {
    transform: scale(1.05);
}

.btn-submit {
    background: #1a7f5a;
    color: white;
    border: none;
    border-radius: 8px;
    padding: 0.75rem 1.5rem;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
    transition: background 0.2s;
}

.btn-submit:hover {
    background: #15664a;
}

.custom-file-upload {
    margin-bottom: 1rem;
}

.hidden-file-input {
    display: none;
}

.file-label {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    background: #1a7f5a;
    color: white;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s;
}

.file-label:hover {
    background: #15664a;
}
</style>
@endpush

@push('js')
<script>
function tambahFasilitas() {
    const html = `
        <div class="fasilitas-item">
            <input type="text" name="fasilitas[]" required>
            <button type="button" class="btn-remove" onclick="this.parentNode.remove()">
                <i data-feather="minus-circle"></i>
            </button>
        </div>`;
    document.querySelector('.fasilitas-container').insertAdjacentHTML('beforeend', html);
    feather.replace();
}

document.addEventListener('DOMContentLoaded', function() {
    feather.replace();
});

document.getElementById('foto').addEventListener('change', function(e) {
    const files = e.target.files;
    const container = document.querySelector('.foto-container');
    container.querySelectorAll('.foto-preview').forEach(preview => preview.remove()); // Remove old previews

    Array.from(files).forEach((file) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.createElement('div');
            preview.className = 'foto-preview';
            preview.innerHTML = `<img src="${e.target.result}" alt="Preview Foto">`;
            container.appendChild(preview);
        }
        reader.readAsDataURL(file);
    });

    // Update label text
    const label = document.querySelector('.file-label span');
    label.textContent = files.length > 1 ? `${files.length} files selected` : files[0].name;
});
</script>
@endpush
@endsection

