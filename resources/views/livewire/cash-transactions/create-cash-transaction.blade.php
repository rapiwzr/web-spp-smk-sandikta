<div>
    {{-- 1. LOAD LIBRARY (Wajib ada) --}}
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

    {{-- 2. CSS PERBAIKAN --}}
    <style>
        /* Paksa dropdown muncul di paling depan */
        .ts-dropdown, .ts-wrapper { 
            z-index: 99999 !important; 
        }
        /* Perbaikan tampilan di dalam Modal */
        .ts-control {
            padding: 10px 12px;
            border-radius: 8px;
            border: 1px solid #dce7f1;
        }
        
        /* FIX DARK MODE TOMSELECT */
        [data-bs-theme="dark"] .ts-control {
            background-color: #2b3442;
            color: #fff;
            border-color: #4b5563;
        }
        [data-bs-theme="dark"] .ts-dropdown {
            background-color: #2b3442;
            color: #fff;
            border-color: #4b5563;
        }
        [data-bs-theme="dark"] .ts-dropdown .option:hover,
        [data-bs-theme="dark"] .ts-dropdown .active {
            background-color: #3b82f6;
            color: #fff;
        }
    </style>

    <div wire:ignore.self data-bs-backdrop="static" class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    {{-- Tambah text-body agar judul modal terlihat --}}
                    <h1 class="modal-title fs-5 text-body" id="createModalLabel">Tambah Data Pembayaran SPP</h1>
                    <button wire:loading.attr="disabled" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    <form wire:submit="save">
                        <div class="row">
                            {{-- KOLOM KIRI --}}
                            <div class="col-md-6">
                                <div class="mb-3">
                                    {{-- GANTI text-dark/default JADI text-body AGAR TERLIHAT DI DARK MODE --}}
                                    <label class="form-label fw-bold text-body">
                                        <i class="bi bi-people-fill me-1"></i> Pilih Pelajar
                                    </label>
                                    
                                    {{-- 3. BAGIAN SEARCH (Menggunakan x-init & x-data) --}}
                                    <div wire:ignore>
                                        <select x-data="{
                                            tomSelectInstance: null,
                                            initTomSelect() {
                                                this.tomSelectInstance = new TomSelect(this.$el, {
                                                    plugins: ['remove_button', 'clear_button'],
                                                    placeholder: 'Ketik Nama Siswa...',
                                                    maxOptions: 100,
                                                    dropdownParent: 'body', // Kunci agar tidak ketutup modal
                                                    onChange: (value) => {
                                                        // Update data ke Livewire saat dipilih
                                                        @this.set('form.student_ids', value);
                                                    }
                                                });
                                            }
                                        }"
                                        x-init="initTomSelect()"
                                        id="select-student-create" 
                                        multiple 
                                        class="form-select" 
                                        autocomplete="off">
                                            <option value="">Cari Nama Siswa...</option>
                                            @foreach ($students as $student)
                                                <option value="{{ $student->id }}">
                                                    {{ $student->identification_number }} - {{ $student->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-text small text-body-secondary">Ketik nama atau NISN untuk mencari.</div>
                                </div>
                                
                                @error('form.student_ids') 
                                    <div class="text-danger small fw-bold mb-3">{{ $message }}</div> 
                                @enderror
                            </div>

                            {{-- KOLOM KANAN --}}
                            <div class="col-sm-12 col-md-6">
                                {{-- Input Tagihan --}}
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-body">Tagihan (Rp)</label>
                                    <input wire:model.blur="form.amount" type="number" class="form-control" placeholder="Contoh: 150000">
                                </div>

                                <div class="mb-3">
                                    <label for="date_paid" class="form-label fw-bold text-body">Tanggal Bayar:</label>
                                    <input wire:model.blur="form.date_paid" type="date"
                                        class="form-control @error('form.date_paid') is-invalid @enderror" id="date_paid">
                                    @error('form.date_paid')
                                        <div class="d-block invalid-feedback fw-bold">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold text-body">Catatan</label>
                                    <textarea wire:model="form.transaction_note" class="form-control" rows="3" placeholder="Masukan catatan.. (opsional)"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button wire:loading.attr="disabled" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button wire:loading.remove type="submit" class="btn btn-primary">Simpan</button>

                            <div wire:loading wire:target="save">
                                <button class="btn btn-primary" type="button" disabled>
                                    <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                                    <span role="status">Menyimpan...</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>