{{--
    Partial formulir data antrian.
    Dipakai bersama oleh halaman tambah (create) dan ubah (edit).

    Variabel:
    - $kendaraans       : daftar kendaraan untuk pilihan dropdown
    - $antrian          : data antrian yang sedang diedit (kosong saat menambah data)
    - $nomorBerikutnya  : usulan nomor antrian berikutnya (hanya saat menambah data)
--}}
@php
    $data = $antrian ?? null;
@endphp

<div class="space-y-5">
    {{-- Pilih Kendaraan --}}
    <div>
        <x-input-label for="kendaraan_id" value="Kendaraan" />
        <select id="kendaraan_id" name="kendaraan_id"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">-- Pilih Kendaraan --</option>
            @foreach ($kendaraans as $kendaraan)
                <option value="{{ $kendaraan->id }}"
                    @selected((string) old('kendaraan_id', $data?->kendaraan_id) === (string) $kendaraan->id)>
                    {{ $kendaraan->nomor_polisi }} - {{ $kendaraan->jenis_kendaraan }} ({{ $kendaraan->nama_pemilik }})
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('kendaraan_id')" class="mt-2" />
        @if ($kendaraans->isEmpty())
            <p class="mt-2 text-sm text-amber-600">
                Belum ada data kendaraan. Silakan
                <a href="{{ route('kendaraan.create') }}" class="font-medium underline">tambah kendaraan</a> terlebih dahulu.
            </p>
        @endif
    </div>

    {{-- Nomor Antrian --}}
    <div>
        <x-input-label for="nomor_antrian" value="Nomor Antrian" />
        <x-text-input id="nomor_antrian" name="nomor_antrian" type="text"
                      class="mt-1 block w-full"
                      placeholder="Contoh: A001"
                      :value="old('nomor_antrian', $data?->nomor_antrian ?? ($nomorBerikutnya ?? ''))" />
        <x-input-error :messages="$errors->get('nomor_antrian')" class="mt-2" />
    </div>

    <div class="grid gap-5 sm:grid-cols-2">
        {{-- Tanggal --}}
        <div>
            <x-input-label for="tanggal" value="Tanggal" />
            <x-text-input id="tanggal" name="tanggal" type="date"
                          class="mt-1 block w-full"
                          :value="old('tanggal', $data?->tanggal?->format('Y-m-d') ?? now()->format('Y-m-d'))" />
            <x-input-error :messages="$errors->get('tanggal')" class="mt-2" />
        </div>

        {{-- Waktu --}}
        <div>
            <x-input-label for="waktu" value="Waktu" />
            <x-text-input id="waktu" name="waktu" type="time"
                          class="mt-1 block w-full"
                          :value="old('waktu', $data?->waktu_singkat ?? now()->format('H:i'))" />
            <x-input-error :messages="$errors->get('waktu')" class="mt-2" />
        </div>
    </div>

    {{-- Status Antrian --}}
    <div>
        <x-input-label for="status" value="Status Antrian" />
        <select id="status" name="status"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @foreach (\App\Models\Antrian::STATUS as $pilihan)
                <option value="{{ $pilihan }}"
                    @selected(old('status', $data?->status ?? 'menunggu') === $pilihan)>
                    {{ ucfirst($pilihan) }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('status')" class="mt-2" />
    </div>
</div>
