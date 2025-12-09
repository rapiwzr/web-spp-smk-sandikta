<div>
  {{-- BARIS 1: STATISTIK HARI & MINGGU (REALTIME) --}}
  <div class="row">
    <div class="col-6">
      <div class="card">
        <div class="card-body px-4">
          <div class="row">
            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
              <div class="stats-icon purple"><i class="iconly-boldChart"></i></div>
            </div>
            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
              <h6 class="text-muted font-semibold">Total Hari Ini</h6>
              <h6 class="font-extrabold mb-0">{{ $statistics['totalToday'] }}</h6>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-6">
      <div class="card">
        <div class="card-body px-4">
          <div class="row">
            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
              <div class="stats-icon purple"><i class="iconly-boldChart"></i></div>
            </div>
            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
              <h6 class="text-muted font-semibold">Total Minggu Ini</h6>
              <h6 class="font-extrabold mb-0">{{ $statistics['totalCurrentWeek'] }}</h6>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- BARIS 2: STATISTIK SEMESTER & TAHUN (DINAMIS MENGIKUTI FILTER) --}}
  <div class="row">
    <div class="col-6">
      <div class="card">
        <div class="card-body px-4">
          <div class="row">
            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
              <div class="stats-icon purple"><i class="iconly-boldChart"></i></div>
            </div>
            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
              {{-- Label & Nilai berubah sesuai filter semester --}}
              <h6 class="text-muted font-semibold">{{ $statistics['labelMonth'] ?? 'Total Bulan Ini' }}</h6>
              <h6 class="font-extrabold mb-0">{{ $statistics['totalCurrentMonth'] }}</h6>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-6">
      <div class="card">
        <div class="card-body px-4">
          <div class="row">
            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
              <div class="stats-icon purple"><i class="iconly-boldChart"></i></div>
            </div>
            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
              {{-- Label & Nilai berubah sesuai filter tahun --}}
              <h6 class="text-muted font-semibold">{{ $statistics['labelYear'] ?? 'Total Tahun Ini' }}</h6>
              <h6 class="font-extrabold mb-0">{{ $statistics['totalCurrentYear'] }}</h6>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header text-center">
          <h4>Filter Data dengan Rentang Tanggal</h4>
        </div>
        
        <div class="card-body">
          {{-- INPUT FILTER (4 KOLOM) --}}
          <div class="row">
            {{-- 1. Pilih Tahun --}}
            <div class="col-sm-12 col-md-3">
              <div class="mb-3">
                <label for="filterYear" class="form-label fw-bold">Pilih Tahun:</label>
                <select wire:model.live="filterYear" id="filterYear" class="form-control">
                  <option value="">-- Pilih Tahun --</option>
                  @for($i = date('Y'); $i >= 2020; $i--)
                    <option value="{{ $i }}">{{ $i }}</option>
                  @endfor
                </select>
              </div>
            </div>

            {{-- 2. Pilih Semester --}}
            <div class="col-sm-12 col-md-3">
              <div class="mb-3">
                <label for="filterSemester" class="form-label fw-bold">Pilih Semester:</label>
                <select wire:model.live="filterSemester" id="filterSemester" class="form-control">
                  <option value="">Setahun Penuh</option>
                  <option value="ganjil">Ganjil (Juli - Des)</option>
                  <option value="genap">Genap (Jan - Juni)</option>
                </select>
              </div>
            </div>

            {{-- 3. Tanggal Awal (Otomatis tapi bisa diedit) --}}
            <div class="col-sm-12 col-md-3">
              <div class="mb-3">
                <label for="start_date" class="form-label">Tanggal Awal:</label>
                <input wire:model.live="start_date" type="date" class="form-control" id="start_date">
              </div>
            </div>

            {{-- 4. Tanggal Akhir (Otomatis tapi bisa diedit) --}}
            <div class="col-sm-12 col-md-3">
              <div class="mb-3">
                <label for="end_date" class="form-label">Tanggal Akhir:</label>
                <input wire:model.live="end_date" type="date" class="form-control" id="end_date">
              </div>
            </div>
          </div>
          {{-- END INPUT FILTER --}}

          <div class="divider">
            <div class="divider-text fw-bold">Data akan muncul otomatis berdasarkan filter di atas</div>
          </div>

          {{-- BAGIAN SISWA BELUM BAYAR --}}
          @if($statistics['studentsNotPaidCount'] > 0)
          <div class="card">
            <div class="card-body">
              <button type="button" class="btn btn-danger btn-block btn-xl font-bold" data-bs-toggle="modal"
                data-bs-target="#notPaidModal">
                Ada <b>{{ $statistics['studentsNotPaidCount'] }}</b> orang belum membayar pada rentang tanggal tersebut!
                <i class="bi bi-exclamation-triangle"></i>
              </button>
              
              <div class="row text-center mt-3">
                @foreach ($statistics['studentsNotPaidLimit'] as $student)
                <div class="col-sm-12 col-md-6 mb-3">
                  <div class="p-3 border rounded">
                    <h5 class="mb-1">{{ $student->name }}</h5>
                    <h6 class="text-muted mb-0">{{ $student->identification_number }}</h6>
                  </div>
                </div>
                @endforeach
              </div>

              <button type="button" class="btn btn-primary btn-block btn-xl font-bold" data-bs-toggle="modal"
                data-bs-target="#notPaidModal">Lihat Selengkapnya</button>
              
              {{-- MODAL --}}
              <div wire:ignore.self data-bs-backdrop="static" class="modal fade" id="notPaidModal" tabindex="-1"
                aria-labelledby="notPaidModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h1 class="modal-title fs-5" id="notPaidModalLabel">Daftar Pelajar Yang Belum Membayar</h1>
                      <button wire:loading.attr="disabled" type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <div class="row">
                        @foreach ($statistics['studentsNotPaid'] as $student)
                        <div class="col-sm-12 col-md-6 mb-3">
                          <div class="card border rounded">
                            <div class="card-body">
                              <h5 class="card-title fw-bold">{{ $student->name }}</h5>
                              <p class="card-text text-muted">{{ $student->identification_number }}</p>
                              <p class="card-text text-muted">
                                <span class="badge bg-secondary">
                                  <i class="bi bi-telephone-fill"></i> {{ $student->phone_number }}
                                </span>
                              </p>
                              <span class="badge bg-primary"><i class="bi bi-bookmark"></i> {{
                                $student->schoolClass->name }}</span>
                              <span class="badge bg-success"><i class="bi bi-briefcase"></i> {{
                                $student->schoolMajor->name }}</span>
                              <span class="badge bg-light-{{ $student->gender == 1 ? 'primary' : 'danger' }}"><i
                                  class="bi bi-gender-{{ $student->gender == 1 ? 'male' : 'female' }}"></i></span>
                            </div>
                          </div>
                        </div>
                        @endforeach
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button wire:loading.attr="disabled" type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">Tutup</button>
                    </div>
                  </div>
                </div>
              </div>
              {{-- END MODAL --}}
            </div>
          </div>

          <div class="d-flex flex-wrap justify-content-end">
            <button wire:click="$refresh" class="btn btn-outline-secondary btn-sm rounded">
              <i class="bi bi-arrow-clockwise me-1"></i> Refresh
            </button>
          </div>
          @endif
          
          {{-- INPUT PENCARIAN NAMA --}}
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
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Nama Pelajar</th>
                  <th scope="col">Total Bayar</th>
                  <th scope="col">Tanggal</th>
                  <th scope="col">Dicatat Oleh</th>
                </tr>
              </thead>
              <tbody>
                <tr wire:loading>
                  <td colspan="5">
                    <div class="d-flex justify-content-center">
                      <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                      </div>
                    </div>
                  </td>
                </tr>
                @php
                $startIndex = ($filteredResult->currentPage() - 1) * $filteredResult->perPage() + 1;
                @endphp
                @forelse ($filteredResult as $index => $cashTransaction)
                <tr wire:key="{{ $cashTransaction->id }}">
                  <th scope="row">{{ $startIndex + $index }}</th>
                  <td class="text-uppercase fw-bold">
                    <div>{{ $cashTransaction->student->name }}</div>

                    <span class="badge bg-success mt-1">
                      <i class="bi bi-briefcase-fill"></i>
                      {{ $cashTransaction->student->schoolMajor->name }}
                    </span>

                    <span class="badge bg-primary mt-1">
                      <i class="bi bi-bookmark-fill"></i>
                      {{ $cashTransaction->student->schoolClass->name }}
                    </span>
                  </td>
                  <td>{{ local_amount_format($cashTransaction->amount) }}</td>
                  <td>{{ $cashTransaction->date_paid }}</td>
                  <td class="text-center">
                    <span class="badge bg-primary">
                      <i class="bi bi-person-badge-fill"></i> {{ $cashTransaction->createdBy->name }}
                    </span>
                  </td>
                </tr>
                @empty
                <tr wire:loading.remove class="text-center">
                  <th colspan="6" class="fw-bold">Tidak ada data yang ditemukan!</th>
                </tr>
                @endforelse
              </tbody>
              @if($filteredResult->count() > 0)
              <tfoot>
                <tr role="row">
                  <td colspan="4" class="fw-bold text-end">Total (Berdasarkan Filter):</td>
                  <td colspan="2">
                    <span class="fw-bold">{{ local_amount_format($sumAmountDateRange) }}</span>
                  </td>
                </tr>
              </tfoot>
              @endif
            </table>
            {{ $filteredResult->links(data: ['scrollTo' => false]) }}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>