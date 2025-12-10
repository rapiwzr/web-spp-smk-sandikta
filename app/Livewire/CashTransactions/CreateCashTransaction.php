<?php

namespace App\Livewire\CashTransactions;

use App\Livewire\Forms\StoreCashTransactionForm;
use App\Models\Student;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class CreateCashTransaction extends Component
{
    public StoreCashTransactionForm $form;

    // --- FUNGSI CEK HARGA ---
    public function cekHarga($studentId)
    {
        // 1. Simpan ID ke Form
        $this->form->student_ids = [$studentId];

        // 2. Cari Data
        $student = Student::with('schoolMajor')->find($studentId);

        // 3. Logika Update Harga
        if ($student && $student->schoolMajor) {
            $harga = $student->schoolMajor->tuition_fee;
            
            $this->form->amount = (int) $harga;
            $this->form->note = "✅ SUKSES: Jurusan " . $student->schoolMajor->name . " | Harga: Rp " . number_format($harga);
        } else {
            $this->form->amount = 0;
            $this->form->note = "❌ ERROR: Siswa ini belum punya Jurusan. Edit data siswa dulu!";
        }
    }

    public function render(): View
    {
        $students = Student::select('id', 'identification_number', 'name')->orderBy('name')->get();

        return view('livewire.cash-transactions.create-cash-transaction', [
            'students' => $students
        ]);
    }

    public function save(): void
    {
        $this->form->store();
        $this->dispatch('close-modal');
        $this->dispatch('success', message: 'Transaksi berhasil!');
        $this->dispatch('cash-transaction-created')->to(CashTransactionCurrentWeekTable::class);
    }
}