<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <nav class="flex text-sm text-secondary-500 mb-2">
                    <a href="{{ route('program-kerja.index') }}" class="hover:text-primary-600">Program Kerja</a>
                    <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <a href="{{ route('program-kerja.divisi-show', $divisi) }}" class="hover:text-primary-600">{{ $divisi->nama_divisi }}</a>
                    <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <span class="text-secondary-900">Edit {{ $programKerja->nama_program }}</span>
                </nav>
                <h1 class="text-2xl font-bold text-secondary-900">Edit Program Kerja</h1>
                <p class="text-secondary-600 mt-1">Ubah data program kerja</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('program-kerja.show', [$divisi, $programKerja]) }}" class="inline-flex items-center px-4 py-2 bg-white border border-secondary-300 text-secondary-700 rounded-xl hover:bg-secondary-50 transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    Lihat Detail
                </a>
                <a href="{{ route('program-kerja.divisi-show', $divisi) }}" class="inline-flex items-center px-4 py-2 bg-white border border-secondary-300 text-secondary-700 rounded-xl hover:bg-secondary-50 transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <!-- Info Banner -->
        <div class="bg-primary-50 border border-primary-200 rounded-2xl p-4 mb-6">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-primary-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <span class="font-medium text-primary-900">Divisi:</span> <span class="text-primary-700">{{ $divisi->nama_divisi }}</span>
                    <span class="mx-3 text-primary-400">|</span>
                    <span class="font-medium text-primary-900">Periode:</span> <span class="text-primary-700">{{ $programKerja->periodeAnggaran->nama_periode ?? '-' }}</span>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('program-kerja.update', [$divisi, $programKerja]) }}">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-2xl shadow-soft p-8 mb-6">
                <h2 class="text-lg font-semibold text-secondary-900 mb-6">Informasi Program Kerja</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Kode Program -->
                    <div>
                        <label class="block text-sm font-medium text-secondary-700 mb-2">Kode Program <span class="text-red-500">*</span></label>
                        <input type="text" name="kode_program" value="{{ old('kode_program', $programKerja->kode_program) }}" required placeholder="Contoh: 1.01.01" class="w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        @error('kode_program')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nama Program -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-secondary-700 mb-2">Nama Program <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_program" value="{{ old('nama_program', $programKerja->nama_program) }}" required placeholder="Contoh: Program Peningkatan Kualitas Pelayanan" class="w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        @error('nama_program')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Deskripsi -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-secondary-700 mb-2">Deskripsi</label>
                        <textarea name="deskripsi" rows="3" class="w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="Jelaskan tujuan dan ruang lingkup program kerja...">{{ old('deskripsi', $programKerja->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Target Output -->
                    <div>
                        <label class="block text-sm font-medium text-secondary-700 mb-2">Target Output</label>
                        <input type="text" name="target_output" value="{{ old('target_output', $programKerja->target_output) }}" placeholder="Contoh: 100 kegiatan terlaksana" class="w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        @error('target_output')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pagu Anggaran -->
                    <div>
                        <label class="block text-sm font-medium text-secondary-700 mb-2">Pagu Anggaran <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-secondary-500 font-medium">Rp</span>
                            <input type="number" name="pagu_anggaran" value="{{ old('pagu_anggaran', $programKerja->pagu_anggaran) }}" required min="0" step="0.01" class="w-full pl-12 pr-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="0">
                        </div>
                        @error('pagu_anggaran')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Mulai -->
                    <div>
                        <label class="block text-sm font-medium text-secondary-700 mb-2">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai', $programKerja->tanggal_mulai?->format('Y-m-d')) }}" class="w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        @error('tanggal_mulai')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Selesai -->
                    <div>
                        <label class="block text-sm font-medium text-secondary-700 mb-2">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai', $programKerja->tanggal_selesai?->format('Y-m-d')) }}" class="w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        @error('tanggal_selesai')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-secondary-700 mb-2">Status</label>
                        <select name="status" class="w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            <option value="active" {{ old('status', $programKerja->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ old('status', $programKerja->status) == 'inactive' ? 'selected' : '' }}>Non-Aktif</option>
                            <option value="suspended" {{ old('status', $programKerja->status) == 'suspended' ? 'selected' : '' }}>Ditangguhkan</option>
                        </select>
                        @error('status')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-4">
                <a href="{{ route('program-kerja.show', [$divisi, $programKerja]) }}" class="px-6 py-3 bg-white border border-secondary-300 text-secondary-700 rounded-xl hover:bg-secondary-50 transition-all duration-200">
                    Batal
                </a>
                <button type="submit" class="px-6 py-3 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-all duration-200 shadow-soft hover:shadow-medium">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
