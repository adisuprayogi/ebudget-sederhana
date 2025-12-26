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

                <!-- Detail Pengajuan -->
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
                                        <input type="hidden" name="total_pengajuan" id="total_pengajuan" value="0">
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

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

                <!-- Lampiran -->
                <div class="bg-white rounded-2xl shadow-soft p-6">
                    <h2 class="text-lg font-semibold text-secondary-900 mb-4 flex items-center">
                        <span class="w-8 h-8 bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.472 6.472a4 4 0 01-5.656 0L4 12m0 0l6-6m0 0l6.472 6.472a4 4 0 015.656 0L20 13m-6-6h.01" />
                            </svg>
                        </span>
                        Dokumen Lampiran
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="attachments" value="Lampiran Dokumen (Opsional)" />
                            <input type="file" name="attachments[]" id="attachments" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png" multiple
                                class="mt-1 block w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent text-sm file:mr-4 file:py-2 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-primary-600 file:text-white hover:file:bg-primary-700">
                            <p class="mt-1 text-xs text-secondary-500">PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG, PNG (Maks 2MB per file, Maks 5 file)</p>
                            <x-input-error :messages="$errors->get('attachments')" class="mt-2" />
                        </div>
                    </div>
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
                <button type="submit" class="px-6 py-3 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-all duration-200 shadow-soft hover:shadow-medium">
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

                    // Reset all detail anggaran selects
                    document.querySelectorAll('.detail-anggaran-select').forEach(select => {
                        select.innerHTML = '<option value="">Pilih Sub Program Terlebih Dahulu</option>';
                        select.disabled = true;
                    });
                    document.querySelectorAll('.detail-anggaran-sisa').forEach(span => {
                        span.textContent = '';
                    });

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

                    // Reset all detail anggaran selects
                    document.querySelectorAll('.detail-anggaran-select').forEach(select => {
                        select.innerHTML = '<option value="">Pilih Detail Anggaran</option>';
                        select.disabled = !subProgramId;
                    });
                    document.querySelectorAll('.detail-anggaran-sisa').forEach(span => {
                        span.textContent = '';
                    });

                    if (!subProgramId || !programKerjaId || !divisiId) {
                        return;
                    }

                    try {
                        // Fetch detail anggarans
                        const response = await fetch(`/program-kerja/${divisiId}/${programKerjaId}/sub-programs/${subProgramId}/detail-anggarans`);
                        const data = await response.json();

                        // Store in cache
                        detailAnggaranCache = data.detail_anggarans || [];

                        // Populate all detail anggaran selects
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
                    document.getElementById('total_pengajuan').value = grandTotal;
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
    </script>
</x-app-layout>
