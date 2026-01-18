<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <nav class="flex text-sm text-gray-500 mb-2">
                    <a href="{{ route('program-kerja.index') }}" class="hover:text-primary-600">Program Kerja</a>
                    <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <a href="{{ route('program-kerja.divisi-show', $divisi) }}" class="hover:text-primary-600">{{ $divisi->nama_divisi }}</a>
                    <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <span class="text-gray-900">Tambah Program</span>
                </nav>
                <h1 class="text-2xl font-bold text-gray-900">Tambah Program Kerja Baru</h1>
                <p class="text-gray-600 mt-1">Buat program kerja untuk {{ $divisi->nama_divisi }}</p>
            </div>
            <a href="{{ route('program-kerja.divisi-show', $divisi) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <!-- Info Banner -->
        <div class="bg-blue-50 border border-primary-200 rounded-2xl p-4 mb-6">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-primary-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <span class="font-medium text-primary-900">Divisi:</span> <span class="text-primary-700">{{ $divisi->nama_divisi }}</span>
                    <span class="mx-3 text-primary-400">|</span>
                    <span class="font-medium text-primary-900">Periode:</span> <span class="text-primary-700">{{ $activePeriode->nama_periode }} ({{ $activePeriode->tanggal_mulai_perencanaan_anggaran->format('Y') }})</span>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('program-kerja.store', $divisi) }}">
            @csrf

            <div class="bg-white rounded-2xl shadow-soft p-8 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-6">Informasi Program Kerja</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Kode Program -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kode Program <span class="text-red-500">*</span></label>
                        <input type="text" name="kode_program" value="{{ old('kode_program') }}" required placeholder="Contoh: 1.01.01" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        @error('kode_program')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nama Program -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Program <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_program" value="{{ old('nama_program') }}" required placeholder="Contoh: Program Peningkatan Kualitas Pelayanan" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        @error('nama_program')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Deskripsi -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                        <textarea name="deskripsi" rows="3" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500" placeholder="Jelaskan tujuan dan ruang lingkup program kerja...">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Target Output -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Target Output</label>
                        <input type="text" name="target_output" value="{{ old('target_output') }}" placeholder="Contoh: 100 kegiatan terlaksana" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        @error('target_output')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pagu Anggaran Info -->
                    <div class="md:col-span-2 bg-gray-50 border border-gray-200 rounded-xl p-4">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div class="text-sm text-gray-600">
                                <span class="font-medium">Pagu Anggaran</span> akan dihitung otomatis dari total Detail Anggaran yang ditambahkan melalui Sub Program.
                            </div>
                        </div>
                    </div>

                    <!-- Tanggal Mulai -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        <p class="mt-1 text-xs text-gray-500">Default: {{ $activePeriode->tanggal_mulai_perencanaan_anggaran->format('d M Y') }}</p>
                        @error('tanggal_mulai')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Selesai -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        <p class="mt-1 text-xs text-gray-500">Default: {{ $activePeriode->tanggal_selesai_perencanaan_anggaran->format('d M Y') }}</p>
                        @error('tanggal_selesai')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-4">
                <a href="{{ route('program-kerja.divisi-show', $divisi) }}" class="px-6 py-3 bg-white border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-all duration-200">
                    Batal
                </a>
                <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-all duration-200 shadow-soft hover:shadow-medium">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan Program Kerja
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
