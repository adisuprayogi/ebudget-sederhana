<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg shadow-amber-500/30">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Edit Perencanaan Penerimaan</h1>
                    <p class="text-gray-500 text-sm mt-0.5">Ubah data perencanaan penerimaan dana</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('perencanaan-penerimaan.show', $perencanaan) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 font-medium rounded-xl shadow-sm transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    Lihat Detail
                </a>
                <a href="{{ route('perencanaan-penerimaan.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 font-medium rounded-xl shadow-sm transition-all duration-200 hover:shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-6xl mx-auto">
        <form method="POST" action="{{ route('perencanaan-penerimaan.update', $perencanaan) }}" id="editForm">
            @csrf
            @method('PUT')

            <!-- Informasi Perencanaan -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-amber-50 to-orange-50 px-6 py-4 border-b border-amber-100">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-sm flex-shrink-0">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-amber-900">Informasi Perencanaan</p>
                            <p class="text-sm text-amber-700 mt-0.5">Ubah data perencanaan penerimaan dana</p>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Periode Anggaran (Read Only) -->
                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Periode Anggaran</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <input type="text" value="{{ $perencanaan->periodeAnggaran->nama_periode ?? '-' }} ({{ $perencanaan->periodeAnggaran->tahun_anggaran ?? '-' }})" readonly class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl bg-gray-50 text-gray-500 cursor-not-allowed">
                            </div>
                        </div>

                        <!-- Divisi (Read Only) -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Divisi</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                                <input type="text" value="{{ $perencanaan->divisi->nama_divisi ?? '-' }}" readonly class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl bg-gray-50 text-gray-500 cursor-not-allowed">
                            </div>
                        </div>

                        <!-- Kode Rekening -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Kode Rekening</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                    </svg>
                                </div>
                                <input type="text" name="kode_rekening" value="{{ old('kode_rekening', $perencanaan->kode_rekening) }}" placeholder="Contoh: 4.1.1.01" class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all font-mono">
                            </div>
                            @error('kode_rekening')
                                <p class="text-sm text-red-600 mt-1.5 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Uraian -->
                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Uraian <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute top-3 left-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <textarea name="uraian" rows="3" required class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all resize-none" placeholder="Jelaskan sumber penerimaan dana...">{{ old('uraian', $perencanaan->uraian) }}</textarea>
                            </div>
                            @error('uraian')
                                <p class="text-sm text-red-600 mt-1.5 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Sumber Dana -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Sumber Dana <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <select name="sumber_dana_id" required class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all appearance-none bg-white">
                                    <option value="">Pilih Sumber Dana</option>
                                    @foreach($sumberDanas ?? [] as $sumberDana)
                                        <option value="{{ $sumberDana->id }}" {{ old('sumber_dana_id', $perencanaan->sumber_dana_id) == $sumberDana->id ? 'selected' : '' }}>
                                            {{ $sumberDana->nama_sumber }} ({{ $sumberDana->kode_sumber }})
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>
                            @error('sumber_dana_id')
                                <p class="text-sm text-red-600 mt-1.5 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Jumlah Estimasi -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Jumlah Estimasi <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 font-bold text-sm">Rp</span>
                                </div>
                                <input type="text" id="jumlah_estimasi_display" class="currency-input w-full pl-12 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-right font-mono" placeholder="0" data-target="jumlah_estimasi">
                                <input type="hidden" name="jumlah_estimasi" id="jumlah_estimasi" value="{{ old('jumlah_estimasi', $perencanaan->jumlah_estimasi) }}">
                            </div>
                            @error('jumlah_estimasi')
                                <p class="text-sm text-red-600 mt-1.5 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Perkiraan Per Bulan -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-purple-50 to-pink-50 px-6 py-4 border-b border-purple-100">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-sm flex-shrink-0">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-purple-900">Perkiraan Per Bulan</p>
                            <p class="text-sm text-purple-700 mt-0.5">Rincikan estimasi penerimaan per bulan sesuai periode anggaran</p>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <!-- Month inputs will be dynamically loaded here -->
                    <div id="months-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div class="col-span-full text-center py-8 text-gray-500">
                            <p>Memuat daftar bulan...</p>
                        </div>
                    </div>

                    <!-- Summary -->
                    <div class="mt-6 p-5 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-100">
                        <div class="flex flex-wrap justify-between items-center gap-4">
                            <div class="flex items-center gap-2">
                                <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center shadow-sm">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <span class="text-sm text-blue-700 block">Total Per Bulan:</span>
                                    <span id="total_bulanan" class="text-lg font-bold text-blue-900">Rp 0</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center shadow-sm">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <span class="text-sm text-blue-700 block">Estimasi:</span>
                                    <span id="estimasi_display" class="text-lg font-bold text-blue-900">Rp 0</span>
                                </div>
                            </div>
                            <div id="selisih_container" class="hidden flex items-center gap-2">
                                <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center shadow-sm">
                                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                                <div>
                                    <span class="text-sm text-blue-700 block">Selisih:</span>
                                    <span id="selisih" class="text-lg font-bold text-red-600">Rp 0</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 flex items-start gap-3 p-4 bg-amber-50 rounded-xl border border-amber-200">
                        <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-sm text-amber-800">Total per bulan harus sama dengan jumlah estimasi</p>
                    </div>
                </div>
            </div>

            <!-- Catatan -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-4 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-sm flex-shrink-0">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Catatan</h3>
                    </div>
                </div>
                <div class="p-6">
                    <div class="relative">
                        <div class="absolute top-3 left-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <textarea name="catatan" rows="3" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all resize-none" placeholder="Tambahkan catatan atau keterangan tambahan...">{{ old('catatan', $perencanaan->catatan) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('perencanaan-penerimaan.show', $perencanaan) }}" class="inline-flex items-center gap-2 px-5 py-2.5 text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 font-medium rounded-xl shadow-sm transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-700 hover:to-orange-700 text-white font-medium rounded-xl shadow-lg shadow-amber-500/30 transition-all duration-200 hover:scale-[1.02]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    <script>
        let availableMonths = [];
        const existingData = @js($perencanaan->perkiraan_bulanan ?? []);

        // Currency Formatter
        function formatCurrency(value) {
            let cleanValue = value.replace(/[^0-9]/g, '');
            if (cleanValue === '') return '';
            let number = parseInt(cleanValue, 10);
            return number.toLocaleString('id-ID');
        }

        function parseCurrency(formattedValue) {
            let cleanValue = formattedValue.replace(/[^0-9]/g, '');
            return cleanValue === '' ? 0 : parseInt(cleanValue, 10);
        }

        function initCurrencyInputs() {
            document.querySelectorAll('.currency-input').forEach(input => {
                const targetId = input.getAttribute('data-target');
                const targetInput = document.getElementById(targetId);

                if (targetInput && targetInput.value) {
                    input.value = formatCurrency(targetInput.value);
                }

                input.addEventListener('input', function(e) {
                    let value = e.target.value;
                    let cursorPos = e.target.selectionStart;

                    let formatted = formatCurrency(value);
                    e.target.value = formatted;

                    if (targetInput) {
                        targetInput.value = parseCurrency(formatted);
                    }

                    let lengthDiff = formatted.length - value.length;
                    e.target.setSelectionRange(cursorPos + lengthDiff, cursorPos + lengthDiff);

                    if (typeof updateDisplay === 'function') {
                        updateDisplay();
                    }
                });

                input.addEventListener('blur', function(e) {
                    if (e.target.value === '') {
                        e.target.value = '';
                        if (targetInput) targetInput.value = 0;
                    }
                });

                input.addEventListener('focus', function(e) {
                    e.target.select();
                });
            });
        }

        // Generate months based on periode anggaran
        function loadMonths() {
            const container = document.getElementById('months-container');
            const startDate = new Date('{{ $perencanaan->periodeAnggaran->tanggal_mulai_penggunaan_anggaran ?? now() }}');
            const endDate = new Date('{{ $perencanaan->periodeAnggaran->tanggal_selesai_penggunaan_anggaran ?? now() }}');

            availableMonths = [];
            const currentDate = new Date(startDate);

            while (currentDate <= endDate) {
                const key = currentDate.toISOString().slice(0, 7);
                const label = currentDate.toLocaleDateString('id-ID', { year: 'numeric', month: 'long' });
                availableMonths.push({ key, label });
                currentDate.setMonth(currentDate.getMonth() + 1);
            }

            renderMonthInputs();
        }

        function renderMonthInputs() {
            const container = document.getElementById('months-container');

            if (availableMonths.length === 0) {
                container.innerHTML = '<div class="col-span-full text-center py-8 text-gray-500">Periode anggaran tidak valid</div>';
                return;
            }

            let html = '';
            availableMonths.forEach((month, index) => {
                const colors = [
                    'from-emerald-50 to-green-100 border-emerald-200 text-emerald-700',
                    'from-blue-50 to-indigo-100 border-blue-200 text-blue-700',
                    'from-amber-50 to-orange-100 border-amber-200 text-amber-700',
                    'from-purple-50 to-pink-100 border-purple-200 text-purple-700'
                ];
                const colorClass = colors[index % colors.length];
                const existingValue = existingData[month.key] || 0;

                html += `
                    <div class="bg-gradient-to-br ${colorClass} border rounded-xl p-4">
                        <label class="block text-sm font-bold mb-2">${month.label}</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-600 text-sm font-semibold">Rp</span>
                            <input type="text"
                                   name="perkiraan_bulanan_display[${month.key}]"
                                   class="bulanan-input currency-input-month w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm text-right font-mono bg-white"
                                   placeholder="0"
                                   value="${existingValue > 0 ? formatCurrency(existingValue.toString()) : ''}"
                                   data-month-key="${month.key}"
                                   data-month="${month.label}">
                            <input type="hidden" name="perkiraan_bulanan[${month.key}]" id="bulanan_${month.key}" class="bulanan-hidden" value="${existingValue}">
                        </div>
                    </div>
                `;
            });

            container.innerHTML = html;

            initMonthlyCurrencyInputs();

            updateDisplay();
        }

        function initMonthlyCurrencyInputs() {
            document.querySelectorAll('.currency-input-month').forEach(input => {
                const monthKey = input.getAttribute('data-month-key');
                const hiddenInput = document.getElementById('bulanan_' + monthKey);

                input.addEventListener('input', function(e) {
                    let value = e.target.value;
                    let cursorPos = e.target.selectionStart;

                    let formatted = formatCurrency(value);
                    e.target.value = formatted;

                    if (hiddenInput) {
                        hiddenInput.value = parseCurrency(formatted);
                    }

                    let lengthDiff = formatted.length - value.length;
                    e.target.setSelectionRange(cursorPos + lengthDiff, cursorPos + lengthDiff);

                    updateDisplay();
                });

                input.addEventListener('focus', function(e) {
                    e.target.select();
                });
            });
        }

        function updateDisplay() {
            const hiddenInputs = document.querySelectorAll('.bulanan-hidden');
            let total = 0;
            hiddenInputs.forEach(input => {
                total += parseFloat(input.value) || 0;
            });

            const estimasi = parseFloat(document.getElementById('jumlah_estimasi')?.value) || 0;

            document.getElementById('total_bulanan').textContent = 'Rp ' + total.toLocaleString('id-ID');
            document.getElementById('estimasi_display').textContent = 'Rp ' + estimasi.toLocaleString('id-ID');

            const selisih = Math.abs(total - estimasi);
            const selisihContainer = document.getElementById('selisih_container');
            const selisihDisplay = document.getElementById('selisih');

            if (total > 0 && estimasi > 0) {
                selisihContainer.classList.remove('hidden');
                selisihDisplay.textContent = 'Rp ' + selisih.toLocaleString('id-ID');
                if (selisih > 1) {
                    selisihDisplay.classList.add('text-red-600');
                    selisihDisplay.classList.remove('text-emerald-600');
                } else {
                    selisihDisplay.classList.remove('text-red-600');
                    selisihDisplay.classList.add('text-emerald-600');
                }
            } else {
                selisihContainer.classList.add('hidden');
            }
        }

        // Load months on page load
        document.addEventListener('DOMContentLoaded', function() {
            initCurrencyInputs();
            loadMonths();
        });
    </script>
</x-app-layout>
