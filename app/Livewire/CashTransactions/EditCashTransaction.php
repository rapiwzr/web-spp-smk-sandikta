<?php

namespace App\Livewire\CashTransactions;

use App\Livewire\Forms\StoreCashTransactionForm;
use App\Models\CashTransaction;
use App\Models\PaymentCategory; // <--- INI NAMA MODEL YANG BENAR
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class EditCashTransaction extends Component
{
    use WithFileUploads;

    public StoreCashTransactionForm $form; 
    public $transactionId;
    public $studentName;
    public $proof; 

    // DATA UNTUK DROPDOWN
    public $programs = []; 
    public $selectedProgramId = null; 

    // 1. AMBIL DATA KATEGORI SAAT COMPONENT DIMUAT
    public function mount()
    {
        $this->programs = PaymentCategory::all();
    }

    // 2. LOGIKA OTOMATIS GANTI HARGA (SAMA SEPERTI FILE CREATE)
    public function updatedSelectedProgramId($value)
    {
        if ($value) {
            $program = PaymentCategory::find($value);
            // Ambil harga dari additional_fee, jika null set 0
            $this->form->amount = (int) ($program->additional_fee ?? 0);
            
            // Opsional: Jika ingin otomatis ganti catatan seperti Create, bisa tambahkan logic disini
            // Tapi biasanya Edit tidak mengubah catatan kecuali user mau.
        } 
        // Note: Jika pilih kosong, kita TIDAK mereset jadi 0 di mode edit 
        // supaya nominal lama tidak hilang tiba-tiba kalau user batal milih kategori.
    }

    public function render(): View
    {
        return view('livewire.cash-transactions.edit-cash-transaction');
    }

    #[On('edit-transaction')]
    public function setTransaction($id)
    {
        $transaction = CashTransaction::with('student')->find($id);

        if ($transaction) {
            $this->transactionId = $transaction->id;
            $this->studentName = $transaction->student->name ?? 'Siswa Tidak Dikenal';

            $this->form->amount = (int) $transaction->amount;
            $this->form->date_paid = $transaction->date_paid;
            $this->form->transaction_note = $transaction->note ?? "";
            
            // Reset input file & dropdown kategori setiap buka modal baru
            $this->reset(['proof', 'selectedProgramId']);

            $this->dispatch('open-edit-modal');
        }
    }

    public function update(): void
    {
        $this->validate([
            'form.amount' => 'required|numeric',
            'form.date_paid' => 'required|date',
            'proof' => 'nullable|image|max:10240',
        ]);

        if ($this->transactionId) {
            $transaction = CashTransaction::find($this->transactionId);
            
            $dataToUpdate = [
                'amount' => $this->form->amount,
                'date_paid' => $this->form->date_paid,
                'note' => $this->form->transaction_note,
            ];

            if ($this->proof) {
                if ($transaction->proof_of_payment) {
                    Storage::disk('public')->delete($transaction->proof_of_payment);
                }
                $dataToUpdate['proof_of_payment'] = $this->proof->store('payment-proofs', 'public');
            }

            $transaction->update($dataToUpdate);

            $this->dispatch('close-modal');
            $this->dispatch('success', message: 'Data berhasil diubah!');
            $this->dispatch('cash-transaction-updated')->to(CashTransactionCurrentWeekTable::class);
            
            $this->reset('proof');
        }
    }
}