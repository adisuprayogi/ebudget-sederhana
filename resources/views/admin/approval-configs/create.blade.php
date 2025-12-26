<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Tambah Konfigurasi Approval</h1>
                <p class="text-gray-600 mt-1">Buat konfigurasi approval baru</p>
            </div>
            <a href="{{ route('admin.approval-configs.index') }}" class="text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto py-8">
        <form method="POST" action="{{ route('admin.approval-configs.store') }}" class="bg-white rounded-lg shadow-sm p-6">
            @csrf

            <div class="space-y-6">
                <!-- Jenis Pengajuan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Pengajuan</label>
                    <select name="jenis_pengajuan" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @foreach($jenisPengajuanList as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('jenis_pengajuan')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Minimal Nominal -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Minimal Nominal</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">Rp</span>
                        <input type="number" name="minimal_nominal" value="{{ old('minimal_nominal') }}" required min="0" step="0.01"
                            class="w-full pl-12 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="0">
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Konfigurasi ini akan berlaku untuk pengajuan dengan nominal sama atau lebih besar dari nilai ini.</p>
                    @error('minimal_nominal')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Level Approval -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Level Approval</label>
                    <select name="level" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @foreach($levelList as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('level')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Urutan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Urutan Approval</label>
                    <input type="number" name="urutan" value="{{ old('urutan', 1) }}" required min="1"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="1">
                    <p class="text-xs text-gray-500 mt-1">Nomor urutan approval (1 = pertama, 2 = kedua, dst).</p>
                    @error('urutan')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Active Status -->
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" checked class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">Aktif</span>
                    </label>
                    <p class="text-xs text-gray-500 mt-1">Hilangkan centang jika tidak ingin menggunakan konfigurasi ini untuk sementara.</p>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end space-x-4">
                <a href="{{ route('admin.approval-configs.index') }}" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Simpan Konfigurasi
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
