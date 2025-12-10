<div>
  <div wire:ignore.self data-bs-backdrop="static" class="modal fade" id="editModal" tabindex="-1"
    aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="editModalLabel">Ubah Data Jurusan</h1>
          <button wire:loading.attr="disabled" type="button" class="btn-close" data-bs-dismiss="modal"
            aria-label="Close"></button>
        </div>
        <div class="modal-body">
          {{-- Pastikan wire:submit mengarah ke 'save' --}}
          <form wire:submit="save">
            <div class="row">
              <div class="col">
                {{-- Input Lama --}}
                <x-forms.input-with-icon wire:model.blur="form.name" label="Nama Jurusan" name="form.name"
                  placeholder="Masukan nama jurusan.." type="text" icon="bi bi-briefcase" />

                <x-forms.input-with-icon wire:model.blur="form.abbreviation" label="Singkatan Jurusan"
                  name="form.abbreviation" placeholder="Masukan singkatan jurusan.." type="text"
                  icon="bi bi-card-text" />

                {{-- PERBAIKAN: Gunakan 'tuition_fee' (Bukan monthly_fee) --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Biaya SPP (Rp)</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        {{-- GANTI DI SINI --}}
                        <input type="number" class="form-control @error('form.tuition_fee') is-invalid @enderror" 
                               wire:model.blur="form.tuition_fee" placeholder="Contoh: 150000">
                    </div>
                    <div class="form-text text-muted small">Nominal ini akan otomatis muncul saat membuat transaksi baru.</div>
                    {{-- GANTI DI SINI JUGA --}}
                    @error('form.tuition_fee') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

              </div>
            </div>

            <div class="modal-footer">
              <button wire:loading.attr="disabled" type="button" class="btn btn-secondary"
                data-bs-dismiss="modal">Tutup</button>

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