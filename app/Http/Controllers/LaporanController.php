<?php

namespace App\Http\Controllers;

use App\Models\Antrian;
use App\Models\Kendaraan;
use App\Support\LaporanPdf;
use Illuminate\Http\Response;
use Illuminate\View\View;

/**
 * Controller laporan PDF (menggunakan library FPDF).
 *
 * Menyediakan halaman laporan serta dua berkas PDF:
 * - Laporan data kendaraan
 * - Laporan data antrian kendaraan
 *
 * Setiap laporan memuat judul, tanggal cetak, tabel data, dan jumlah data.
 */
class LaporanController extends Controller
{
    /**
     * Halaman pusat laporan yang berisi tombol cetak PDF.
     */
    public function index(): View
    {
        return view('laporan.index', [
            'jumlahKendaraan' => Kendaraan::count(),
            'jumlahAntrian' => Antrian::count(),
            'antrianPerStatus' => Antrian::query()
                ->selectRaw('status, COUNT(*) as jumlah')
                ->groupBy('status')
                ->pluck('jumlah', 'status'),
        ]);
    }

    /**
     * Mencetak laporan PDF data kendaraan.
     */
    public function kendaraan(): Response
    {
        $data = Kendaraan::with('user')->orderBy('nomor_polisi')->get();

        $pdf = new LaporanPdf('Laporan Data Kendaraan', 'Data seluruh kendaraan yang terdaftar');
        $pdf->AddPage();

        $baris = $data->values()->map(fn (Kendaraan $kendaraan, int $index): array => [
            (string) ($index + 1),
            $kendaraan->nomor_polisi,
            $kendaraan->jenis_kendaraan,
            $kendaraan->nama_pemilik,
            $kendaraan->user?->name ?? '-',
        ])->all();

        $pdf->tabel(
            ['No', 'Nomor Polisi', 'Jenis Kendaraan', 'Nama Pemilik', 'Didaftarkan Oleh'],
            [10, 35, 35, 60, 50],
            $baris,
            ['C', 'L', 'L', 'L', 'L']
        );

        $pdf->Ln(3);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(0, 7, 'Jumlah Data Kendaraan: '.$data->count(), 0, 1, 'L');

        return $this->responPdf($pdf, 'laporan-data-kendaraan.pdf');
    }

    /**
     * Mencetak laporan PDF data antrian kendaraan.
     */
    public function antrian(): Response
    {
        $data = Antrian::with(['kendaraan', 'user'])
            ->orderByDesc('tanggal')
            ->orderBy('waktu')
            ->get();

        $pdf = new LaporanPdf('Laporan Data Antrian Kendaraan', 'Data seluruh antrian kendaraan');
        $pdf->AddPage();

        $baris = $data->values()->map(fn (Antrian $antrian, int $index): array => [
            (string) ($index + 1),
            $antrian->nomor_antrian,
            $antrian->tanggal->format('d-m-Y'),
            $antrian->waktu_singkat,
            ucfirst($antrian->status),
            $antrian->kendaraan?->nomor_polisi ?? '-',
            $antrian->kendaraan?->nama_pemilik ?? '-',
        ])->all();

        $pdf->tabel(
            ['No', 'No. Antrian', 'Tanggal', 'Waktu', 'Status', 'Nomor Polisi', 'Nama Pemilik'],
            [10, 25, 25, 20, 25, 35, 50],
            $baris,
            ['C', 'C', 'C', 'C', 'C', 'L', 'L']
        );

        $pdf->Ln(3);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(0, 7, 'Jumlah Data Antrian: '.$data->count(), 0, 1, 'L');

        return $this->responPdf($pdf, 'laporan-data-antrian.pdf');
    }

    /**
     * Membentuk HTTP response berisi berkas PDF.
     *
     * Output('S') dari FPDF mengembalikan isi PDF dalam bentuk string
     * sehingga dapat langsung dikirim melalui response Laravel.
     */
    private function responPdf(LaporanPdf $pdf, string $namaBerkas): Response
    {
        return response($pdf->Output('S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$namaBerkas.'"',
        ]);
    }
}
