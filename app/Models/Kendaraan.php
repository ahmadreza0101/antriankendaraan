<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model Kendaraan
 *
 * Merepresentasikan tabel `kendaraans`.
 * Relasi:
 * - belongsTo(User)      : setiap kendaraan dimiliki oleh satu user.
 * - hasMany(Antrian)     : satu kendaraan dapat memiliki banyak data antrian.
 */
#[Fillable(['user_id', 'nomor_polisi', 'jenis_kendaraan', 'nama_pemilik'])]
class Kendaraan extends Model
{
    /**
     * Daftar jenis kendaraan yang boleh dipilih pada formulir.
     *
     * @var list<string>
     */
    public const JENIS_KENDARAAN = ['Motor', 'Mobil', 'Truk', 'Bus', 'Pick Up'];

    /**
     * Relasi ke tabel users (pemilik data kendaraan).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke tabel antrians.
     */
    public function antrians(): HasMany
    {
        return $this->hasMany(Antrian::class);
    }
}
