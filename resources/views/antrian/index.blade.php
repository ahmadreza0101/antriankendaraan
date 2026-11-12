<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Daftar Antrian Kendaraan
                </h2>
                <p class="text-sm text-gray-500">Daftar seluruh antrian kendaraan yang terdaftar.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                {{-- Tombol Cetak PDF --}}
                <a href="{{ route('laporan.antrian') }}" target="_blank"
                   class="inline-flex items-center gap-2 rounded-md bg-red-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white shadow-sm transition hover:bg-red-500">
                    Cetak PDF
                </a>
                <a href="{{ route('antrian.create') }}"
                   class="inline-flex items-center gap-2 rounded-md bg-indigo-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white shadow-sm transition hover:bg-indigo-500">
                    Tambah Antrian
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

            {{-- Filter & pencarian --}}
            <div class="bg-white p-4 shadow-sm sm:rounded-lg">
                <form method="GET" action="{{ route('antrian.index') }}"
                      class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <input type="text" name="cari" value="{{ $cari }}"
                           placeholder="Cari nomor antrian, nomor polisi, atau pemilik..."
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:w-80">

                    <select name="status"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:w-48">
                        <option value="">Semua Status</option>
                        @foreach (\App\Models\Antrian::STATUS as $pilihan)
                            <option value="{{ $pilihan }}" @selected($status === $pilihan)>{{ ucfirst($pilihan) }}</option>
                        @endforeach
                    </select>

                    <div class="flex gap-2">
                        <button type="submit"
                                class="rounded-md bg-gray-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-gray-700">
                            Terapkan
                        </button>
                        @if ($cari !== '' || $status !== '')
                            <a href="{{ route('antrian.index') }}"
                               class="rounded-md border border-gray-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 transition hover:bg-gray-50">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Tabel data antrian --}}
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600">No</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600">No. Antrian</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600">Tanggal</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600">Waktu</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600">Kendaraan</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600">Status</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600">Petugas</th>
                                <th class="px-4 py-3 text-right font-semibold text-gray-600">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($antrians as $antrian)
                                @php
                                    $warna = match ($antrian->status) {
                                        'menunggu' => 'bg-yellow-100 text-yellow-800',
                                        'diproses' => 'bg-blue-100 text-blue-800',
                                        'selesai' => 'bg-green-100 text-green-800',
                                        'batal' => 'bg-red-100 text-red-800',
                                        default => 'bg-gray-100 text-gray-800',
                                    };
                                @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-gray-600">
                                        {{ $antrians->firstItem() + $loop->index }}
                                    </td>
                                    <td class="px-4 py-3 font-semibold text-gray-800">
                                        {{ $antrian->nomor_antrian }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-700">
                                        {{ $antrian->tanggal->format('d-m-Y') }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-700">{{ $antrian->waktu_singkat }}</td>
                                    <td class="px-4 py-3 text-gray-700">
                                        <div class="font-medium">{{ $antrian->kendaraan?->nomor_polisi ?? '-' }}</div>
                                        <div class="text-xs text-gray-500">
                                            {{ $antrian->kendaraan?->nama_pemilik ?? '-' }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium {{ $warna }}">
                                            {{ ucfirst($antrian->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-gray-500">{{ $antrian->user?->name ?? '-' }}</td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-end gap-3">
                                            <a href="{{ route('antrian.edit', $antrian) }}"
                                               class="font-medium text-indigo-600 hover:text-indigo-800">Edit</a>
                                            <form method="POST" action="{{ route('antrian.destroy', $antrian) }}"
                                                  onsubmit="return confirm('Yakin ingin menghapus data antrian ini?')">
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
                                    <td colspan="8" class="px-4 py-10 text-center text-gray-500">
                                        Belum ada data antrian.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-gray-100 px-4 py-3">
                    {{ $antrians->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
