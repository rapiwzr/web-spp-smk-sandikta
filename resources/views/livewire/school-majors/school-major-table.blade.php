<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Daftar Jurusan</h5>
        
        {{-- FILTER & TOMBOL --}}
        <div class="d-flex flex-wrap justify-content-end mb-3 gap-3">
          <select wire:model.live="limit" class="form-select form-select-sm w-auto rounded">
            <option value="5">5</option>
            <option value="10">10</option>
            <option value="15">15</option>
          </select>

          <select wire:model.live="orderByColumn" class="form-select form-select-sm w-auto rounded">
            <option value="name">Nama Jurusan</option>
            {{-- HAPUS OPSI TUITION_FEE --}}
            <option value="created_at">Baru Ditambahkan</option>
          </select>

          <select wire:model.live="orderBy" class="form-select form-select-sm w-auto rounded">
            <option value="asc">A-Z</option>
            <option value="desc">Z-A</option>
          </select>

          <button wire:click="resetFilter" type="button" class="btn btn-outline-warning btn-sm rounded">
            <i class="bi bi-x-circle me-1"></i> Reset Filter
          </button>

          <button type="button" class="btn btn-primary btn-sm rounded" data-bs-toggle="modal"
            data-bs-target="#createModal">
            <i class="bi bi-plus-circle me-1"></i> Tambah Data
          </button>

          <button wire:click="$refresh" class="btn btn-outline-secondary btn-sm rounded">
            <i class="bi bi-arrow-clockwise me-1"></i> Refresh
          </button>
        </div>

        {{-- SEARCH BAR --}}
        <div class="mb-3">
          <div class="form-group has-icon-left">
            <div class="position-relative">
              <input wire:model.live="query" type="text" class="form-control form-control shadow-sm rounded fw-bold"
                placeholder="Masukan keyword pencarian...">
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
                <th scope="col" width="5%">#</th>
                
                <th scope="col" style="cursor: pointer" wire:click="$set('orderByColumn', 'name')">
                    Nama Jurusan <i class="bi bi-arrow-down-up small text-muted"></i>
                </th>
                
                <th scope="col">Singkatan</th>

                {{-- HAPUS KOLOM BIAYA SPP --}}
                
                <th scope="col" width="15%">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <tr wire:loading class="position-absolute w-100 h-100 top-0 start-0 bg-white opacity-75">
                <td colspan="4" class="text-center align-middle">
                  <div class="spinner-border text-primary" role="status"></div>
                </td>
              </tr>

              @php
              $startIndex = ($schoolMajors->currentPage() - 1) * $schoolMajors->perPage() + 1;
              @endphp
              
              @forelse ($schoolMajors as $index => $schoolMajor)
              <tr wire:key="{{ $schoolMajor->id }}">
                <th scope="row">{{ $startIndex + $index }}</th>
                
                <td class="fw-bold">{{ $schoolMajor->name }}</td>
                
                <td><span class="badge bg-secondary">{{ $schoolMajor->abbreviation }}</span></td>
                
                {{-- HAPUS DATA BIAYA SPP --}}

                <td>
                  <div class="btn-group gap-1" role="group">
                    <button wire:loading.attr="disabled"
                      wire:click="$dispatch('school-major-edit', {schoolMajor: {{ $schoolMajor->id }}})" type="button"
                      class="btn btn-sm btn-success rounded" data-bs-toggle="modal" data-bs-target="#editModal">
                      <i class="bi bi-pencil-square"></i>
                    </button>
                    <button wire:loading.attr="disabled"
                      wire:click="$dispatch('school-major-delete', {schoolMajor: {{ $schoolMajor->id }}})" type="button"
                      class="btn btn-sm btn-danger rounded" data-bs-toggle="modal" data-bs-target="#deleteModal">
                      <i class="bi bi-trash-fill"></i>
                    </button>
                  </div>
                </td>
              </tr>
              @empty
              <tr wire:loading.remove class="text-center">
                <td colspan="4" class="fw-bold py-4 text-muted">Tidak ada data yang ditemukan!</td>
              </tr>
              @endforelse
            </tbody>
          </table>

          <div class="mt-3">
            {{ $schoolMajors->links(data: ['scrollTo' => false]) }}
          </div>
        </div>
      </div>
    </div>
  </div>

  <livewire:school-majors.create-school-major />
  <livewire:school-majors.edit-school-major />
  <livewire:school-majors.delete-school-major />
</div>