<?php

namespace App\Http\Controllers;

use App\Http\Requests\KendaraanRequest;
use App\Models\Kendaraan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controller CRUD data kendaraan.
 *
 * Menerapkan pola MVC: Controller ini mengatur alur data antara Model `Kendaraan`
 * dan View `resources/views/kendaraan/*`.
 */
class KendaraanController extends Controller
{
    /**
     * Menampilkan daftar kendaraan beserta fitur pencarian dan paginasi.
     */
    public function index(Request $request): View
    {
        $cari = trim((string) $request->input('cari'));

        $kendaraans = Kendaraan::query()
            ->with('user')                 // eager load relasi ke tabel users
            ->withCount('antrians')        // hitung jumlah antrian tiap kendaraan
            ->when($cari !== '', function ($query) use ($cari) {
                $query->where(function ($sub) use ($cari) {
                    $sub->where('nomor_polisi', 'like', "%{$cari}%")
                        ->orWhere('jenis_kendaraan', 'like', "%{$cari}%")
                        ->orWhere('nama_pemilik', 'like', "%{$cari}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('kendaraan.index', [
            'kendaraans' => $kendaraans,
            'cari' => $cari,
        ]);
    }

    /**
     * Menampilkan formulir tambah kendaraan.
     */
    public function create(): View
    {
        return view('kendaraan.create');
    }

    /**
     * Menyimpan data kendaraan baru.
     *
     * Data otomatis berelasi dengan user yang sedang login melalui relasi `kendaraans()`.
     */
    public function store(KendaraanRequest $request): RedirectResponse
    {
        $request->user()->kendaraans()->create($request->validated());

        return redirect()
            ->route('kendaraan.index')
            ->with('sukses', 'Data kendaraan berhasil ditambahkan.');
    }

    /**
     * Menampilkan formulir ubah kendaraan.
     */
    public function edit(Kendaraan $kendaraan): View
    {
        return view('kendaraan.edit', [
            'kendaraan' => $kendaraan,
        ]);
    }

    /**
     * Memperbarui data kendaraan.
     */
    public function update(KendaraanRequest $request, Kendaraan $kendaraan): RedirectResponse
    {
        $kendaraan->update($request->validated());

        return redirect()
            ->route('kendaraan.index')
            ->with('sukses', 'Data kendaraan berhasil diperbarui.');
    }

    /**
     * Menghapus data kendaraan.
     */
    public function destroy(Kendaraan $kendaraan): RedirectResponse
    {
        $kendaraan->delete();

        return redirect()
            ->route('kendaraan.index')
            ->with('sukses', 'Data kendaraan berhasil dihapus.');
    }
}
