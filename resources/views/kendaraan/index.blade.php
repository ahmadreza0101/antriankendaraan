<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Data Kendaraan
                </h2>
                <p class="text-sm text-gray-500">Kelola data kendaraan beserta pemiliknya.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                {{-- Tombol Cetak PDF --}}
                <a href="{{ route('laporan.kendaraan') }}" target="_blank"
                   class="inline-flex items-center gap-2 rounded-md bg-red-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white shadow-sm transition hover:bg-red-500">
                    Cetak PDF
                </a>
                <a href="{{ route('kendaraan.create') }}"
                   class="inline-flex items-center gap-2 rounded-md bg-indigo-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white shadow-sm transition hover:bg-indigo-500">
                    Tambah Kendaraan
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-4 sm:px-6 lg:px-8">

            @if (session('sukses'))
                <div class="rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                    {{ session('sukses') }}
                </div>
            @endif

            {{-- Form pencarian --}}
            <div class="bg-white p-4 shadow-sm sm:rounded-lg">
                <form method="GET" action="{{ route('kendaraan.index') }}" class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <input type="text" name="cari" value="{{ $cari }}"
                           placeholder="Cari nomor polisi, jenis, atau nama pemilik..."
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:w-96">
                    <div class="flex gap-2">
                        <button type="submit"
                                class="rounded-md bg-gray-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-gray-700">
                            Cari
                        </button>
                        @if ($cari !== '')
                            <a href="{{ route('kendaraan.index') }}"
                               class="rounded-md border border-gray-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 transition hover:bg-gray-50">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Tabel data kendaraan --}}
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600">No</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600">Nomor Polisi</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600">Jenis Kendaraan</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600">Nama Pemilik</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600">Didaftarkan Oleh</th>
                                <th class="px-4 py-3 text-center font-semibold text-gray-600">Jumlah Antrian</th>
                                <th class="px-4 py-3 text-right font-semibold text-gray-600">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($kendaraans as $kendaraan)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-gray-600">
                                        {{ $kendaraans->firstItem() + $loop->index }}
                                    </td>
                                    <td class="px-4 py-3 font-semibold text-gray-800">
                                        {{ $kendaraan->nomor_polisi }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-700">
                                        <span class="inline-flex rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-medium text-blue-700">
                                            {{ $kendaraan->jenis_kendaraan }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-gray-700">{{ $kendaraan->nama_pemilik }}</td>
                                    <td class="px-4 py-3 text-gray-500">{{ $kendaraan->user?->name ?? '-' }}</td>
                                    <td class="px-4 py-3 text-center text-gray-600">{{ $kendaraan->antrians_count }}</td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-end gap-3">
                                            <a href="{{ route('kendaraan.edit', $kendaraan) }}"
                                               class="font-medium text-indigo-600 hover:text-indigo-800">Edit</a>
                                            <form method="POST" action="{{ route('kendaraan.destroy', $kendaraan) }}"
                                                  onsubmit="return confirm('Yakin ingin menghapus data kendaraan ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="font-medium text-red-600 hover:text-red-800">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-10 text-center text-gray-500">
                                        Belum ada data kendaraan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-gray-100 px-4 py-3">
                    {{ $kendaraans->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
