<?php

namespace App\Livewire\Forms;

use App\Models\CashTransaction;
use Livewire\Attributes\Validate;
use Livewire\Form;

class StoreCashTransactionForm extends Form
{
    // Pastikan semua variabel ini ADA:
    public $student_ids = []; // Untuk menampung ID siswa (array)
    
    #[Validate('required|numeric|min:0')]
    public $amount = 0; // <--- INI WAJIB ADA (Tagihan)

    #[Validate('required|date')]
    public $date_paid;

    public $note;

    public function store()
    {
        $this->validate();

        // Loop karena student_ids bentuknya array (meski isinya cuma 1)
        foreach ($this->student_ids as $studentId) {
            CashTransaction::create([
                'student_id' => $studentId,
                'amount' => $this->amount,
                'date_paid' => $this->date_paid,
                'note' => $this->note,
                'created_by' => auth()->id(), // Pastikan user login tersimpan
            ]);
        }

        $this->reset();
    }
}