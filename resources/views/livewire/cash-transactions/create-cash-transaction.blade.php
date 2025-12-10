<div>
    <div wire:ignore.self data-bs-backdrop="static" class="modal fade" id="createModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Tambah Transaksi</h1>
                    <button wire:loading.attr="disabled" type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                
                <form wire:submit="save">
                    <div class="modal-body">
                        <div class="row">
                            {{-- KOLOM KIRI --}}
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Pilih Pelajar</label>
                                    
                                    {{-- BAGIAN INI KUNCINYA --}}
                                    <div wire:ignore 
                                         x-data 
                                         x-init="
                                            new TomSelect($refs.select_pelajar, {
                                                create: false,
                                                maxItems: 1, // Single Select
                                                maxOptions: null, // <-- INI AGAR SEMUA SISWA MUNCUL (UNLIMITED)
                                                sortField: {
                                                    field: 'text',
                                                    direction: 'asc'
                                                },
                                                placeholder: 'Ketik Nama Siswa...',
                                                
                                                // Saat dipilih, panggil fungsi PHP
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
                                    <label class="form-label fw-bold text-primary">Tagihan Otomatis</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-primary text-white">Rp</span>
                                        <input type="number" class="form-control fw-bold" wire:model.live="form.amount" readonly>
                                    </div>
                                    <div class="form-text text-muted small">Harga muncul setelah nama dipilih.</div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Tanggal Bayar</label>
                                    <input type="date" class="form-control" wire:model="form.date_paid">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Status / Catatan</label>
                                    <textarea class="form-control fw-bold text-success" wire:model="form.note" rows="3" readonly></textarea>
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