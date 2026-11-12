{{--
    Partial formulir data kendaraan.
    Dipakai bersama oleh halaman tambah (create) dan ubah (edit).

    Variabel:
    - $kendaraan : data kendaraan yang sedang diedit (kosong saat menambah data)
--}}
@php
    $data = $kendaraan ?? null;
@endphp

<div class="space-y-5">
    {{-- Nomor Polisi --}}
    <div>
        <x-input-label for="nomor_polisi" value="Nomor Polisi" />
        <x-text-input id="nomor_polisi" name="nomor_polisi" type="text"
                      class="mt-1 block w-full"
                      placeholder="Contoh: B 1234 XYZ"
                      :value="old('nomor_polisi', $data?->nomor_polisi)" />
        <x-input-error :messages="$errors->get('nomor_polisi')" class="mt-2" />
    </div>

    {{-- Jenis Kendaraan --}}
    <div>
        <x-input-label for="jenis_kendaraan" value="Jenis Kendaraan" />
        <select id="jenis_kendaraan" name="jenis_kendaraan"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">-- Pilih Jenis Kendaraan --</option>
            @foreach (\App\Models\Kendaraan::JENIS_KENDARAAN as $jenis)
                <option value="{{ $jenis }}" @selected(old('jenis_kendaraan', $data?->jenis_kendaraan) === $jenis)>
                    {{ $jenis }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('jenis_kendaraan')" class="mt-2" />
    </div>

    {{-- Nama Pemilik --}}
    <div>
        <x-input-label for="nama_pemilik" value="Nama Pemilik" />
        <x-text-input id="nama_pemilik" name="nama_pemilik" type="text"
                      class="mt-1 block w-full"
                      placeholder="Contoh: Budi Santoso"
                      :value="old('nama_pemilik', $data?->nama_pemilik)" />
        <x-input-error :messages="$errors->get('nama_pemilik')" class="mt-2" />
    </div>
</div>
