<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tambah Kendaraan</h2>
        <p class="text-sm text-gray-500">Lengkapi formulir di bawah untuk menambah data kendaraan.</p>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-2xl sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('kendaraan.store') }}"
                  class="bg-white p-6 shadow-sm sm:rounded-lg">
                @csrf

                @include('kendaraan._form')

                <div class="mt-6 flex items-center justify-end gap-3 border-t border-gray-100 pt-5">
                    <a href="{{ route('kendaraan.index') }}"
                       class="rounded-md border border-gray-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 transition hover:bg-gray-50">
                        Batal
                    </a>
                    <x-primary-button>Simpan</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
