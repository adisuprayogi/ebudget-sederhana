<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Penetapan Pagu</h1>
                <p class="text-gray-600 mt-1">Ubah data alokasi pagu anggaran</p>
            </div>
            <a href="{{ route('penetapan-pagu.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto py-8">
        <form method="POST" action="{{ route('penetapan-pagu.update', $penetapanPagu) }}">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-6">Informasi Pagu</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Periode Anggaran (Read Only) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Periode Anggaran</label>
                        <input type="text" value="{{ $penetapanPagu->periodeAnggaran->nama_periode ?? '-' }} ({{ $penetapanPagu->periodeAnggaran->tahun_anggaran ?? '-' }})" readonly class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-600">
                    </div>

                    <!-- Divisi (Read Only) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Divisi</label>
                        <input type="text" value="{{ $penetapanPagu->divisi->nama_divisi ?? '-' }}" readonly class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-600">
                    </div>

                    <!-- Jumlah Pagu -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Pagu <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-medium">Rp</span>
                            <input type="text" id="jumlah_pagu_display" class="currency-input w-full pl-12 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-right" placeholder="0" data-target="jumlah_pagu" value="{{ $penetapanPagu->jumlah_pagu }}">
                            <input type="hidden" name="jumlah_pagu" id="jumlah_pagu" value="{{ old('jumlah_pagu', $penetapanPagu->jumlah_pagu) }}">
                        </div>
                        <p class="mt-2 text-sm text-gray-500">Total dana yang dialokasikan untuk divisi ini</p>
                        @error('jumlah_pagu')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Current Usage Info -->
                <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                    <h3 class="text-sm font-semibold text-blue-900 mb-3">Penggunaan Saat Ini</h3>
                    <div class="grid grid-cols-3 gap-4 text-center">
                        <div>
                            <div class="text-2xl font-bold text-blue-600">{{ formatRupiah($penetapanPagu->jumlah_pagu) }}</div>
                            <div class="text-xs text-blue-700">Total Pagu</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-amber-600">{{ formatRupiah($penetapanPagu->used_amount) }}</div>
                            <div class="text-xs text-amber-700">Terpakai</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-green-600">{{ formatRupiah($penetapanPagu->remaining_amount) }}</div>
                            <div class="text-xs text-green-700">Sisa</div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="flex justify-between text-xs text-blue-700 mb-1">
                            <span>Penggunaan</span>
                            <span>{{ number_format($penetapanPagu->usage_percentage, 1) }}%</span>
                        </div>
                        <div class="w-full bg-blue-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ min($penetapanPagu->usage_percentage, 100) }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Catatan -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Catatan</h2>
                <textarea name="catatan" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Tambahkan catatan atau keterangan tambahan...">{{ old('catatan', $penetapanPagu->catatan) }}</textarea>
                @error('catatan')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-4">
                <a href="{{ route('penetapan-pagu.show', $penetapanPagu) }}" class="px-6 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

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

        document.addEventListener('DOMContentLoaded', function() {
            const displayInput = document.getElementById('jumlah_pagu_display');
            const hiddenInput = document.getElementById('jumlah_pagu');

            // Set initial display value
            if (displayInput && hiddenInput && hiddenInput.value) {
                displayInput.value = formatCurrency(hiddenInput.value);
            }

            if (displayInput) {
                displayInput.addEventListener('input', function(e) {
                    let value = e.target.value;
                    let cursorPos = e.target.selectionStart;

                    let formatted = formatCurrency(value);
                    e.target.value = formatted;

                    if (hiddenInput) {
                        hiddenInput.value = parseCurrency(formatted);
                    }

                    let lengthDiff = formatted.length - value.length;
                    e.target.setSelectionRange(cursorPos + lengthDiff, cursorPos + lengthDiff);
                });

                displayInput.addEventListener('blur', function(e) {
                    if (e.target.value === '') {
                        e.target.value = '';
                        if (hiddenInput) hiddenInput.value = 0;
                    }
                });

                displayInput.addEventListener('focus', function(e) {
                    e.target.select();
                });
            }
        });
    </script>
</x-app-layout>
