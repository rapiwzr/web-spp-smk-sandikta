<?php

namespace App\Livewire\PaymentCategories;

use App\Models\PaymentCategory;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Kategori Program')]
class PaymentCategoryIndex extends Component
{
    use WithPagination;

    public $name;
    public $additional_fee;
    public $selectedId;
    public $isEditMode = false;

    // --- VALIDASI ---
    protected $rules = [
        'name' => 'required|string|max:255',
        // KITA KEMBALIKAN 'min:0'
        // Karena ini adalah "Harga Utama", tidak boleh minus.
        // Kalau Beasiswa Full, cukup isi 0.
        'additional_fee' => 'required|numeric|min:0',
    ];

    public function render()
    {
        return view('livewire.payment-categories.payment-category-index', [
            'categories' => PaymentCategory::latest()->paginate(10)
        ]);
    }

    public function create()
    {
        $this->reset(['name', 'additional_fee', 'selectedId', 'isEditMode']);
        $this->dispatch('open-modal');
    }

    public function store()
    {
        $this->validate();

        PaymentCategory::create([
            'name' => $this->name,
            'additional_fee' => $this->additional_fee,
        ]);

        $this->dispatch('close-modal');
        $this->dispatch('success', message: 'Kategori berhasil ditambahkan!');
        $this->reset(['name', 'additional_fee']);
    }

    public function edit($id)
    {
        $category = PaymentCategory::find($id);
        
        $this->selectedId = $id;
        $this->name = $category->name;
        // Pastikan tampil sebagai integer agar rapi di form
        $this->additional_fee = (int) $category->additional_fee; 
        $this->isEditMode = true;

        $this->dispatch('open-modal');
    }

    public function update()
    {
        $this->validate();

        if ($this->selectedId) {
            $category = PaymentCategory::find($this->selectedId);
            $category->update([
                'name' => $this->name,
                'additional_fee' => $this->additional_fee,
            ]);

            $this->dispatch('close-modal');
            $this->dispatch('success', message: 'Kategori berhasil diupdate!');
            $this->reset(['name', 'additional_fee', 'selectedId', 'isEditMode']);
        }
    }

    public function delete($id)
    {
        $category = PaymentCategory::find($id);
        if ($category) {
            $category->delete();
            $this->dispatch('success', message: 'Kategori berhasil dihapus!');
        }
    }
}