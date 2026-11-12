<?php

namespace App\Support;

use Codedge\Fpdf\Fpdf\Fpdf;

/**
 * Kelas pembantu pembuatan laporan PDF menggunakan library FPDF.
 *
 * Kelas ini mewarisi class FPDF bawaan package `codedge/laravel-fpdf` dan
 * menambahkan:
 * - Header  : judul laporan + tanggal cetak (otomatis pada setiap halaman)
 * - Footer  : nomor halaman
 * - tabel() : fungsi bantu untuk mencetak tabel data
 */
class LaporanPdf extends Fpdf
{
    /** Judul laporan yang tampil di bagian atas halaman. */
    public string $judul;

    /** Keterangan tambahan di bawah judul (opsional). */
    public string $keterangan;

    /** Tanggal laporan dicetak. */
    public string $tanggalCetak;

    /**
     * @param  string  $judul       Judul laporan, contoh: "Laporan Data Kendaraan".
     * @param  string  $keterangan  Keterangan tambahan di bawah judul.
     */
    public function __construct(string $judul, string $keterangan = '')
    {
        // Orientasi Potret, satuan milimeter, ukuran kertas A4.
        parent::__construct('P', 'mm', 'A4');

        $this->judul = $judul;
        $this->keterangan = $keterangan;
        $this->tanggalCetak = now()->format('d-m-Y H:i').' WIB';

        $this->AliasNbPages();                  // mengaktifkan penomoran {nb} pada footer
        $this->SetMargins(10, 10, 10);          // margin kiri, atas, kanan
        $this->SetAutoPageBreak(true, 20);      // page break otomatis, sisakan 20mm bawah
        $this->SetTitle($judul);
    }

    /**
     * Header halaman: dicetak otomatis oleh FPDF pada setiap halaman baru.
     */
    public function Header(): void
    {
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 8, $this->teks('APLIKASI ANTRIAN KENDARAAN'), 0, 1, 'C');

        $this->SetFont('Arial', 'B', 11);
        $this->Cell(0, 6, $this->teks($this->judul), 0, 1, 'C');

        $this->SetFont('Arial', '', 9);
        $this->Cell(0, 5, $this->teks('Tanggal Cetak: '.$this->tanggalCetak), 0, 1, 'C');

        if ($this->keterangan !== '') {
            $this->Cell(0, 5, $this->teks($this->keterangan), 0, 1, 'C');
        }

        $this->Ln(2);
        $this->SetDrawColor(100, 100, 100);
        $this->Line(10, $this->GetY(), 200, $this->GetY());
        $this->Ln(3);
    }

    /**
     * Footer halaman: nomor halaman.
     */
    public function Footer(): void
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(100, 100, 100);
        $this->Cell(0, 10, $this->teks('Halaman '.$this->PageNo().' dari {nb}'), 0, 0, 'C');
        $this->SetTextColor(0, 0, 0);
    }

    /**
     * Mencetak tabel data ke halaman PDF.
     *
     * @param  list<string>   $kolom    Judul setiap kolom.
     * @param  list<float>    $lebar    Lebar setiap kolom (mm).
     * @param  iterable<array<int, string>>  $baris  Isi tabel.
     * @param  list<string>   $perataan  Perataan teks tiap kolom (L/C/R), default "L".
     */
    public function tabel(array $kolom, array $lebar, iterable $baris, array $perataan = []): void
    {
        // Baris judul kolom.
        $this->SetFont('Arial', 'B', 9);
        $this->SetFillColor(230, 232, 235);
        foreach ($kolom as $index => $judulKolom) {
            $this->Cell($lebar[$index], 8, $this->teks($judulKolom), 1, 0, 'C', true);
        }
        $this->Ln();

        // Baris isi tabel.
        $this->SetFont('Arial', '', 9);
        foreach ($baris as $data) {
            foreach (array_values($data) as $index => $nilai) {
                $this->Cell($lebar[$index], 7, $this->teks((string) $nilai), 1, 0, $perataan[$index] ?? 'L');
            }
            $this->Ln();
        }
    }

    /**
     * Konversi teks UTF-8 ke ISO-8859-1 karena font bawaan FPDF (Arial/Helvetica)
     * hanya mendukung karakter latin.
     */
    private function teks(string $teks): string
    {
        return mb_convert_encoding($teks, 'ISO-8859-1', 'UTF-8');
    }
}
