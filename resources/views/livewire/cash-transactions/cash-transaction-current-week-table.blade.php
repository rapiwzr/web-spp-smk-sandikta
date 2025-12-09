<div>
    {{-- LOGIC HITUNG MANUAL --}}
    @php
        $bulanIni = date('n');
        $tahunIni = date('Y');
        
        if ($bulanIni >= 7) {
            $start = "$tahunIni-07-01";
            $end   = "$tahunIni-12-31";
            $labelSemester = "Ganjil $tahunIni";
        } else {
            $start = "$tahunIni-01-01";
            $end   = "$tahunIni-06-30";
            $labelSemester = "Genap $tahunIni";
        }

        $totalSiswaReal = \App\Models\Student::count();
        $siswaSudahBayarCount = \App\Models\CashTransaction::whereBetween('date_paid', [$start, $end])->distinct('student_id')->count('student_id');
        $siswaBelumBayarCount = $totalSiswaReal - $siswaSudahBayarCount;
        $listNunggak = \App\Models\Student::whereDoesntHave('cashTransactions', function($q) use ($start, $end) {
            $q->whereBetween('date_paid', [$start, $end]);
        })->paginate(100); 
    @endphp

    <div class="row">
        {{-- CARD 1: TOTAL KAS --}}
        <div class="col-6">
            <div class="card">
                <div class="card-body px-4">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                            <div class="stats-icon blue"><i class="iconly-boldChart"></i></div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-body-secondary font-semibold">Total SPP ({{ $labelSemester }})</h6>
                            <h6 class="font-extrabold mb-0">{{ $statistics['totalCurrentMonth'] }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- CARD 2: TOTAL TAHUN INI --}}
        <div class="col-6">
            <div class="card">
                <div class="card-body px-4">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                            <div class="stats-icon green"><i class="iconly-boldWallet"></i></div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-body-secondary font-semibold">Total Tahun Ini</h6>
                            <h6 class="font-extrabold mb-0">{{ $statistics['totalCurrentYear'] }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- CARD 3: SISWA LUNAS --}}
        <div class="col-6">
            <div class="card">
                <div class="card-body px-4">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                            <div class="stats-icon purple"><i class="iconly-boldActivity"></i></div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-body-secondary font-semibold">Siswa Lunas ({{ $labelSemester }})</h6>
                            <h6 class="font-extrabold mb-0">{{ $siswaSudahBayarCount }} Siswa</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- CARD 4: BELUM LUNAS --}}
        <div class="col-6">
            <div class="card">
                <div class="card-body px-4">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                            <div class="stats-icon red"><i class="iconly-boldActivity"></i></div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-body-secondary font-semibold">Belum Lunas ({{ $labelSemester }})</h6>
                            <h6 class="font-extrabold mb-0">{{ $siswaBelumBayarCount }} Siswa</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ALERT STATUS PEMBAYARAN --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header text-center">
                    @if($totalSiswaReal > 0)
                    <h4>
                        Daftar Tunggakan SPP Semester {{ $labelSemester }}
                        <span class="fw-bolder fst-italic fs-6 d-block text-body-secondary mt-1">
                            ({{ \Carbon\Carbon::parse($start)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($end)->format('d M Y') }})
                        </span>
                    </h4>
                    @endif
                </div>

                <div class="card-body">
                    @if($totalSiswaReal == 0)
                        <div class="alert alert-danger text-center fw-bold">
                            <i class="bi bi-person-x-fill me-2"></i>
                            Data Pelajar Kosong (0 Siswa). Harap input data pelajar dahulu.
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('students.index') }}" class="btn btn-danger btn-sm shadow-sm">
                                <i class="bi bi-plus-circle"></i> Input Data Pelajar
                            </a>
                        </div>

                    @elseif($siswaBelumBayarCount > 0)
                        <div class="alert alert-danger text-center fw-bold">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            Ada {{ $siswaBelumBayarCount }} siswa yang belum melunasi pembayaran semester ini.
                        </div>
                        <div class="text-center mt-3">
                            <button type="button" class="btn btn-primary btn-lg font-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#notPaidModal">
                                <i class="bi bi-eye-fill me-2"></i> Lihat Daftar Siswa Belum Lunas
                            </button>
                        </div>

                    @else
                        <div class="alert alert-success text-center fw-bold">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            Luar biasa! Semua siswa ({{ $totalSiswaReal }} orang) sudah melunasi SPP.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL DAFTAR TUNGGAKAN --}}
    <div wire:ignore.self data-bs-backdrop="static" class="modal fade" id="notPaidModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h1 class="modal-title fs-5"><i class="bi bi-file-earmark-x me-2"></i> Tunggakan {{ $labelSemester }}</h1>
                    <button wire:loading.attr="disabled" type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    <div class="alert alert-info py-2 mb-3 small">
                        Menampilkan <strong>{{ count($listNunggak) }}</strong> dari <strong>{{ $siswaBelumBayarCount }}</strong> siswa yang belum lunas.
                    </div>

                    <div class="row">
                        @forelse ($listNunggak as $student)
                        <div class="col-sm-12 col-md-6 mb-3">
                            <div class="card border shadow-sm h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="avatar bg-light-danger me-3 text-danger fw-bold rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            {{ substr($student->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <h6 class="card-title fw-bold mb-0 text-body">{{ $student->name }}</h6>
                                            <small class="text-body-secondary">{{ $student->identification_number }}</small>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-wrap gap-1">
                                        <span class="badge bg-primary bg-opacity-10 text-primary">{{ $student->schoolClass->name ?? '-' }}</span>
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary">{{ $student->schoolMajor->name ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12 text-center py-5"><p class="text-muted">Tidak ada data.</p></div>
                        @endforelse
                    </div>

                    <div class="mt-3">
                        {{ $listNunggak->links(data: ['scrollTo' => false]) }}
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    {{-- TABEL TRANSAKSI --}}
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Riwayat Transaksi ({{ $labelSemester }})</h5>
                    
                    {{-- MENU FILTER & TOMBOL AKSI --}}
                    <div class="d-flex flex-wrap justify-content-end mb-3 gap-2">
                        {{-- Limit Data (Bisa pilih 500 biar scrollable) --}}
                        <select wire:model.live="limit" class="form-select form-select-sm w-auto rounded">
                            <option value="10">10 Data</option>
                            <option value="50">50 Data</option>
                            <option value="100">100 Data</option>
                            <option value="500">500 Data</option>
                        </select>

                        {{-- Tombol Filter Toggle --}}
                        <a class="btn btn-outline-info btn-sm" data-bs-toggle="collapse" href="#filterCollapse" role="button">
                            <i class="bi bi-funnel"></i> Filter
                        </a>

                        {{-- Tombol Reset --}}
                        <button wire:click="resetFilter" type="button" class="btn btn-outline-warning btn-sm rounded">
                            <i class="bi bi-arrow-counterclockwise"></i> Reset
                        </button>

                        {{-- Tombol Tambah --}}
                        <button type="button" class="btn btn-primary btn-sm rounded shadow-sm" data-bs-toggle="modal" data-bs-target="#createModal">
                            <i class="bi bi-plus-lg"></i> Tambah Transaksi
                        </button>
                    </div>

                    {{-- FILTER LANJUTAN (COLLAPSE) --}}
                    <div wire:ignore.self class="collapse border rounded mb-3 p-3 bg-body-tertiary" id="filterCollapse">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label small text-body-secondary">Admin Pencatat</label>
                                <select wire:model.live="filters.user_id" class="form-select form-select-sm">
                                    <option value="">Semua Admin</option>
                                    @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small text-body-secondary">Jurusan</label>
                                <select wire:model.live="filters.schoolMajorID" class="form-select form-select-sm">
                                    <option value="">Semua Jurusan</option>
                                    @foreach ($schoolMajors as $schoolMajor)
                                    <option value="{{ $schoolMajor->id }}">{{ $schoolMajor->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small text-body-secondary">Kelas</label>
                                <select wire:model.live="filters.schoolClassID" class="form-select form-select-sm">
                                    <option value="">Semua Kelas</option>
                                    @foreach ($schoolClasses as $schoolClass)
                                    <option value="{{ $schoolClass->id }}">{{ $schoolClass->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- SEARCH BAR --}}
                    <div class="mb-3 position-relative">
                        <input wire:model.live.debounce.300ms="query" type="text" class="form-control ps-5" placeholder="Cari Nama Siswa atau NISN...">
                        <div class="position-absolute top-50 start-0 translate-middle-y ps-3 text-muted">
                            <i class="bi bi-search"></i>
                        </div>
                    </div>

                    {{-- TABEL UTAMA (SCROLLABLE) --}}
                    <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-light position-sticky top-0" style="z-index: 1;">
                                <tr>
                                    <th>#</th>
                                    <th>Nama Pelajar</th>
                                    
                                    {{-- Sortable Header --}}
                                    <th style="cursor: pointer;" wire:click="$set('orderByColumn', 'amount')">
                                        Nominal 
                                        @if($orderByColumn === 'amount')
                                            <i class="bi bi-arrow-{{ $orderBy === 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </th>
                                    
                                    <th style="cursor: pointer;" wire:click="$set('orderByColumn', 'date_paid')">
                                        Tanggal
                                        @if($orderByColumn === 'date_paid')
                                            <i class="bi bi-arrow-{{ $orderBy === 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </th>
                                    
                                    <th>Dicatat Oleh</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Loading Indicator --}}
                                <tr wire:loading class="position-absolute w-100 h-100 top-0 start-0 bg-white opacity-75" style="z-index: 5;">
                                    <td colspan="6" class="text-center align-middle">
                                        <div class="spinner-border text-primary" role="status"></div>
                                    </td>
                                </tr>

                                {{-- Data Loop --}}
                                @php
                                    $startIndex = ($cashTransactions->currentPage() - 1) * $cashTransactions->perPage() + 1;
                                @endphp

                                @forelse ($cashTransactions as $index => $cashTransaction)
                                <tr wire:key="{{ $cashTransaction->id }}">
                                    <td>{{ $startIndex + $index }}</td>
                                    <td>
                                        <div class="fw-bold text-body text-uppercase">{{ $cashTransaction->student->name }}</div>
                                        <div class="d-flex gap-1 mt-1 flex-wrap">
                                            <span class="badge bg-success small">
                                                <i class="bi bi-briefcase-fill"></i> {{ $cashTransaction->student->schoolMajor->name }}
                                            </span>
                                            <span class="badge bg-primary small">
                                                <i class="bi bi-bookmark-fill"></i> {{ $cashTransaction->student->schoolClass->name }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="text-primary fw-bold">{{ local_amount_format($cashTransaction->amount) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($cashTransaction->date_paid)->format('d M Y') }}</td>
                                    <td>
                                        <span class="badge bg-info text-dark">
                                            <i class="bi bi-person-badge-fill"></i> {{ $cashTransaction->createdBy->name }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group gap-1">
                                            {{-- Tombol Invoice --}}
                                            <a href="{{ route('invoice', $cashTransaction->id) }}" target="_blank" class="btn btn-sm btn-primary rounded" title="Cetak Invoice">
                                                <i class="bi bi-printer"></i>
                                            </a>

                                            {{-- Tombol Edit --}}
                                            <button wire:click="$dispatch('cash-transaction-edit', {cashTransaction: {{ $cashTransaction->id }}})" class="btn btn-sm btn-success rounded" data-bs-toggle="modal" data-bs-target="#editModal">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>

                                            {{-- Tombol Hapus --}}
                                            <button wire:click="$dispatch('cash-transaction-delete', {cashTransaction: {{ $cashTransaction->id }}})" class="btn btn-sm btn-danger rounded" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted fw-bold">Tidak ada data transaksi ditemukan.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- Pagination --}}
                    <div class="mt-3">
                        {{ $cashTransactions->links(data: ['scrollTo' => false]) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL COMPONENTS (Menggunakan Variable $students, BUKAN $this->students) --}}
    <livewire:cash-transactions.create-cash-transaction :students="$students" />
    <livewire:cash-transactions.edit-cash-transaction :students="$students" />
    <livewire:cash-transactions.delete-cash-transaction />
    
</div>