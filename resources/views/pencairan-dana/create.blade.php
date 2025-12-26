<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('pencairan-dana.index') }}" class="p-2 text-secondary-600 hover:text-secondary-900 hover:bg-secondary-100 rounded-lg transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-secondary-900">Buat Pencairan Dana Baru</h1>
                <p class="text-secondary-600 mt-1">Proses pencairan dana untuk pengajuan yang disetujui</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto py-8">
        <!-- Alert: Only approved pengajuan -->
        <div class="mb-6 bg-blue-50 border border-blue-200 rounded-xl p-4 flex items-start">
            <svg class="w-5 h-5 text-blue-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div class="text-blue-700 text-sm">
                Hanya pengajuan dengan status <strong>disetujui</strong> yang dapat dicairkan.
            </div>
        </div>

        <form method="POST" action="{{ route('pencairan-dana.store') }}" class="bg-white rounded-2xl shadow-soft p-8">
            @csrf

            @error('pengajuan_dana_id')
                <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4 flex items-start">
                    <svg class="w-5 h-5 text-red-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="text-red-700 text-sm">{{ $message }}</div>
                </div>
            @enderror

            <!-- Pilih Pengajuan -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-secondary-900 mb-4 flex items-center">
                    <span class="w-8 h-8 bg-primary-100 text-primary-600 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                    </span>
                    Pilih Pengajuan Dana
                </h2>

                <div>
                    <x-input-label for="pengajuan_dana_id" value="Pengajuan Dana *" />
                    <select name="pengajuan_dana_id" id="pengajuan_dana_id" required
                        class="mt-1 block w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                        onchange="loadPengajuanDetails(this.value)">
                        <option value="">Pilih Pengajuan Dana</option>
                        @foreach($pengajuans ?? [] as $pengajuan)
                            @if($pengajuan->status === 'approved' && (!$pengajuan->pencairanDana || $pengajuan->pencairanDana->status === 'cancelled'))
                            <option value="{{ $pengajuan->id }}" data-jumlah="{{ $pengajuan->total_pengajuan }}" data-metode="{{ $pengajuan->metode_pencairan }}">
                                {{ $pengajuan->nomor_pengajuan }} - {{ $pengajuan->judul_pengajuan }} ({{ formatRupiah($pengajuan->total_pengajuan) }})
                            </option>
                            @endif
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('pengajuan_dana_id')" class="mt-2" />
                </div>

                <!-- Pengajuan Details Preview -->
                <div id="pengajuan-details" class="mt-4 p-4 bg-secondary-50 rounded-xl hidden">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-secondary-500">Judul Pengajuan</p>
                            <p id="detail-judul" class="font-medium text-secondary-900">-</p>
                        </div>
                        <div>
                            <p class="text-sm text-secondary-500">Divisi</p>
                            <p id="detail-divisi" class="font-medium text-secondary-900">-</p>
                        </div>
                        <div>
                            <p class="text-sm text-secondary-500">Total Pengajuan</p>
                            <p id="detail-total" class="font-bold text-primary-600">-</p>
                        </div>
                        <div>
                            <p class="text-sm text-secondary-500">Metode Pencairan</p>
                            <p id="detail-metode" class="font-medium text-secondary-900">-</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detail Pencairan -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-secondary-900 mb-4 flex items-center">
                    <span class="w-8 h-8 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </span>
                    Detail Pencairan
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="jumlah_pencairan" value="Jumlah Pencairan *" />
                        <input type="number" name="jumlah_pencairan" id="jumlah_pencairan" value="{{ old('jumlah_pencairan') }}" min="0" step="0.01" required
                            class="mt-1 block w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                            placeholder="0.00">
                        <x-input-error :messages="$errors->get('jumlah_pencairan')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="tanggal_pencairan" value="Tanggal Pencairan *" />
                        <input type="date" name="tanggal_pencairan" id="tanggal_pencairan" value="{{ old('tanggal_pencairan') }}" required
                            class="mt-1 block w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <x-input-error :messages="$errors->get('tanggal_pencairan')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="metode_pencairan" value="Metode Pencairan *" />
                        <select name="metode_pencairan" id="metode_pencairan" required
                            class="mt-1 block w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            <option value="">Pilih Metode</option>
                            <option value="transfer" {{ old('metode_pencairan') === 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                            <option value="cash" {{ old('metode_pencairan') === 'cash' ? 'selected' : '' }}>Uang Tunai</option>
                            <option value="reimburse" {{ old('metode_pencairan') === 'reimburse' ? 'selected' : '' }}>Reimburse</option>
                        </select>
                        <x-input-error :messages="$errors->get('metode_pencairan')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="status" value="Status" />
                        <select name="status" id="status"
                            class="mt-1 block w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            <option value="pending" {{ old('status', 'pending') === 'pending' ? 'selected' : '' }}>Menunggu Proses</option>
                            <option value="processing" {{ old('status') === 'processing' ? 'selected' : '' }}>Sedang Diproses</option>
                            <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                        </select>
                        <x-input-error :messages="$errors->get('status')" class="mt-2" />
                    </div>
                </div>
            </div>

            <!-- Informasi Rekening (untuk transfer) -->
            <div id="rekening-section" class="mb-8 hidden">
                <h2 class="text-lg font-semibold text-secondary-900 mb-4 flex items-center">
                    <span class="w-8 h-8 bg-green-100 text-green-600 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    </span>
                    Informasi Rekening Tujuan
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="nama_bank" value="Nama Bank" />
                        <input type="text" name="nama_bank" id="nama_bank" value="{{ old('nama_bank') }}"
                            class="mt-1 block w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                            placeholder="Contoh: BCA, Mandiri, BNI">
                        <x-input-error :messages="$errors->get('nama_bank')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="nomor_rekening" value="Nomor Rekening" />
                        <input type="text" name="nomor_rekening" id="nomor_rekening" value="{{ old('nomor_rekening') }}"
                            class="mt-1 block w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                            placeholder="Nomor rekening tujuan">
                        <x-input-error :messages="$errors->get('nomor_rekening')" class="mt-2" />
                    </div>

                    <div class="md:col-span-2">
                        <x-input-label for="atas_nama" value="Atas Nama" />
                        <input type="text" name="atas_nama" id="atas_nama" value="{{ old('atas_nama') }}"
                            class="mt-1 block w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                            placeholder="Nama pemilik rekening">
                        <x-input-error :messages="$errors->get('atas_nama')" class="mt-2" />
                    </div>
                </div>
            </div>

            <!-- Catatan -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-secondary-900 mb-4 flex items-center">
                    <span class="w-8 h-8 bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </span>
                    Catatan
                </h2>

                <div>
                    <x-input-label for="catatan" value="Catatan Pencairan" />
                    <textarea name="catatan" id="catatan" rows="3"
                        class="mt-1 block w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                        placeholder="Tambahkan catatan untuk pencairan ini...">{{ old('catatan') }}</textarea>
                    <x-input-error :messages="$errors->get('catatan') class="mt-2" />
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-secondary-200">
                <a href="{{ route('pencairan-dana.index') }}" class="px-6 py-3 border border-secondary-200 text-secondary-700 rounded-xl hover:bg-secondary-50 transition-all duration-200">
                    Batal
                </a>
                <button type="submit" class="px-6 py-3 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-all duration-200 shadow-soft hover:shadow-medium">
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Simpen Pencairan
                    </span>
                </button>
            </div>
        </form>
    </div>

    <script>
        const pengajuanData = @json($pengajuans ?? []);

        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
        }

        function loadPengajuanDetails(pengajuanId) {
            const pengajuan = pengajuanData.find(p => p.id == pengajuanId);
            const detailsDiv = document.getElementById('pengajuan-details');

            if (pengajuan) {
                document.getElementById('detail-judul').textContent = pengajuan.judul_pengajuan;
                document.getElementById('detail-divisi').textContent = pengajuan.divisi?.nama_divisi || '-';
                document.getElementById('detail-total').textContent = formatRupiah(pengajuan.total_pengajuan);
                document.getElementById('detail-metode').textContent = pengajuan.metode_pencairan || '-';

                document.getElementById('jumlah_pencairan').value = pengajuan.total_pengajuan;
                document.getElementById('metode_pencairan').value = pengajuan.metode_pencairan || 'transfer';

                detailsDiv.classList.remove('hidden');
                toggleRekeningSection();
            } else {
                detailsDiv.classList.add('hidden');
            }
        }

        function toggleRekeningSection() {
            const metode = document.getElementById('metode_pencairan').value;
            const rekeningSection = document.getElementById('rekening-section');

            if (metode === 'transfer') {
                rekeningSection.classList.remove('hidden');
            } else {
                rekeningSection.classList.add('hidden');
            }
        }

        document.getElementById('metode_pencairan')?.addEventListener('change', toggleRekeningSection);

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            const pengajuanSelect = document.getElementById('pengajuan_dana_id');
            if (pengajuanSelect && pengajuanSelect.value) {
                loadPengajuanDetails(pengajuanSelect.value);
            }
        });
    </script>
</x-app-layout>
