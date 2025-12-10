<?php

namespace App\Livewire\CashTransactions;

use App\Livewire\Forms\StoreCashTransactionForm;
use App\Models\Student;
use App\Models\PaymentCategory;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateCashTransaction extends Component
{
    use WithFileUploads;

    public StoreCashTransactionForm $form;
    public $selectedProgramId = null;
    public $proof;

    // --- FUNGSI CEK HARGA ---
    public function cekHarga($studentId)
    {
        // 1. Bersihkan ID jika formatnya array
        if (is_array($studentId)) {
            $studentId = reset($studentId);
        }
        
        // 2. Set ID ke Form (Cuma simpan ID, tidak mengubah Catatan)
        $this->form->student_ids = [$studentId];
    }

    public function updatedSelectedProgramId($value)
    {
        if ($value) {
            $program = PaymentCategory::find($value);
            $this->form->amount = (int) ($program->additional_fee ?? 0);
        } else {
            $this->form->amount = 0;
        }
    }

    public function render(): View
    {
        return view('livewire.cash-transactions.create-cash-transaction', [
            'students' => Student::select('id', 'name', 'identification_number')->orderBy('name')->get(),
            'programs' => PaymentCategory::all()
        ]);
    }

    public function save(): void
    {
        if (empty($this->selectedProgramId)) {
            $this->dispatch('warning', message: 'Wajib memilih Kategori Program!');
            return;
        }

        $this->validate([
            'form.student_ids' => 'required',
            'form.amount' => 'required|numeric',
            'form.date_paid' => 'required|date',
            'proof' => 'nullable|image|max:10240', // 10MB
        ]);

        $proofPath = null;
        if ($this->proof) {
            $proofPath = $this->proof->store('payment-proofs', 'public');
        }

        foreach ($this->form->student_ids as $studentId) {
            \App\Models\CashTransaction::create([
                'student_id' => $studentId,
                'amount' => $this->form->amount,
                'date_paid' => $this->form->date_paid,
                'note' => $this->form->transaction_note, 
                'proof_of_payment' => $proofPath,
                'created_by' => auth()->id(),
            ]);
        }

        $this->dispatch('close-modal');
        $this->dispatch('success', message: 'Transaksi berhasil disimpan!');
        $this->dispatch('cash-transaction-created')->to(CashTransactionCurrentWeekTable::class);
        
        $this->reset(['selectedProgramId', 'proof']);
        $this->form->reset();
    }
}