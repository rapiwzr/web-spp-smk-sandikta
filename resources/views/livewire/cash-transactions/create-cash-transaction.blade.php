<div> {{-- DIV UTAMA --}}
    
    <div wire:ignore.self 
         class="modal fade" 
         id="createModal" 
         tabindex="-1"
         x-data
         x-on:buka-modal-create.window="new bootstrap.Modal($el).show()"
         x-on:close-modal.window="bootstrap.Modal.getInstance($el).hide()"
    >
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Tambah Transaksi</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                
                <form wire:submit="save">
                    <div class="modal-body">
                        <div class="row">
                            {{-- KOLOM KIRI --}}
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Pilih Pelajar</label>
                                    
                                    <div wire:ignore 
                                         x-data 
                                         x-init="
                                            new TomSelect($refs.select_pelajar, {
                                                create: false,
                                                maxItems: 1,
                                                maxOptions: null,
                                                placeholder: 'Cari Nama Siswa...',
                                                onChange: function(value) {
                                                    $wire.cekHarga(value);
                                                }
                                            });
                                         ">
                                        <select x-ref="select_pelajar" class="form-select" autocomplete="off">
                                            <option value="">-- Cari Siswa --</option>
                                            @foreach ($students as $student)
                                                <option value="{{ $student->id }}">
                                                    {{ $student->name }} ({{ $student->identification_number }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('form.student_ids') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            {{-- KOLOM KANAN --}}
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-primary">Pilih Kategori SPP</label>
                                    <select class="form-select border-primary" wire:model.live="selectedProgramId">
                                        <option value="">-- Pilih Jenis Pembayaran --</option>
                                        @foreach ($programs as $program)
                                            <option value="{{ $program->id }}">
                                                {{ $program->name }} 
                                                (Rp {{ number_format(abs($program->additional_fee), 0, ',', '.') }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Nominal Bayar</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" class="form-control fw-bold" wire:model.live="form.amount" readonly placeholder="0">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Tanggal Bayar</label>
                                    <input type="date" class="form-control" wire:model="form.date_paid">
                                    @error('form.date_paid') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Catatan</label>
                                    {{-- PERBAIKAN: Gunakan form.transaction_note --}}
                                    <textarea class="form-control" wire:model="form.transaction_note" rows="2"></textarea>
                                </div>

                                {{-- INPUT BUKTI PEMBAYARAN --}}
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Bukti Pembayaran</label>
                                    <input type="file" class="form-control" wire:model="proof" accept="image/png, image/jpeg, image/jpg">
                                    <div class="form-text small text-muted">Format: JPG/PNG. Max: 10MB.</div>
                                    <div wire:loading wire:target="proof" class="text-primary small mt-1">Mengupload...</div>
                                    @error('proof') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>