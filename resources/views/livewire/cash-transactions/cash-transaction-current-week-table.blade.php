<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                {{-- JUDUL & FILTER --}}
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title mb-0">Riwayat Transaksi ({{ $semesterLabel ?? 'Semester Ini' }})</h5>
                    
                    <div class="d-flex gap-2">
                        <select wire:model.live="limit" class="form-select form-select-sm w-auto">
                            <option value="10">10 Data</option>
                            <option value="50">50 Data</option>
                            <option value="100">100 Data</option>
                        </select>

                        <button wire:click="resetFilter" class="btn btn-outline-warning btn-sm">
                            <i class="bi bi-arrow-counterclockwise"></i> Reset
                        </button>

                        {{-- TOMBOL TAMBAH TRANSAKSI --}}
                        <button type="button" class="btn btn-primary btn-sm" wire:click="$dispatch('buka-modal-create')">
                            <i class="bi bi-plus-lg"></i> Tambah Transaksi
                        </button>
                    </div>
                </div>

                {{-- SEARCH BAR --}}
                <div class="mb-3">
                    <input type="text" class="form-control" placeholder="Cari Nama Siswa atau NISN..." wire:model.live="query">
                </div>

                {{-- TABEL DATA --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Nama Pelajar</th>
                                <th>Nominal</th>
                                <th>Tanggal</th>
                                <th>Kategori / Jurusan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($cashTransactions as $index => $transaction)
                                <tr wire:key="{{ $transaction->id }}">
                                    <td>{{ $cashTransactions->firstItem() + $index }}</td>
                                    
                                    <td>
                                        <div class="fw-bold">{{ $transaction->student->name }}</div>
                                        <small class="text-muted">{{ $transaction->student->identification_number }}</small>
                                    </td>
                                    
                                    <td class="fw-bold text-primary">
                                        Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                    </td>
                                    
                                    <td>
                                        {{ \Carbon\Carbon::parse($transaction->date_paid)->format('d M Y') }}
                                    </td>

                                    <td>
                                        <span class="badge bg-info">
                                            {{ $transaction->student->schoolMajor->abbreviation ?? '-' }}
                                        </span>
                                    </td>

                                    <td>
                                        <div class="btn-group gap-1">
                                            
                                            {{-- 1. DOWNLOAD BUKTI --}}
                                            @if($transaction->proof_of_payment)
                                                <a href="{{ asset('storage/' . $transaction->proof_of_payment) }}" 
                                                   download="Bukti_Bayar_{{ str_replace(' ', '_', $transaction->student->name) }}"
                                                   class="btn btn-sm btn-info rounded text-white"
                                                   title="Unduh Bukti Pembayaran">
                                                    <i class="bi bi-download"></i>
                                                </a>
                                            @endif

                                            {{-- 2. TOMBOL EDIT --}}
                                            <button wire:click="$dispatch('edit-transaction', { id: {{ $transaction->id }} })" 
                                                    class="btn btn-sm btn-success rounded"
                                                    {{-- HAPUS data-bs-toggle DI SINI, BIAR COMPONENT YANG HANDLE --}}
                                                    >
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            
                                            {{-- 3. TOMBOL DELETE --}}
                                            <button wire:click="$dispatch('delete-transaction', { id: {{ $transaction->id }} })" 
                                                    class="btn btn-sm btn-danger rounded"
                                                    wire:confirm="Yakin ingin menghapus transaksi ini?">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        Belum ada data transaksi.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $cashTransactions->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- MEMUAT MODAL CREATE --}}
    <livewire:cash-transactions.create-cash-transaction />
    
    {{-- MEMUAT MODAL EDIT (INI YANG KITA TAMBAHKAN) --}}
    {{-- Pastikan file resources/views/livewire/cash-transactions/edit-cash-transaction.blade.php SUDAH ADA --}}
    <livewire:cash-transactions.edit-cash-transaction />
    
    {{-- MEMUAT MODAL DELETE --}}
    <livewire:cash-transactions.delete-cash-transaction />
</div>