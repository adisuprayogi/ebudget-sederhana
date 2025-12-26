<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('periode-anggaran.show', $periodeAnggaran) }}" class="p-2 text-secondary-600 hover:text-secondary-900 hover:bg-secondary-100 rounded-lg transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-secondary-900">Edit Periode Anggaran</h1>
                <p class="text-secondary-600 mt-1">{{ $periodeAnggaran->nama_periode }}</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto py-8">
        <form method="POST" action="{{ route('periode-anggaran.update', $periodeAnggaran) }}" class="bg-white rounded-2xl shadow-soft p-8">
            @csrf
            @method('PUT')

            <!-- Alert: Only draft can be edited -->
            <div class="mb-6 bg-blue-50 border border-blue-200 rounded-xl p-4 flex items-start">
                <svg class="w-5 h-5 text-blue-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="text-blue-700 text-sm">
                    Hanya periode dengan status <strong>draft</strong> yang dapat diedit. Tahun anggaran tidak dapat diubah setelah dibuat.
                </div>
            </div>
            
            @error('nama_periode')
                <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4 flex items-start">
                    <svg class="w-5 h-5 text-red-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="text-red-700 text-sm">{{ $message }}</div>
                </div>
            @enderror

            <!-- Informasi Dasar -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-secondary-900 mb-4 flex items-center">
                    <span class="w-8 h-8 bg-primary-100 text-primary-600 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                    Informasi Dasar
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="nama_periode" value="Nama Periode" />
                        <input type="text" name="nama_periode" id="nama_periode" value="{{ old('nama_periode', $periodeAnggaran->nama_periode) }}" required
                            class="mt-1 block w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <x-input-error :messages="$errors->get('nama_periode')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="tahun_anggaran" value="Tahun Anggaran" />
                        <input type="number" name="tahun_anggaran" id="tahun_anggaran" value="{{ $periodeAnggaran->tahun_anggaran }}" readonly
                            class="mt-1 block w-full px-4 py-3 border border-secondary-200 rounded-xl bg-secondary-50 text-secondary-500 cursor-not-allowed">
                        <p class="mt-1 text-xs text-secondary-500">Tahun anggaran tidak dapat diubah</p>
                    </div>

                    <div class="md:col-span-2">
                        <x-input-label for="deskripsi" value="Deskripsi (Opsional)" />
                        <textarea name="deskripsi" id="deskripsi" rows="3"
                            class="mt-1 block w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">{{ old('deskripsi', $periodeAnggaran->deskripsi) }}</textarea>
                        <x-input-error :messages="$errors->get('deskripsi')" class="mt-2" />
                    </div>
                </div>
            </div>

            <!-- Fase Perencanaan -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-secondary-900 mb-4 flex items-center">
                    <span class="w-8 h-8 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </span>
                    Fase Perencanaan Anggaran
                </h2>

                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-4">
                    <p class="text-sm text-blue-700">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Pada fase ini, perencanaan penerimaan, penetapan pagu, dan program kerja dilakukan.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="tanggal_mulai_perencanaan_anggaran" value="Tanggal Mulai" />
                        <input type="date" name="tanggal_mulai_perencanaan_anggaran" id="tanggal_mulai_perencanaan_anggaran" 
                            value="{{ old('tanggal_mulai_perencanaan_anggaran', $periodeAnggaran->tanggal_mulai_perencanaan_anggaran?->format('Y-m-d')) }}" required
                            class="mt-1 block w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <x-input-error :messages="$errors->get('tanggal_mulai_perencanaan_anggaran')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="tanggal_selesai_perencanaan_anggaran" value="Tanggal Selesai" />
                        <input type="date" name="tanggal_selesai_perencanaan_anggaran" id="tanggal_selesai_perencanaan_anggaran" 
                            value="{{ old('tanggal_selesai_perencanaan_anggaran', $periodeAnggaran->tanggal_selesai_perencanaan_anggaran?->format('Y-m-d')) }}" required
                            class="mt-1 block w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <x-input-error :messages="$errors->get('tanggal_selesai_perencanaan_anggaran')" class="mt-2" />
                    </div>
                </div>
            </div>

            <!-- Fase Penggunaan -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-secondary-900 mb-4 flex items-center">
                    <span class="w-8 h-8 bg-green-100 text-green-600 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </span>
                    Fase Penggunaan Anggaran
                </h2>

                <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-4">
                    <p class="text-sm text-green-700">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Pada fase ini, pengajuan dana, pencairan, dan penggunaan anggaran dilakukan.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="tanggal_mulai_penggunaan_anggaran" value="Tanggal Mulai" />
                        <input type="date" name="tanggal_mulai_penggunaan_anggaran" id="tanggal_mulai_penggunaan_anggaran" 
                            value="{{ old('tanggal_mulai_penggunaan_anggaran', $periodeAnggaran->tanggal_mulai_penggunaan_anggaran?->format('Y-m-d')) }}" required
                            class="mt-1 block w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <x-input-error :messages="$errors->get('tanggal_mulai_penggunaan_anggaran')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="tanggal_selesai_penggunaan_anggaran" value="Tanggal Selesai" />
                        <input type="date" name="tanggal_selesai_penggunaan_anggaran" id="tanggal_selesai_penggunaan_anggaran" 
                            value="{{ old('tanggal_selesai_penggunaan_anggaran', $periodeAnggaran->tanggal_selesai_penggunaan_anggaran?->format('Y-m-d')) }}" required
                            class="mt-1 block w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <x-input-error :messages="$errors->get('tanggal_selesai_penggunaan_anggaran')" class="mt-2" />
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-secondary-200">
                <a href="{{ route('periode-anggaran.show', $periodeAnggaran) }}" class="px-6 py-3 border border-secondary-200 text-secondary-700 rounded-xl hover:bg-secondary-50 transition-all duration-200">
                    Batal
                </a>
                <button type="submit" class="px-6 py-3 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-all duration-200 shadow-soft hover:shadow-medium">
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Simpan Perubahan
                    </span>
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
