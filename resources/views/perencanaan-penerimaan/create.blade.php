<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Buat Perencanaan Penerimaan Baru</h1>
                <p class="text-gray-600 mt-1">Tambahkan perencanaan penerimaan dana</p>
            </div>
            <a href="{{ route('perencanaan-penerimaan.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="max-w-6xl mx-auto py-8">
        <form method="POST" action="{{ route('perencanaan-penerimaan.store') }}" id="createForm">
            @csrf

            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-6">Informasi Perencanaan</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Periode Anggaran -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Periode Anggaran <span class="text-red-500">*</span></label>
                        <select name="periode_anggaran_id" id="periode_anggaran_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Pilih Periode Anggaran</option>
                            @foreach($periodeAnggarans ?? [] as $periode)
                                <option value="{{ $periode->id }}" data-start="{{ $periode->tanggal_mulai_penggunaan_anggaran }}" data-end="{{ $periode->tanggal_selesai_penggunaan_anggaran }}">
                                    {{ $periode->nama_periode }} ({{ $periode->tahun_anggaran }})
                                </option>
                            @endforeach
                        </select>
                        @error('periode_anggaran_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Divisi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Divisi <span class="text-red-500">*</span></label>
                        <select name="divisi_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Pilih Divisi</option>
                            @foreach($divisis ?? [] as $divisi)
                                <option value="{{ $divisi->id }}" {{ old('divisi_id') == $divisi->id || $defaultDivisi == $divisi->id ? 'selected' : '' }}>
                                    {{ $divisi->nama_divisi }}
                                </option>
                            @endforeach
                        </select>
                        @error('divisi_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kode Rekening -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kode Rekening</label>
                        <input type="text" name="kode_rekening" value="{{ old('kode_rekening') }}" placeholder="Contoh: 4.1.1.01" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('kode_rekening')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Uraian -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Uraian <span class="text-red-500">*</span></label>
                        <textarea name="uraian" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Jelaskan sumber penerimaan dana...">{{ old('uraian') }}</textarea>
                        @error('uraian')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Sumber Dana -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sumber Dana <span class="text-red-500">*</span></label>
                        <select name="sumber_dana_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Pilih Sumber Dana</option>
                            @foreach($sumberDanas ?? [] as $sumberDana)
                                <option value="{{ $sumberDana->id }}" {{ old('sumber_dana_id') == $sumberDana->id ? 'selected' : '' }}>
                                    {{ $sumberDana->nama_sumber }} ({{ $sumberDana->kode_sumber }})
                                </option>
                            @endforeach
                        </select>
                        @error('sumber_dana_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jumlah Estimasi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Estimasi <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-medium">Rp</span>
                            <input type="text" id="jumlah_estimasi_display" class="currency-input w-full pl-12 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-right" placeholder="0" data-target="jumlah_estimasi">
                            <input type="hidden" name="jumlah_estimasi" id="jumlah_estimasi" value="{{ old('jumlah_estimasi') }}">
                        </div>
                        @error('jumlah_estimasi')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Perkiraan Per Bulan -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-2">Perkiraan Per Bulan</h2>
                <p class="text-sm text-gray-500 mb-6">Rincikan estimasi penerimaan per bulan sesuai periode anggaran</p>

                <!-- Month inputs will be dynamically loaded here -->
                <div id="months-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div class="col-span-full text-center py-8 text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p>Pilih periode anggaran untuk melihat daftar bulan</p>
                    </div>
                </div>

                <!-- Summary -->
                <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                    <div class="flex justify-between items-center">
                        <div>
                            <span class="text-sm text-blue-700">Total Per Bulan:</span>
                            <span id="total_bulanan" class="ml-2 font-bold text-blue-900">Rp 0</span>
                        </div>
                        <div>
                            <span class="text-sm text-blue-700">Estimasi:</span>
                            <span id="estimasi_display" class="ml-2 font-bold text-blue-900">Rp 0</span>
                        </div>
                        <div id="selisih_container" class="hidden">
                            <span class="text-sm text-blue-700">Selisih:</span>
                            <span id="selisih" class="ml-2 font-bold text-red-600">Rp 0</span>
                        </div>
                    </div>
                </div>

                <p class="mt-4 text-sm text-gray-500 bg-gray-50 px-4 py-3 rounded-lg">
                    <svg class="w-5 h-5 inline-block mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Total per bulan harus sama dengan jumlah estimasi
                </p>
            </div>

            <!-- Catatan -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Catatan</h2>
                <textarea name="catatan" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Tambahkan catatan atau keterangan tambahan...">{{ old('catatan') }}</textarea>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-4">
                <a href="{{ route('perencanaan-penerimaan.index') }}" class="px-6 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Simpan Perencanaan
                </button>
            </div>
        </form>
    </div>

    <script>
        let availableMonths = [];

        // Currency Formatter
        function formatCurrency(value) {
            // Remove all non-numeric characters except decimal separator
            let cleanValue = value.replace(/[^0-9]/g, '');
            if (cleanValue === '') return '';
            // Convert to number and format with thousand separators
            let number = parseInt(cleanValue, 10);
            return number.toLocaleString('id-ID');
        }

        function parseCurrency(formattedValue) {
            // Remove all non-numeric characters
            let cleanValue = formattedValue.replace(/[^0-9]/g, '');
            return cleanValue === '' ? 0 : parseInt(cleanValue, 10);
        }

        function initCurrencyInputs() {
            // Initialize all currency inputs
            document.querySelectorAll('.currency-input').forEach(input => {
                const targetId = input.getAttribute('data-target');
                const targetInput = document.getElementById(targetId);

                // Set initial display value if target has value
                if (targetInput && targetInput.value) {
                    input.value = formatCurrency(targetInput.value);
                }

                input.addEventListener('input', function(e) {
                    let value = e.target.value;
                    let cursorPos = e.target.selectionStart;

                    // Format the value
                    let formatted = formatCurrency(value);
                    e.target.value = formatted;

                    // Update the target hidden input
                    if (targetInput) {
                        targetInput.value = parseCurrency(formatted);
                    }

                    // Restore cursor position
                    let lengthDiff = formatted.length - value.length;
                    e.target.setSelectionRange(cursorPos + lengthDiff, cursorPos + lengthDiff);

                    // Trigger updateDisplay if exists
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

        // Generate months based on selected periode anggaran
        function loadMonths() {
            const periodeId = document.getElementById('periode_anggaran_id').value;
            const container = document.getElementById('months-container');

            if (!periodeId) {
                container.innerHTML = `
                    <div class="col-span-full text-center py-8 text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p>Pilih periode anggaran untuk melihat daftar bulan</p>
                    </div>
                `;
                availableMonths = [];
                updateDisplay();
                return;
            }

            try {
                // Generate months from the selected option's data attributes
                const select = document.getElementById('periode_anggaran_id');
                const selectedOption = select.options[select.selectedIndex];
                const startDate = new Date(selectedOption.getAttribute('data-start'));
                const endDate = new Date(selectedOption.getAttribute('data-end'));

                availableMonths = [];
                const currentDate = new Date(startDate);

                while (currentDate <= endDate) {
                    const key = currentDate.toISOString().slice(0, 7); // YYYY-MM format
                    const label = currentDate.toLocaleDateString('id-ID', { year: 'numeric', month: 'long' });
                    availableMonths.push({ key, label });
                    currentDate.setMonth(currentDate.getMonth() + 1);
                }

                // Render month inputs
                renderMonthInputs();

            } catch (error) {
                console.error('Error loading months:', error);
            }
        }

        function renderMonthInputs() {
            const container = document.getElementById('months-container');

            if (availableMonths.length === 0) {
                container.innerHTML = `
                    <div class="col-span-full text-center py-8 text-gray-500">
                        <p>Periode anggaran tidak valid</p>
                    </div>
                `;
                return;
            }

            let html = '';
            availableMonths.forEach((month, index) => {
                const colors = [
                    'from-green-50 to-green-100 text-green-700',
                    'from-blue-50 to-blue-100 text-blue-700',
                    'from-amber-50 to-amber-100 text-amber-700',
                    'from-purple-50 to-purple-100 text-purple-700'
                ];
                const colorClass = colors[index % colors.length];

                html += `
                    <div class="bg-gradient-to-br ${colorClass} rounded-lg p-4">
                        <label class="block text-sm font-semibold mb-1">${month.label}</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-600 text-sm">Rp</span>
                            <input type="text"
                                   name="perkiraan_bulanan_display[${month.key}]"
                                   class="bulanan-input currency-input-month w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm text-right"
                                   placeholder="0"
                                   data-month-key="${month.key}"
                                   data-month="${month.label}">
                            <input type="hidden" name="perkiraan_bulanan[${month.key}]" id="bulanan_${month.key}" class="bulanan-hidden">
                        </div>
                    </div>
                `;
            });

            container.innerHTML = html;

            // Initialize currency inputs for monthly fields
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

                    // Format the value
                    let formatted = formatCurrency(value);
                    e.target.value = formatted;

                    // Update the hidden input
                    if (hiddenInput) {
                        hiddenInput.value = parseCurrency(formatted);
                    }

                    // Restore cursor position
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
                    selisihDisplay.classList.remove('text-green-600');
                } else {
                    selisihDisplay.classList.remove('text-red-600');
                    selisihDisplay.classList.add('text-green-600');
                }
            } else {
                selisihContainer.classList.add('hidden');
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            initCurrencyInputs();

            // Event listeners
            document.getElementById('periode_anggaran_id').addEventListener('change', loadMonths);
        });
    </script>
</x-app-layout>
