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
        <form method="POST" action="{{ route('pencairan-dana.update', $pencairan) }}" enctype="multipart/form-data" class="bg-white rounded-2xl shadow-soft p-8">
            @csrf
            @method('PUT')

            @error('pengajuan_dana_id')
                <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4 flex items-start">
                    <svg class="w-5 h-5 text-red-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="text-red-700 text-sm">{{ $message }}</div>
                </div>
            @enderror

            <!-- Pengajuan Dana Info (Read-only) -->
            <div class="mb-8 bg-gradient-to-r from-blue-50 to-orange-50 border border-blue-100 rounded-2xl p-6">
                <h2 class="text-lg font-semibold text-secondary-900 mb-4 flex items-center">
                    <span class="w-8 h-8 bg-primary-100 text-blue-600 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </span>
                    Informasi Pengajuan Dana
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-xs font-semibold text-secondary-500 uppercase tracking-wider mb-1">Nomor Pengajuan</p>
                        <p class="font-mono text-lg font-semibold text-blue-600">{{ $pencairan->pengajuanDana->nomor_pengajuan }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-secondary-500 uppercase tracking-wider mb-1">Judul Pengajuan</p>
                        <p class="font-medium text-secondary-900">{{ $pencairan->pengajuanDana->judul_pengajuan }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-secondary-500 uppercase tracking-wider mb-1">Divisi</p>
                        <p class="font-medium text-secondary-900">{{ $pencairan->pengajuanDana->divisi->nama_divisi ?? '-' }}</p>
                    </div>
                    @if($pencairan->pengajuanDana->programKerja)
                    <div>
                        <p class="text-xs font-semibold text-secondary-500 uppercase tracking-wider mb-1">Program Kerja</p>
                        <p class="font-medium text-secondary-900">{{ $pencairan->pengajuanDana->programKerja->nama_program }}</p>
                    </div>
                    @endif
                    <div>
                        <p class="text-xs font-semibold text-secondary-500 uppercase tracking-wider mb-1">Total Pengajuan</p>
                        <p class="text-2xl font-bold text-blue-600">{{ formatRupiah($pencairan->pengajuanDana->total_pengajuan) }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-secondary-500 uppercase tracking-wider mb-1">Status Pencairan</p>
                        <p class="font-medium text-secondary-900">{{ ucfirst($pencairan->status) }}</p>
                    </div>
                </div>
            </div>

            <!-- Detail Pencairan -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-secondary-900 mb-4 flex items-center">
                    <span class="w-8 h-8 bg-gradient-to-br from-blue-600 to-blue-700 text-white shadow-blue-500/30 shadow-md rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </span>
                    Detail Pencairan
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="jumlah_pencairan" value="Jumlah Pencairan *" />
                        <input type="text" name="jumlah_pencairan_display" id="jumlah_pencairan_display"
                            value="{{ old('jumlah_pencairan_display', number_format($pencairan->jumlah_pencairan, 0, ',', '.')) }}"
                            data-max="{{ $pencairan->pengajuanDana->total_pengajuan }}"
                            required
                            class="mt-1 block w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-orange-500 focus:border-orange-500 transition-all duration-200 bg-green-50 font-semibold text-blue-600"
                            placeholder="0">
                        <input type="hidden" name="jumlah_pencairan" id="jumlah_pencairan" value="{{ old('jumlah_pencairan', $pencairan->jumlah_pencairan) }}">
                        <p class="mt-1 text-xs text-secondary-500">Maksimal: {{ formatRupiah($pencairan->pengajuanDana->total_pengajuan) }}</p>
                        <p id="jumlah_pencairan_error" class="mt-1 text-xs text-red-600 hidden"></p>
                        <x-input-error :messages="$errors->get('jumlah_pencairan')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="tanggal_pencairan" value="Tanggal Pencairan *" />
                        <input type="date" name="tanggal_pencairan" id="tanggal_pencairan" value="{{ old('tanggal_pencairan', $pencairan->tanggal_pencairan->format('Y-m-d')) }}" required
                            class="mt-1 block w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-orange-500 focus:border-orange-500 transition-all duration-200">
                        <x-input-error :messages="$errors->get('tanggal_pencairan')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="metode_pencairan" value="Metode Pencairan *" />
                        <select name="metode_pencairan" id="metode_pencairan" required
                            class="mt-1 block w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-orange-500 focus:border-orange-500 transition-all duration-200">
                            <option value="">Pilih Metode</option>
                            <option value="transfer" {{ old('metode_pencairan', $pencairan->metode_pencairan) === 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                            <option value="cash" {{ old('metode_pencairan', $pencairan->metode_pencairan) === 'cash' ? 'selected' : '' }}>Uang Tunai</option>
                            <option value="reimburse" {{ old('metode_pencairan', $pencairan->metode_pencairan) === 'reimburse' ? 'selected' : '' }}>Reimburse</option>
                        </select>
                        <x-input-error :messages="$errors->get('metode_pencairan')" class="mt-2" />
                    </div>
                </div>
            </div>

            <!-- Informasi Rekening (untuk transfer) -->
            <div id="rekening-section" class="mb-8 @if(old('metode_pencairan', $pencairan->metode_pencairan) !== 'transfer') hidden @endif">
                <h2 class="text-lg font-semibold text-secondary-900 mb-4 flex items-center">
                    <span class="w-8 h-8 bg-gradient-to-br from-blue-600 to-blue-700 text-white shadow-blue-500/30 shadow-md rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    </span>
                    Informasi Rekening
                </h2>

                <!-- Rekening Sumber -->
                <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                    <h3 class="text-sm font-semibold text-blue-800 mb-3 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" />
                        </svg>
                        Rekening Sumber (Perusahaan)
                    </h3>
                    <div>
                        <x-input-label for="rekening_perusahaan_id" value="Pilih Rekening Sumber *" />
                        <select name="rekening_perusahaan_id" id="rekening_perusahaan_id" required
                            class="mt-1 block w-full px-4 py-3 border border-blue-200 rounded-xl focus:ring-orange-500 focus:border-orange-500 transition-all duration-200 bg-white">
                            <option value="">-- Pilih Rekening Perusahaan --</option>
                            @foreach($rekeningPerusahaan ?? \App\Models\RekeningPerusahaan::with('bank')->active()->orderBy('is_default', 'desc')->orderBy('created_at', 'desc')->get() as $rekening)
                                <option value="{{ $rekening->id }}" {{ old('rekening_perusahaan_id', $pencairan->rekening_perusahaan_id) == $rekening->id ? 'selected' : ($rekening->is_default ? 'selected' : '') }}>
                                    {{ $rekening->bank->nama_bank }} - {{ $rekening->nomor_rekening_formatted }} - {{ $rekening->atas_nama }}
                                    @if($rekening->is_default) <span class="ml-2 text-xs text-blue-600">(Default)</span> @endif
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('rekening_perusahaan_id')" class="mt-2" />
                    </div>
                </div>

                <!-- Rekening Tujuan -->
                <div class="p-4 bg-green-50 border border-green-200 rounded-xl">
                    <h3 class="text-sm font-semibold text-green-800 mb-3 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6" />
                        </svg>
                        Rekening Tujuan
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="bank_id" value="Nama Bank Tujuan" />
                            <select name="bank_id" id="bank_id"
                                class="mt-1 block w-full px-4 py-3 border border-green-200 rounded-xl focus:ring-orange-500 focus:border-orange-500 transition-all duration-200 bg-white">
                                <option value="">-- Pilih Bank --</option>
                                @foreach($banks ?? \App\Models\Bank::active()->orderBy('nama_bank')->get() as $bank)
                                    <option value="{{ $bank->id }}" {{ old('bank_id') == $bank->id || $pencairan->nama_bank == $bank->nama_bank ? 'selected' : '' }}>
                                        {{ $bank->nama_bank }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('bank_id')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="nomor_rekening" value="Nomor Rekening Tujuan" />
                            <input type="text" name="nomor_rekening" id="nomor_rekening" value="{{ old('nomor_rekening', $pencairan->nomor_rekening) }}"
                                class="mt-1 block w-full px-4 py-3 border border-green-200 rounded-xl focus:ring-orange-500 focus:border-orange-500 transition-all duration-200 bg-white"
                                placeholder="Nomor rekening tujuan">
                            <x-input-error :messages="$errors->get('nomor_rekening')" class="mt-2" />
                        </div>

                        <div class="md:col-span-2">
                            <x-input-label for="atas_nama" value="Atas Nama Tujuan" />
                            <input type="text" name="atas_nama" id="atas_nama" value="{{ old('atas_nama', $pencairan->atas_nama) }}"
                                class="mt-1 block w-full px-4 py-3 border border-green-200 rounded-xl focus:ring-orange-500 focus:border-orange-500 transition-all duration-200 bg-white"
                                placeholder="Nama pemilik rekening tujuan">
                            <x-input-error :messages="$errors->get('atas_nama')" class="mt-2" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Catatan -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-secondary-900 mb-4 flex items-center">
                    <span class="w-8 h-8 bg-gradient-to-br from-blue-600 to-blue-700 text-white shadow-blue-500/30 shadow-md rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </span>
                    Catatan
                </h2>

                <div>
                    <x-input-label for="catatan" value="Catatan Pencairan" />
                    <textarea name="catatan" id="catatan" rows="3"
                        class="mt-1 block w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-orange-500 focus:border-orange-500 transition-all duration-200"
                        placeholder="Tambahkan catatan untuk pencairan ini...">{{ old('catatan', $pencairan->catatan) }}</textarea>
                    <x-input-error :messages="$errors->get('catatan')" class="mt-2" />
                </div>
            </div>

            <!-- Existing Lampiran -->
            @if($pencairan->lampirans && $pencairan->lampirans->isNotEmpty())
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-secondary-900 mb-4 flex items-center">
                    <span class="w-8 h-8 bg-gradient-to-br from-blue-600 to-blue-700 text-white shadow-blue-500/30 shadow-md rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                        </svg>
                    </span>
                    Lampiran Saat Ini
                </h2>

                <div class="space-y-2">
                    @foreach($pencairan->lampirans as $lampiran)
                    <div class="flex items-center justify-between p-3 bg-white border border-secondary-200 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <span class="text-2xl">📎</span>
                            <div>
                                <p class="text-sm font-medium text-secondary-900">{{ $lampiran->nama_file }}</p>
                                <p class="text-xs text-secondary-500">{{ $lampiran->formatted_size }}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <a href="{{ $lampiran->file_url }}" target="_blank" class="text-blue-600 hover:text-primary-800 text-sm">Lihat</a>
                            <label class="flex items-center text-red-600 hover:text-red-800 text-sm cursor-pointer">
                                <input type="checkbox" name="remove_lampiran[]" value="{{ $lampiran->id }}" class="rounded border-secondary-300 text-red-600 focus:ring-red-500 mr-1">
                                Hapus
                            </label>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Upload New Lampiran -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-secondary-900 mb-4 flex items-center">
                    <span class="w-8 h-8 bg-gradient-to-br from-blue-600 to-blue-700 text-white shadow-blue-500/30 shadow-md rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                        </svg>
                    </span>
                    Tambah Lampiran Baru
                </h2>

                <div>
                    <x-input-label for="lampiran" value="Upload Lampiran Tambahan" />
                    <div id="lampiran-dropzone" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-dashed border-secondary-300 rounded-xl bg-secondary-50 hover:bg-secondary-100 transition-colors cursor-pointer">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-secondary-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-secondary-600">
                                <label for="lampiran" class="relative cursor-pointer bg-white rounded-md font-medium text-orange-500 hover:text-orange-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary-500">
                                    <span>Pilih file</span>
                                    <input id="lampiran" name="lampiran[]" type="file" class="sr-only" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" multiple>
                                </label>
                                <p class="pl-1">atau drag and drop</p>
                            </div>
                            <p class="text-xs text-secondary-500">PDF, JPG, PNG, DOC, DOCX (Max. 5MB per file)</p>
                        </div>
                    </div>

                    <!-- File Preview List -->
                    <div id="lampiran-preview" class="mt-3 space-y-2 hidden">
                        <p class="text-sm font-medium text-secondary-700">File yang dipilih:</p>
                        <ul id="lampiran-list" class="space-y-2"></ul>
                    </div>

                    <x-input-error :messages="$errors->get('lampiran')" class="mt-2" />
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-secondary-200">
                <a href="{{ route('pencairan-dana.show', $pencairan) }}" class="px-6 py-3 border border-secondary-200 text-secondary-700 rounded-xl hover:bg-secondary-50 transition-all duration-200">
                    Batal
                </a>
                <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-all duration-200 shadow-soft hover:shadow-medium">
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

        // Format number with Indonesian thousand separator (dot)
        function formatNumber(value) {
            const cleanValue = value.replace(/\D/g, '');
            if (cleanValue === '') return '';
            return cleanValue.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        // Handle jumlah_pencairan display input
        const displayInput = document.getElementById('jumlah_pencairan_display');
        const hiddenInput = document.getElementById('jumlah_pencairan');
        const errorMsg = document.getElementById('jumlah_pencairan_error');

        if (displayInput && hiddenInput) {
            const maxValue = parseInt(displayInput.dataset.max) || 0;

            // Format on input
            displayInput.addEventListener('input', function(e) {
                const cursorPosition = e.target.selectionStart;
                const originalLength = e.target.value.length;

                // Format the displayed value
                e.target.value = formatNumber(e.target.value);

                // Get clean numeric value
                const cleanValue = parseInt(e.target.value.replace(/\./g, '')) || 0;

                // Validate against max value
                if (cleanValue > maxValue) {
                    // Set to max value
                    e.target.value = formatNumber(maxValue.toString());
                    hiddenInput.value = maxValue;

                    // Show error
                    if (errorMsg) {
                        errorMsg.textContent = 'Jumlah pencairan tidak boleh melebihi ' + formatNumber(maxValue.toString());
                        errorMsg.classList.remove('hidden');
                    }

                    // Add error styling
                    e.target.classList.add('border-red-500', 'bg-red-50');
                    e.target.classList.remove('border-secondary-200', 'bg-green-50');
                } else {
                    // Clear error
                    if (errorMsg) {
                        errorMsg.classList.add('hidden');
                    }

                    // Remove error styling
                    e.target.classList.remove('border-red-500', 'bg-red-50');
                    e.target.classList.add('border-secondary-200', 'bg-green-50');

                    // Update the hidden input with clean numeric value
                    hiddenInput.value = cleanValue;
                }

                // Adjust cursor position
                const newLength = e.target.value.length;
                e.target.setSelectionRange(cursorPosition + (newLength - originalLength), cursorPosition + (newLength - originalLength));
            });

            // Prevent non-numeric input
            displayInput.addEventListener('keypress', function(e) {
                const char = String.fromCharCode(e.which);
                if (!/[\d]/.test(char)) {
                    e.preventDefault();
                }
            });

            // Prevent paste non-numeric content
            displayInput.addEventListener('paste', function(e) {
                const pastedData = e.clipboardData.getData('text');
                if (!/^\d+$/.test(pastedData.replace(/\./g, ''))) {
                    e.preventDefault();
                }
            });
        }

        document.getElementById('metode_pencairan')?.addEventListener('change', toggleRekeningSection);

        // Handle lampiran file selection and display
        const lampiranInput = document.getElementById('lampiran');
        const lampiranDropzone = document.getElementById('lampiran-dropzone');
        const lampiranPreview = document.getElementById('lampiran-preview');
        const lampiranList = document.getElementById('lampiran-list');

        // Function to format file size
        function formatFileSize(bytes) {
            if (bytes >= 1048576) {
                return (bytes / 1048576).toFixed(2) + ' MB';
            } else if (bytes >= 1024) {
                return (bytes / 1024).toFixed(2) + ' KB';
            } else {
                return bytes + ' bytes';
            }
        }

        // Function to get file icon based on type
        function getFileIcon(type) {
            if (type.includes('pdf')) return '📄';
            if (type.includes('image')) return '🖼️';
            if (type.includes('word') || type.includes('document')) return '📝';
            return '📎';
        }

        // Function to render file list
        function renderFileList(files) {
            lampiranList.innerHTML = '';

            Array.from(files).forEach((file) => {
                const li = document.createElement('li');
                li.className = 'flex items-center justify-between p-3 bg-white border border-secondary-200 rounded-lg';
                li.innerHTML = `
                    <div class="flex items-center space-x-3">
                        <span class="text-2xl">${getFileIcon(file.type)}</span>
                        <div>
                            <p class="text-sm font-medium text-secondary-900">${file.name}</p>
                            <p class="text-xs text-secondary-500">${formatFileSize(file.size)}</p>
                        </div>
                    </div>
                    <span class="text-green-500 text-sm">Siap diupload</span>
                `;
                lampiranList.appendChild(li);
            });

            // Show/hide preview section
            if (files.length > 0) {
                lampiranPreview.classList.remove('hidden');
            } else {
                lampiranPreview.classList.add('hidden');
            }
        }

        // Handle file selection
        lampiranInput?.addEventListener('change', function(e) {
            renderFileList(e.target.files);
        });

        // Handle drag and drop - click to open file dialog
        lampiranDropzone?.addEventListener('click', function() {
            lampiranInput?.click();
        });

        lampiranDropzone?.addEventListener('dragover', function(e) {
            e.preventDefault();
            lampiranDropzone.classList.add('border-primary-500', 'bg-primary-50');
        });

        lampiranDropzone?.addEventListener('dragleave', function(e) {
            e.preventDefault();
            lampiranDropzone.classList.remove('border-primary-500', 'bg-primary-50');
        });

        lampiranDropzone?.addEventListener('drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
            lampiranDropzone.classList.remove('border-primary-500', 'bg-primary-50');

            const files = e.dataTransfer.files;
            const validFiles = Array.from(files).filter(file => {
                const validTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
                return validTypes.includes(file.type) || file.name.match(/\.(pdf|jpg|jpeg|png|doc|docx)$/i);
            });

            if (validFiles.length > 0) {
                // Create DataTransfer and add files
                const dt = new DataTransfer();
                validFiles.forEach(file => dt.items.add(file));
                lampiranInput.files = dt.files;
                renderFileList(lampiranInput.files);
            }
        });

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleRekeningSection();
        });
    </script>
</x-app-layout>
