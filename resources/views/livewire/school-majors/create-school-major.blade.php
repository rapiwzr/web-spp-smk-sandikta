<div>
    <div wire:ignore.self data-bs-backdrop="static" class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="createModalLabel">Tambah Jurusan Baru</h1>
                    <button wire:loading.attr="disabled" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <form wire:submit="save">
                    <div class="modal-body">
                        
                        {{-- Input Nama --}}
                        <div class="mb-3">
                            <label class="form-label">Nama Jurusan</label>
                            <input type="text" class="form-control @error('form.name') is-invalid @enderror" 
                                   wire:model="form.name" placeholder="Contoh: Rekayasa Perangkat Lunak">
                            @error('form.name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Input Singkatan --}}
                        <div class="mb-3">
                            <label class="form-label">Singkatan Jurusan</label>
                            <input type="text" class="form-control @error('form.abbreviation') is-invalid @enderror" 
                                   wire:model="form.abbreviation" placeholder="Contoh: RPL">
                            @error('form.abbreviation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- HAPUS INPUT BIAYA SPP --}}
                        {{-- Karena harga sekarang diatur di menu 'Kategori Program', bukan di Jurusan lagi. --}}

                    </div>
                    
                    <div class="modal-footer">
                        <button wire:loading.attr="disabled" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button wire:loading.attr="disabled" type="submit" class="btn btn-primary">
                            <span wire:loading.remove>Simpan</span>
                            <span wire:loading>Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>