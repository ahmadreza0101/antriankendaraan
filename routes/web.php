<?php

use App\Http\Controllers\AntrianController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProfileController;
use App\Models\Antrian;
use App\Models\Kendaraan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Route Aplikasi Antrian Kendaraan
|--------------------------------------------------------------------------
| Semua route data kendaraan, antrian, dan laporan hanya dapat diakses
| oleh user yang sudah login (middleware auth).
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Halaman utama: ringkasan jumlah data.
    Route::get('/dashboard', function () {
        return view('dashboard', [
            'jumlahKendaraan' => Kendaraan::count(),
            'jumlahAntrian' => Antrian::count(),
            'antrianMenunggu' => Antrian::where('status', 'menunggu')->count(),
            'antrianSelesai' => Antrian::where('status', 'selesai')->count(),
        ]);
    })->name('dashboard');

    // CRUD data kendaraan.
    Route::resource('kendaraan', KendaraanController::class)->except('show');

    // CRUD data antrian kendaraan.
    Route::resource('antrian', AntrianController::class)->except('show');

    // Laporan PDF (FPDF).
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/kendaraan/pdf', [LaporanController::class, 'kendaraan'])->name('laporan.kendaraan');
    Route::get('/laporan/antrian/pdf', [LaporanController::class, 'antrian'])->name('laporan.antrian');

    // Profil user (bawaan Laravel Breeze).
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
