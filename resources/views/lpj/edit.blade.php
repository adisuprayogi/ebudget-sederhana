<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('lpj.show', $lpj) }}" class="p-2 text-secondary-600 hover:text-secondary-900 hover:bg-secondary-100 rounded-lg transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-secondary-900">Edit LPJ</h1>
                <p class="text-secondary-600 mt-1">{{ $lpj->nomor_lpj }}</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto py-8">
        <!-- Alert: Only draft/rejected can be edited -->
        <div class="mb-6 bg-amber-50 border border-amber-200 rounded-xl p-4 flex items-start">
            <svg class="w-5 h-5 text-amber-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div class="text-amber-700 text-sm">
                Hanya LPJ dengan status <strong>draft</strong> atau <strong>ditolak</strong> yang dapat diedit.
            </div>
        </div>

        <form method="POST" action="{{ route('lpj.update', $lpj) }}" enctype="multipart/form-data" class="bg-white rounded-2xl shadow-soft p-8">
            @csrf
            @method('PUT')

            @error('judul_lpj')
                <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4 flex items-start">
                    <svg class="w-5 h-5 text-red-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="text-red-700 text-sm">{{ $message }}</div>
                </div>
            @enderror

            <!-- Informasi Pencairan (Read-only) -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-secondary-900 mb-4 flex items-center">
                    <span class="w-8 h-8 bg-primary-100 text-primary-600 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </span>
                    Informasi Pencairan
                </h2>

                <div class="p-4 bg-secondary-50 rounded-xl">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm text-secondary-500">Nomor Pencairan</dt>
                            <dd class="mt-1 font-mono text-sm font-semibold text-primary-600">{{ $lpj->pencairanDana->nomor_pencairan }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-secondary-500">Judul Pengajuan</dt>
                            <dd class="mt-1 text-sm text-secondary-900">{{ $lpj->pencairanDana->pengajuanDana->judul_pengajuan }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-secondary-500">Jumlah Pencairan</dt>
                            <dd class="mt-1 text-sm font-semibold text-secondary-900">{{ formatRupiah($lpj->pencairanDana->jumlah_pencairan) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-secondary-500">Tanggal Pencairan</dt>
                            <dd class="mt-1 text-sm text-secondary-900">{{ \Carbon\Carbon::parse($lpj->pencairanDana->tanggal_pencairan)->format('d/m/Y') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Informasi LPJ -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-secondary-900 mb-4 flex items-center">
                    <span class="w-8 h-8 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </span>
                    Informasi LPJ
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <x-input-label for="judul_lpj" value="Judul LPJ *" />
                        <input type="text" name="judul_lpj" id="judul_lpj" value="{{ old('judul_lpj', $lpj->judul_lpj) }}" required
                            class="mt-1 block w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <x-input-error :messages="$errors->get('judul_lpj')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="tanggal_lpj" value="Tanggal LPJ *" />
                        <input type="date" name="tanggal_lpj" id="tanggal_lpj" value="{{ old('tanggal_lpj', $lpj->tanggal_lpj->format('Y-m-d')) }}" required
                            class="mt-1 block w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <x-input-error :messages="$errors->get('tanggal_lpj')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="periode_anggaran_id" value="Periode Anggaran *" />
                        <select name="periode_anggaran_id" id="periode_anggaran_id" required
                            class="mt-1 block w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            <option value="">Pilih Periode</option>
                            @foreach($periodeAnggarans ?? [] as $periode)
                                <option value="{{ $periode->id }}" {{ old('periode_anggaran_id', $lpj->periode_anggaran_id) == $periode->id ? 'selected' : '' }}>
                                    {{ $periode->nama_periode }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('periode_anggaran_id')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="tanggal_mulai_pelaksanaan" value="Tanggal Mulai Pelaksanaan *" />
                        <input type="date" name="tanggal_mulai_pelaksanaan" id="tanggal_mulai_pelaksanaan"
                            value="{{ old('tanggal_mulai_pelaksanaan', $lpj->tanggal_mulai_pelaksanaan->format('Y-m-d')) }}" required
                            class="mt-1 block w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <x-input-error :messages="$errors->get('tanggal_mulai_pelaksanaan')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="tanggal_selesai_pelaksanaan" value="Tanggal Selesai Pelaksanaan *" />
                        <input type="date" name="tanggal_selesai_pelaksanaan" id="tanggal_selesai_pelaksanaan"
                            value="{{ old('tanggal_selesai_pelaksanaan', $lpj->tanggal_selesai_pelaksanaan->format('Y-m-d')) }}" required
                            class="mt-1 block w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <x-input-error :messages="$errors->get('tanggal_selesai_pelaksanaan')" class="mt-2" />
                    </div>

                    <div class="md:col-span-2">
                        <x-input-label for="deskripsi" value="Deskripsi Pelaksanaan *" />
                        <textarea name="deskripsi" id="deskripsi" rows="4" required
                            class="mt-1 block w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                            placeholder="Jelaskan secara detail pelaksanaan kegiatan...">{{ old('deskripsi', $lpj->deskripsi) }}</textarea>
                        <x-input-error :messages="$errors->get('deskripsi')" class="mt-2" />
                    </div>
                </div>
            </div>

            <!-- Rincian Penggunaan Dana -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-secondary-900 mb-4 flex items-center">
                    <span class="w-8 h-8 bg-green-100 text-green-600 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                    Rincian Penggunaan Dana
                </h2>

                <div class="p-4 bg-secondary-50 rounded-xl mb-4">
                    <p class="text-sm text-secondary-600">
                        <span class="font-medium">Jumlah Pencairan:</span> {{ formatRupiah($lpj->pencairanDana->jumlah_pencairan) }}
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="jumlah_digunakan" value="Jumlah Dana Digunakan *" />
                        <input type="number" name="jumlah_digunakan" id="jumlah_digunakan" value="{{ old('jumlah_digunakan', $lpj->jumlah_digunakan) }}" min="0" step="0.01" required
                            class="mt-1 block w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <x-input-error :messages="$errors->get('jumlah_digunakan')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="sisa_dana" value="Sisa Dana *" />
                        <input type="number" name="sisa_dana" id="sisa_dana" value="{{ old('sisa_dana', $lpj->sisa_dana) }}" min="0" step="0.01" required
                            class="mt-1 block w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <x-input-error :messages="$errors->get('sisa_dana')" class="mt-2" />
                    </div>

                    <div class="md:col-span-2">
                        <x-input-label for="jenis_penggunaan" value="Jenis Penggunaan *" />
                        <select name="jenis_penggunaan" id="jenis_penggunaan" required
                            class="mt-1 block w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            <option value="">Pilih Jenis Penggunaan</option>
                            <option value="penuh" {{ old('jenis_penggunaan', $lpj->jenis_penggunaan) === 'penuh' ? 'selected' : '' }}>Penggunaan Penuh</option>
                            <option value="sebagian" {{ old('jenis_penggunaan', $lpj->jenis_penggunaan) === 'sebagian' ? 'selected' : '' }}>Penggunaan Sebagian</option>
                        </select>
                        <x-input-error :messages="$errors->get('jenis_penggunaan')" class="mt-2" />
                    </div>
                </div>
            </div>

            <!-- Lampiran -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-secondary-900 mb-4 flex items-center">
                    <span class="w-8 h-8 bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                        </svg>
                    </span>
                    Lampiran Dokumen
                </h2>

                <div class="border-2 border-dashed border-secondary-200 rounded-xl p-6 text-center hover:border-primary-400 transition-colors">
                    <svg class="mx-auto h-12 w-12 text-secondary-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <div class="mt-4">
                        <label for="lampiran" class="cursor-pointer">
                            <span class="mt-2 block text-sm font-medium text-secondary-900">Upload bukti penggunaan dana</span>
                            <span class="text-sm text-secondary-500">PDF, JPG, PNG hingga 10MB. Multiple files allowed.</span>
                        </label>
                        <input id="lampiran" name="lampiran[]" type="file" class="sr-only" multiple accept=".pdf,.jpg,.jpeg,.png" onchange="handleFileSelect(this)">
                    </div>
                </div>

                <!-- Existing files -->
                @if($lpj->lampiranLpj && $lpj->lampiranLpj->count() > 0)
                <div class="mt-4 space-y-2">
                    <p class="text-sm font-medium text-secondary-700">File yang sudah diupload:</p>
                    @foreach($lpj->lampiranLpj as $lampiran)
                    <div class="flex items-center justify-between p-3 bg-secondary-50 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-secondary-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            <span class="text-sm text-secondary-700">{{ $lampiran->nama_dokumen }}</span>
                        </div>
                        <input type="checkbox" name="remove_lampiran[]" value="{{ $lampiran->id }}" class="rounded border-secondary-300 text-primary-600 focus:ring-primary-500">
                    </div>
                    @endforeach
                    <p class="text-xs text-secondary-500">Centang file yang ingin dihapus</p>
                </div>
                @endif

                <div id="file-list" class="mt-4 space-y-2"></div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-secondary-200">
                <a href="{{ route('lpj.show', $lpj) }}" class="px-6 py-3 border border-secondary-200 text-secondary-700 rounded-xl hover:bg-secondary-50 transition-all duration-200">
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

    <script>
        function handleFileSelect(input) {
            const fileList = document.getElementById('file-list');
            const files = input.files;

            for (const file of files) {
                const fileDiv = document.createElement('div');
                fileDiv.className = 'flex items-center justify-between p-3 bg-secondary-50 rounded-lg';
                fileDiv.innerHTML = `
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-secondary-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        <span class="text-sm text-secondary-700">${file.name}</span>
                        <span class="text-xs text-secondary-500 ml-2">(${(file.size / 1024).toFixed(1)} KB)</span>
                    </div>
                `;
                fileList.appendChild(fileDiv);
            }
        }
    </script>
</x-app-layout>
