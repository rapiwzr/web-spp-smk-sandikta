<?php

namespace App\Livewire\Forms;

use App\Models\SchoolMajor;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class UpdateSchoolMajorForm extends Form
{
    public ?SchoolMajor $schoolMajor;

    #[Validate]
    public ?string $name;

    public ?string $abbreviation;

    // --- 1. GANTI NAMA VARIABEL (Agar sinkron dengan Database) ---
    // Ubah $monthly_fee jadi $tuition_fee
    public $tuition_fee; 

    /**
     * Set data awal saat tombol Edit diklik.
     */
    public function setSchoolMajor(SchoolMajor $schoolMajor): void
    {
        $this->schoolMajor = $schoolMajor;
        
        // Isi properti form dengan data dari database
        $this->name = $schoolMajor->name;
        $this->abbreviation = $schoolMajor->abbreviation;
        
        // --- 2. AMBIL DATA TUITION FEE ---
        $this->tuition_fee = $schoolMajor->tuition_fee;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(): void
    {
        $this->validate();

        // Update data ke database
        $this->schoolMajor->update([
            'name' => $this->name,
            'abbreviation' => $this->abbreviation,
            
            // --- 3. SIMPAN KE KOLOM TUITION_FEE ---
            'tuition_fee' => $this->tuition_fee,
        ]);

        $this->reset();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|max:255',
            'abbreviation' => [
                'required',
                'max:255',
                Rule::unique('school_majors')->ignore($this->schoolMajor),
            ],
            
            // --- 4. VALIDASI TUITION_FEE ---
            'tuition_fee' => 'required|numeric|min:0',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama jurusan tidak boleh kosong!',
            'name.max' => 'Nama jurusan harus maksimal :max karakter!',

            'abbreviation.required' => 'Singkatan jurusan tidak boleh kosong!',
            'abbreviation.max' => 'Singkatan jurusan harus maksimal :max karakter!',
            'abbreviation.unique' => 'Singkatan jurusan sudah terdaftar!',

            // --- 5. PESAN ERROR UNTUK TUITION_FEE ---
            'tuition_fee.required' => 'Biaya SPP tidak boleh kosong!',
            'tuition_fee.numeric' => 'Biaya SPP harus berupa angka!',
            'tuition_fee.min' => 'Biaya SPP tidak boleh minus!',
        ];
    }
}