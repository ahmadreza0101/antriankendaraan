<?php

namespace App\Http\Requests;

use App\Models\Kendaraan;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validasi formulir data kendaraan.
 *
 * Digunakan untuk proses simpan (store) maupun ubah (update) data kendaraan.
 * Saat proses ubah, validasi unik nomor polisi akan mengabaikan data yang sedang diedit.
 */
class KendaraanRequest extends FormRequest
{
    /**
     * Hanya user yang sudah login yang boleh memproses formulir ini.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Aturan validasi formulir.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        // Saat update, route model binding mengembalikan model Kendaraan.
        $kendaraan = $this->route('kendaraan');

        return [
            'nomor_polisi' => [
                'required',
                'string',
                'max:20',
                Rule::unique('kendaraans', 'nomor_polisi')->ignore($kendaraan?->id),
            ],
            'jenis_kendaraan' => ['required', 'string', Rule::in(Kendaraan::JENIS_KENDARAAN)],
            'nama_pemilik' => ['required', 'string', 'max:100'],
        ];
    }

    /**
     * Nama atribut yang lebih mudah dibaca pada pesan error.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'nomor_polisi' => 'nomor polisi',
            'jenis_kendaraan' => 'jenis kendaraan',
            'nama_pemilik' => 'nama pemilik',
        ];
    }

    /**
     * Pesan error dalam Bahasa Indonesia.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nomor_polisi.required' => 'Nomor polisi wajib diisi.',
            'nomor_polisi.unique' => 'Nomor polisi sudah terdaftar.',
            'nomor_polisi.max' => 'Nomor polisi maksimal 20 karakter.',
            'jenis_kendaraan.required' => 'Jenis kendaraan wajib dipilih.',
            'jenis_kendaraan.in' => 'Jenis kendaraan tidak valid.',
            'nama_pemilik.required' => 'Nama pemilik wajib diisi.',
            'nama_pemilik.max' => 'Nama pemilik maksimal 100 karakter.',
        ];
    }
}
