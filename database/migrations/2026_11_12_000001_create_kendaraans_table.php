<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migrasi tabel `kendaraans`.
 *
 * Tabel ini menyimpan data kendaraan yang dimiliki / didaftarkan oleh seorang user.
 * Terdapat foreign key `user_id` yang berelasi ke tabel `users`
 * (satu user dapat memiliki banyak kendaraan / one-to-many).
 */
return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::create('kendaraans', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel users: kendaraan ini milik user mana.
            // Jika user dihapus, data kendaraannya ikut terhapus (cascade).
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('nomor_polisi', 20)->unique();   // contoh: B 1234 XYZ
            $table->string('jenis_kendaraan', 50);          // contoh: Motor, Mobil, Truk, Bus
            $table->string('nama_pemilik', 100);            // nama pemilik kendaraan

            $table->timestamps();
        });
    }

    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('kendaraans');
    }
};
