<div class="modal fade" id="addTransactionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="transactionForm" action="{{ route('admin.financial.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah {{ request()->routeIs('admin.financial.expense') ? 'Pengeluaran' : 'Pemasukan' }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label required">Kamar</label>
                        <select name="kost_id" class="form-select" required>
                            <option value="">Pilih Kamar</option>
                            @foreach($kosts as $kost)
                                <option value="{{ $kost->id }}">Kamar {{ $kost->nomor_kamar }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label required">Nama Transaksi</label>
                        <input type="text" name="nama_transaksi" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label required">Tanggal Transaksi</label>
                        <input type="date" name="tanggal_transaksi" class="form-control"
                               value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label required">Total</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="total" class="form-control" required>
                        </div>
                    </div>

                    <input type="hidden" name="status"
                           value="{{ request()->routeIs('admin.financial.expense') ? 'Pengeluaran' : 'Pemasukan' }}">

                    <div class="mb-3">
                        <label class="form-label required">Bukti Transaksi</label>
                        <input type="file" name="bukti_pembayaran" class="form-control"
                               accept="image/*" required>
                        <div class="form-text">Format: JPG, PNG, JPEG (Max. 5MB)</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i data-feather="save" class="me-1"></i>
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
