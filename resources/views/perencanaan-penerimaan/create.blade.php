<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('perencanaan-penerimaan.index') }}" class="w-10 h-10 rounded-xl bg-blue-50 hover:bg-blue-100 flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-xl font-semibold text-gray-900">Buat Perencanaan Penerimaan</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Tambahkan perencanaan dana baru</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl">
        <form method="POST" action="{{ route('perencanaan-penerimaan.store') }}" id="createForm">
            @csrf

            <!-- Main Form Card -->
            <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
                <!-- Form Fields -->
                <div class="p-8 space-y-6">
                    <!-- Periode Anggaran -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Periode Anggaran <span class="text-red-500">*</span></label>
                        <select name="periode_anggaran_id" id="periode_anggaran_id" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all bg-white text-sm">
                            <option value="">Pilih Periode Anggaran</option>
                            @foreach($periodeAnggarans ?? [] as $periode)
                                <option value="{{ $periode->id }}" data-start="{{ $periode->tanggal_mulai_penggunaan_anggaran }}" data-end="{{ $periode->tanggal_selesai_penggunaan_anggaran }}">
                                    {{ $periode->nama_periode }} ({{ $periode->tahun_anggaran }})
                                </option>
                            @endforeach
                        </select>
                        @error('periode_anggaran_id')
                            <p class="text-sm text-red-600 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Divisi & Kode Rekening -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Divisi <span class="text-red-500">*</span></label>
                            <select name="divisi_id" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all bg-white text-sm">
                                <option value="">Pilih Divisi</option>
                                @foreach($divisis ?? [] as $divisi)
                                    <option value="{{ $divisi->id }}" {{ old('divisi_id') == $divisi->id || $defaultDivisi == $divisi->id ? 'selected' : '' }}>
                                        {{ $divisi->nama_divisi }}
                                    </option>
                                @endforeach
                            </select>
                            @error('divisi_id')
                                <p class="text-sm text-red-600 mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kode Rekening</label>
                            <input type="text" name="kode_rekening" value="{{ old('kode_rekening') }}" placeholder="Contoh: 4.1.1.01" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all font-mono text-sm">
                            @error('kode_rekening')
                                <p class="text-sm text-red-600 mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Uraian -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Uraian <span class="text-red-500">*</span></label>
                        <textarea name="uraian" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all resize-none text-sm" placeholder="Jelaskan sumber penerimaan dana...">{{ old('uraian') }}</textarea>
                        @error('uraian')
                            <p class="text-sm text-red-600 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Sumber Dana & Jumlah Estimasi -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Sumber Dana <span class="text-red-500">*</span></label>
                            <select name="sumber_dana_id" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all bg-white text-sm">
                                <option value="">Pilih Sumber Dana</option>
                                @foreach($sumberDanas ?? [] as $sumberDana)
                                    <option value="{{ $sumberDana->id }}" {{ old('sumber_dana_id') == $sumberDana->id ? 'selected' : '' }}>
                                        {{ $sumberDana->nama_sumber }} ({{ $sumberDana->kode_sumber }})
                                    </option>
                                @endforeach
                            </select>
                            @error('sumber_dana_id')
                                <p class="text-sm text-red-600 mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Estimasi <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm font-medium">Rp</span>
                                <input type="text" id="jumlah_estimasi_display" class="currency-input w-full pl-12 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-right font-mono text-sm" placeholder="0" data-target="jumlah_estimasi">
                                <input type="hidden" name="jumlah_estimasi" id="jumlah_estimasi" value="{{ old('jumlah_estimasi') }}">
                            </div>
                            @error('jumlah_estimasi')
                                <p class="text-sm text-red-600 mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Perkiraan Per Bulan -->
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-sm font-semibold text-gray-700">Perkiraan Per Bulan</h3>
                            <span id="total_bulanan" class="text-sm font-bold text-blue-600">Rp 0</span>
                        </div>

                        <div id="months-container" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 mb-4">
                            <div class="col-span-full text-center py-12">
                                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p class="text-gray-500 text-sm">Pilih periode anggaran untuk melihat daftar bulan</p>
                            </div>
                        </div>

                        <!-- Summary Card -->
                        <div class="bg-blue-50 rounded-xl p-4 border border-blue-100">
                            <div class="flex flex-wrap items-center justify-between gap-3">
                                <div class="flex items-center gap-6">
                                    <div>
                                        <p class="text-xs text-gray-500">Total Bulanan</p>
                                        <p id="total_bulanan_card" class="text-lg font-bold text-gray-900">Rp 0</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Estimasi</p>
                                        <p id="estimasi_display" class="text-lg font-bold text-gray-900">Rp 0</p>
                                    </div>
                                </div>
                                <div id="selisih_container" class="hidden">
                                    <p class="text-xs text-gray-500">Selisih</p>
                                    <p id="selisih" class="text-lg font-bold text-red-600">Rp 0</p>
                                </div>
                            </div>
                        </div>

                        <p class="text-xs text-gray-500 mt-2 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Total per bulan harus sama dengan jumlah estimasi
                        </p>
                    </div>

                    <!-- Catatan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                        <textarea name="catatan" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all resize-none text-sm" placeholder="Tambahkan catatan atau keterangan tambahan...">{{ old('catatan') }}</textarea>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-gray-50 px-8 py-4 border-t border-gray-200 flex items-center justify-end gap-3">
                    <a href="{{ route('perencanaan-penerimaan.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 font-medium rounded-lg transition-colors text-sm">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan Perencanaan
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        let availableMonths = [];

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

        function loadMonths() {
            const periodeId = document.getElementById('periode_anggaran_id').value;
            const container = document.getElementById('months-container');

            if (!periodeId) {
                container.innerHTML = `
                    <div class="col-span-full text-center py-12">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p class="text-gray-500 text-sm">Pilih periode anggaran untuk melihat daftar bulan</p>
                    </div>
                `;
                availableMonths = [];
                updateDisplay();
                return;
            }

            try {
                const select = document.getElementById('periode_anggaran_id');
                const selectedOption = select.options[select.selectedIndex];
                const startDate = new Date(selectedOption.getAttribute('data-start'));
                const endDate = new Date(selectedOption.getAttribute('data-end'));

                availableMonths = [];
                const currentDate = new Date(startDate);

                while (currentDate <= endDate) {
                    const key = currentDate.toISOString().slice(0, 7);
                    const label = currentDate.toLocaleDateString('id-ID', { year: 'numeric', month: 'long' });
                    availableMonths.push({ key, label });
                    currentDate.setMonth(currentDate.getMonth() + 1);
                }

                renderMonthInputs();

            } catch (error) {
                console.error('Error loading months:', error);
            }
        }

        function renderMonthInputs() {
            const container = document.getElementById('months-container');

            if (availableMonths.length === 0) {
                container.innerHTML = `
                    <div class="col-span-full text-center py-8 text-gray-500 text-sm">
                        <p>Periode anggaran tidak valid</p>
                    </div>
                `;
                return;
            }

            let html = '';
            availableMonths.forEach((month) => {
                html += `
                    <div class="bg-white border border-gray-200 rounded-lg p-3">
                        <label class="block text-xs font-medium text-gray-600 mb-2">${month.label}</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs">Rp</span>
                            <input type="text"
                                   name="perkiraan_bulanan_display[${month.key}]"
                                   class="bulanan-input currency-input-month w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-xs text-right font-mono"
                                   placeholder="0"
                                   data-month-key="${month.key}"
                                   data-month="${month.label}">
                            <input type="hidden" name="perkiraan_bulanan[${month.key}]" id="bulanan_${month.key}" class="bulanan-hidden">
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

            const formattedTotal = 'Rp ' + total.toLocaleString('id-ID');
            document.getElementById('total_bulanan').textContent = formattedTotal;
            document.getElementById('total_bulanan_card').textContent = formattedTotal;
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

        document.addEventListener('DOMContentLoaded', function() {
            initCurrencyInputs();
            document.getElementById('periode_anggaran_id').addEventListener('change', loadMonths);
        });
    </script>
</x-app-layout>
