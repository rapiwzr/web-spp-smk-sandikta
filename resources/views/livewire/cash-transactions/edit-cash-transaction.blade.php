<div>
    {{-- 1. LOAD LIBRARY & CSS (Sama seperti Create, wajib ada biar tidak error) --}}
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

    <style>
        .ts-wrapper { z-index: 99999 !important; }
        .ts-dropdown { z-index: 99999 !important; }
        .ts-control {
            border-radius: 0.375rem;
            padding: 0.5rem 0.75rem;
            border: 1px solid #dee2e6;
        }
    </style>

    <div wire:ignore.self data-bs-backdrop="static" class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editModalLabel">Ubah Data Pembayaran SPP</h1>
                    <button wire:loading.attr="disabled" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    <form wire:submit="edit">
                        <div class="row">
                            {{-- KOLOM KIRI --}}
                            <div class="col-md-6">
                                {{-- INPUT PELAJAR DENGAN SEARCH (VERSI EDIT) --}}
                                <div class="mb-3" wire:ignore>
                                    <label class="form-label fw-bold">
                                        <i class="bi bi-people-fill me-1"></i> Pilih Pelajar
                                    </label>
                                    
                                    {{-- 
                                        Perbedaan dengan Create:
                                        1. Tidak pakai 'multiple' (karena edit cuma 1 data)
                                        2. Pakai $watch untuk sinkronisasi data saat modal dibuka
                                    --}}
                                    <select x-data="{
                                        tomSelectInstance: null,
                                        initTomSelect() {
                                            this.tomSelectInstance = new TomSelect(this.$el, {
                                                create: false,
                                                placeholder: 'Cari Nama Siswa...',
                                                dropdownParent: 'body',
                                                maxOptions: 100,
                                            });

                                            // FITUR PENTING: Watcher
                                            // Saat kamu klik tombol Edit di tabel, Livewire mengisi 'form.student_id'.
                                            // Kode ini menyuruh TomSelect untuk ikut berubah sesuai data tersebut.
                                            this.$watch('$wire.form.student_id', (value) => {
                                                if(value) {
                                                    this.tomSelectInstance.setValue(value, true); // true = update diam-diam
                                                }
                                            });
                                        }
                                    }"
                                    x-init="initTomSelect()"
                                    id="select-student-edit" 
                                    wire:model="form.student_id" 
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
                                
                                {{-- Error Message --}}
                                @error('form.student_id') 
                                    <div class="text-danger small fw-bold mb-3">{{ $message }}</div> 
                                @enderror
                            </div>

                            {{-- KOLOM KANAN --}}
                            <div class="col-sm-12 col-md-6">
                                {{-- Input Tagihan --}}
                                <x-forms.input-with-icon wire:model.blur="form.amount" label="Tagihan (Rp)" name="form.amount"
                                    placeholder="Contoh: 150000" type="number" icon="bi bi-cash" />

                                {{-- Input Tanggal --}}
                                <div class="mb-3">
                                    <label for="date_paid_edit" class="form-label">Tanggal Bayar:</label>
                                    <input wire:model.blur="form.date_paid" type="date"
                                        class="form-control @error('form.date_paid') is-invalid @enderror" id="date_paid_edit">
                                    @error('form.date_paid')
                                        <div class="d-block invalid-feedback fw-bold">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Input Catatan --}}
                                <x-forms.textarea-with-icon label="Catatan" name="form.transaction_note"
                                    placeholder="Masukan catatan.. (opsional)" icon="bi bi-card-text" cols="30" rows="3" />
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button wire:loading.attr="disabled" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button wire:loading.remove type="submit" class="btn btn-primary">Simpan Perubahan</button>

                            <div wire:loading wire:target="edit">
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