<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Tambah Sumber Dana Baru</h1>
                <p class="text-gray-600 mt-1">Tambahkan data sumber dana baru</p>
            </div>
            <a href="{{ route('sumber-dana.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto py-8">
        <form method="POST" action="{{ route('sumber-dana.store') }}">
            @csrf

            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-6">Informasi Sumber Dana</h2>

                <div class="space-y-6">
                    <!-- Kode Sumber -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kode Sumber <span class="text-red-500">*</span></label>
                        <input type="text" name="kode_sumber" value="{{ old('kode_sumber') }}" required maxlength="50" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Contoh: APBD, APBN">
                        @error('kode_sumber')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Kode unik untuk sumber dana ini</p>
                    </div>

                    <!-- Nama Sumber -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Sumber Dana <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_sumber" value="{{ old('nama_sumber') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Contoh: Anggaran Pendapatan dan Belanja Daerah">
                        @error('nama_sumber')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                        <textarea name="deskripsi" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Jelaskan sumber dana ini...">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status Aktif -->
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', 1) == 1 ? 'checked' : '' }} class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Aktif</span>
                        </label>
                        <p class="mt-1 text-sm text-gray-500">Sumber dana yang aktif akan muncul di pilihan form</p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-4">
                <a href="{{ route('sumber-dana.index') }}" class="px-6 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan Sumber Dana
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
