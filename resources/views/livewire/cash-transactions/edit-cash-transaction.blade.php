<div>
    <div wire:ignore.self 
         class="modal fade" 
         id="editModal" 
         tabindex="-1"
         x-data
         x-on:open-edit-modal.window="new bootstrap.Modal($el).show()"
         x-on:close-modal.window="bootstrap.Modal.getInstance($el).hide()"
    >
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h1 class="modal-title fs-5">Edit Transaksi</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                
                <form wire:submit="update">
                    <div class="modal-body">
                        
                        <div wire:loading wire:target="setTransaction" class="alert alert-info w-100">
                            <span class="spinner-border spinner-border-sm me-2"></span>
                            Mengambil data transaksi...
                        </div>

                        <div class="row" wire:loading.remove wire:target="setTransaction">
                            {{-- KOLOM KIRI --}}
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Nama Pelajar</label>
                                    <input type="text" class="form-control bg-light" value="{{ $studentName }}" readonly>
                                    <div class="form-text small text-muted">Siswa tidak dapat diubah pada mode edit.</div>
                                </div>
                            </div>

                            {{-- KOLOM KANAN --}}
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Nominal (Rp)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" class="form-control" wire:model="form.amount">
                                    </div>
                                    @error('form.amount') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Tanggal Bayar</label>
                                    <input type="date" class="form-control" wire:model="form.date_paid">
                                    @error('form.date_paid') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Catatan</label>
                                    <textarea class="form-control" wire:model="form.transaction_note" rows="2"></textarea>
                                </div>

                                {{-- INPUT GANTI BUKTI PEMBAYARAN --}}
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Ganti Bukti Pembayaran</label>
                                    <input type="file" class="form-control" wire:model="proof" accept="image/png, image/jpeg, image/jpg">
                                    <div class="form-text small text-muted">
                                        Biarkan kosong jika tidak ingin mengubah foto. (Max: 10MB)
                                    </div>
                                    
                                    {{-- Loading saat upload --}}
                                    <div wire:loading wire:target="proof" class="text-primary small mt-1">
                                        <span class="spinner-border spinner-border-sm" aria-hidden="true"></span> Mengupload...
                                    </div>
                                    @error('proof') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>