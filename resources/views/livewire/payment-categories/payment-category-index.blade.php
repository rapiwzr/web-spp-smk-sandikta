<div>
    <div class="page-heading">
        <h3>Halaman Kategori Program</h3>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-12 d-flex justify-content-end gap-2">
                    {{-- Tombol Refresh --}}
                    <button wire:click="$refresh" class="btn btn-outline-secondary btn-sm rounded">
                        <i class="bi bi-arrow-clockwise me-1"></i> Refresh
                    </button>
                    
                    {{-- Tombol Tambah --}}
                    <button wire:click="create" class="btn btn-primary btn-sm rounded">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Data
                    </button>
                </div>
            </div>

            {{-- SEARCH BAR --}}
            <div class="mb-3">
                <div class="form-group has-icon-left">
                    <div class="position-relative">
                        <input type="text" class="form-control shadow-sm rounded" placeholder="Cari nama kategori..." wire:model.live="name">
                        <div class="form-control-icon">
                            <i class="bi bi-search"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TABEL DATA --}}
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">#</th>
                            <th>Nama Kategori</th>
                            <th>Nominal SPP</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categories as $index => $category)
                            <tr wire:key="{{ $category->id }}">
                                <td>{{ $categories->firstItem() + $index }}</td>
                                <td class="fw-bold">{{ $category->name }}</td>
                                
                                {{-- KOLOM HARGA BERSIH (TANPA PLUS MINUS) --}}
                                <td class="fw-bold text-primary">
                                    @if($category->additional_fee == 0)
                                        Rp 0 (Gratis/Beasiswa Full)
                                    @else
                                        {{-- Tampilkan angka positif saja --}}
                                        Rp {{ number_format($category->additional_fee, 0, ',', '.') }}
                                    @endif
                                </td>

                                <td>
                                    <div class="btn-group gap-1">
                                        <button wire:click="edit({{ $category->id }})" class="btn btn-sm btn-success rounded">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button wire:click="delete({{ $category->id }})" 
                                                wire:confirm="Yakin ingin menghapus kategori ini?" 
                                                class="btn btn-sm btn-danger rounded">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4 fw-bold">
                                    Tidak ada data kategori ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                {{ $categories->links() }}
            </div>
        </div>
    </div>

    {{-- MODAL FORM --}}
    <div wire:ignore.self class="modal fade" id="categoryModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        {{ $isEditMode ? 'Edit Kategori' : 'Tambah Kategori Baru' }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <form wire:submit="{{ $isEditMode ? 'update' : 'store' }}">
                    <div class="modal-body">
                        {{-- Input Nama --}}
                        <div class="mb-3">
                            <label class="form-label">Nama Kategori</label>
                            <input type="text" class="form-control" wire:model="name" placeholder="Contoh: Reguler / Beasiswa Full">
                            @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        {{-- Input Harga --}}
                        <div class="mb-3">
                            <label class="form-label">Nominal SPP (Rp)</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control" wire:model="additional_fee" placeholder="0">
                            </div>
                            <div class="form-text small text-muted">
                                Masukkan nominal <b>Harga Pas</b> (Bukan tambahan/potongan).<br>
                                Isi <code>0</code> jika Gratis (Beasiswa Full).
                            </div>
                            @error('additional_fee') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            {{ $isEditMode ? 'Simpan Perubahan' : 'Simpan' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- SCRIPT MODAL --}}
    @script
    <script>
        const myModal = new bootstrap.Modal(document.getElementById('categoryModal'));
        $wire.on('open-modal', () => myModal.show());
        $wire.on('close-modal', () => myModal.hide());
    </script>
    @endscript
</div>