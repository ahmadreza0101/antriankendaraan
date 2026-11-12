<?php

namespace App\Http\Requests;

use App\Models\Antrian;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validasi formulir data antrian.
 *
 * Digunakan untuk proses simpan (store) maupun ubah (update) data antrian.
 * Nomor antrian harus unik untuk setiap tanggal antrian.
 */
class AntrianRequest extends FormRequest
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
        $antrian = $this->route('antrian');

        return [
            'kendaraan_id' => ['required', 'exists:kendaraans,id'],
            'nomor_antrian' => [
                'required',
                'string',
                'max:10',
                Rule::unique('antrians', 'nomor_antrian')
                    ->where(fn ($query) => $query->where('tanggal', $this->input('tanggal')))
                    ->ignore($antrian?->id),
            ],
            'tanggal' => ['required', 'date'],
            // Menerima format 08:30 maupun 08:30:00 dari input type="time".
            'waktu' => ['required', 'date_format:H:i,H:i:s'],
            'status' => ['required', 'string', Rule::in(Antrian::STATUS)],
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
            'kendaraan_id' => 'kendaraan',
            'nomor_antrian' => 'nomor antrian',
            'tanggal' => 'tanggal',
            'waktu' => 'waktu',
            'status' => 'status',
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
            'kendaraan_id.required' => 'Kendaraan wajib dipilih.',
            'kendaraan_id.exists' => 'Kendaraan yang dipilih tidak ditemukan.',
            'nomor_antrian.required' => 'Nomor antrian wajib diisi.',
            'nomor_antrian.unique' => 'Nomor antrian sudah dipakai pada tanggal tersebut.',
            'nomor_antrian.max' => 'Nomor antrian maksimal 10 karakter.',
            'tanggal.required' => 'Tanggal wajib diisi.',
            'tanggal.date' => 'Format tanggal tidak valid.',
            'waktu.required' => 'Waktu wajib diisi.',
            'waktu.date_format' => 'Format waktu tidak valid (contoh: 08:30).',
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status antrian tidak valid.',
        ];
    }
}
