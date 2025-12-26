<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('pencairan-dana.show', $pencairan) }}" class="p-2 text-secondary-600 hover:text-secondary-900 hover:bg-secondary-100 rounded-lg transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-secondary-900">Edit Pencairan Dana</h1>
                <p class="text-secondary-600 mt-1">{{ $pencairan->nomor_pencairan }}</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto py-8">
        <!-- Alert: Only pending can be edited -->
        <div class="mb-6 bg-amber-50 border border-amber-200 rounded-xl p-4 flex items-start">
            <svg class="w-5 h-5 text-amber-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div class="text-amber-700 text-sm">
                Hanya pencairan dengan status <strong>menunggu</strong> yang dapat diedit.
            </div>
        </div>

        <form method="POST" action="{{ route('pencairan-dana.update', $pencairan) }}" enctype="multipart/form-data" class="bg-white rounded-2xl shadow-soft p-8">
            @csrf
            @method('PUT')

            @error('jumlah_pencairan')
                <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4 flex items-start">
                    <svg class="w-5 h-5 text-red-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="text-red-700 text-sm">{{ $message }}</div>
                </div>
            @enderror

            <!-- Informasi Pengajuan (Read-only) -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-secondary-900 mb-4 flex items-center">
                    <span class="w-8 h-8 bg-primary-100 text-primary-600 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </span>
                    Informasi Pengajuan
                </h2>

                <div class="p-4 bg-secondary-50 rounded-xl">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <dt class="text-sm text-secondary-500">Nomor Pengajuan</dt>
                            <dd class="mt-1 font-mono text-sm font-semibold text-primary-600">{{ $pencairan->pengajuanDana->nomor_pengajuan }}</dd>
                        </div>
                        <div class="md:col-span-2">
                            <dt class="text-sm text-secondary-500">Judul Pengajuan</dt>
                            <dd class="mt-1 text-sm text-secondary-900">{{ $pencairan->pengajuanDana->judul_pengajuan }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-secondary-500">Total Pengajuan</dt>
                            <dd class="mt-1 text-sm font-semibold text-secondary-900">{{ formatRupiah($pencairan->pengajuanDana->total_pengajuan) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-secondary-500">Metode Pengajuan</dt>
                            <dd class="mt-1 text-sm text-secondary-900">{{ ucfirst($pencairan->pengajuanDana->metode_pencairan) }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Detail Pencairan -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-secondary-900 mb-4 flex items-center">
                    <span class="w-8 h-8 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </span>
                    Detail Pencairan
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="jumlah_pencairan" value="Jumlah Pencairan *" />
                        <input type="number" name="jumlah_pencairan" id="jumlah_pencairan" value="{{ old('jumlah_pencairan', $pencairan->jumlah_pencairan) }}" min="0" step="0.01" required
                            class="mt-1 block w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <x-input-error :messages="$errors->get('jumlah_pencairan')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="tanggal_pencairan" value="Tanggal Pencairan *" />
                        <input type="date" name="tanggal_pencairan" id="tanggal_pencairan" value="{{ old('tanggal_pencairan', $pencairan->tanggal_pencairan->format('Y-m-d')) }}" required
                            class="mt-1 block w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <x-input-error :messages="$errors->get('tanggal_pencairan')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="metode_pencairan" value="Metode Pencairan *" />
                        <select name="metode_pencairan" id="metode_pencairan" required
                            class="mt-1 block w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                            onchange="toggleRekeningSection()">
                            <option value="">Pilih Metode</option>
                            <option value="transfer" {{ old('metode_pencairan', $pencairan->metode_pencairan) === 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                            <option value="cash" {{ old('metode_pencairan', $pencairan->metode_pencairan) === 'cash' ? 'selected' : '' }}>Uang Tunai</option>
                            <option value="reimburse" {{ old('metode_pencairan', $pencairan->metode_pencairan) === 'reimburse' ? 'selected' : '' }}>Reimburse</option>
                        </select>
                        <x-input-error :messages="$errors->get('metode_pencairan')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="status" value="Status *" />
                        <select name="status" id="status" required
                            class="mt-1 block w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            <option value="pending" {{ old('status', $pencairan->status) === 'pending' ? 'selected' : '' }}>Menunggu Proses</option>
                            <option value="processing" {{ old('status', $pencairan->status) === 'processing' ? 'selected' : '' }}>Sedang Diproses</option>
                            <option value="completed" {{ old('status', $pencairan->status) === 'completed' ? 'selected' : '' }}>Selesai</option>
                            <option value="failed" {{ old('status', $pencairan->status) === 'failed' ? 'selected' : '' }}>Gagal</option>
                        </select>
                        <x-input-error :messages="$errors->get('status')" class="mt-2" />
                    </div>
                </div>
            </div>

            <!-- Informasi Rekening (untuk transfer) -->
            <div id="rekening-section" class="mb-8 @if(old('metode_pencairan', $pencairan->metode_pencairan) !== 'transfer') hidden @endif">
                <h2 class="text-lg font-semibold text-secondary-900 mb-4 flex items-center">
                    <span class="w-8 h-8 bg-green-100 text-green-600 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    </span>
                    Informasi Rekening Tujuan
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="nama_bank" value="Nama Bank" />
                        <input type="text" name="nama_bank" id="nama_bank" value="{{ old('nama_bank', $pencairan->nama_bank) }}"
                            class="mt-1 block w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                            placeholder="Contoh: BCA, Mandiri, BNI">
                        <x-input-error :messages="$errors->get('nama_bank')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="nomor_rekening" value="Nomor Rekening" />
                        <input type="text" name="nomor_rekening" id="nomor_rekening" value="{{ old('nomor_rekening', $pencairan->nomor_rekening) }}"
                            class="mt-1 block w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                            placeholder="Nomor rekening tujuan">
                        <x-input-error :messages="$errors->get('nomor_rekening')" class="mt-2" />
                    </div>

                    <div class="md:col-span-2">
                        <x-input-label for="atas_nama" value="Atas Nama" />
                        <input type="text" name="atas_nama" id="atas_nama" value="{{ old('atas_nama', $pencairan->atas_nama) }}"
                            class="mt-1 block w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                            placeholder="Nama pemilik rekening">
                        <x-input-error :messages="$errors->get('atas_nama')" class="mt-2" />
                    </div>
                </div>
            </div>

            <!-- Catatan -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-secondary-900 mb-4 flex items-center">
                    <span class="w-8 h-8 bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </span>
                    Catatan
                </h2>

                <div>
                    <x-input-label for="catatan" value="Catatan Pencairan" />
                    <textarea name="catatan" id="catatan" rows="3"
                        class="mt-1 block w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                        placeholder="Tambahkan catatan untuk pencairan ini...">{{ old('catatan', $pencairan->catatan) }}</textarea>
                    <x-input-error :messages="$errors->get('catatan')" class="mt-2" />
                </div>
            </div>

            <!-- Alasan Gagal (jika status = failed) -->
            <div id="failed-section" class="mb-8 @if(old('status', $pencairan->status) !== 'failed') hidden @endif">
                <h2 class="text-lg font-semibold text-secondary-900 mb-4 flex items-center">
                    <span class="w-8 h-8 bg-red-100 text-red-600 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                    Alasan Kegagalan
                </h2>

                <div>
                    <x-input-label for="alasan_gagal" value="Alasan Pencairan Gagal" />
                    <textarea name="alasan_gagal" id="alasan_gagal" rows="3"
                        class="mt-1 block w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                        placeholder="Jelaskan alasan kegagalan pencairan...">{{ old('alasan_gagal', $pencairan->alasan_gagal) }}</textarea>
                    <x-input-error :messages="$errors->get('alasan_gagal')" class="mt-2" />
                </div>
            </div>

            <!-- Bukti Pencairan -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-secondary-900 mb-4 flex items-center">
                    <span class="w-8 h-8 bg-amber-100 text-amber-600 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                        </svg>
                    </span>
                    Bukti Pencairan (Opsional)
                </h2>

                <div class="border-2 border-dashed border-secondary-200 rounded-xl p-6 text-center hover:border-primary-400 transition-colors">
                    <svg class="mx-auto h-12 w-12 text-secondary-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <div class="mt-4">
                        <label for="bukti_pencairan" class="cursor-pointer">
                            <span class="mt-2 block text-sm font-medium text-secondary-900">Upload bukti pencairan</span>
                            <span class="text-sm text-secondary-500">PDF, JPG, PNG hingga 5MB</span>
                        </label>
                        <input id="bukti_pencairan" name="bukti_pencairan" type="file" class="sr-only" accept=".pdf,.jpg,.jpeg,.png" onchange="handleFileSelect(this)">
                    </div>
                </div>

                <!-- Existing file -->
                @if($pencairan->bukti_pencairan)
                <div class="mt-4 p-3 bg-secondary-50 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-secondary-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            <span class="text-sm text-secondary-700">File saat ini</span>
                        </div>
                        <input type="checkbox" name="remove_bukti" value="1" class="rounded border-secondary-300 text-primary-600 focus:ring-primary-500">
                        <span class="text-xs text-secondary-500 ml-2">Hapus</span>
                    </div>
                </div>
                @endif

                <div id="file-preview" class="mt-4"></div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-secondary-200">
                <a href="{{ route('pencairan-dana.show', $pencairan) }}" class="px-6 py-3 border border-secondary-200 text-secondary-700 rounded-xl hover:bg-secondary-50 transition-all duration-200">
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
        function toggleRekeningSection() {
            const metode = document.getElementById('metode_pencairan').value;
            const rekeningSection = document.getElementById('rekening-section');

            if (metode === 'transfer') {
                rekeningSection.classList.remove('hidden');
            } else {
                rekeningSection.classList.add('hidden');
            }
        }

        document.getElementById('status')?.addEventListener('change', function() {
            const failedSection = document.getElementById('failed-section');
            if (this.value === 'failed') {
                failedSection.classList.remove('hidden');
            } else {
                failedSection.classList.add('hidden');
            }
        });

        function handleFileSelect(input) {
            const filePreview = document.getElementById('file-preview');
            const file = input.files[0];

            if (file) {
                filePreview.innerHTML = `
                    <div class="flex items-center justify-between p-3 bg-secondary-50 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-secondary-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            <span class="text-sm text-secondary-700">${file.name}</span>
                            <span class="text-xs text-secondary-500 ml-2">(${(file.size / 1024).toFixed(1)} KB)</span>
                        </div>
                    </div>
                `;
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleRekeningSection();

            const statusSelect = document.getElementById('status');
            if (statusSelect && statusSelect.value === 'failed') {
                document.getElementById('failed-section').classList.remove('hidden');
            }
        });
    </script>
</x-app-layout>
