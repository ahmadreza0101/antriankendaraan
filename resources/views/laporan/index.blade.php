<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Laporan PDF</h2>
        <p class="text-sm text-gray-500">Cetak laporan data kendaraan dan antrian dalam format PDF.</p>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">

            {{-- Ringkasan jumlah data --}}
            <div class="grid gap-4 sm:grid-cols-2">
                <div class="rounded-lg bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-gray-500">Total Data Kendaraan</p>
                    <p class="mt-1 text-3xl font-bold text-gray-800">{{ $jumlahKendaraan }}</p>
                </div>
                <div class="rounded-lg bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-gray-500">Total Data Antrian</p>
                    <p class="mt-1 text-3xl font-bold text-gray-800">{{ $jumlahAntrian }}</p>
                </div>
            </div>

            {{-- Rincian antrian per status --}}
            <div class="rounded-lg bg-white p-5 shadow-sm">
                <p class="mb-3 text-sm font-semibold text-gray-700">Antrian Berdasarkan Status</p>
                <div class="grid gap-3 sm:grid-cols-4">
                    @foreach (\App\Models\Antrian::STATUS as $status)
                        <div class="rounded-md border border-gray-200 px-4 py-3">
                            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                                {{ $status }}
                            </p>
                            <p class="text-xl font-semibold text-gray-800">
                                {{ $antrianPerStatus[$status] ?? 0 }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Tombol cetak PDF --}}
            <div class="grid gap-4 sm:grid-cols-2">
                <div class="flex flex-col justify-between rounded-lg bg-white p-5 shadow-sm">
                    <div>
                        <p class="font-semibold text-gray-800">Laporan Data Kendaraan</p>
                        <p class="mt-1 text-sm text-gray-500">
                            Memuat judul laporan, tanggal cetak, tabel data kendaraan, dan jumlah data.
                        </p>
                    </div>
                    <a href="{{ route('laporan.kendaraan') }}" target="_blank"
                       class="mt-4 inline-flex w-fit items-center rounded-md bg-red-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white shadow-sm transition hover:bg-red-500">
                        Cetak PDF Kendaraan
                    </a>
                </div>

                <div class="flex flex-col justify-between rounded-lg bg-white p-5 shadow-sm">
                    <div>
                        <p class="font-semibold text-gray-800">Laporan Data Antrian</p>
                        <p class="mt-1 text-sm text-gray-500">
                            Memuat judul laporan, tanggal cetak, tabel data antrian, dan jumlah data.
                        </p>
                    </div>
                    <a href="{{ route('laporan.antrian') }}" target="_blank"
                       class="mt-4 inline-flex w-fit items-center rounded-md bg-red-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white shadow-sm transition hover:bg-red-500">
                        Cetak PDF Antrian
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
