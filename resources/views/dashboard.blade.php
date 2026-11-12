<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
        <p class="text-sm text-gray-500">Ringkasan data aplikasi antrian kendaraan.</p>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">

            {{-- Kartu ringkasan --}}
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-lg bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-gray-500">Data Kendaraan</p>
                    <p class="mt-1 text-3xl font-bold text-gray-800">{{ $jumlahKendaraan }}</p>
                </div>
                <div class="rounded-lg bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-gray-500">Total Antrian</p>
                    <p class="mt-1 text-3xl font-bold text-gray-800">{{ $jumlahAntrian }}</p>
                </div>
                <div class="rounded-lg bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-gray-500">Antrian Menunggu</p>
                    <p class="mt-1 text-3xl font-bold text-amber-600">{{ $antrianMenunggu }}</p>
                </div>
                <div class="rounded-lg bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-gray-500">Antrian Selesai</p>
                    <p class="mt-1 text-3xl font-bold text-green-600">{{ $antrianSelesai }}</p>
                </div>
            </div>

            {{-- Menu cepat --}}
            <div class="grid gap-4 sm:grid-cols-3">
                <a href="{{ route('kendaraan.index') }}"
                   class="rounded-lg bg-white p-5 shadow-sm transition hover:shadow-md">
                    <p class="font-semibold text-gray-800">Data Kendaraan</p>
                    <p class="mt-1 text-sm text-gray-500">Tambah, ubah, dan hapus data kendaraan.</p>
                </a>
                <a href="{{ route('antrian.index') }}"
                   class="rounded-lg bg-white p-5 shadow-sm transition hover:shadow-md">
                    <p class="font-semibold text-gray-800">Daftar Antrian</p>
                    <p class="mt-1 text-sm text-gray-500">Lihat dan kelola antrian kendaraan.</p>
                </a>
                <a href="{{ route('laporan.index') }}"
                   class="rounded-lg bg-white p-5 shadow-sm transition hover:shadow-md">
                    <p class="font-semibold text-gray-800">Laporan PDF</p>
                    <p class="mt-1 text-sm text-gray-500">Cetak laporan kendaraan dan antrian.</p>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
