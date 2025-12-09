<?php

namespace App\Livewire\CashTransactions;

use App\Models\CashTransaction;
use App\Repositories\CashTransactionRepository;
use App\Repositories\StudentRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Halaman Filter Transaksi SPP')]
class FilterCashTransaction extends Component
{
    use WithPagination;

    protected StudentRepository $studentRepository;
    protected CashTransactionRepository $cashTransactionRepository;

    public ?string $start_date = '';
    public ?string $end_date = '';
    public ?string $query = '';

    // [BARU] Variable Filter Dropdown
    public ?string $filterYear = '';
    public ?string $filterSemester = '';

    public ?array $statistics = [];

    public function boot(
        StudentRepository $studentRepository,
        CashTransactionRepository $cashTransactionRepository
    ): void {
        $this->studentRepository = $studentRepository;
        $this->cashTransactionRepository = $cashTransactionRepository;
    }

    public function mount(): void
    {
        // Default tahun sekarang agar data langsung tampil
        $this->filterYear = date('Y');
        
        // Panggil fungsi untuk set tanggal otomatis
        $this->calculateDates();

        // Init array statistics
        $this->statistics = [
            'totalCurrentWeek' => 0,
            'totalCurrentMonth' => 0,
            'totalCurrentYear' => 0,
            'studentsNotPaidLimit' => collect(),
            'studentsNotPaid' => collect(),
            'studentsNotPaidCount' => 0,
        ];
    }

    public function updated(): void
    {
        $this->resetPage();
    }

    // Trigger saat Tahun berubah
    public function updatedFilterYear()
    {
        $this->calculateDates();
        $this->resetPage();
    }

    // Trigger saat Semester berubah
    public function updatedFilterSemester()
    {
        $this->calculateDates();
        $this->resetPage();
    }

    // Logic Set Tanggal Otomatis (Ganjil/Genap/Tahun)
    private function calculateDates()
    {
        if (empty($this->filterYear)) {
            // Jika tahun dikosongkan, reset tanggal
            $this->start_date = '';
            $this->end_date = '';
            return;
        }

        $year = $this->filterYear;

        if ($this->filterSemester == 'ganjil') {
            // Ganjil: Juli - Desember
            $this->start_date = $year . '-07-01';
            $this->end_date = $year . '-12-31';
        } elseif ($this->filterSemester == 'genap') {
            // Genap: Januari - Juni
            $this->start_date = $year . '-01-01';
            $this->end_date = $year . '-06-30';
        } else {
            // Setahun Penuh (Jan - Des)
            $this->start_date = $year . '-01-01';
            $this->end_date = $year . '-12-31';
        }
    }

    public function render(): View
    {
        // 1. Query Data Tabel
        $filteredResult = CashTransaction::query()
            ->with('student', 'createdBy')
            ->when($this->query, function (Builder $query) {
                return $query->whereHas('student', function ($studentQuery) {
                    return $studentQuery->where('name', 'like', "%{$this->query}%");
                });
            })
            ->when($this->start_date && $this->end_date, function ($q) {
                $q->whereBetween('date_paid', [$this->start_date, $this->end_date]);
            });

        // 2. Hitung Total Uang pada Rentang Tanggal Terpilih (Untuk Footer Tabel)
        $sumAmountDateRange = 0;
        if ($this->start_date && $this->end_date) {
            $sumAmountDateRange = CashTransaction::whereBetween('date_paid', [$this->start_date, $this->end_date])->sum('amount');
        }

        // 3. Logic Siswa Belum Bayar
        if ($this->start_date && $this->end_date) {
            $studentPaidStatus = $this->studentRepository->getStudentPaymentStatus($this->start_date, $this->end_date);

            $this->statistics['studentsNotPaidLimit'] = $studentPaidStatus['studentsNotPaid']->take(6);
            $this->statistics['studentsNotPaid'] = $studentPaidStatus['studentsNotPaid'];
            $this->statistics['studentsNotPaidCount'] = $studentPaidStatus['studentsNotPaid']->count();
        }

        // 4. STATISTIK DINAMIS (Agar Kartu Atas Mengikuti Filter)
        
        // Ambil data default (Realtime Hari Ini/Minggu Ini)
        $rawStats = $this->cashTransactionRepository->calculateTransactionSums();

        // Default Label
        $labelYear = 'Total Tahun Ini';
        $labelMonth = 'Total Bulan Ini';
        $valYear = $rawStats['year'];
        $valMonth = $rawStats['month'];

        // Jika user memfilter Tahun
        if (!empty($this->filterYear)) {
            $labelYear = 'Total Tahun ' . $this->filterYear;
            $valYear = CashTransaction::whereYear('date_paid', $this->filterYear)->sum('amount');

            // Jika user memfilter Semester juga
            if (!empty($this->filterSemester)) {
                $labelMonth = 'Total Semester ' . ucfirst($this->filterSemester);
                $valMonth = $sumAmountDateRange; // Pakai total range tanggal yg sudah dihitung
            } else {
                // Jika pilih tahun tapi semester kosong
                $labelMonth = 'Total Bulan (Pilih Semester)';
                $valMonth = 0; 
            }
        }

        // Masukkan ke array statistics view
        $this->statistics['totalToday'] = local_amount_format($rawStats['today']); 
        $this->statistics['totalCurrentWeek'] = local_amount_format($rawStats['week']);
        
        // Nilai Dinamis
        $this->statistics['totalCurrentMonth'] = local_amount_format($valMonth);
        $this->statistics['totalCurrentYear'] = local_amount_format($valYear);
        
        // Label Dinamis
        $this->statistics['labelMonth'] = $labelMonth;
        $this->statistics['labelYear'] = $labelYear;

        return view('livewire.cash-transactions.filter-cash-transaction', [
            'filteredResult' => $filteredResult->paginate(10),
            'sumAmountDateRange' => $sumAmountDateRange,
        ]);
    }
}