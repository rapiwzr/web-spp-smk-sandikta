<?php

namespace App\Livewire\SchoolMajors;

use App\Livewire\Forms\UpdateSchoolMajorForm;
use App\Models\SchoolMajor;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class EditSchoolMajor extends Component
{
    public UpdateSchoolMajorForm $form;

    /**
     * Render the view.
     */
    public function render(): View
    {
        return view('livewire.school-majors.edit-school-major');
    }

    /**
     * Set data awal saat tombol Edit diklik di tabel.
     */
    #[On('school-major-edit')]
    public function setValue(SchoolMajor $schoolMajor): void
    {
        // --- PERBAIKAN DI SINI ---
        // Kita panggil method setSchoolMajor() yang ada di Form Object.
        // Method ini akan otomatis mengisi nama, singkatan, DAN monthly_fee.
        $this->form->setSchoolMajor($schoolMajor);
    }

    /**
     * Update data ke database saat tombol Simpan diklik.
     */
    public function save(): void // <-- SAYA UBAH NAMANYA JADI 'save' (sebelumnya 'edit')
    {
        // Panggil fungsi update di form object
        $this->form->update();

        // Tutup modal dan beri notifikasi
        $this->dispatch('close-modal');
        $this->dispatch('success', message: 'Data berhasil diubah!');
        
        // Refresh tabel
        $this->dispatch('school-major-updated')->to(SchoolMajorTable::class);
    }
}