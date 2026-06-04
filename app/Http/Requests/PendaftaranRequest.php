<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PendaftaranRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Auto-format phone number
        $hp = $this->hp_wali;
        if ($hp) {
            // Remove all non-numeric characters
            $hp = preg_replace('/[^0-9]/', '', $hp);
            // Replace leading 62 with 0
            if (str_starts_with($hp, '62')) {
                $hp = '0' . substr($hp, 2);
            }
            $this->merge([
                'hp_wali' => $hp,
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // Hidden siswa fields
            'siswa_id' => 'required|integer',
            'nama_lengkap' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|string',
            'id_jenis_sekolah' => 'nullable|integer',
            'status_siswa' => 'required|string',

            // Editable / Additional fields
            'email_edit' => 'nullable|email|max:255',
            'hp_wali' => 'nullable|string|max:20',
            'nama_wali' => 'required|string|max:255',
            'alamat' => 'required|string|max:1000',
            'kelas' => 'nullable|string|max:20',
            'riwayat_kesehatan' => 'nullable|string|max:2000',
            'consent_wali' => 'required|accepted',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'nama_wali.required' => 'Nama orang tua/wali wajib diisi.',
            'alamat.required' => 'Alamat domisili wajib diisi.',
            'consent_wali.required' => 'Persetujuan wali wajib dicentang.',
            'consent_wali.accepted' => 'Anda harus menyetujui pernyataan persetujuan wali.',
            'email_edit.email' => 'Format email tidak valid.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'email_edit' => 'Email',
            'hp_wali' => 'Nomor HP Wali',
            'nama_wali' => 'Nama Orang Tua/Wali',
            'alamat' => 'Alamat Domisili',
            'kelas' => 'Kelas',
            'riwayat_kesehatan' => 'Riwayat Kesehatan',
            'consent_wali' => 'Persetujuan Wali',
        ];
    }
}
