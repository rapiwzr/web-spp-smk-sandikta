<?php

namespace App\Livewire\CashTransactions;

use App\Models\CashTransaction;
use App\Models\SchoolClass;
use App\Models\SchoolMajor;
use App\Models\Student;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Laporan SPP Semester')]
class CashTransactionCurrentWeekTable extends Component
{
    use WithPagination;

    // --- 1. KONFIGURASI ---
    public int $limit = 500; // Default tampilkan banyak data agar bisa di-scroll
    public ?string $query = '';
    public string $orderByColumn = 'date_paid';
    public string $orderBy = 'desc';

    public array $filters = [
        'user_id' => '',
        'schoolMajorID' => '',
        'schoolClassID' => '',
    ];

    // Variabel Statistik Publik
    public $statistics = []; 

    // --- 2. FUNGSI RESET & UPDATE ---
    public function updatedQuery(): void
    {
        $this->resetPage();
    }

    public function resetFilter(): void
    {
        $this->reset(['query', 'limit', 'orderByColumn', 'orderBy', 'filters']);
    }

    // --- 3. COMPUTED PROPERTIES (DATA REFERENSI) ---
    #[Computed]
    public function students(): Collection
    {
        return Student::select('id', 'identification_number', 'name')->orderBy('name')->get();
    }

    #[Computed]
    public function users(): Collection
    {
        return User::select('id', 'name')->orderBy('name')->get();
    }

    #[Computed]
    public function schoolMajors(): Collection
    {
        return SchoolMajor::select('id', 'name')->get();
    }

    #[Computed]
    public function schoolClasses(): Collection
    {
        return SchoolClass::select('id', 'name')->get();
    }

    // --- 4. RENDER (LOGIKA UTAMA) ---
    #[On('cash-transaction-created')]
    #[On('cash-transaction-updated')]
    #[On('cash-transaction-deleted')]
    public function render(): View
    {
        // A. Tentukan Tanggal Semester (Ganjil/Genap)
        $bulanSekarang = date('n');
        $tahunSekarang = date('Y');

        if ($bulanSekarang >= 7) {
            // Semester Ganjil (Juli - Desember)
            $startDate = "$tahunSekarang-07-01";
            $endDate = "$tahunSekarang-12-31";
            $semesterLabel = "Ganjil " . $tahunSekarang;
        } else {
            // Semester Genap (Januari - Juni)
            $startDate = "$tahunSekarang-01-01";
            $endDate = "$tahunSekarang-06-30";
            $semesterLabel = "Genap " . $tahunSekarang;
        }

        // B. Query Utama (Tabel Transaksi)
        $transactions = CashTransaction::query()
            ->with(['student.schoolMajor', 'student.schoolClass', 'createdBy'])
            
            // Filter Wajib: Hanya data di semester ini
            ->whereBetween('date_paid', [$startDate, $endDate])
            
            // Filter Pencarian
            ->when($this->query, function (Builder $q) {
                $q->whereHas('student', function ($subQ) {
                    $subQ->where('name', 'like', '%' . $this->query . '%')
                         ->orWhere('identification_number', 'like', '%' . $this->query . '%');
                });
            })
            // Filter Dropdown
            ->when($this->filters['user_id'], fn (Builder $q) => $q->where('created_by', $this->filters['user_id']))
            ->when($this->filters['schoolMajorID'], fn (Builder $q) => $q->whereRelation('student', 'school_major_id', $this->filters['schoolMajorID']))
            ->when($this->filters['schoolClassID'], fn (Builder $q) => $q->whereRelation('student', 'school_class_id', $this->filters['schoolClassID']))
            
            ->orderBy($this->orderByColumn, $this->orderBy)
            ->paginate($this->limit);

        // C. Hitung Statistik
        $totalUangSemester = CashTransaction::whereBetween('date_paid', [$startDate, $endDate])->sum('amount');
        $totalUangTahun = CashTransaction::whereYear('date_paid', $tahunSekarang)->sum('amount');

        // Simpan ke variabel public $statistics
        $this->statistics = [
            'totalCurrentMonth' => local_amount_format($totalUangSemester),
            'totalCurrentYear' => local_amount_format($totalUangTahun),
        ];

        // D. Hitung Data Siswa (Lunas/Belum)
        $totalSiswa = Student::count();
        $sudahBayarCount = CashTransaction::whereBetween('date_paid', [$startDate, $endDate])
                            ->distinct('student_id')
                            ->count('student_id');
        $belumBayarCount = $totalSiswa - $sudahBayarCount;

        // Ambil data siswa yang belum bayar untuk Modal
        $listNunggak = Student::whereDoesntHave('cashTransactions', function($q) use ($startDate, $endDate) {
            $q->whereBetween('date_paid', [$startDate, $endDate]);
        })->paginate(100); 

        // E. Kirim Semua Data ke View (Blade)
        return view('livewire.cash-transactions.cash-transaction-current-week-table', [
            'cashTransactions' => $transactions,
            
            // Variabel Data Referensi (Ini yang tadi bikin error undefined variable)
            'users' => $this->users,               // <-- PENTING
            'schoolMajors' => $this->schoolMajors, // <-- PENTING
            'schoolClasses' => $this->schoolClasses, // <-- PENTING
            'students' => $this->students,         // <-- PENTING
            
            // Variabel Logika & Statistik
            'semesterLabel' => $semesterLabel,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'sudahBayar' => $sudahBayarCount,
            'belumBayar' => $belumBayarCount,
            'siswaBelumBayar' => $listNunggak,
            'statistics' => $this->statistics,
        ]);
    }
}