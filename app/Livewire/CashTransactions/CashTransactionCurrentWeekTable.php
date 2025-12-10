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
    public int $limit = 50; 
    public ?string $query = '';
    public string $orderByColumn = 'date_paid';
    public string $orderBy = 'desc';

    public array $filters = [
        'user_id' => '',
        'schoolMajorID' => '',
        'schoolClassID' => '',
    ];

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

    // --- 4. EVENT HANDLER: HAPUS TRANSAKSI (WAJIB ADA) ---
    #[On('delete-transaction')]
    public function deleteTransaction($id)
    {
        $transaction = CashTransaction::find($id);

        if ($transaction) {
            $transaction->delete();
            $this->dispatch('success', message: 'Transaksi berhasil dihapus!');
        } else {
            $this->dispatch('error', message: 'Data tidak ditemukan!');
        }
    }

    // --- 5. EVENT HANDLER: EDIT TRANSAKSI ---
    #[On('edit-transaction')]
    public function editTransaction($id)
    {
        // Event ini akan ditangkap oleh modal Edit (jika file-nya ada)
        // Kita teruskan ID-nya ke komponen Edit
        $this->dispatch('set-edit-transaction', id: $id)->to('cash-transactions.edit-cash-transaction');
        
        // Buka modal edit (jika pakai script JS manual)
        // $this->dispatch('open-edit-modal'); 
    }

    // --- 6. RENDER (LOGIKA UTAMA) ---
    #[On('cash-transaction-created')]
    #[On('cash-transaction-updated')]
    #[On('cash-transaction-deleted')]
    public function render(): View
    {
        // A. Tentukan Tanggal Semester
        $bulanSekarang = date('n');
        $tahunSekarang = date('Y');

        if ($bulanSekarang >= 7) {
            $startDate = "$tahunSekarang-07-01";
            $endDate = "$tahunSekarang-12-31";
            $semesterLabel = "Ganjil " . $tahunSekarang;
        } else {
            $startDate = "$tahunSekarang-01-01";
            $endDate = "$tahunSekarang-06-30";
            $semesterLabel = "Genap " . $tahunSekarang;
        }

        // B. Query Utama
        $transactions = CashTransaction::query()
            ->with(['student.schoolMajor', 'student.schoolClass', 'createdBy'])
            ->whereBetween('date_paid', [$startDate, $endDate])
            ->when($this->query, function (Builder $q) {
                $q->whereHas('student', function ($subQ) {
                    $subQ->where('name', 'like', '%' . $this->query . '%')
                          ->orWhere('identification_number', 'like', '%' . $this->query . '%');
                });
            })
            ->when($this->filters['user_id'], fn (Builder $q) => $q->where('created_by', $this->filters['user_id']))
            ->when($this->filters['schoolMajorID'], fn (Builder $q) => $q->whereRelation('student', 'school_major_id', $this->filters['schoolMajorID']))
            ->when($this->filters['schoolClassID'], fn (Builder $q) => $q->whereRelation('student', 'school_class_id', $this->filters['schoolClassID']))
            ->orderBy($this->orderByColumn, $this->orderBy)
            ->paginate($this->limit);

        // C. Hitung Statistik (Manual)
        $totalUangSemester = CashTransaction::whereBetween('date_paid', [$startDate, $endDate])->sum('amount');
        $totalUangTahun = CashTransaction::whereYear('date_paid', $tahunSekarang)->sum('amount');

        // Gunakan number_format (aman & standar PHP)
        $this->statistics = [
            'totalCurrentMonth' => "Rp " . number_format($totalUangSemester, 0, ',', '.'),
            'totalCurrentYear' => "Rp " . number_format($totalUangTahun, 0, ',', '.'),
        ];

        // D. Hitung Data Siswa
        $totalSiswa = Student::count();
        $sudahBayarCount = CashTransaction::whereBetween('date_paid', [$startDate, $endDate])
                            ->distinct('student_id')
                            ->count('student_id');
        $belumBayarCount = $totalSiswa - $sudahBayarCount;

        // Ambil data siswa nunggak
        $listNunggak = Student::whereDoesntHave('cashTransactions', function($q) use ($startDate, $endDate) {
            $q->whereBetween('date_paid', [$startDate, $endDate]);
        })->paginate(100); 

        // E. Kirim ke View
        return view('livewire.cash-transactions.cash-transaction-current-week-table', [
            'cashTransactions' => $transactions,
            
            // Data Referensi
            'users' => $this->users,               
            'schoolMajors' => $this->schoolMajors, 
            'schoolClasses' => $this->schoolClasses, 
            'students' => $this->students,         
            
            // Data Logika
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