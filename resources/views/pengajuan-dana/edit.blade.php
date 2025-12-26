<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('pengajuan-dana.show', $pengajuan) }}" class="p-2 text-secondary-600 hover:text-secondary-900 hover:bg-secondary-100 rounded-lg transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-secondary-900">Edit Pengajuan Dana</h1>
                <p class="text-secondary-600 mt-1">{{ $pengajuan->nomor_pengajuan }}</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto py-8">
        <!-- Alert: Only draft/rejected can be edited -->
        <div class="mb-6 bg-amber-50 border border-amber-200 rounded-xl p-4 flex items-start">
            <svg class="w-5 h-5 text-amber-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div class="text-amber-700 text-sm">
                Hanya pengajuan dengan status <strong>draft</strong> atau <strong>ditolak</strong> yang dapat diedit.
            </div>
        </div>

        <form method="POST" action="{{ route('pengajuan-dana.update', $pengajuan) }}" enctype="multipart/form-data" class="bg-white rounded-2xl shadow-soft p-8">
            @csrf
            @method('PUT')

            @error('judul_pengajuan')
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
                    <div class="md:col-span-2">
                        <x-input-label for="judul_pengajuan" value="Judul Pengajuan *" />
                        <input type="text" name="judul_pengajuan" id="judul_pengajuan" value="{{ old('judul_pengajuan', $pengajuan->judul_pengajuan) }}" required
                            class="mt-1 block w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                            placeholder="Contoh: Pengadaan ATK Bulan Januari">
                        <x-input-error :messages="$errors->get('judul_pengajuan')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="periode_anggaran_id" value="Periode Anggaran *" />
                        <select name="periode_anggaran_id" id="periode_anggaran_id" required
                            class="mt-1 block w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            <option value="">Pilih Periode</option>
                            @foreach($periodeAnggarans ?? [] as $periode)
                                <option value="{{ $periode->id }}" {{ old('periode_anggaran_id', $pengajuan->periode_anggaran_id) == $periode->id ? 'selected' : '' }}>
                                    {{ $periode->nama_periode }} ({{ $periode->tahun_anggaran }})
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('periode_anggaran_id')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="tanggal_dibutuhkan" value="Tanggal Dana Dibutuhkan *" />
                        <input type="date" name="tanggal_dibutuhkan" id="tanggal_dibutuhkan" value="{{ old('tanggal_dibutuhkan', $pengajuan->tanggal_dibutuhkan?->format('Y-m-d')) }}" required
                            class="mt-1 block w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <x-input-error :messages="$errors->get('tanggal_dibutuhkan')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="program_kerja_id" value="Program Kerja" />
                        <select name="program_kerja_id" id="program_kerja_id"
                            class="mt-1 block w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            <option value="">Pilih Program Kerja (Opsional)</option>
                            @foreach($programKerjas ?? [] as $program)
                                <option value="{{ $program->id }}" {{ old('program_kerja_id', $pengajuan->program_kerja_id) == $program->id ? 'selected' : '' }}>
                                    {{ $program->nama_program }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('program_kerja_id')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="kegiatan_id" value="Kegiatan" />
                        <select name="kegiatan_id" id="kegiatan_id"
                            class="mt-1 block w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            <option value="">Pilih Kegiatan (Opsional)</option>
                            @foreach($kegiatans ?? [] as $kegiatan)
                                <option value="{{ $kegiatan->id }}" {{ old('kegiatan_id', $pengajuan->kegiatan_id) == $kegiatan->id ? 'selected' : '' }}>
                                    {{ $kegiatan->nama_kegiatan }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('kegiatan_id')" class="mt-2" />
                    </div>

                    <div class="md:col-span-2">
                        <x-input-label for="metode_pencairan" value="Metode Pencairan *" />
                        <select name="metode_pencairan" id="metode_pencairan" required
                            class="mt-1 block w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            <option value="">Pilih Metode Pencairan</option>
                            <option value="transfer" {{ old('metode_pencairan', $pengajuan->metode_pencairan) === 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                            <option value="cash" {{ old('metode_pencairan', $pengajuan->metode_pencairan) === 'cash' ? 'selected' : '' }}>Uang Tunai</option>
                            <option value="reimburse" {{ old('metode_pencairan', $pengajuan->metode_pencairan) === 'reimburse' ? 'selected' : '' }}>Reimburse</option>
                        </select>
                        <x-input-error :messages="$errors->get('metode_pencairan')" class="mt-2" />
                    </div>

                    <div class="md:col-span-2">
                        <x-input-label for="deskripsi" value="Deskripsi Pengajuan" />
                        <textarea name="deskripsi" id="deskripsi" rows="3"
                            class="mt-1 block w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                            placeholder="Jelaskan detail pengajuan dana...">{{ old('deskripsi', $pengajuan->deskripsi) }}</textarea>
                        <x-input-error :messages="$errors->get('deskripsi')" class="mt-2" />
                    </div>
                </div>
            </div>

            <!-- Detail Pengajuan -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-secondary-900 mb-4 flex items-center">
                    <span class="w-8 h-8 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </span>
                    Rincian Pengajuan
                </h2>

                <div id="detail-container" class="space-y-3">
                    @php
                        $details = old('details', $pengajuan->detailPengajuan->toArray() ?? []);
                        $index = 0;
                    @endphp
                    @foreach($details as $detail)
                    <div class="detail-row grid grid-cols-12 gap-3 items-start p-4 bg-secondary-50 rounded-xl">
                        <div class="col-span-12 md:col-span-4">
                            <label class="block text-xs font-medium text-secondary-700 mb-1">Uraian</label>
                            <input type="text" name="details[{{ $index }}][uraian]" value="{{ $detail['uraian'] ?? '' }}" required
                                class="w-full px-3 py-2 border border-secondary-200 rounded-lg text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                placeholder="Nama barang/jasa">
                        </div>
                        <div class="col-span-3 md:col-span-2">
                            <label class="block text-xs font-medium text-secondary-700 mb-1">Volume</label>
                            <input type="number" name="details[{{ $index }}][volume]" value="{{ $detail['volume'] ?? '' }}" min="1" required
                                class="w-full px-3 py-2 border border-secondary-200 rounded-lg text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent detail-volume"
                                data-index="{{ $index }}" onchange="calculateRowTotal({{ $index }})">
                        </div>
                        <div class="col-span-4 md:col-span-2">
                            <label class="block text-xs font-medium text-secondary-700 mb-1">Satuan</label>
                            <input type="text" name="details[{{ $index }}][satuan]" value="{{ $detail['satuan'] ?? '' }}"
                                class="w-full px-3 py-2 border border-secondary-200 rounded-lg text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                placeholder="unit/pcs/ls">
                        </div>
                        <div class="col-span-3 md:col-span-2">
                            <label class="block text-xs font-medium text-secondary-700 mb-1">Harga</label>
                            <input type="number" name="details[{{ $index }}][harga_satuan]" value="{{ $detail['harga_satuan'] ?? '' }}" min="0" step="0.01" required
                                class="w-full px-3 py-2 border border-secondary-200 rounded-lg text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent detail-harga"
                                data-index="{{ $index }}" onchange="calculateRowTotal({{ $index }})">
                        </div>
                        <div class="col-span-2 md:col-span-1 text-right">
                            <label class="block text-xs font-medium text-secondary-700 mb-1">Jumlah</label>
                            <div class="py-2 text-sm font-semibold text-secondary-900 detail-jumlah" data-index="{{ $index }}">
                                {{ formatRupiah(($detail['volume'] ?? 0) * ($detail['harga_satuan'] ?? 0)) }}
                            </div>
                        </div>
                        <div class="col-span-12 md:col-span-1 text-center">
                            <label class="block text-xs font-medium text-secondary-700 mb-1">&nbsp;</label>
                            <button type="button" onclick="removeDetailRow(this)" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    @php
                        $index++;
                    @endphp
                    @endforeach
                </div>

                <button type="button" onclick="addDetailRow()" class="mt-4 flex items-center text-sm text-primary-600 hover:text-primary-700 font-medium">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Rincian
                </button>

                <div class="mt-6 p-4 bg-primary-50 rounded-xl">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-secondary-700">Total Pengajuan:</span>
                        <span id="total-pengajuan" class="text-xl font-bold text-primary-600">{{ formatRupiah(old('total_pengajuan', $pengajuan->total_pengajuan)) }}</span>
                    </div>
                </div>
            </div>

            <!-- Penerima Manfaat -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-secondary-900 mb-4 flex items-center">
                    <span class="w-8 h-8 bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </span>
                    Penerima Manfaat (Opsional)
                </h2>

                <div id="penerima-container" class="space-y-3">
                    @php
                        $penerimas = old('penerimas', $pengajuan->penerimaManfaat->toArray() ?? []);
                        $pIndex = 0;
                    @endphp
                    @foreach($penerimas as $penerima)
                    <div class="penerima-row grid grid-cols-12 gap-3 items-center p-4 bg-secondary-50 rounded-xl">
                        <div class="col-span-12 md:col-span-5">
                            <input type="text" name="penerimas[{{ $pIndex }}][nama_penerima]" value="{{ $penerima['nama_penerima'] ?? '' }}"
                                class="w-full px-3 py-2 border border-secondary-200 rounded-lg text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                placeholder="Nama penerima">
                        </div>
                        <div class="col-span-6 md:col-span-4">
                            <input type="text" name="penerimas[{{ $pIndex }}][jenis_penerima]" value="{{ $penerima['jenis_penerima'] ?? '' }}"
                                class="w-full px-3 py-2 border border-secondary-200 rounded-lg text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                placeholder="Jenis (pegawai/vendor/etc)">
                        </div>
                        <div class="col-span-6 md:col-span-2">
                            <input type="text" name="penerimas[{{ $pIndex }}][nomor_identitas]" value="{{ $penerima['nomor_identitas'] ?? '' }}"
                                class="w-full px-3 py-2 border border-secondary-200 rounded-lg text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                placeholder="No. Identitas">
                        </div>
                        <div class="col-span-12 md:col-span-1 text-center">
                            <button type="button" onclick="removePenerimaRow(this)" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    @php
                        $pIndex++;
                    @endphp
                    @endforeach
                </div>

                <button type="button" onclick="addPenerimaRow()" class="mt-4 flex items-center text-sm text-primary-600 hover:text-primary-700 font-medium">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Penerima
                </button>
            </div>

            <!-- Lampiran -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-secondary-900 mb-4 flex items-center">
                    <span class="w-8 h-8 bg-amber-100 text-amber-600 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                        </svg>
                    </span>
                    Lampiran Dokumen (Opsional)
                </h2>

                <div class="border-2 border-dashed border-secondary-200 rounded-xl p-6 text-center hover:border-primary-400 transition-colors">
                    <svg class="mx-auto h-12 w-12 text-secondary-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <div class="mt-4">
                        <label for="lampiran" class="cursor-pointer">
                            <span class="mt-2 block text-sm font-medium text-secondary-900">Upload dokumen pendukung</span>
                            <span class="text-sm text-secondary-500">PDF, JPG, PNG hingga 10MB</span>
                        </label>
                        <input id="lampiran" name="lampiran[]" type="file" class="sr-only" multiple accept=".pdf,.jpg,.jpeg,.png" onchange="handleFileSelect(this)">
                    </div>
                </div>

                <!-- Existing files -->
                @if($pengajuan->lampiranPengajuan && $pengajuan->lampiranPengajuan->count() > 0)
                <div class="mt-4 space-y-2">
                    <p class="text-sm font-medium text-secondary-700">File yang sudah diupload:</p>
                    @foreach($pengajuan->lampiranPengajuan as $lampiran)
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
                <a href="{{ route('pengajuan-dana.show', $pengajuan) }}" class="px-6 py-3 border border-secondary-200 text-secondary-700 rounded-xl hover:bg-secondary-50 transition-all duration-200">
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
        let detailIndex = {{ $index ?? 0 }};
        let penerimaIndex = {{ $pIndex ?? 0 }};

        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
        }

        function calculateRowTotal(index) {
            const volume = document.querySelector(`.detail-volume[data-index="${index}"]`)?.value || 0;
            const harga = document.querySelector(`.detail-harga[data-index="${index}"]`)?.value || 0;
            const total = volume * harga;
            const jumlahElement = document.querySelector(`.detail-jumlah[data-index="${index}"]`);
            if (jumlahElement) {
                jumlahElement.textContent = formatRupiah(total);
            }
            calculateTotal();
        }

        function calculateTotal() {
            let grandTotal = 0;
            document.querySelectorAll('.detail-row').forEach(row => {
                const volume = row.querySelector('.detail-volume')?.value || 0;
                const harga = row.querySelector('.detail-harga')?.value || 0;
                grandTotal += volume * harga;
            });
            document.getElementById('total-pengajuan').textContent = formatRupiah(grandTotal);
        }

        function addDetailRow() {
            const container = document.getElementById('detail-container');
            const rowHtml = `
                <div class="detail-row grid grid-cols-12 gap-3 items-start p-4 bg-secondary-50 rounded-xl">
                    <div class="col-span-12 md:col-span-4">
                        <label class="block text-xs font-medium text-secondary-700 mb-1">Uraian</label>
                        <input type="text" name="details[${detailIndex}][uraian]" required
                            class="w-full px-3 py-2 border border-secondary-200 rounded-lg text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                            placeholder="Nama barang/jasa">
                    </div>
                    <div class="col-span-3 md:col-span-2">
                        <label class="block text-xs font-medium text-secondary-700 mb-1">Volume</label>
                        <input type="number" name="details[${detailIndex}][volume]" min="1" required
                            class="w-full px-3 py-2 border border-secondary-200 rounded-lg text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent detail-volume"
                            data-index="${detailIndex}" onchange="calculateRowTotal(${detailIndex})">
                    </div>
                    <div class="col-span-4 md:col-span-2">
                        <label class="block text-xs font-medium text-secondary-700 mb-1">Satuan</label>
                        <input type="text" name="details[${detailIndex}][satuan]"
                            class="w-full px-3 py-2 border border-secondary-200 rounded-lg text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                            placeholder="unit/pcs/ls">
                    </div>
                    <div class="col-span-3 md:col-span-2">
                        <label class="block text-xs font-medium text-secondary-700 mb-1">Harga</label>
                        <input type="number" name="details[${detailIndex}][harga_satuan]" min="0" step="0.01" required
                            class="w-full px-3 py-2 border border-secondary-200 rounded-lg text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent detail-harga"
                            data-index="${detailIndex}" onchange="calculateRowTotal(${detailIndex})">
                    </div>
                    <div class="col-span-2 md:col-span-1 text-right">
                        <label class="block text-xs font-medium text-secondary-700 mb-1">Jumlah</label>
                        <div class="py-2 text-sm font-semibold text-secondary-900 detail-jumlah" data-index="${detailIndex}">Rp 0</div>
                    </div>
                    <div class="col-span-12 md:col-span-1 text-center">
                        <label class="block text-xs font-medium text-secondary-700 mb-1">&nbsp;</label>
                        <button type="button" onclick="removeDetailRow(this)" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', rowHtml);
            detailIndex++;
        }

        function removeDetailRow(button) {
            const row = button.closest('.detail-row');
            if (document.querySelectorAll('.detail-row').length > 1) {
                row.remove();
                calculateTotal();
            }
        }

        function addPenerimaRow() {
            const container = document.getElementById('penerima-container');
            const rowHtml = `
                <div class="penerima-row grid grid-cols-12 gap-3 items-center p-4 bg-secondary-50 rounded-xl">
                    <div class="col-span-12 md:col-span-5">
                        <input type="text" name="penerimas[${penerimaIndex}][nama_penerima]"
                            class="w-full px-3 py-2 border border-secondary-200 rounded-lg text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                            placeholder="Nama penerima">
                    </div>
                    <div class="col-span-6 md:col-span-4">
                        <input type="text" name="penerimas[${penerimaIndex}][jenis_penerima]"
                            class="w-full px-3 py-2 border border-secondary-200 rounded-lg text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                            placeholder="Jenis (pegawai/vendor/etc)">
                    </div>
                    <div class="col-span-6 md:col-span-2">
                        <input type="text" name="penerimas[${penerimaIndex}][nomor_identitas]"
                            class="w-full px-3 py-2 border border-secondary-200 rounded-lg text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                            placeholder="No. Identitas">
                    </div>
                    <div class="col-span-12 md:col-span-1 text-center">
                        <button type="button" onclick="removePenerimaRow(this)" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', rowHtml);
            penerimaIndex++;
        }

        function removePenerimaRow(button) {
            button.closest('.penerima-row').remove();
        }

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

        // Initialize calculations on page load
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.detail-row').forEach(row => {
                const index = row.querySelector('.detail-volume')?.dataset.index;
                if (index) {
                    calculateRowTotal(index);
                }
            });
        });
    </script>
</x-app-layout>
