<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Seeder data awal aplikasi antrian kendaraan.
 *
 * Membuat satu akun petugas beserta data kendaraan dan antrian contoh
 * agar aplikasi langsung dapat diuji setelah `php artisan migrate --seed`.
 */
class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Jalankan seeder.
     */
    public function run(): void
    {
        // Akun petugas untuk login ke aplikasi.
        $petugas = User::factory()->create([
            'name' => 'Petugas Antrian',
            'email' => 'admin@antrian.test',
            'password' => Hash::make('password'),
        ]);

        // Data kendaraan contoh (berelasi ke tabel users melalui user_id).
        $daftarKendaraan = [
            ['nomor_polisi' => 'B 1234 XYZ', 'jenis_kendaraan' => 'Mobil', 'nama_pemilik' => 'Budi Santoso'],
            ['nomor_polisi' => 'D 5678 ABC', 'jenis_kendaraan' => 'Motor', 'nama_pemilik' => 'Siti Aminah'],
            ['nomor_polisi' => 'F 9012 DEF', 'jenis_kendaraan' => 'Truk', 'nama_pemilik' => 'Andi Wijaya'],
            ['nomor_polisi' => 'B 3456 GHI', 'jenis_kendaraan' => 'Bus', 'nama_pemilik' => 'Rina Kartika'],
            ['nomor_polisi' => 'L 7890 JKL', 'jenis_kendaraan' => 'Pick Up', 'nama_pemilik' => 'Dedi Kurniawan'],
            ['nomor_polisi' => 'AB 2468 MNO', 'jenis_kendaraan' => 'Mobil', 'nama_pemilik' => 'Maya Sari'],
        ];

        $daftarStatus = ['menunggu', 'diproses', 'selesai', 'batal'];

        foreach ($daftarKendaraan as $index => $data) {
            $kendaraan = $petugas->kendaraans()->create($data);

            // Setiap kendaraan dibuatkan satu data antrian untuk hari ini.
            $petugas->antrians()->create([
                'kendaraan_id' => $kendaraan->id,
                'nomor_antrian' => 'A'.str_pad((string) ($index + 1), 3, '0', STR_PAD_LEFT),
                'tanggal' => today()->toDateString(),
                'waktu' => sprintf('%02d:30', 8 + $index),
                'status' => $daftarStatus[$index % count($daftarStatus)],
            ]);
        }

        // Contoh antrian tambahan pada tanggal sebelumnya untuk kendaraan pertama.
        $petugas->antrians()->create([
            'kendaraan_id' => $petugas->kendaraans()->value('id'),
            'nomor_antrian' => 'A001',
            'tanggal' => today()->subDay()->toDateString(),
            'waktu' => '09:00',
            'status' => 'selesai',
        ]);
    }
}
