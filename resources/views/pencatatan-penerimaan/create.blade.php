<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-blue-700 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/30">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Catat Penerimaan Dana Baru</h1>
                    <p class="text-gray-500 text-sm mt-0.5">Tambahkan pencatatan penerimaan dana yang terealisasi</p>
                </div>
            </div>
            @if($defaultPerencanaan ?? null)
                <a href="{{ route('perencanaan-penerimaan.show', $defaultPerencanaan->id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 hover:border-gray-300 font-medium rounded-xl shadow-sm transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span class="text-sm">Kembali ke Perencanaan</span>
                </a>
            @else
                <a href="{{ route('pencatatan-penerimaan.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 hover:border-gray-300 font-medium rounded-xl shadow-sm transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span class="text-sm">Kembali</span>
                </a>
            @endif
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <form method="POST" action="{{ route('pencatatan-penerimaan.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <!-- Info Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-white">Informasi Penerimaan</p>
                            <p class="text-sm text-blue-100 mt-0.5">Lengkapi data penerimaan dana di bawah ini</p>
                        </div>
                    </div>
                </div>

                <div class="p-6 space-y-5">
                    <!-- Referensi Perencanaan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Referensi Perencanaan</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none z-10">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <select name="perencanaan_penerimaan_id" id="perencanaan_penerimaan_id" required class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all text-sm {{ $errors->has('perencanaan_penerimaan_id') ? 'border-red-500' : '' }}">
                                <option value="">Pilih Perencanaan</option>
                                @foreach($perencanaanPenerimaans ?? [] as $perencanaan)
                                    <option value="{{ $perencanaan->id }}"
                                            data-periode-anggaran-id="{{ $perencanaan->periode_anggaran_id }}"
                                            data-periode-anggaran-name="{{ $perencanaan->periodeAnggaran->nama_periode ?? '-' }} ({{ $perencanaan->periodeAnggaran->tahun_anggaran ?? '-' }})"
                                            data-sumber-dana-id="{{ $perencanaan->sumber_dana_id }}"
                                            {{ old('perencanaan_penerimaan_id', $defaultPerencanaan->id ?? null) == $perencanaan->id ? 'selected' : '' }}>
                                        {{ $perencanaan->uraian }} - {{ $perencanaan->periodeAnggaran->nama_periode ?? '-' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('perencanaan_penerimaan_id')
                            <p class="text-sm text-red-600 mt-1.5 flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Hidden Fields -->
                    <input type="hidden" name="periode_anggaran_id" id="periode_anggaran_id" value="{{ old('periode_anggaran_id', $defaultPerencanaan->periode_anggaran_id ?? $defaultPeriodeAnggaranId ?? null) }}">
                    <input type="hidden" name="sumber_dana_id" id="sumber_dana_id" value="{{ old('sumber_dana_id', $defaultPerencanaan->sumber_dana_id ?? $sumberDanas->first()?->id ?? null) }}">

                    <!-- Auto-filled Fields Display -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-blue-50 rounded-xl p-4 border border-blue-100">
                            <label class="block text-xs font-semibold text-blue-700 mb-1.5 uppercase tracking-wide flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Periode Anggaran
                            </label>
                            <p class="text-sm font-medium text-gray-900 mt-1" id="periode_anggaran_display">{{ $defaultPerencanaan->periodeAnggaran->nama_periode ?? '-' }} ({{ $defaultPerencanaan->periodeAnggaran->tahun_anggaran ?? '-' }})</p>
                        </div>
                        <div class="bg-emerald-50 rounded-xl p-4 border border-emerald-100">
                            <label class="block text-xs font-semibold text-emerald-700 mb-1.5 uppercase tracking-wide flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Sumber Dana
                            </label>
                            <p class="text-sm font-medium text-gray-900 mt-1" id="sumber_dana_display">{{ $defaultPerencanaan->sumberDana->nama_sumber ?? $sumberDanas->first()?->nama_sumber ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detail Penerimaan Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Detail Penerimaan
                    </h3>
                </div>

                <div class="p-6 space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Tanggal Penerimaan -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Penerimaan</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <input type="date" name="tanggal_penerimaan" value="{{ old('tanggal_penerimaan', now()->format('Y-m-d')) }}" required class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all text-sm {{ $errors->has('tanggal_penerimaan') ? 'border-red-500' : '' }}">
                            </div>
                            @error('tanggal_penerimaan')
                                <p class="text-sm text-red-600 mt-1.5 flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Jumlah Diterima -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Diterima</label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-1/2 transform -translate-y-1/2 text-gray-500 font-medium text-sm">Rp</span>
                                <input type="text" id="jumlah_diterima_display" class="currency-input w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all text-right font-medium text-gray-900 {{ $errors->has('jumlah_diterima') ? 'border-red-500' : '' }}" placeholder="0" data-target="jumlah_diterima">
                                <input type="hidden" name="jumlah_diterima" id="jumlah_diterima" value="{{ old('jumlah_diterima') }}">
                            </div>
                            @error('jumlah_diterima')
                                <p class="text-sm text-red-600 mt-1.5 flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Uraian -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Uraian Penerimaan</label>
                        <div class="relative">
                            <div class="absolute top-3 left-3.5 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <textarea name="uraian" rows="3" required class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all resize-none text-sm {{ $errors->has('uraian') ? 'border-red-500' : '' }}" placeholder="Jelaskan sumber dan tujuan penerimaan dana...">{{ old('uraian') }}</textarea>
                        </div>
                        @error('uraian')
                            <p class="text-sm text-red-600 mt-1.5 flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Bukti Penerimaan Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        Bukti Penerimaan
                    </h3>
                </div>

                <div class="p-6">
                    <div class="flex justify-center px-6 pt-6 pb-6 border-2 border-dashed rounded-xl {{ $errors->has('bukti_penerimaan') ? 'border-red-300 bg-red-50' : 'border-gray-300 hover:border-blue-400 bg-gray-50' }} transition-colors">
                        <div class="space-y-3 text-center w-full">
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mx-auto">
                                <svg class="w-6 h-6 text-blue-600" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <div class="flex items-center justify-center gap-1 text-sm text-gray-600">
                                <label class="relative cursor-pointer font-medium text-blue-600 hover:text-blue-700 transition-colors">
                                    <span>Pilih file</span>
                                    <input type="file" name="bukti_penerimaan" id="bukti_penerimaan" class="sr-only" accept=".pdf,.jpg,.jpeg,.png">
                                </label>
                                <span>atau drag & drop</span>
                            </div>
                            <p class="text-xs text-gray-500">PDF, JPG, PNG hingga 5MB</p>
                            <p id="file_name_display" class="text-sm font-medium text-blue-600 mt-2 hidden flex items-center justify-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span></span>
                            </p>
                        </div>
                    </div>
                    @error('bukti_penerimaan')
                        <p class="text-sm text-red-600 mt-1.5 flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 flex items-center justify-end gap-3">
                @if($defaultPerencanaan ?? null)
                    <a href="{{ route('perencanaan-penerimaan.show', $defaultPerencanaan->id) }}" class="inline-flex items-center gap-2 px-5 py-2.5 text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 font-medium rounded-xl transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <span class="text-sm">Batal</span>
                    </a>
                @else
                    <a href="{{ route('pencatatan-penerimaan.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 font-medium rounded-xl transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <span class="text-sm">Batal</span>
                    </a>
                @endif
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-medium rounded-xl shadow-lg shadow-blue-500/30 transition-all duration-200 hover:scale-[1.02]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span class="text-sm">Simpan Pencatatan</span>
                </button>
            </div>
        </form>
    </div>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <style>
        /* Custom Select2 Styling - Blue Theme */
        .select2-container {
            display: block;
        }

        .select2-container .select2-selection--single {
            height: 48px;
            border: 1px solid #e5e7eb;
            border-radius: 0.75rem;
            outline: none;
            transition: all 0.2s;
            box-sizing: border-box;
            background: #f9fafb;
        }

        .select2-container--open .select2-selection--single {
            border-color: #3b82f6;
            background: white;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .select2-container .select2-selection--single:focus {
            border-color: #3b82f6;
            background: white;
        }

        .select2-container .select2-selection--single .select2-selection__rendered {
            padding-left: 2.75rem;
            padding-right: 1rem;
            line-height: 46px;
            color: #1f2937;
        }

        .select2-container .select2-selection--single .select2-selection__placeholder {
            color: #9ca3af;
        }

        .select2-container .select2-selection--single .select2-selection__arrow {
            height: 46px;
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

        .select2-container--default .select2-selection--single .select2-selection__clear {
            font-size: 18px;
            color: #9ca3af;
            margin-right: 1rem;
        }

        .select2-container--default .select2-selection--single .select2-selection__clear:hover {
            color: #ef4444;
        }

        /* Dropdown */
        .select2-container--open .select2-dropdown--below {
            border: 1px solid #e5e7eb;
            border-radius: 0.75rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            margin-top: 4px;
        }

        .select2-results__option {
            padding: 0.75rem 1rem;
            color: #1f2937;
            font-size: 0.875rem;
        }

        .select2-results__option--highlighted {
            background-color: #eff6ff;
            color: #1e40af;
        }

        .select2-results__option[aria-selected="true"] {
            background-color: #eff6ff;
            color: #1e40af;
        }

        .select2-container--default .select2-results__option[aria-selected="true"] {
            background-color: #eff6ff;
            color: #1e40af;
        }

        /* Search box in dropdown */
        .select2-search--dropdown .select2-search__field {
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 0.625rem 0.875rem;
            outline: none;
            font-size: 0.875rem;
        }

        .select2-search--dropdown .select2-search__field:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
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

            // File input - display selected file name
            const fileInput = document.getElementById('bukti_penerimaan');
            const fileNameDisplay = document.getElementById('file_name_display');

            if (fileInput) {
                fileInput.addEventListener('change', function(e) {
                    if (this.files && this.files.length > 0) {
                        fileNameDisplay.querySelector('span').textContent = this.files[0].name;
                        fileNameDisplay.classList.remove('hidden');
                    } else {
                        fileNameDisplay.classList.add('hidden');
                    }
                });
            }
        });
    </script>
</x-app-layout>
