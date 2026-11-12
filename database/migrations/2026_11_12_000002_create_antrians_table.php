<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migrasi tabel `antrians`.
 *
 * Tabel ini menyimpan data antrian kendaraan. Tabel ini berelasi dengan:
 * - tabel `kendaraans` melalui kolom `kendaraan_id` (satu kendaraan bisa mengantri berkali-kali)
 * - tabel `users` melalui kolom `user_id` (petugas yang mendaftarkan antrian)
 */
return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::create('antrians', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel kendaraans.
            $table->foreignId('kendaraan_id')
                ->constrained('kendaraans')
                ->cascadeOnDelete();

            // Relasi ke tabel users (petugas pendaftar antrian).
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('nomor_antrian', 10);                       // contoh: A001
            $table->date('tanggal');                                   // tanggal antrian
            $table->time('waktu');                                     // jam antrian
            $table->enum('status', ['menunggu', 'diproses', 'selesai', 'batal'])
                ->default('menunggu');                                 // status antrian

            $table->timestamps();
        });
    }

    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('antrians');
    }
};
