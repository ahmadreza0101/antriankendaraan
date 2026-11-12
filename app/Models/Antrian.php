<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model Antrian
 *
 * Merepresentasikan tabel `antrians`.
 * Relasi:
 * - belongsTo(Kendaraan) : antrian dimiliki oleh satu kendaraan.
 * - belongsTo(User)      : antrian didaftarkan oleh satu user (petugas).
 */
#[Fillable(['kendaraan_id', 'user_id', 'nomor_antrian', 'tanggal', 'waktu', 'status'])]
class Antrian extends Model
{
    /**
     * Daftar status antrian yang tersedia.
     *
     * @var list<string>
     */
    public const STATUS = ['menunggu', 'diproses', 'selesai', 'batal'];

    /**
     * Casting tipe data kolom.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
        ];
    }

    /**
     * Relasi ke tabel kendaraans.
     */
    public function kendaraan(): BelongsTo
    {
        return $this->belongsTo(Kendaraan::class);
    }

    /**
     * Relasi ke tabel users (petugas yang mendaftarkan antrian).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Accessor: kolom TIME di MySQL menyimpan nilai "HH:MM:SS",
     * accessor ini mengubahnya menjadi "HH:MM" agar rapi saat ditampilkan.
     */
    protected function waktuSingkat(): Attribute
    {
        return Attribute::get(fn (): string => substr((string) $this->waktu, 0, 5));
    }
}
