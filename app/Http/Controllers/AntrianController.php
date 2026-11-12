<?php

namespace App\Http\Controllers;

use App\Http\Requests\AntrianRequest;
use App\Models\Antrian;
use App\Models\Kendaraan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controller CRUD data antrian kendaraan.
 *
 * Mengelola data antrian yang berelasi dengan tabel `kendaraans` dan tabel `users`.
 */
class AntrianController extends Controller
{
    /**
     * Menampilkan daftar antrian kendaraan.
     */
    public function index(Request $request): View
    {
        $cari = trim((string) $request->input('cari'));
        $status = trim((string) $request->input('status'));

        $antrians = Antrian::query()
            ->with(['kendaraan', 'user'])   // eager load relasi kendaraan & user
            ->when($cari !== '', function ($query) use ($cari) {
                $query->where(function ($sub) use ($cari) {
                    $sub->where('nomor_antrian', 'like', "%{$cari}%")
                        ->orWhereHas('kendaraan', function ($kendaraan) use ($cari) {
                            $kendaraan->where('nomor_polisi', 'like', "%{$cari}%")
                                ->orWhere('nama_pemilik', 'like', "%{$cari}%");
                        });
                });
            })
            ->when(in_array($status, Antrian::STATUS, true), function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->orderByDesc('tanggal')
            ->orderBy('waktu')
            ->paginate(10)
            ->withQueryString();

        return view('antrian.index', [
            'antrians' => $antrians,
            'cari' => $cari,
            'status' => $status,
        ]);
    }

    /**
     * Menampilkan formulir tambah antrian.
     */
    public function create(): View
    {
        return view('antrian.create', [
            'kendaraans' => Kendaraan::orderBy('nomor_polisi')->get(),
            'nomorBerikutnya' => $this->nomorAntrianBerikutnya(),
        ]);
    }

    /**
     * Menyimpan data antrian baru.
     */
    public function store(AntrianRequest $request): RedirectResponse
    {
        $request->user()->antrians()->create($request->validated());

        return redirect()
            ->route('antrian.index')
            ->with('sukses', 'Data antrian berhasil ditambahkan.');
    }

    /**
     * Menampilkan formulir ubah antrian.
     */
    public function edit(Antrian $antrian): View
    {
        return view('antrian.edit', [
            'antrian' => $antrian,
            'kendaraans' => Kendaraan::orderBy('nomor_polisi')->get(),
        ]);
    }

    /**
     * Memperbarui data antrian.
     */
    public function update(AntrianRequest $request, Antrian $antrian): RedirectResponse
    {
        $antrian->update($request->validated());

        return redirect()
            ->route('antrian.index')
            ->with('sukses', 'Data antrian berhasil diperbarui.');
    }

    /**
     * Menghapus data antrian.
     */
    public function destroy(Antrian $antrian): RedirectResponse
    {
        $antrian->delete();

        return redirect()
            ->route('antrian.index')
            ->with('sukses', 'Data antrian berhasil dihapus.');
    }

    /**
     * Membuat usulan nomor antrian berikutnya, contoh: A001, A002, ...
     * Nomor dihitung dari jumlah antrian pada tanggal hari ini.
     */
    private function nomorAntrianBerikutnya(): string
    {
        $jumlah = Antrian::whereDate('tanggal', today())->count();

        return 'A'.str_pad((string) ($jumlah + 1), 3, '0', STR_PAD_LEFT);
    }
}
