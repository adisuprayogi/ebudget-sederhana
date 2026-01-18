<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-blue-700 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/30">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Edit Pencatatan Penerimaan</h1>
                    <p class="text-gray-500 text-sm mt-0.5">Ubah data pencatatan penerimaan dana</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('pencatatan-penerimaan.show', $pencatatan) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 font-medium rounded-xl shadow-sm transition-all duration-200 hover:shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    Lihat
                </a>
                <a href="{{ route('pencatatan-penerimaan.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 font-medium rounded-xl shadow-sm transition-all duration-200 hover:shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <form method="POST" action="{{ route('pencatatan-penerimaan.update', $pencatatan) }}" enctype="multipart/form-data" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            @csrf
            @method('PUT')

            <!-- Info Banner -->
            <div class="bg-gradient-to-r from-blue-50 to-orange-50 px-6 py-4 border-b border-blue-100">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-sm flex-shrink-0">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-blue-900">Edit Pencatatan Penerimaan</p>
                        <p class="text-sm text-blue-700 mt-0.5">Ubah data penerimaan dana di bawah ini</p>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <div class="space-y-6">
                    <!-- Referensi Perencanaan -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Referensi Perencanaan</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                                <svg class="w-5 h-5 text-gray-400 select2-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <select name="perencanaan_penerimaan_id" id="perencanaan_penerimaan_id" class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all {{ $errors->has('perencanaan_penerimaan_id') ? 'border-red-500' : '' }}">
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
                        </div>
                        @error('perencanaan_penerimaan_id')
                            <p class="text-sm text-red-600 mt-1.5 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Hidden Fields -->
                    <input type="hidden" name="periode_anggaran_id" id="periode_anggaran_id" value="{{ old('periode_anggaran_id', $pencatatan->periode_anggaran_id) }}">
                    <input type="hidden" name="sumber_dana_id" id="sumber_dana_id" value="{{ old('sumber_dana_id', $pencatatan->sumber_dana_id) }}">

                    <!-- Auto-filled Fields Display -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Periode Anggaran</label>
                            <div class="px-4 py-2.5 bg-gray-100 border border-gray-300 rounded-xl text-gray-700 flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span id="periode_anggaran_display">{{ $pencatatan->periodeAnggaran->nama_periode ?? '-' }} ({{ $pencatatan->periodeAnggaran->tahun_anggaran ?? '-' }})</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Sumber Dana</label>
                            <div class="px-4 py-2.5 bg-gray-100 border border-gray-300 rounded-xl text-gray-700 flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span id="sumber_dana_display">{{ $pencatatan->sumberDana->nama_sumber ?? '-' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Tanggal Penerimaan -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Tanggal Penerimaan</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <input type="date" name="tanggal_penerimaan" value="{{ old('tanggal_penerimaan', $pencatatan->tanggal_penerimaan?->format('Y-m-d')) }}" required class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all {{ $errors->has('tanggal_penerimaan') ? 'border-red-500' : '' }}">
                            </div>
                            @error('tanggal_penerimaan')
                                <p class="text-sm text-red-600 mt-1.5 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Jumlah Diterima -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Jumlah Diterima</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 font-medium">Rp</span>
                                <input type="text" id="jumlah_diterima_display" class="currency-input w-full pl-12 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all text-right {{ $errors->has('jumlah_diterima') ? 'border-red-500' : '' }}" placeholder="0" data-target="jumlah_diterima" value="{{ $pencatatan->jumlah_diterima }}">
                                <input type="hidden" name="jumlah_diterima" id="jumlah_diterima" value="{{ old('jumlah_diterima', $pencatatan->jumlah_diterima) }}">
                            </div>
                            @error('jumlah_diterima')
                                <p class="text-sm text-red-600 mt-1.5 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Uraian -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Uraian Penerimaan</label>
                        <div class="relative">
                            <div class="absolute top-3 left-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <textarea name="uraian" rows="3" required class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all resize-none {{ $errors->has('uraian') ? 'border-red-500' : '' }}" placeholder="Jelaskan sumber dan tujuan penerimaan dana...">{{ old('uraian', $pencatatan->uraian) }}</textarea>
                        </div>
                        @error('uraian')
                            <p class="text-sm text-red-600 mt-1.5 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Bukti Penerimaan -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Bukti Penerimaan</label>
                        @if($pencatatan->bukti_penerimaan)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-200 mb-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">{{ basename($pencatatan->bukti_penerimaan) }}</span>
                                </div>
                                <a href="{{ asset('storage/' . $pencatatan->bukti_penerimaan) }}" target="_blank" class="text-blue-600 hover:text-blue-700 font-medium text-sm flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    Lihat
                                </a>
                            </div>
                        @endif
                        <div class="flex justify-center px-6 pt-6 pb-6 border-2 border-dashed rounded-xl {{ $errors->has('bukti_penerimaan') ? 'border-red-300 bg-red-50' : 'border-gray-300 hover:border-orange-400' }} transition-colors">
                            <div class="space-y-2 text-center w-full">
                                <svg class="mx-auto h-8 w-8 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600 justify-center">
                                    <label class="relative cursor-pointer bg-white rounded-md font-medium text-orange-500 hover:text-orange-600 transition-colors">
                                        <span>Ganti file</span>
                                        <input type="file" name="bukti_penerimaan" class="sr-only" accept=".pdf,.jpg,.jpeg,.png">
                                    </label>
                                </div>
                                <p class="text-xs text-gray-500">PDF, JPG, PNG hingga 5MB</p>
                            </div>
                        </div>
                        @error('bukti_penerimaan')
                            <p class="text-sm text-red-600 mt-1.5 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex items-center justify-end gap-3">
                <a href="{{ route('pencatatan-penerimaan.show', $pencatatan) }}" class="inline-flex items-center gap-2 px-5 py-2.5 text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 font-medium rounded-xl shadow-sm transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl shadow-lg shadow-blue-500/30 transition-all duration-200 hover:scale-[1.02]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <style>
        /* Custom Select2 Styling to match existing form */
        .select2-container {
            display: block;
        }

        .select2-container .select2-selection--single {
            height: 44px;
            border: 1px solid #d1d5db;
            border-radius: 0.75rem;
            outline: none;
            transition: all 0.2s;
            box-sizing: border-box;
            background: white;
        }

        .select2-container--open .select2-selection--single {
            border-color: #f97316;
            box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1);
        }

        .select2-container .select2-selection--single:focus {
            border-color: #f97316;
            box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1);
        }

        .select2-container .select2-selection--single .select2-selection__rendered {
            padding-left: 3rem;
            padding-right: 2.5rem;
            line-height: 42px;
            color: #1f2937;
        }

        .select2-container .select2-selection--single .select2-selection__placeholder {
            color: #9ca3af;
        }

        .select2-container .select2-selection--single .select2-selection__arrow {
            height: 42px;
            right: 1rem;
            width: 20px;
        }

        .select2-container .select2-selection--single .select2-selection__arrow b {
            border-color: #6b7280 transparent;
            border-width: 5px 5px 0;
            margin-left: -5px;
            margin-top: -2px;
        }

        .select2-container--open .select2-selection--single .select2-selection__arrow b {
            border-width: 0 5px 5px;
        }

        /* Dropdown */
        .select2-container--open .select2-dropdown--below {
            border: 1px solid #d1d5db;
            border-radius: 0.75rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            margin-top: 4px;
        }

        .select2-results__option {
            padding: 0.625rem 1rem;
            color: #1f2937;
        }

        .select2-results__option--highlighted {
            background-color: #fff7ed;
            color: #9a3412;
        }

        .select2-results__option[aria-selected="true"] {
            background-color: #fff7ed;
            color: #9a3412;
        }

        /* Search box in dropdown */
        .select2-search--dropdown .select2-search__field {
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            padding: 0.5rem 0.75rem;
            outline: none;
        }

        .select2-search--dropdown .select2-search__field:focus {
            border-color: #f97316;
            box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1);
        }

        /* Fix icon visibility - adjust parent relative positioning */
        .relative > .select2-icon {
            z-index: 10 !important;
        }
    </style>

    <script>
        $(document).ready(function() {
            // Initialize Select2 for searchable dropdown
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
