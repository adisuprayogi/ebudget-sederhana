<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('pengajuan-dana.select-jenis') }}" class="p-2 text-secondary-600 hover:text-secondary-900 hover:bg-secondary-100 rounded-lg transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-secondary-900">
                    Pengajuan {{ [
                        'kegiatan' => 'Kegiatan',
                        'pengadaan' => 'Pengadaan',
                        'pembayaran' => 'Pembayaran',
                        'honorarium' => 'Honorarium',
                        'sewa' => 'Sewa',
                        'konsumi' => 'Konsumi',
                        'reimbursement' => 'Reimbursement',
                        'lainnya' => 'Lainnya'
                    ][$jenisPengajuan] ?? 'Dana' }}
                </h1>
                <p class="text-secondary-600 mt-1">Formulir pengajuan dana untuk keperluan operasional</p>
            </div>
        </div>
    </x-slot>

    <x-slot name="scripts">
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Active Periode Info Banner -->
        @if(isset($activePeriode))
            <div class="mb-6 bg-gradient-to-r from-primary-500 to-primary-600 rounded-2xl shadow-soft p-6 text-white">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="text-sm text-primary-100 mb-1">Periode Anggaran Aktif (Fase Penggunaan)</div>
                        <div class="text-xl font-bold">{{ $activePeriode->nama_periode }}</div>
                        <div class="text-primary-100 mt-1">
                            {{ $activePeriode->tanggal_mulai_penggunaan_anggaran->translatedFormat('d F Y') }} - {{ $activePeriode->tanggal_selesai_penggunaan_anggaran->translatedFormat('d F Y') }}
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-primary-100 mb-1">Tahun Anggaran</div>
                        <div class="text-2xl font-bold">{{ $activePeriode->tahun_anggaran }}</div>
                    </div>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('pengajuan-dana.store') }}" enctype="multipart/form-data" id="pengajuan-dana-form">
            @csrf

            @if($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Terjadi kesalahan pada input:</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="space-y-6">
                <!-- Informasi Dasar -->
                <div class="bg-white rounded-2xl shadow-soft p-6">
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
                            <x-input-label for="judul_pengajuan" value="Judul Pengajuan *" />
                            <input type="text" name="judul_pengajuan" id="judul_pengajuan" value="{{ old('judul_pengajuan') }}" required
                                class="mt-1 block w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                placeholder="Contoh: Pengadaan ATK Bulan Desember 2025">
                            <x-input-error :messages="$errors->get('judul_pengajuan')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="program_kerja_id" value="Program Kerja *" />
                            <select name="program_kerja_id" id="program_kerja_id" required
                                class="mt-1 block w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                <option value="">Pilih Program Kerja</option>
                                @foreach($programKerjas as $program)
                                    <option value="{{ $program->id }}" data-divisi-id="{{ $program->divisi_id }}" {{ old('program_kerja_id') == $program->id ? 'selected' : '' }}>
                                        {{ $program->nama_program }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('program_kerja_id')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="sub_program_id" value="Sub Program *" />
                            <select name="sub_program_id" id="sub_program_id" required disabled
                                class="mt-1 block w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent disabled:bg-gray-100">
                                <option value="">Pilih Program Kerja</option>
                            </select>
                            <x-input-error :messages="$errors->get('sub_program_id')" class="mt-2" />
                        </div>

                        @if($jenisPengajuan === 'honorarium')
                            <!-- Detail Anggaran untuk Honorarium -->
                            <div>
                                <x-input-label for="detail_anggaran_id" value="Detail Anggaran *" />
                                <select name="detail_anggaran_id" id="detail_anggaran_id" required disabled
                                    class="mt-1 block w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent disabled:bg-gray-100">
                                    <option value="">Pilih Sub Program Terlebih Dahulu</option>
                                </select>
                                <div id="detail-anggaran-sisa" class="mt-1 text-sm text-gray-600"></div>
                                <x-input-error :messages="$errors->get('detail_anggaran_id')" class="mt-2" />
                            </div>
                        @endif

                        <!-- Hidden field for divisi_id - will be set based on program kerja -->
                        <input type="hidden" name="divisi_id" id="divisi_id" value="">

                        <!-- Hidden field for jenis_pengajuan -->
                        <input type="hidden" name="jenis_pengajuan" value="{{ $jenisPengajuan }}" id="jenis_pengajuan">

                        <div class="md:col-span-2">
                            <x-input-label for="deskripsi" value="Deskripsi *" />
                            <textarea name="deskripsi" id="deskripsi" rows="3" required
                                class="mt-1 block w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                placeholder="Jelaskan secara singkat tujuan pengajuan dana ini">{{ old('deskripsi') }}</textarea>
                            <x-input-error :messages="$errors->get('deskripsi')" class="mt-2" />
                        </div>
                    </div>
                </div>

                @if($jenisPengajuan === 'honorarium')
                    <!-- Daftar Penerima Honorarium -->
                    <div class="bg-white rounded-2xl shadow-soft p-6">
                        <h2 class="text-lg font-semibold text-secondary-900 mb-4 flex items-center justify-between">
                            <div class="flex items-center">
                                <span class="w-8 h-8 bg-amber-100 text-amber-600 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </span>
                                Daftar Penerima Honorarium
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="button" onclick="window.honorariumForm().openImportModal()" class="px-4 py-2 bg-green-600 text-white rounded-xl text-sm hover:bg-green-700 transition-all">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                    Import Excel/CSV
                                </button>
                                <button type="button" onclick="window.honorariumForm().addRecipientRow()" class="px-4 py-2 bg-primary-600 text-white rounded-xl text-sm hover:bg-primary-700 transition-all">
                                    + Tambah Penerima
                                </button>
                            </div>
                        </h2>

                        <!-- Table with Inline Edit -->
                        <div class="overflow-x-auto">
                            <table class="w-full" id="honorarium-table">
                                <thead class="bg-secondary-50 border-b border-secondary-200">
                                    <tr>
                                        <th class="px-3 py-3 text-left text-xs font-semibold text-secondary-600 uppercase w-10">No</th>
                                        <th class="px-3 py-3 text-left text-xs font-semibold text-secondary-600 uppercase">Tipe</th>
                                        <th class="px-3 py-3 text-left text-xs font-semibold text-secondary-600 uppercase">Nama Penerima *</th>
                                        <th class="px-3 py-3 text-left text-xs font-semibold text-secondary-600 uppercase">Jumlah Honor *</th>
                                        <th class="px-3 py-3 text-left text-xs font-semibold text-secondary-600 uppercase">No. Rekening *</th>
                                        <th class="px-3 py-3 text-left text-xs font-semibold text-secondary-600 uppercase">Deskripsi</th>
                                        <th class="px-3 py-3 text-left text-xs font-semibold text-secondary-600 uppercase">Lampiran</th>
                                        <th class="px-3 py-3 text-center text-xs font-semibold text-secondary-600 uppercase w-24">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-secondary-100" id="honorarium-list-body">
                                    <tr id="empty-row">
                                        <td colspan="8" class="px-4 py-8 text-center text-secondary-500">
                                            Belum ada penerima. Klik "Tambah Penerima" atau "Import Excel/CSV" untuk menambahkan.
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot class="bg-secondary-50 border-t border-secondary-200">
                                    <tr>
                                        <td colspan="7" class="px-4 py-3 text-right text-sm font-semibold text-secondary-900">Total Pengajuan:</td>
                                        <td class="px-4 py-3 text-right">
                                            <span id="total-honorarium" class="text-xl font-bold text-primary-600">Rp 0</span>
                                            <input type="hidden" name="total_pengajuan" id="total_pengajuan" value="0">
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <!-- Hidden container for deleted rows -->
                        <input type="hidden" name="honorarium_row_count" id="honorarium_row_count" value="0">
                    </div>

                    <!-- Modal Import Excel/CSV -->
                    <div id="import-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
                        <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full">
                            <!-- Header -->
                            <div class="px-6 py-4 border-b border-gray-200">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg font-semibold text-gray-900">Import Daftar Penerima</h3>
                                    <button type="button" onclick="window.honorariumForm().closeImportModal()" class="text-gray-400 hover:text-gray-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="p-6">
                                <!-- Download Template -->
                                <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 text-blue-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-blue-700">Download Template Excel</p>
                                            <p class="text-xs text-blue-600 mt-1">Gunakan template ini untuk memastikan format data yang benar.</p>
                                            <button type="button" onclick="window.honorariumForm().downloadTemplate()" class="mt-2 px-3 py-1.5 bg-blue-600 text-white text-xs rounded-lg hover:bg-blue-700">
                                                Download Template
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Download Daftar Karyawan -->
                                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl">
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-green-700">Download Daftar Karyawan</p>
                                            <p class="text-xs text-green-600 mt-1">Gunakan daftar ini untuk memastikan nama karyawan sesuai dengan sistem.</p>
                                            <button type="button" onclick="window.honorariumForm().downloadEmployeeList()" class="mt-2 px-3 py-1.5 bg-green-600 text-white text-xs rounded-lg hover:bg-green-700">
                                                Download Daftar Karyawan
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Upload File -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload File Excel/CSV</label>
                                    <div class="mt-1 flex justify-center px-4 pt-5 pb-4 border-2 border-gray-300 border-dashed rounded-lg hover:bg-gray-50">
                                        <div class="text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <div class="mt-2">
                                                <label for="import-file" class="cursor-pointer">
                                                    <span class="mt-1 block text-sm font-medium text-gray-900">Klik untuk upload</span>
                                                    <span class="mt-1 block text-xs text-gray-500">.xlsx, .xls, .csv</span>
                                                </label>
                                                <input id="import-file" name="import_file" type="file" accept=".xlsx,.xls,.csv" class="sr-only" onchange="window.honorariumForm().handleImportFileSelect(this)">
                                            </div>
                                            <p class="text-xs text-gray-500 mt-2">Maksimal 5MB</p>
                                        </div>
                                        <div id="import-file-info" class="hidden mt-2 p-3 bg-green-50 rounded-lg">
                                            <p class="text-sm font-medium text-green-800" id="import-filename"></p>
                                            <p class="text-xs text-green-600 mt-1" id="import-filesize"></p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Format Guide -->
                                <div class="mt-4 text-xs text-gray-500">
                                    <p class="font-medium text-gray-700 mb-1">Format Kolom Excel:</p>
                                    <ul class="list-disc list-inside space-y-1">
                                        <li><strong>Tipe:</strong> karyawan / non_karyawan</li>
                                        <li><strong>Nama Karyawan:</strong> (isi jika tipe=karyawan)</li>
                                        <li><strong>Nama Penerima:</strong> (isi jika tipe=non_karyawan)</li>
                                        <li><strong>Jumlah Honor:</strong> angka</li>
                                        <li><strong>Nomor Rekening:</strong> teks</li>
                                        <li><strong>Deskripsi:</strong> teks (opsional)</li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end gap-3 rounded-b-2xl">
                                <button type="button" onclick="window.honorariumForm().closeImportModal()" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100">
                                    Batal
                                </button>
                                <button type="button" onclick="window.honorariumForm().processImport()" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">
                                    Import Data
                                </button>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Detail Pengajuan (Regular) -->
                    <div class="bg-white rounded-2xl shadow-soft p-6">
                    <h2 class="text-lg font-semibold text-secondary-900 mb-4 flex items-center justify-between">
                        <div class="flex items-center">
                            <span class="w-8 h-8 bg-amber-100 text-amber-600 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </span>
                            Rincian Pengajuan
                        </div>
                        <button type="button" onclick="window.pengajuanForm().addDetail()" class="px-4 py-2 bg-primary-600 text-white rounded-xl text-sm hover:bg-primary-700 transition-all">
                            + Tambah Item
                        </button>
                    </h2>

                    <div class="overflow-x-auto">
                        <table class="w-full" id="detail-table">
                            <thead class="bg-secondary-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-secondary-600 uppercase">Detail Anggaran</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-secondary-600 uppercase">Uraian</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-secondary-600 uppercase">Volume</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-secondary-600 uppercase">Satuan</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-secondary-600 uppercase">Harga Satuan</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-secondary-600 uppercase">Jumlah</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-secondary-600 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-secondary-100" id="detail-body">
                                <tr class="detail-row">
                                    <td class="px-4 py-3 w-64">
                                        <select name="details[0][detail_anggaran_id]" class="detail-anggaran-select w-full px-3 py-2 border border-secondary-200 rounded-lg focus:ring-2 focus:ring-primary-500 text-sm" disabled onchange="pengajuanForm().onDetailAnggaranChange(this)">
                                            <option value="">Pilih Sub Program Terlebih Dahulu</option>
                                        </select>
                                        <span class="detail-anggaran-sisa text-xs text-secondary-500 block mt-1"></span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="text" name="details[0][uraian]" placeholder="Nama barang/jasa" required oninput="pengajuanForm().saveFormState()"
                                            class="w-full px-3 py-2 border border-secondary-200 rounded-lg focus:ring-2 focus:ring-primary-500 text-sm">
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="number" name="details[0][volume]" value="1" min="1" required onchange="pengajuanForm().calculateTotal()"
                                            class="w-24 px-3 py-2 border border-secondary-200 rounded-lg focus:ring-2 focus:ring-primary-500 text-sm">
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="text" name="details[0][satuan]" placeholder="pcs, kg, meter" required oninput="pengajuanForm().saveFormState()"
                                            class="w-20 px-3 py-2 border border-secondary-200 rounded-lg focus:ring-2 focus:ring-primary-500 text-sm">
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="number" name="details[0][harga_satuan]" placeholder="0" required onchange="pengajuanForm().calculateTotal()"
                                            class="w-32 px-3 py-2 border border-secondary-200 rounded-lg focus:ring-2 focus:ring-primary-500 text-sm">
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="detail-total font-semibold">Rp 0</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <button type="button" onclick="pengajuanForm().removeDetail(0)" class="text-red-600 hover:text-red-800">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot class="bg-secondary-50">
                                <tr>
                                    <td colspan="5" class="px-4 py-3 text-right font-semibold text-secondary-700">Total Pengajuan:</td>
                                    <td colspan="2" class="px-4 py-3">
                                        <span id="grand-total" class="text-xl font-bold text-primary-600">Rp 0</span>
                                        <input type="hidden" name="total_pengajuan" id="total_pengajuan_regular" value="0">
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                @endif

                @if($jenisPengajuan !== 'honorarium')
                <!-- Penerima Manfaat -->
                <div class="bg-white rounded-2xl shadow-soft p-6">
                    <h2 class="text-lg font-semibold text-secondary-900 mb-4 flex items-center">
                        <span class="w-8 h-8 bg-green-100 text-green-600 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </span>
                        Penerima Manfaat
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <x-input-label for="jenis_penerima" value="Jenis Penerima *" />
                            <select name="jenis_penerima" id="jenis_penerima" required
                                class="mt-1 block w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                <option value="">Pilih Jenis</option>
                                <option value="karyawan" {{ old('jenis_penerima') == 'karyawan' ? 'selected' : '' }}>Karyawan</option>
                                <option value="vendor" {{ old('jenis_penerima') == 'vendor' ? 'selected' : '' }}>Vendor</option>
                                <option value="lainnya" {{ old('jenis_penerima') == 'lainnya' ? 'selected' : '' }}>Pihak Lain</option>
                            </select>
                            <x-input-error :messages="$errors->get('jenis_penerima')" class="mt-2" />
                        </div>

                        <!-- Dropdown untuk karyawan (hidden by default) -->
                        <div id="penerima-karyawan-container" class="hidden">
                            <x-input-label for="penerima_manfaat_id" value="Nama Penerima *" />
                            <select name="penerima_manfaat_id" id="penerima_manfaat_id"
                                class="mt-1 block w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                <option value="">Pilih Karyawan</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('penerima_manfaat_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('penerima_manfaat_id')" class="mt-2" />
                        </div>

                        <!-- Text input manual untuk vendor/pihak lain (hidden by default) -->
                        <div id="penerima-manual-container" class="hidden">
                            <x-input-label for="penerima_manfaat_name" value="Nama Penerima *" />
                            <input type="text" name="penerima_manfaat_name" id="penerima_manfaat_name" value="{{ old('penerima_manfaat_name') }}"
                                class="mt-1 block w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                placeholder="Masukkan nama penerima">
                            <x-input-error :messages="$errors->get('penerima_manfaat_name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="rekening_tujuan" value="Rekening Tujuan *" />
                            <input type="text" name="rekening_tujuan" id="rekening_tujuan" value="{{ old('rekening_tujuan') }}" required
                                class="mt-1 block w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                placeholder="Nomor rekening tujuan transfer">
                            <x-input-error :messages="$errors->get('rekening_tujuan')" class="mt-2" />
                        </div>
                    </div>
                </div>
                @endif

                <!-- Dokumen Lampiran (Global untuk seluruh pengajuan) -->
                <div class="bg-white rounded-2xl shadow-soft p-6">
                    <h2 class="text-lg font-semibold text-secondary-900 mb-4 flex items-center">
                        <span class="w-8 h-8 bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.472 6.472a4 4 0 01-5.656 0L4 12m0 0l6-6m0 0l6.472 6.472a4 4 0 015.656 0L20 13m-6-6h.01" />
                            </svg>
                        </span>
                        Dokumen Lampiran Pengajuan
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="attachments" value="Lampiran Dokumen *" />
                            <input type="file" name="attachments[]" id="attachments" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png" multiple required
                                class="mt-1 block w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent text-sm file:mr-4 file:py-2 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-primary-600 file:text-white hover:file:bg-primary-700">
                            <p class="mt-1 text-xs text-secondary-500">PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG, PNG (Maks 2MB per file, Maks 5 file)</p>
                            <x-input-error :messages="$errors->get('attachments')" class="mt-2" />
                        </div>
                    </div>

                    @if($jenisPengajuan === 'honorarium')
                        <p class="mt-3 text-sm text-blue-600 bg-blue-50 px-3 py-2 rounded-lg">
                            <strong>Catatan:</strong> Lampiran per penerima honorarium diisi di tabel Daftar Penerima pada kolom "Lampiran".
                        </p>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-secondary-200 mt-8">
                <a href="{{ route('pengajuan-dana.index') }}" class="px-6 py-3 border border-secondary-200 text-secondary-700 rounded-xl hover:bg-secondary-50 transition-all duration-200">
                    Batal
                </a>
                <button type="button" onclick="resetForm()" class="px-6 py-3 border border-red-300 text-red-600 rounded-xl hover:bg-red-50 transition-all duration-200">
                    Reset Form
                </button>
                <button type="button" id="submit-pengajuan-btn" onclick="submitForm()" class="px-6 py-3 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-all duration-200 shadow-soft hover:shadow-medium">
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan Pengajuan
                    </span>
                </button>
            </div>
        </form>
    </div>

    <!-- SheetJS for Excel export -->
    <script src="https://cdn.sheetjs.com/xlsx-0.20.0/package/dist/xlsx.full.min.js"></script>

    <script>
        let detailAnggaranCache = [];
        const FORM_STORAGE_KEY = 'pengajuan_dana_form_state';

        // Singleton instance
        let pengajuanFormInstance = null;

        window.pengajuanForm = function() {
            if (!pengajuanFormInstance) {
                pengajuanFormInstance = {
                detailCount: 1,
                lastProgramKerjaId: null,
                lastSubProgramId: null,
                isRestoring: false,

                async loadSubPrograms(restoreState = false) {
                    const divisiId = document.getElementById('divisi_id').value;
                    const programKerjaId = document.getElementById('program_kerja_id').value;
                    const subProgramSelect = document.getElementById('sub_program_id');

                    // Skip if program kerja hasn't changed and not restoring
                    if (!restoreState && !this.isRestoring && programKerjaId === this.lastProgramKerjaId) {
                        return;
                    }

                    this.lastProgramKerjaId = programKerjaId;

                    // Reset sub program options
                    subProgramSelect.innerHTML = '<option value="">Pilih Sub Program</option>';

                    // Clear detail anggaran cache
                    detailAnggaranCache = [];

                    // Reset all detail anggaran selects (for regular pengajuan)
                    document.querySelectorAll('.detail-anggaran-select').forEach(select => {
                        select.innerHTML = '<option value="">Pilih Sub Program Terlebih Dahulu</option>';
                        select.disabled = true;
                    });
                    document.querySelectorAll('.detail-anggaran-sisa').forEach(span => {
                        span.textContent = '';
                    });

                    // Reset honorarium detail anggaran
                    const honorariumDetailSelect = document.getElementById('detail_anggaran_id');
                    if (honorariumDetailSelect) {
                        honorariumDetailSelect.innerHTML = '<option value="">Pilih Sub Program Terlebih Dahulu</option>';
                        honorariumDetailSelect.disabled = true;
                    }
                    const honorariumSisa = document.getElementById('detail-anggaran-sisa');
                    if (honorariumSisa) {
                        honorariumSisa.textContent = '';
                    }

                    if (!programKerjaId || !divisiId) {
                        return;
                    }

                    try {
                        // Fetch sub programs
                        const response = await fetch(`/program-kerja/${divisiId}/${programKerjaId}/sub-programs`);
                        const data = await response.json();

                        // Populate sub programs
                        if (data.sub_programs && data.sub_programs.length > 0) {
                            data.sub_programs.forEach(sub => {
                                const option = document.createElement('option');
                                option.value = sub.id;
                                option.textContent = sub.nama_sub_program;
                                subProgramSelect.appendChild(option);
                            });

                            subProgramSelect.disabled = false;

                            // Restore selected sub program if restoring state
                            if (restoreState) {
                                const savedState = this.loadFormState();
                                if (savedState.sub_program_id) {
                                    subProgramSelect.value = savedState.sub_program_id;
                                    // Trigger load detail anggarans
                                    await this.loadDetailAnggarans(true);
                                }
                            }
                        }

                        // Save form state
                        this.saveFormState();
                    } catch (error) {
                        console.error('Failed to load sub programs:', error);
                    }
                },

                async loadDetailAnggarans(restoreState = false) {
                    const divisiId = document.getElementById('divisi_id').value;
                    const subProgramId = document.getElementById('sub_program_id').value;
                    const programKerjaId = document.getElementById('program_kerja_id').value;

                    // Skip if sub program hasn't changed and not restoring
                    if (!restoreState && !this.isRestoring && subProgramId === this.lastSubProgramId) {
                        return;
                    }

                    this.lastSubProgramId = subProgramId;

                    // Clear cache when sub program changes
                    detailAnggaranCache = [];

                    // Reset all detail anggaran selects (for regular pengajuan)
                    document.querySelectorAll('.detail-anggaran-select').forEach(select => {
                        select.innerHTML = '<option value="">Pilih Detail Anggaran</option>';
                        select.disabled = !subProgramId;
                    });
                    document.querySelectorAll('.detail-anggaran-sisa').forEach(span => {
                        span.textContent = '';
                    });

                    // Reset honorarium detail anggaran
                    const honorariumDetailSelect = document.getElementById('detail_anggaran_id');
                    if (honorariumDetailSelect) {
                        honorariumDetailSelect.innerHTML = '<option value="">Pilih Detail Anggaran</option>';
                        honorariumDetailSelect.disabled = !subProgramId;
                    }
                    const honorariumSisa = document.getElementById('detail-anggaran-sisa');
                    if (honorariumSisa) {
                        honorariumSisa.textContent = '';
                    }

                    if (!subProgramId || !programKerjaId || !divisiId) {
                        return;
                    }

                    try {
                        // Fetch detail anggarans
                        const response = await fetch(`/program-kerja/${divisiId}/${programKerjaId}/sub-programs/${subProgramId}/detail-anggarans`);
                        const data = await response.json();

                        // Store in cache
                        detailAnggaranCache = data.detail_anggarans || [];

                        // Populate all detail anggaran selects (for regular pengajuan)
                        document.querySelectorAll('.detail-anggaran-select').forEach(select => {
                            select.innerHTML = '<option value="">Pilih Detail Anggaran</option>';
                            detailAnggaranCache.forEach(detail => {
                                const option = document.createElement('option');
                                option.value = detail.id;
                                option.textContent = detail.nama_detail;
                                option.setAttribute('data-sisa', detail.sisa_nominal);
                                option.setAttribute('data-satuan', detail.satuan || '');
                                option.setAttribute('data-harga', detail.nominal_per_periode || 0);
                                select.appendChild(option);
                            });
                            select.disabled = false;
                        });

                        // Populate honorarium detail anggaran select
                        if (honorariumDetailSelect) {
                            honorariumDetailSelect.innerHTML = '<option value="">Pilih Detail Anggaran</option>';
                            detailAnggaranCache.forEach(detail => {
                                const option = document.createElement('option');
                                option.value = detail.id;
                                option.textContent = `${detail.nama_detail} (Sisa: Rp ${parseFloat(detail.sisa_nominal || 0).toLocaleString('id-ID')})`;
                                option.setAttribute('data-sisa', detail.sisa_nominal);
                                honorariumDetailSelect.appendChild(option);
                            });
                            honorariumDetailSelect.disabled = false;
                        }

                        // Restore selected detail anggarans if restoring state
                        if (restoreState) {
                            const savedState = this.loadFormState();
                            if (savedState.details) {
                                savedState.details.forEach((detail, index) => {
                                    const select = document.querySelector(`select[name="details[${index}][detail_anggaran_id]"]`);
                                    if (select && detail.detail_anggaran_id) {
                                        select.value = detail.detail_anggaran_id;
                                        this.onDetailAnggaranChange(select);
                                    }
                                });
                            }
                        }

                        // Save form state
                        this.saveFormState();
                    } catch (error) {
                        console.error('Failed to load detail anggarans:', error);
                    }
                },

                onDetailAnggaranChange(select) {
                    const sisaSpan = select.parentElement.querySelector('.detail-anggaran-sisa');
                    const row = select.closest('tr');
                    const satuanInput = row.querySelector('input[name*="[satuan]"]');
                    const hargaInput = row.querySelector('input[name*="[harga_satuan]"]');

                    const selectedOption = select.options[select.selectedIndex];

                    // Update sisa nominal display
                    if (sisaSpan) {
                        const sisa = selectedOption?.getAttribute('data-sisa');
                        if (sisa && select.value) {
                            sisaSpan.textContent = 'Sisa: Rp ' + parseFloat(sisa).toLocaleString('id-ID');
                        } else {
                            sisaSpan.textContent = '';
                        }
                    }

                    // Auto-fill satuan and harga_satuan from detail anggaran
                    if (select.value) {
                        const satuan = selectedOption?.getAttribute('data-satuan');
                        const harga = selectedOption?.getAttribute('data-harga');

                        if (satuan) {
                            satuanInput.value = satuan;
                        }

                        if (harga) {
                            hargaInput.value = harga;
                        }

                        // Recalculate total
                        this.calculateTotal();
                    }

                    // Save form state
                    this.saveFormState();
                },

                addDetail() {
                    const tbody = document.getElementById('detail-body');
                    const index = this.detailCount++;

                    const row = document.createElement('tr');
                    row.className = 'detail-row';

                    let detailAnggaranOptions = '<option value="">Pilih Detail Anggaran</option>';
                    if (detailAnggaranCache.length > 0) {
                        detailAnggaranCache.forEach(detail => {
                            detailAnggaranOptions += `<option value="${detail.id}" data-sisa="${detail.sisa_nominal}" data-satuan="${detail.satuan || ''}" data-harga="${detail.nominal_per_periode || 0}">${detail.nama_detail}</option>`;
                        });
                    } else if (!document.getElementById('sub_program_id').value) {
                        detailAnggaranOptions = '<option value="">Pilih Sub Program Terlebih Dahulu</option>';
                    }

                    const isDisabled = !document.getElementById('sub_program_id').value ? 'disabled' : '';

                    row.innerHTML = `
                        <td class="px-4 py-3 w-64">
                            <select name="details[${index}][detail_anggaran_id]" class="detail-anggaran-select w-full px-3 py-2 border border-secondary-200 rounded-lg focus:ring-2 focus:ring-primary-500 text-sm" ${isDisabled} onchange="pengajuanForm().onDetailAnggaranChange(this)">
                                ${detailAnggaranOptions}
                            </select>
                            <span class="detail-anggaran-sisa text-xs text-secondary-500 block mt-1"></span>
                        </td>
                        <td class="px-4 py-3">
                            <input type="text" name="details[${index}][uraian]" placeholder="Nama barang/jasa" required oninput="pengajuanForm().saveFormState()"
                                class="w-full px-3 py-2 border border-secondary-200 rounded-lg focus:ring-2 focus:ring-primary-500 text-sm">
                        </td>
                        <td class="px-4 py-3">
                            <input type="number" name="details[${index}][volume]" value="1" min="1" required onchange="pengajuanForm().calculateTotal()"
                                class="w-24 px-3 py-2 border border-secondary-200 rounded-lg focus:ring-2 focus:ring-primary-500 text-sm">
                        </td>
                        <td class="px-4 py-3">
                            <input type="text" name="details[${index}][satuan]" placeholder="pcs, kg, meter" required oninput="pengajuanForm().saveFormState()"
                                class="w-20 px-3 py-2 border border-secondary-200 rounded-lg focus:ring-2 focus:ring-primary-500 text-sm">
                        </td>
                        <td class="px-4 py-3">
                            <input type="number" name="details[${index}][harga_satuan]" placeholder="0" required onchange="pengajuanForm().calculateTotal()"
                                class="w-32 px-3 py-2 border border-secondary-200 rounded-lg focus:ring-2 focus:ring-primary-500 text-sm">
                        </td>
                        <td class="px-4 py-3">
                            <span class="detail-total font-semibold">Rp 0</span>
                        </td>
                        <td class="px-4 py-3">
                            <button type="button" onclick="pengajuanForm().removeDetail(${index})" class="text-red-600 hover:text-red-800">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </td>
                    `;
                    tbody.appendChild(row);
                },

                removeDetail(index) {
                    const rows = document.querySelectorAll('.detail-row');
                    if (rows.length > 1) {
                        rows[index]?.remove();
                        this.calculateTotal();
                        this.saveFormState();
                    }
                },

                calculateTotal() {
                    let grandTotal = 0;

                    document.querySelectorAll('.detail-row').forEach(row => {
                        const volume = row.querySelector('input[name*="[volume]"]')?.value || 0;
                        const harga = row.querySelector('input[name*="[harga_satuan]"]')?.value || 0;
                        const total = volume * harga;

                        const totalSpan = row.querySelector('.detail-total');
                        if (totalSpan) {
                            totalSpan.textContent = 'Rp ' + total.toLocaleString('id-ID');
                        }

                        grandTotal += total;
                    });

                    document.getElementById('grand-total').textContent = 'Rp ' + grandTotal.toLocaleString('id-ID');
                    // Set total_pengajuan_regular for form submission (regular pengajuan)
                    document.getElementById('total_pengajuan_regular').value = grandTotal;
                },

                saveFormState() {
                    const formState = {
                        divisi_id: document.getElementById('divisi_id')?.value,
                        program_kerja_id: document.getElementById('program_kerja_id')?.value,
                        sub_program_id: document.getElementById('sub_program_id')?.value,
                        jenis_penerima: document.getElementById('jenis_penerima')?.value,
                        penerima_manfaat_id: document.getElementById('penerima_manfaat_id')?.value,
                        penerima_manfaat_name: document.getElementById('penerima_manfaat_name')?.value,
                        details: []
                    };

                    // Save details
                    document.querySelectorAll('.detail-row').forEach(row => {
                        const detailAnggaranSelect = row.querySelector('select[name*="[detail_anggaran_id]"]');
                        const uraianInput = row.querySelector('input[name*="[uraian]"]');
                        const volumeInput = row.querySelector('input[name*="[volume]"]');
                        const satuanInput = row.querySelector('input[name*="[satuan]"]');
                        const hargaInput = row.querySelector('input[name*="[harga_satuan]"]');

                        if (detailAnggaranSelect) {
                            const detailIndex = detailAnggaranSelect.name.match(/\d+/)?.[0];
                            formState.details.push({
                                index: detailIndex,
                                detail_anggaran_id: detailAnggaranSelect.value,
                                uraian: uraianInput?.value,
                                volume: volumeInput?.value,
                                satuan: satuanInput?.value,
                                harga_satuan: hargaInput?.value
                            });
                        }
                    });

                    localStorage.setItem(FORM_STORAGE_KEY, JSON.stringify(formState));
                },

                loadFormState() {
                    const savedState = localStorage.getItem(FORM_STORAGE_KEY);
                    if (savedState) {
                        return JSON.parse(savedState);
                    }
                    return null;
                },

                async restoreFormState() {
                    const savedState = this.loadFormState();
                    if (!savedState) return;

                    this.isRestoring = true;

                    // Restore program kerja (will also set divisi_id automatically)
                    if (savedState.program_kerja_id) {
                        const programKerjaSelect = document.getElementById('program_kerja_id');
                        programKerjaSelect.value = savedState.program_kerja_id;
                        // Set divisi_id from selected option
                        const selectedOption = programKerjaSelect.options[programKerjaSelect.selectedIndex];
                        if (selectedOption) {
                            const divisiId = selectedOption.getAttribute('data-divisi-id');
                            document.getElementById('divisi_id').value = divisiId;
                        }
                        await this.loadSubPrograms(true);
                    }

                    // Restore jenis_penerima
                    if (savedState.jenis_penerima) {
                        const jenisPenerimaSelect = document.getElementById('jenis_penerima');
                        jenisPenerimaSelect.value = savedState.jenis_penerima;
                        // Restore penerima_manfaat_id or penerima_manfaat_name FIRST
                        if (savedState.penerima_manfaat_id) {
                            const penerimaIdSelect = document.getElementById('penerima_manfaat_id');
                            if (penerimaIdSelect) penerimaIdSelect.value = savedState.penerima_manfaat_id;
                        }
                        if (savedState.penerima_manfaat_name) {
                            const penerimaNameInput = document.getElementById('penerima_manfaat_name');
                            if (penerimaNameInput) penerimaNameInput.value = savedState.penerima_manfaat_name;
                        }
                        // Trigger the change to show correct container (skip reset)
                        this.onJenisPenerimaChange(true);
                    }

                    // Restore details count for adding new rows
                    if (savedState.details && savedState.details.length > 0) {
                        this.detailCount = savedState.details.length;
                    }

                    this.isRestoring = false;
                },

                clearFormState() {
                    localStorage.removeItem(FORM_STORAGE_KEY);
                    this.lastProgramKerjaId = null;
                    this.lastSubProgramId = null;
                },

                onProgramKerjaChange() {
                    const programKerjaSelect = document.getElementById('program_kerja_id');
                    const selectedOption = programKerjaSelect.options[programKerjaSelect.selectedIndex];

                    // Extract and set divisi_id from selected program kerja option
                    if (selectedOption && selectedOption.value) {
                        const divisiId = selectedOption.getAttribute('data-divisi-id');
                        document.getElementById('divisi_id').value = divisiId;
                    } else {
                        document.getElementById('divisi_id').value = '';
                    }

                    this.loadSubPrograms();
                    this.saveFormState();
                },

                onSubProgramChange() {
                    this.loadDetailAnggarans();
                    this.saveFormState();
                },

                onJenisPenerimaChange(skipReset = false) {
                    const jenisPenerima = document.getElementById('jenis_penerima').value;
                    const karyawanContainer = document.getElementById('penerima-karyawan-container');
                    const manualContainer = document.getElementById('penerima-manual-container');
                    const karyawanSelect = document.getElementById('penerima_manfaat_id');
                    const manualInput = document.getElementById('penerima_manfaat_name');

                    // Reset both fields (skip if restoring)
                    if (!skipReset) {
                        if (karyawanSelect) karyawanSelect.value = '';
                        if (manualInput) manualInput.value = '';
                    }

                    // Hide both containers first
                    karyawanContainer.classList.add('hidden');
                    manualContainer.classList.add('hidden');

                    // Show appropriate container based on selection
                    if (jenisPenerima === 'karyawan') {
                        karyawanContainer.classList.remove('hidden');
                        karyawanSelect.required = true;
                        manualInput.required = false;
                    } else if (jenisPenerima === 'vendor' || jenisPenerima === 'lainnya') {
                        manualContainer.classList.remove('hidden');
                        karyawanSelect.required = false;
                        manualInput.required = true;
                    }

                    this.saveFormState();
                }
            };
            }
            return pengajuanFormInstance;
        };

        // Global reset function
        async function resetForm() {
            if (confirm('Apakah Anda yakin ingin mereset form? Semua data yang belum disimpan akan hilang.')) {
                pengajuanForm().clearFormState();
                location.reload();
            }
        }

        // Event listener for sub_program_id change
        document.addEventListener('DOMContentLoaded', async function() {
            const form = document.getElementById('pengajuan-dana-form') || document.querySelector('form[action*="pengajuan-dana.store"]');

            // Clear storage on successful submit
            if (form) {
                form.addEventListener('submit', function() {
                    pengajuanForm().clearFormState();
                });
            }

            // Add change listeners for dropdowns
            const programKerjaSelect = document.getElementById('program_kerja_id');
            const subProgramSelect = document.getElementById('sub_program_id');
            const jenisPenerimaSelect = document.getElementById('jenis_penerima');

            if (programKerjaSelect) {
                programKerjaSelect.addEventListener('change', () => pengajuanForm().onProgramKerjaChange());
            }

            if (subProgramSelect) {
                subProgramSelect.addEventListener('change', () => pengajuanForm().onSubProgramChange());
            }

            if (jenisPenerimaSelect) {
                jenisPenerimaSelect.addEventListener('change', () => pengajuanForm().onJenisPenerimaChange());
            }

            // Honorarium detail anggaran change listener
            const honorariumDetailSelect = document.getElementById('detail_anggaran_id');
            if (honorariumDetailSelect) {
                honorariumDetailSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const sisa = selectedOption?.getAttribute('data-sisa');
                    const sisaDiv = document.getElementById('detail-anggaran-sisa');
                    if (sisaDiv) {
                        if (sisa && this.value) {
                            sisaDiv.textContent = 'Sisa: Rp ' + parseFloat(sisa).toLocaleString('id-ID');
                        } else {
                            sisaDiv.textContent = '';
                        }
                    }
                });
            }

            // Restore form state on page load
            await pengajuanForm().restoreFormState();

            // Add change listeners to all basic inputs
            const inputsToSave = [
                '#judul_pengajuan',
                '#jenis_pengajuan',
                '#deskripsi'
            ];

            inputsToSave.forEach(selector => {
                const input = document.querySelector(selector);
                if (input) {
                    input.addEventListener('input', () => pengajuanForm().saveFormState());
                    input.addEventListener('change', () => pengajuanForm().saveFormState());
                }
            });
        });

        // Honorarium Form Handler - Singleton Pattern
        let honorariumFormInstance = null;

        window.honorariumForm = function() {
            if (!honorariumFormInstance) {
                honorariumFormInstance = {
                    rowCount: 0,
                    users: @js($users ?? []),

                    addRecipientRow() {
                        this.rowCount++;
                        const index = this.rowCount;

                        // Update row count input
                        document.getElementById('honorarium_row_count').value = this.rowCount;

                        // Hide empty row
                        const emptyRow = document.getElementById('empty-row');
                        if (emptyRow) emptyRow.style.display = 'none';

                        const tbody = document.getElementById('honorarium-list-body');

                        // Build options for karyawan select
                        let userOptions = '<option value="">Pilih Karyawan</option>';
                        this.users.forEach(user => {
                            userOptions += `<option value="${user.id}">${user.name}</option>`;
                        });

                        const row = document.createElement('tr');
                        row.id = `honorarium-row-${index}`;
                        row.innerHTML = `
                            <td class="px-3 py-2 text-sm text-secondary-900">${this.rowCount}</td>
                            <td class="px-3 py-2">
                                <select name="honorarium_details[${index}][penerima_manfaat_type]" class="w-full text-sm border border-secondary-200 rounded-lg px-2 py-1.5 focus:ring-2 focus:ring-primary-500" onchange="window.honorariumForm().togglePenerimaType(${index})">
                                    <option value="karyawan">Karyawan</option>
                                    <option value="non_karyawan">Non-Karyawan</option>
                                </select>
                            </td>
                            <td class="px-3 py-2">
                                <div id="karyawan-select-${index}">
                                    <select name="honorarium_details[${index}][penerima_manfaat_id]" class="w-full text-sm border border-secondary-200 rounded-lg px-2 py-1.5 focus:ring-2 focus:ring-primary-500">
                                        ${userOptions}
                                    </select>
                                </div>
                                <div id="manual-input-${index}" class="hidden">
                                    <input type="text" name="honorarium_details[${index}][penerima_manfaat_name]" class="w-full text-sm border border-secondary-200 rounded-lg px-2 py-1.5 focus:ring-2 focus:ring-primary-500" placeholder="Nama penerima">
                                </div>
                            </td>
                            <td class="px-3 py-2">
                                <input type="number" name="honorarium_details[${index}][jumlah_honor]" class="w-full text-sm border border-secondary-200 rounded-lg px-2 py-1.5 focus:ring-2 focus:ring-primary-500" placeholder="0" min="0" required onchange="window.honorariumForm().calculateTotal()">
                            </td>
                            <td class="px-3 py-2">
                                <input type="text" name="honorarium_details[${index}][nomor_rekening]" class="w-full text-sm border border-secondary-200 rounded-lg px-2 py-1.5 focus:ring-2 focus:ring-primary-500" placeholder="Nomor rekening" required>
                            </td>
                            <td class="px-3 py-2">
                                <textarea name="honorarium_details[${index}][deskripsi]" rows="1" class="w-full text-sm border border-secondary-200 rounded-lg px-2 py-1.5 focus:ring-2 focus:ring-primary-500" placeholder="Deskripsi"></textarea>
                            </td>
                            <td class="px-3 py-2">
                                <input type="file" name="honorarium_details[${index}][lampiran]" class="w-full text-xs border border-secondary-200 rounded-lg px-2 py-1 focus:ring-2 focus:ring-primary-500" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                            </td>
                            <td class="px-3 py-2 text-center">
                                <button type="button" onclick="window.honorariumForm().removeRecipientRow(${index})" class="text-red-600 hover:text-red-800">
                                    <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </td>
                        `;

                        tbody.appendChild(row);
                    },

                    removeRecipientRow(index) {
                        const row = document.getElementById(`honorarium-row-${index}`);
                        if (row) {
                            row.remove();

                            // Check if empty row should be shown
                            const tbody = document.getElementById('honorarium-list-body');
                            const remainingRows = tbody.querySelectorAll('tr[id^="honorarium-row-"]');

                            if (remainingRows.length === 0) {
                                const emptyRow = document.getElementById('empty-row');
                                if (emptyRow) emptyRow.style.display = '';
                            }

                            this.calculateTotal();
                        }
                    },

                    togglePenerimaType(index) {
                        const select = document.querySelector(`select[name="honorarium_details[${index}][penerima_manfaat_type]"]`);
                        const type = select?.value;
                        const karyawanDiv = document.getElementById(`karyawan-select-${index}`);
                        const manualDiv = document.getElementById(`manual-input-${index}`);
                        const karyawanSelect = document.querySelector(`select[name="honorarium_details[${index}][penerima_manfaat_id]"]`);
                        const manualInput = document.querySelector(`input[name="honorarium_details[${index}][penerima_manfaat_name]"]`);

                        if (type === 'karyawan') {
                            karyawanDiv?.classList.remove('hidden');
                            manualDiv?.classList.add('hidden');
                            if (karyawanSelect) karyawanSelect.required = true;
                            if (manualInput) manualInput.required = false;
                        } else {
                            karyawanDiv?.classList.add('hidden');
                            manualDiv?.classList.remove('hidden');
                            if (karyawanSelect) karyawanSelect.required = false;
                            if (manualInput) manualInput.required = true;
                        }
                    },

                    calculateTotal() {
                        let total = 0;
                        const rows = document.querySelectorAll('tr[id^="honorarium-row-"]');

                        rows.forEach(row => {
                            const input = row.querySelector('input[name*="[jumlah_honor]"]');
                            if (input) {
                                total += parseFloat(input.value) || 0;
                            }
                        });

                        document.getElementById('total-honorarium').textContent = 'Rp ' + total.toLocaleString('id-ID');
                        document.getElementById('total_pengajuan').value = total;
                    },

                    prepareSubmit() {
                        const jenisPengajuan = document.getElementById('jenis_pengajuan')?.value;

                        if (jenisPengajuan === 'honorarium') {
                            const rows = document.querySelectorAll('tr[id^="honorarium-row-"]');

                            if (rows.length === 0) {
                                alert('Mohon tambahkan minimal 1 penerima honorarium');
                                return false;
                            }

                            // Validate each row
                            for (const row of rows) {
                                const type = row.querySelector('select[name*="[penerima_manfaat_type]"]')?.value;
                                const jumlahHonor = row.querySelector('input[name*="[jumlah_honor]"]')?.value;
                                const nomorRekening = row.querySelector('input[name*="[nomor_rekening]"]')?.value;

                                if (!jumlahHonor || parseFloat(jumlahHonor) <= 0) {
                                    alert('Semua penerima harus memiliki jumlah honor');
                                    return false;
                                }

                                if (!nomorRekening) {
                                    alert('Semua penerima harus memiliki nomor rekening');
                                    return false;
                                }

                                if (type === 'karyawan') {
                                    const karyawanId = row.querySelector('select[name*="[penerima_manfaat_id]"]')?.value;
                                    if (!karyawanId) {
                                        alert('Pilih karyawan untuk tipe Karyawan');
                                        return false;
                                    }
                                } else {
                                    const nama = row.querySelector('input[name*="[penerima_manfaat_name]"]')?.value;
                                    if (!nama) {
                                        alert('Isi nama penerima untuk tipe Non-Karyawan');
                                        return false;
                                    }
                                }
                            }

                            this.calculateTotal();
                            const total = parseFloat(document.getElementById('total_pengajuan').value) || 0;

                            if (total < 1000) {
                                alert('Total pengajuan minimal Rp 1.000');
                                return false;
                            }
                        }

                        return true;
                    },

                    // Import functionality
                    openImportModal() {
                        document.getElementById('import-modal').classList.remove('hidden');
                    },

                    closeImportModal() {
                        document.getElementById('import-modal').classList.add('hidden');
                        document.getElementById('import-file').value = '';
                        document.getElementById('import-file-info').classList.add('hidden');
                    },

                    handleImportFileSelect(input) {
                        if (input.files && input.files[0]) {
                            const file = input.files[0];
                            document.getElementById('import-filename').textContent = file.name;
                            document.getElementById('import-filesize').textContent = this.formatFileSize(file.size);
                            document.getElementById('import-file-info').classList.remove('hidden');
                        }
                    },

                    downloadTemplate() {
                        // Use SheetJS to create proper Excel file
                        const XLSX = window.XLSX;

                        // Add instruction row at the top
                        const instructions = [
                            ['CATATAN:'],
                            ['1. Kolom "Nama Karyawan" dapat diisi dengan NAMA KARYAWAN atau EMAIL'],
                            ['2. Untuk karyawan dengan nama yang sama, gunakan EMAIL sebagai pembeda'],
                            ['3. Download "Daftar Karyawan" untuk melihat daftar nama dan email yang valid'],
                            [],
                            ['Tipe', 'Nama Karyawan', 'Nama Penerima', 'Jumlah Honor', 'Nomor Rekening', 'Deskripsi'],
                            ['karyawan', 'John Doe', '', '500000', '1234567890', 'Honorarium bulan Januari'],
                            ['karyawan', 'john.doe@company.com', '', '600000', '1234567890', 'Contoh menggunakan email'],
                            ['non_karyawan', '', 'Dr. Ahmad', '750000', '0987654321', 'Narasumber seminar'],
                        ];

                        // Create worksheet
                        const ws = XLSX.utils.aoa_to_sheet(instructions);

                        // Set column widths
                        ws['!cols'] = [
                            { wch: 15 }, // Tipe
                            { wch: 35 }, // Nama Karyawan (lebar untuk email)
                            { wch: 25 }, // Nama Penerima
                            { wch: 15 }, // Jumlah Honor
                            { wch: 20 }, // Nomor Rekening
                            { wch: 40 }, // Deskripsi
                        ];

                        // Create workbook
                        const wb = XLSX.utils.book_new();
                        XLSX.utils.book_append_sheet(wb, ws, 'Template');

                        // Download file
                        XLSX.writeFile(wb, 'template_honorarium.xlsx');
                    },

                    downloadEmployeeList() {
                        // Use SheetJS to create employee list Excel file
                        const XLSX = window.XLSX;
                        const headers = ['Nama Karyawan', 'Email'];

                        // Build employee data from users array
                        const employeeData = this.users.map(user => [
                            user.name,
                            user.email || ''
                        ]);

                        // Sort by name alphabetically
                        employeeData.sort((a, b) => a[0].localeCompare(b[0]));

                        // Check for duplicate names
                        const nameCount = {};
                        employeeData.forEach(row => {
                            const name = row[0].toLowerCase();
                            nameCount[name] = (nameCount[name] || 0) + 1;
                        });

                        const duplicates = Object.entries(nameCount)
                            .filter(([_, count]) => count > 1)
                            .map(([name, _]) => name);

                        // Create worksheet
                        const ws = XLSX.utils.aoa_to_sheet([headers, ...employeeData]);

                        // Set column widths
                        ws['!cols'] = [
                            { wch: 40 }, // Nama Karyawan
                            { wch: 35 }, // Email
                        ];

                        // Create workbook
                        const wb = XLSX.utils.book_new();
                        XLSX.utils.book_append_sheet(wb, ws, 'Daftar Karyawan');

                        // Add warning sheet if duplicates found
                        if (duplicates.length > 0) {
                            const warningHeaders = ['Nama Karyawan Duplikat', 'Jumlah'];
                            const warningData = duplicates.map(name => [
                                name,
                                nameCount[name]
                            ]);

                            const warningWs = XLSX.utils.aoa_to_sheet([warningHeaders, ...warningData]);
                            warningWs['!cols'] = [{ wch: 40 }, { wch: 15 }];
                            XLSX.utils.book_append_sheet(wb, warningWs, 'Duplikat');
                        }

                        // Download file
                        XLSX.writeFile(wb, 'daftar_karyawan.xlsx');

                        // Show alert if duplicates found
                        if (duplicates.length > 0) {
                            setTimeout(() => {
                                alert(`PERHATIAN: Ditemukan ${duplicates.length} nama karyawan yang duplikat.\n\nCek sheet "Duplikat" di file Excel.\n\nDisarankan untuk menggunakan Email sebagai alternatif pengenal.`);
                            }, 500);
                        }
                    },

                    formatFileSize(bytes) {
                        if (bytes === 0) return '0 Bytes';
                        const k = 1024;
                        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                        const i = Math.floor(Math.log(bytes) / Math.log(k));
                        return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
                    },

                    async processImport() {
                        const fileInput = document.getElementById('import-file');
                        const file = fileInput.files[0];

                        if (!file) {
                            alert('Silakan pilih file Excel/CSV terlebih dahulu');
                            return;
                        }

                        // Check file size (max 5MB)
                        if (file.size > 5 * 1024 * 1024) {
                            alert('Ukuran file maksimal 5MB');
                            return;
                        }

                        const formData = new FormData();
                        formData.append('file', file);
                        formData.append('users', JSON.stringify(this.users));

                        try {
                            const response = await fetch('/honorarium-import-preview', {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                                }
                            });

                            // Check response status
                            if (!response.ok) {
                                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                            }

                            const data = await response.json();

                            if (data.success) {
                                // Add rows from imported data
                                data.data.forEach(row => {
                                    this.rowCount++;
                                    const index = this.rowCount;

                                    document.getElementById('honorarium_row_count').value = this.rowCount;

                                    const emptyRow = document.getElementById('empty-row');
                                    if (emptyRow) emptyRow.style.display = 'none';

                                    const tbody = document.getElementById('honorarium-list-body');

                                    let userOptions = '<option value="">Pilih Karyawan</option>';
                                    this.users.forEach(user => {
                                        userOptions += `<option value="${user.id}" ${user.id == row.penerima_manfaat_id ? 'selected' : ''}>${user.name}</option>`;
                                    });

                                    const newRow = document.createElement('tr');
                                    newRow.id = `honorarium-row-${index}`;
                                    newRow.innerHTML = `
                                        <td class="px-3 py-2 text-sm text-secondary-900">${this.rowCount}</td>
                                        <td class="px-3 py-2">
                                            <select name="honorarium_details[${index}][penerima_manfaat_type]" class="w-full text-sm border border-secondary-200 rounded-lg px-2 py-1.5 focus:ring-2 focus:ring-primary-500" onchange="window.honorariumForm().togglePenerimaType(${index})">
                                                <option value="karyawan" ${row.penerima_manfaat_type === 'karyawan' ? 'selected' : ''}>Karyawan</option>
                                                <option value="non_karyawan" ${row.penerima_manfaat_type === 'non_karyawan' ? 'selected' : ''}>Non-Karyawan</option>
                                            </select>
                                        </td>
                                        <td class="px-3 py-2">
                                            <div id="karyawan-select-${index}" ${row.penerima_manfaat_type === 'non_karyawan' ? 'class="hidden"' : ''}>
                                                <select name="honorarium_details[${index}][penerima_manfaat_id]" class="w-full text-sm border border-secondary-200 rounded-lg px-2 py-1.5 focus:ring-2 focus:ring-primary-500">
                                                    ${userOptions}
                                                </select>
                                            </div>
                                            <div id="manual-input-${index}" ${row.penerima_manfaat_type === 'karyawan' ? 'class="hidden"' : ''}>
                                                <input type="text" name="honorarium_details[${index}][penerima_manfaat_name]" class="w-full text-sm border border-secondary-200 rounded-lg px-2 py-1.5 focus:ring-2 focus:ring-primary-500" value="${row.penerima_manfaat_name || ''}" placeholder="Nama penerima">
                                            </div>
                                        </td>
                                        <td class="px-3 py-2">
                                            <input type="number" name="honorarium_details[${index}][jumlah_honor]" class="w-full text-sm border border-secondary-200 rounded-lg px-2 py-1.5 focus:ring-2 focus:ring-primary-500" placeholder="0" min="0" required onchange="window.honorariumForm().calculateTotal()" value="${row.jumlah_honor || ''}">
                                        </td>
                                        <td class="px-3 py-2">
                                            <input type="text" name="honorarium_details[${index}][nomor_rekening]" class="w-full text-sm border border-secondary-200 rounded-lg px-2 py-1.5 focus:ring-2 focus:ring-primary-500" placeholder="Nomor rekening" required value="${row.nomor_rekening || ''}">
                                        </td>
                                        <td class="px-3 py-2">
                                            <textarea name="honorarium_details[${index}][deskripsi]" rows="1" class="w-full text-sm border border-secondary-200 rounded-lg px-2 py-1.5 focus:ring-2 focus:ring-primary-500" placeholder="Deskripsi">${row.deskripsi || ''}</textarea>
                                        </td>
                                        <td class="px-3 py-2">
                                            <input type="file" name="honorarium_details[${index}][lampiran]" class="w-full text-xs border border-secondary-200 rounded-lg px-2 py-1 focus:ring-2 focus:ring-primary-500" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                        </td>
                                        <td class="px-3 py-2 text-center">
                                            <button type="button" onclick="window.honorariumForm().removeRecipientRow(${index})" class="text-red-600 hover:text-red-800">
                                                <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </td>
                                    `;

                                    tbody.appendChild(newRow);
                                });

                                this.calculateTotal();
                                this.closeImportModal();

                                alert(`Berhasil import ${data.data.length} penerima`);
                            } else {
                                alert('Gagal import: ' + (data.message || 'Unknown error'));
                            }
                        } catch (error) {
                            console.error('Import error:', error);
                            alert('Gagal import file: ' + error.message + '. Silakan coba lagi.');
                        }
                    }
                };
            }
            return honorariumFormInstance;
        };

        // Helper function for formatting
        function formatRupiah(amount) {
            return 'Rp ' + (parseFloat(amount) || 0).toLocaleString('id-ID');
        }

        // Global submit function
        function submitForm() {
            const jenisPengajuan = document.getElementById('jenis_pengajuan')?.value;
            const form = document.getElementById('pengajuan-dana-form');

            if (jenisPengajuan === 'honorarium') {
                // Use honorarium form validation
                if (window.honorariumForm().prepareSubmit()) {
                    form.submit();
                }
            } else {
                // For regular pengajuan, just submit the form
                // HTML5 validation will handle required fields
                if (form.checkValidity()) {
                    form.submit();
                } else {
                    // Trigger HTML5 validation UI
                    form.reportValidity();
                }
            }
        }
    </script>
</x-app-layout>
