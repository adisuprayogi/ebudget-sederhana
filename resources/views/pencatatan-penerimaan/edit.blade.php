<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('pencatatan-penerimaan.show', $pencatatan) }}" class="w-10 h-10 rounded-xl bg-blue-50 hover:bg-blue-100 flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-xl font-semibold text-gray-900">Edit Pencatatan Penerimaan</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Ubah data penerimaan dana</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="max-w-3xl">
        <form method="POST" action="{{ route('pencatatan-penerimaan.update', $pencatatan) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Main Form Card -->
            <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
                <!-- Form Fields -->
                <div class="p-8 space-y-6">
                    <!-- Referensi Perencanaan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Referensi Perencanaan</label>
                        <select name="perencanaan_penerimaan_id" id="perencanaan_penerimaan_id" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all bg-white text-sm">
                            <option value="">Pilih Perencanaan</option>
                            @foreach($perencanaanPenerimaans ?? [] as $perencanaan)
                                <option value="{{ $perencanaan->id }}"
                                        data-periode-anggaran-id="{{ $perencanaan->periode_anggaran_id }}"
                                        data-periode-anggaran-name="{{ $perencanaan->periodeAnggaran->nama_periode ?? '-' }} ({{ $perencanaan->periodeAnggaran->tahun_anggaran ?? '-' }})"
                                        data-sumber-dana-id="{{ $perencanaan->sumber_dana_id }}"
                                        {{ old('perencanaan_penerimaan_id', $pencatatan->perencanaan_penerimaan_id) == $perencanaan->id ? 'selected' : '' }}>
                                    {{ $perencanaan->uraian }} - {{ $perencanaan->periodeAnggaran->nama_periode ?? '-' }}
                                </option>
                            @endforeach
                        </select>
                        @error('perencanaan_penerimaan_id')
                            <p class="text-sm text-red-600 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Hidden Fields -->
                    <input type="hidden" name="periode_anggaran_id" id="periode_anggaran_id" value="{{ old('periode_anggaran_id', $pencatatan->periode_anggaran_id) }}">
                    <input type="hidden" name="sumber_dana_id" id="sumber_dana_id" value="{{ old('sumber_dana_id', $pencatatan->sumber_dana_id) }}">

                    <!-- Auto-filled Fields Display -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-blue-50 rounded-xl p-4 border border-blue-100">
                            <p class="text-xs text-gray-500 mb-1">Periode Anggaran</p>
                            <p class="text-sm font-medium text-gray-900" id="periode_anggaran_display">{{ $pencatatan->periodeAnggaran->nama_periode ?? '-' }} ({{ $pencatatan->periodeAnggaran->tahun_anggaran ?? '-' }})</p>
                        </div>
                        <div class="bg-blue-50 rounded-xl p-4 border border-blue-100">
                            <p class="text-xs text-gray-500 mb-1">Sumber Dana</p>
                            <p class="text-sm font-medium text-gray-900" id="sumber_dana_display">{{ $pencatatan->sumberDana->nama_sumber ?? '-' }}</p>
                        </div>
                    </div>

                    <!-- Tanggal & Jumlah -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Penerimaan <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_penerimaan" value="{{ old('tanggal_penerimaan', $pencatatan->tanggal_penerimaan?->format('Y-m-d')) }}" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm">
                            @error('tanggal_penerimaan')
                                <p class="text-sm text-red-600 mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Diterima <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm font-medium">Rp</span>
                                <input type="text" id="jumlah_diterima_display" class="currency-input w-full pl-12 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-right font-mono text-sm" placeholder="0" data-target="jumlah_diterima">
                                <input type="hidden" name="jumlah_diterima" id="jumlah_diterima" value="{{ old('jumlah_diterima', $pencatatan->jumlah_diterima) }}">
                            </div>
                            @error('jumlah_diterima')
                                <p class="text-sm text-red-600 mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Uraian -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Uraian Penerimaan <span class="text-red-500">*</span></label>
                        <textarea name="uraian" rows="3" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all resize-none text-sm" placeholder="Jelaskan sumber dan tujuan penerimaan dana...">{{ old('uraian', $pencatatan->uraian) }}</textarea>
                        @error('uraian')
                            <p class="text-sm text-red-600 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Bukti Penerimaan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bukti Penerimaan</label>
                        @if($pencatatan->bukti_penerimaan)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200 mb-3">
                                <div class="flex items-center gap-3">
                                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ basename($pencatatan->bukti_penerimaan) }}</p>
                                        <p class="text-xs text-gray-500">File saat ini</p>
                                    </div>
                                </div>
                                <a href="{{ asset('storage/' . $pencatatan->bukti_penerimaan) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    Lihat
                                </a>
                            </div>
                        @endif
                        <div class="flex justify-center px-6 py-6 border-2 border-dashed rounded-xl border-gray-300 hover:border-blue-400 bg-gray-50 transition-colors">
                            <div class="space-y-2 text-center w-full">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mx-auto">
                                    <svg class="w-5 h-5 text-blue-600" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <div class="flex items-center justify-center gap-1 text-sm text-gray-600">
                                    <label class="relative cursor-pointer font-medium text-blue-600 hover:text-blue-700 transition-colors">
                                        <span>{{ $pencatatan->bukti_penerimaan ? 'Ganti file' : 'Pilih file' }}</span>
                                        <input type="file" name="bukti_penerimaan" class="sr-only" accept=".pdf,.jpg,.jpeg,.png">
                                    </label>
                                    <span>atau drag & drop</span>
                                </div>
                                <p class="text-xs text-gray-500">PDF, JPG, PNG hingga 5MB</p>
                            </div>
                        </div>
                        @error('bukti_penerimaan')
                            <p class="text-sm text-red-600 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-gray-50 px-8 py-4 border-t border-gray-200 flex items-center justify-end gap-3">
                    <a href="{{ route('pencatatan-penerimaan.show', $pencatatan) }}" class="inline-flex items-center gap-2 px-5 py-2.5 text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 font-medium rounded-lg transition-colors text-sm">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <style>
        .select2-container { display: block; }
        .select2-container .select2-selection--single {
            height: 42px; border: 1px solid #d1d5db; border-radius: 0.5rem; outline: none; background: white;
        }
        .select2-container--open .select2-selection--single { border-color: #3b82f6; box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1); }
        .select2-container .select2-selection--single .select2-selection__rendered {
            padding-left: 1rem; padding-right: 2rem; line-height: 40px; color: #1f2937; font-size: 0.875rem;
        }
        .select2-container .select2-selection--single .select2-selection__placeholder { color: #9ca3af; }
        .select2-container .select2-selection--single .select2-selection__arrow { height: 40px; right: 0.75rem; width: 20px; }
        .select2-container .select2-selection--single .select2-selection__arrow b { border-color: #6b7280 transparent; border-width: 5px 5px 0; margin-left: -5px; margin-top: -2px; }
        .select2-container--open .select2-selection--single .select2-selection__arrow b { border-width: 0 5px 5px; }
        .select2-container--open .select2-dropdown--below { border: 1px solid #e5e7eb; border-radius: 0.5rem; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); margin-top: 4px; }
        .select2-results__option { padding: 0.5rem 1rem; color: #1f2937; font-size: 0.875rem; }
        .select2-results__option--highlighted { background-color: #eff6ff; color: #1e40af; }
        .select2-results__option[aria-selected="true"] { background-color: #eff6ff; color: #1e40af; }
        .select2-search--dropdown .select2-search__field { border: 1px solid #e5e7eb; border-radius: 0.375rem; padding: 0.5rem 0.75rem; font-size: 0.875rem; }
        .select2-search--dropdown .select2-search__field:focus { border-color: #3b82f6; box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1); }
    </style>

    <script>
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
                });

                input.addEventListener('focus', function(e) {
                    e.target.select();
                });
            });
        }

        $(document).ready(function() {
            // Initialize currency inputs
            initCurrencyInputs();

            // Initialize Select2
            const perencanaanSelect = $('#perencanaan_penerimaan_id');
            if (perencanaanSelect.length) {
                perencanaanSelect.select2({
                    placeholder: 'Cari Perencanaan...',
                    allowClear: false,
                    width: '100%'
                });

                // Handle change event for auto-fill fields
                perencanaanSelect.on('select2:select', function() {
                    const selectedOption = $(this).find(':selected');
                    const periodeAnggaranId = selectedOption.data('periode-anggaran-id');
                    const periodeAnggaranName = selectedOption.data('periode-anggaran-name');
                    const sumberDanaId = selectedOption.data('sumber-dana-id');

                    const periodeAnggaranInput = document.getElementById('periode_anggaran_id');
                    const periodeAnggaranDisplay = document.getElementById('periode_anggaran_display');
                    const sumberDanaInput = document.getElementById('sumber_dana_id');
                    const sumberDanaDisplay = document.getElementById('sumber_dana_display');

                    if (periodeAnggaranId) {
                        periodeAnggaranInput.value = periodeAnggaranId;
                        periodeAnggaranDisplay.textContent = periodeAnggaranName || '-';
                    }

                    if (sumberDanaId) {
                        sumberDanaInput.value = sumberDanaId;
                        const sumberDanaData = @json($sumberDanas);
                        const sumberDana = sumberDanaData.find(s => s.id == sumberDanaId);
                        sumberDanaDisplay.textContent = sumberDana ? sumberDana.nama_sumber : '-';
                    }
                });
            }
        });
    </script>
</x-app-layout>
