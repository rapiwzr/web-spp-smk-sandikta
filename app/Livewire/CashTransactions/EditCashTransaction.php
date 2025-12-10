<?php

namespace App\Livewire\CashTransactions;

use App\Livewire\Forms\StoreCashTransactionForm;
use App\Models\CashTransaction;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads; // 1. IMPORT LIBRARY UPLOAD
use Illuminate\Support\Facades\Storage; // IMPORT STORAGE UNTUK HAPUS FILE LAMA

class EditCashTransaction extends Component
{
    use WithFileUploads; // 2. GUNAKAN TRAIT INI

    public StoreCashTransactionForm $form; 
    public $transactionId;
    public $studentName;
    
    // 3. VARIABEL FILE FOTO BARU
    public $proof; 

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
            
            // Kita reset input file setiap kali buka modal baru
            $this->reset('proof');

            $this->dispatch('open-edit-modal');
        }
    }

    public function update(): void
    {
        $this->validate([
            'form.amount' => 'required|numeric',
            'form.date_paid' => 'required|date',
            'proof' => 'nullable|image|max:10240', // Validasi foto (Max 10MB)
        ]);

        if ($this->transactionId) {
            $transaction = CashTransaction::find($this->transactionId);
            
            // Data dasar yang akan diupdate
            $dataToUpdate = [
                'amount' => $this->form->amount,
                'date_paid' => $this->form->date_paid,
                'note' => $this->form->transaction_note,
            ];

            // 4. LOGIKA GANTI FOTO
            if ($this->proof) {
                // Hapus foto lama jika ada (biar server gak penuh)
                if ($transaction->proof_of_payment) {
                    Storage::disk('public')->delete($transaction->proof_of_payment);
                }

                // Upload foto baru & masukkan ke array update
                $dataToUpdate['proof_of_payment'] = $this->proof->store('payment-proofs', 'public');
            }

            // Eksekusi Update
            $transaction->update($dataToUpdate);

            $this->dispatch('close-modal');
            $this->dispatch('success', message: 'Data berhasil diubah!');
            $this->dispatch('cash-transaction-updated')->to(CashTransactionCurrentWeekTable::class);
            
            // Reset input file
            $this->reset('proof');
        }
    }
}