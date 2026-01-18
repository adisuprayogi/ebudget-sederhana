<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('lpj.show', $lpj) }}" class="p-2 text-secondary-600 hover:text-secondary-900 hover:bg-secondary-100 rounded-lg transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-secondary-900">Revisi LPJ</h1>
                <p class="text-secondary-600 mt-1">{{ $lpj->nomor_lpj }}</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-6xl mx-auto py-8">
        @if($lpj->status === 'revisi')
            <!-- Alert: Revisi Required -->
            <div class="mb-6 bg-orange-50 border border-orange-200 rounded-xl p-4 flex items-start">
                <svg class="w-5 h-5 text-orange-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="text-orange-700 text-sm">
                    <p class="font-medium">LPJ ini perlu direvisi.</p>
                    @if($lpj->rejection_reason)
                        <p class="mt-1">Alasan penolakan: <strong>{{ $lpj->rejection_reason }}</strong></p>
                    @endif
                    <p class="mt-2">Silakan perbaiki data LPJ dan kirim ulang untuk verifikasi.</p>
                </div>
            </div>
        @endif

        <!-- Info Pengajuan & Pencairan -->
        <div class="mb-6 bg-white rounded-2xl shadow-soft p-6">
            <h2 class="text-lg font-semibold text-secondary-900 mb-4 flex items-center">
                <span class="w-8 h-8 bg-primary-100 text-blue-600 rounded-lg flex items-center justify-center mr-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </span>
                Informasi Pengajuan & Pencairan
            </h2>

            @if($lpj->pencairanDana && $lpj->pencairanDana->pengajuanDana)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <p class="text-sm text-secondary-500">Nomor Pengajuan</p>
                    <p class="font-mono font-semibold text-blue-600">{{ $lpj->pencairanDana->pengajuanDana->nomor_pengajuan }}</p>
                </div>
                <div>
                    <p class="text-sm text-secondary-500">Judul Pengajuan</p>
                    <p class="font-medium text-secondary-900">{{ $lpj->pencairanDana->pengajuanDana->judul_pengajuan }}</p>
                </div>
                <div>
                    <p class="text-sm text-secondary-500">Nomor Pencairan</p>
                    <p class="font-mono font-semibold text-blue-600">{{ $lpj->pencairanDana->nomor_pencairan }}</p>
                </div>
                <div>
                    <p class="text-sm text-secondary-500">Total Pencairan</p>
                    <p class="font-bold text-lg text-blue-600">{{ formatRupiah($lpj->pencairanDana->jumlah_pencairan) }}</p>
                </div>
                <div>
                    <p class="text-sm text-secondary-500">Divisi</p>
                    <p class="font-medium text-secondary-900">{{ $lpj->pencairanDana->pengajuanDana->divisi->nama_divisi ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-secondary-500">Program Kerja</p>
                    <p class="font-medium text-secondary-900">{{ $lpj->pencairanDana->pengajuanDana->programKerja->nama_program ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-secondary-500">Tanggal Pencairan</p>
                    <p class="font-medium text-secondary-900">{{ \Carbon\Carbon::parse($lpj->pencairanDana->tanggal_pencairan)->format('d/m/Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-secondary-500">Metode Pencairan</p>
                    <p class="font-medium text-secondary-900">{{ ucfirst($lpj->pencairanDana->metode_pencairan) }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Form LPJ -->
        <form method="POST" action="{{ route('lpj.update', $lpj) }}" enctype="multipart/form-data" class="bg-white rounded-2xl shadow-soft p-8">
            @csrf
            @method('PUT')

            <!-- Informasi LPJ -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-secondary-900 mb-4 flex items-center">
                    <span class="w-8 h-8 bg-gradient-to-br from-blue-600 to-blue-700 text-white shadow-blue-500/30 shadow-md rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </span>
                    Informasi LPJ
                </h2>

                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <x-input-label for="uraian_kegiatan" value="Uraian Kegiatan" />
                        <textarea name="uraian_kegiatan" id="uraian_kegiatan" rows="2"
                            class="mt-1 block w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-orange-500 focus:border-orange-500 transition-all duration-200"
                            placeholder="{{ $lpj->pencairanDana->pengajuanDana->judul_pengajuan ?? '' }}">{{ old('uraian_kegiatan', $lpj->uraian_kegiatan) }}</textarea>
                        <x-input-error :messages="$errors->get('uraian_kegiatan')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="catatan" value="Catatan" />
                        <textarea name="catatan" id="catatan" rows="2"
                            class="mt-1 block w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-orange-500 focus:border-orange-500 transition-all duration-200"
                            placeholder="Catatan tambahan...">{{ old('catatan', $lpj->catatan) }}</textarea>
                        <x-input-error :messages="$errors->get('catatan')" class="mt-2" />
                    </div>
                </div>
            </div>

            <!-- Rincian Realisasi LPJ -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-secondary-900 mb-4 flex items-center">
                    <span class="w-8 h-8 bg-gradient-to-br from-blue-600 to-blue-700 text-white shadow-blue-500/30 shadow-md rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </span>
                    Rincian Realisasi LPJ
                </h2>

                <!-- Info Card -->
                <div class="mb-4 bg-amber-50 border border-amber-200 rounded-xl p-4 flex items-start">
                    <svg class="w-5 h-5 text-amber-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="text-amber-700 text-sm">
                        <p class="font-medium">Perbaiki nominal realisasi sesuai penggunaan dana yang sebenarnya.</p>
                        <p class="text-xs mt-1">Total Pencairan: <strong>{{ formatRupiah($lpj->pencairanDana->jumlah_pencairan) }}</strong></p>
                    </div>
                </div>

                @if($lpj->detailLpjs && $lpj->detailLpjs->count() > 0)
                <div class="space-y-4">
                    @foreach($lpj->detailLpjs as $index => $detail)
                    <div class="border border-secondary-200 rounded-xl p-4 bg-secondary-50">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1">
                                <p class="font-medium text-secondary-900">{{ $detail->uraian }}</p>
                                <p class="text-sm text-secondary-500">
                                    Rencana: {{ $detail->detailPencairan->volume ?? 0 }} {{ $detail->satuan ?? '' }} × {{ formatRupiah($detail->detailPencairan->harga_satuan ?? 0) }} = {{ formatRupiah($detail->detailPencairan->subtotal ?? 0) }}
                                </p>
                            </div>
                            <span class="px-2 py-1 bg-primary-100 text-primary-700 text-xs font-medium rounded-lg">
                                #{{ $index + 1 }}
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                            <input type="hidden" name="details[{{ $index }}][detail_lpj_id]" value="{{ $detail->id }}">
                            <input type="hidden" name="details[{{ $index }}][detail_pencairan_id]" value="{{ $detail->detail_pencairan_id }}">
                            <input type="hidden" name="details[{{ $index }}][uraian]" value="{{ $detail->uraian }}">

                            <div>
                                <label class="block text-sm font-medium text-secondary-700 mb-1">Tanggal Realisasi *</label>
                                <input type="date" name="details[{{ $index }}][tanggal_realisasi]"
                                    value="{{ old("details.$index.tanggal_realisasi", $detail->tanggal_realisasi?->format('Y-m-d')) }}"
                                    required
                                    class="w-full px-3 py-2 border border-secondary-200 rounded-lg focus:ring-orange-500 focus:border-orange-500 transition-all duration-200 text-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-secondary-700 mb-1">Volume Realisasi</label>
                                <input type="number" name="details[{{ $index }}][volume_realisasi]"
                                    value="{{ old("details.$index.volume_realisasi", $detail->volume_realisasi) }}"
                                    min="0" step="0.01"
                                    oninput="calculateSubtotal({{ $index }})"
                                    class="w-full px-3 py-2 border border-secondary-200 rounded-lg focus:ring-orange-500 focus:border-orange-500 transition-all duration-200"
                                    placeholder="0">
                                <p class="text-xs text-secondary-500 mt-1">Satuan: {{ $detail->satuan ?? '' }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-secondary-700 mb-1">Harga Satuan</label>
                                <input type="number" name="details[{{ $index }}][harga_satuan]"
                                    value="{{ old("details.$index.harga_satuan", $detail->harga_satuan) }}"
                                    min="0" step="0.01"
                                    oninput="calculateSubtotal({{ $index }})"
                                    class="w-full px-3 py-2 border border-secondary-200 rounded-lg focus:ring-orange-500 focus:border-orange-500 transition-all duration-200"
                                    placeholder="0">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-secondary-700 mb-1">Subtotal Realisasi</label>
                                <input type="text" id="subtotal_{{ $index }}"
                                    value="{{ formatRupiah(old("details.$index.harga_satuan", $detail->harga_satuan) * old("details.$index.volume_realisasi", $detail->volume_realisasi)) }}"
                                    readonly
                                    class="w-full px-3 py-2 bg-secondary-100 border border-secondary-200 rounded-lg text-secondary-900 font-semibold text-sm">
                                <input type="hidden" name="details[{{ $index }}][subtotal_realisasi]" id="subtotal_hidden_{{ $index }}" value="{{ old("details.$index.subtotal_realisasi", $detail->subtotal_realisasi) }}">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-secondary-700 mb-1">Lampiran</label>
                                @if($detail->file_lampiran)
                                    <p class="text-xs text-secondary-500 mb-1">
                                        <a href="{{ Storage::url($detail->file_lampiran) }}" target="_blank" class="text-blue-600 hover:underline">File ada</a>
                                    </p>
                                @endif
                                <input type="file" name="lampiran_{{ $index }}"
                                    accept=".pdf,.jpg,.jpeg,.png"
                                    class="w-full px-3 py-2 border border-secondary-200 rounded-lg focus:ring-orange-500 focus:border-orange-500 transition-all duration-200 text-sm">
                                <p class="text-xs text-secondary-500 mt-1">PDF, JPG, PNG (max 5MB)</p>
                            </div>
                        </div>

                        <div class="mt-3">
                            <label class="block text-sm font-medium text-secondary-700 mb-1">Keterangan</label>
                            <textarea name="details[{{ $index }}][keterangan]" rows="2"
                                class="w-full px-3 py-2 border border-secondary-200 rounded-lg focus:ring-orange-500 focus:border-orange-500 transition-all duration-200 text-sm"
                                placeholder="Keterangan tambahan...">{{ old("details.$index.keterangan", $detail->keterangan) }}</textarea>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Total Summary -->
                <div class="mt-6 p-4 bg-blue-50 rounded-xl border border-blue-200 border border-primary-200">
                    <div class="flex items-center justify-between">
                        <span class="font-medium text-secondary-900">Total Realisasi:</span>
                        <span id="total_realisasi" class="text-xl font-bold text-blue-600">{{ formatRupiah($lpj->detailLpjs->sum('subtotal_realisasi')) }}</span>
                    </div>
                    <div class="flex items-center justify-between mt-2">
                        <span class="font-medium text-secondary-900">Sisa Dana:</span>
                        <span id="sisa_dana" class="text-lg font-semibold @if($lpj->sisa_dana > 0) text-green-600 @else text-red-600 @endif">{{ formatRupiah($lpj->sisa_dana) }}</span>
                    </div>
                    <input type="hidden" name="total_digunakan" id="total_digunakan_hidden" value="{{ $lpj->total_digunakan }}">
                    <input type="hidden" name="sisa_dana" id="sisa_dana_hidden" value="{{ $lpj->sisa_dana }}">
                </div>
                @else
                <div class="text-center py-8 bg-secondary-50 rounded-xl">
                    <p class="text-secondary-500">Tidak ada rincian LPJ</p>
                </div>
                @endif
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-secondary-200">
                <a href="{{ route('lpj.show', $lpj) }}" class="px-6 py-3 border border-secondary-200 text-secondary-700 rounded-xl hover:bg-secondary-50 transition-all duration-200">
                    Batal
                </a>
                <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-all duration-200 shadow-soft hover:shadow-medium">
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Simpan & Kirim Ulang
                    </span>
                </button>
            </div>
        </form>
    </div>

    <script>
    const detailCount = {{ $lpj->detailLpjs?->count() ?? 0 }};
    const totalPencairan = {{ $lpj->pencairanDana->jumlah_pencairan ?? 0 }};

    function formatRupiahInput(number) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(number);
    }

    function calculateSubtotal(index) {
        const volumeInput = document.querySelector(`input[name="details[${index}][volume_realisasi]"]`);
        const hargaInput = document.querySelector(`input[name="details[${index}][harga_satuan]"]`);
        const subtotalDisplay = document.getElementById(`subtotal_${index}`);
        const subtotalHidden = document.getElementById(`subtotal_hidden_${index}`);

        const volume = parseFloat(volumeInput?.value) || 0;
        const harga = parseFloat(hargaInput?.value) || 0;
        const subtotal = volume * harga;

        if (subtotalDisplay) {
            subtotalDisplay.value = formatRupiahInput(subtotal);
        }
        if (subtotalHidden) {
            subtotalHidden.value = subtotal;
        }

        calculateTotal();
    }

    function calculateTotal() {
        let totalRealisasi = 0;

        for (let i = 0; i < detailCount; i++) {
            const subtotalHidden = document.getElementById(`subtotal_hidden_${i}`);
            if (subtotalHidden && subtotalHidden.value) {
                totalRealisasi += parseFloat(subtotalHidden.value) || 0;
            }
        }

        const sisaDana = totalPencairan - totalRealisasi;

        // Update display
        const totalDisplay = document.getElementById('total_realisasi');
        const sisaDisplay = document.getElementById('sisa_dana');
        const totalHidden = document.getElementById('total_digunakan_hidden');
        const sisaHidden = document.getElementById('sisa_dana_hidden');

        if (totalDisplay) {
            totalDisplay.textContent = formatRupiahInput(totalRealisasi);
        }
        if (sisaDisplay) {
            sisaDisplay.textContent = formatRupiahInput(Math.max(0, sisaDana));
            if (sisaDana < 0) {
                sisaDisplay.classList.remove('text-green-600');
                sisaDisplay.classList.add('text-red-600');
            } else {
                sisaDisplay.classList.remove('text-red-600');
                sisaDisplay.classList.add('text-green-600');
            }
        }
        if (totalHidden) {
            totalHidden.value = totalRealisasi;
        }
        if (sisaHidden) {
            sisaHidden.value = Math.max(0, sisaDana);
        }
    }

    // Initialize calculations on page load
    document.addEventListener('DOMContentLoaded', function() {
        for (let i = 0; i < detailCount; i++) {
            calculateSubtotal(i);
        }
    });
    </script>
</x-app-layout>
