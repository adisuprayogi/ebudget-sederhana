<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('pengajuan-dana.index') }}" class="p-2 text-secondary-600 hover:text-secondary-900 hover:bg-secondary-100 rounded-lg transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-secondary-900">Buat Pengajuan Dana Baru</h1>
                <p class="text-secondary-600 mt-1">Formulir pengajuan dana untuk keperluan operasional</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto py-8">
        <form method="POST" action="{{ route('pengajuan-dana.store') }}" enctype="multipart/form-data" x-data="pengajuanForm">
            @csrf
            
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
                            <x-input-label for="program_kerja_id" value="Program Kerja (Opsional)" />
                            <select name="program_kerja_id" id="program_kerja_id"
                                class="mt-1 block w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                <option value="">Pilih Program Kerja</option>
                                {{-- Add program options dynamically --}}
                            </select>
                            <x-input-error :messages="$errors->get('program_kerja_id')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="tanggal_pengajuan" value="Tanggal Pengajuan *" />
                            <input type="date" name="tanggal_pengajuan" id="tanggal_pengajuan" value="{{ old('tanggal_pengajuan') ?: now()->format('Y-m-d') }}" required
                                class="mt-1 block w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            <x-input-error :messages="$errors->get('tanggal_pengajuan')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="jenis_pengajuan" value="Jenis Pengajuan *" />
                            <select name="jenis_pengajuan" id="jenis_pengajuan" required
                                class="mt-1 block w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                <option value="">Pilih Jenis Pengajuan</option>
                                <option value="operasional" {{ old('jenis_pengajuan') == 'operasional' ? 'selected' : '' }}>Operasional</option>
                                <option value="investasi" {{ old('jenis_pengajuan') == 'investasi' ? 'selected' : '' }}>Investasi</option>
                                <option value="program_kerja" {{ old('jenis_pengajuan') == 'program_kerja' ? 'selected' : '' }}>Program Kerja</option>
                                <option value="kegiatan_khusus" {{ old('jenis_pengajuan') == 'kegiatan_khusus' ? 'selected' : '' }}>Kegiatan Khusus</option>
                                <option value="lainnya" {{ old('jenis_pengajuan') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            <x-input-error :messages="$errors->get('jenis_pengajuan')" class="mt-2" />
                        </div>

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
                        <button type="button" x-on:click="addDetail()" class="px-4 py-2 bg-primary-600 text-white rounded-xl text-sm hover:bg-primary-700 transition-all">
                            + Tambah Item
                        </button>
                    </h2>

                    <div class="overflow-x-auto">
                        <table class="w-full" id="detail-table">
                            <thead class="bg-secondary-50">
                                <tr>
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
                                    <td class="px-4 py-3">
                                        <input type="text" name="details[0][uraian]" placeholder="Nama barang/jasa" required
                                            class="w-full px-3 py-2 border border-secondary-200 rounded-lg focus:ring-2 focus:ring-primary-500 text-sm">
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="number" name="details[0][volume]" placeholder="0" required
                                            class="w-24 px-3 py-2 border border-secondary-200 rounded-lg focus:ring-2 focus:ring-primary-500 text-sm">
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="text" name="details[0][satuan]" placeholder="pcs, kg, meter" required
                                            class="w-20 px-3 py-2 border border-secondary-200 rounded-lg focus:ring-2 focus:ring-primary-500 text-sm">
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="number" name="details[0][harga_satuan]" placeholder="0" required
                                            class="w-32 px-3 py-2 border border-secondary-200 rounded-lg focus:ring-2 focus:ring-primary-500 text-sm">
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="detail-total font-semibold">Rp 0</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <button type="button" x-on:click="removeDetail(0)" class="text-red-600 hover:text-red-800">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot class="bg-secondary-50">
                                <tr>
                                    <td colspan="4" class="px-4 py-3 text-right font-semibold text-secondary-700">Total Pengajuan:</td>
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

                        <div>
                            <x-input-label for="penerima_manfaat_id" value="Nama Penerima *" />
                            <select name="penerima_manfaat_id" id="penerima_manfaat_id" required
                                class="mt-1 block w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                <option value="">Pilih Penerima</option>
                                {{-- Add penerima options dynamically --}}
                            </select>
                            <x-input-error :messages="$errors->get('penerima_manfaat_id')" class="mt-2" />
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
                            <x-input-label for="file_penawaran" value="File Penawaran (Opsional)" />
                            <input type="file" name="file_penawaran" id="file_penawaran" accept=".pdf,.doc,.docx"
                                class="mt-1 block w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent text-sm file:mr-4 file:py-2 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-primary-600 file:text-white hover:file:bg-primary-700">
                            <p class="mt-1 text-xs text-secondary-500">PDF, DOC, atau DOCX (Maks 5MB)</p>
                            <x-input-error :messages="$errors->get('file_penawaran')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="file_kerjasama" value="File Kerjasama (Opsional)" />
                            <input type="file" name="file_kerjasama" id="file_kerjasama" accept=".pdf,.doc,.docx"
                                class="mt-1 block w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent text-sm file:mr-4 file:py-2 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-primary-600 file:text-white hover:file:bg-primary-700">
                            <p class="mt-1 text-xs text-secondary-500">PDF, DOC, atau DOCX (Maks 5MB)</p>
                            <x-input-error :messages="$errors->get('file_kerjasama')" class="mt-2" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-secondary-200 mt-8">
                <a href="{{ route('pengajuan-dana.index') }}" class="px-6 py-3 border border-secondary-200 text-secondary-700 rounded-xl hover:bg-secondary-50 transition-all duration-200">
                    Batal
                </a>
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
        function pengajuanForm() {
            return {
                detailCount: 1,

                addDetail() {
                    const tbody = document.getElementById('detail-body');
                    const index = this.detailCount++;
                    
                    const row = document.createElement('tr');
                    row.className = 'detail-row';
                    row.innerHTML = `
                        <td class="px-4 py-3">
                            <input type="text" name="details[${index}][uraian]" placeholder="Nama barang/jasa" required
                                class="w-full px-3 py-2 border border-secondary-200 rounded-lg focus:ring-2 focus:ring-primary-500 text-sm">
                        </td>
                        <td class="px-4 py-3">
                            <input type="number" name="details[${index}][volume]" placeholder="0" required x-on:change="calculateTotal()"
                                class="w-24 px-3 py-2 border border-secondary-200 rounded-lg focus:ring-2 focus:ring-primary-500 text-sm">
                        </td>
                        <td class="px-4 py-3">
                            <input type="text" name="details[${index}][satuan]" placeholder="pcs, kg, meter" required
                                class="w-20 px-3 py-2 border border-secondary-200 rounded-lg focus:ring-2 focus:ring-primary-500 text-sm">
                        </td>
                        <td class="px-4 py-3">
                            <input type="number" name="details[${index}][harga_satuan]" placeholder="0" required x-on:change="calculateTotal()"
                                class="w-32 px-3 py-2 border border-secondary-200 rounded-lg focus:ring-2 focus:ring-primary-500 text-sm">
                        </td>
                        <td class="px-4 py-3">
                            <span class="detail-total font-semibold">Rp 0</span>
                        </td>
                        <td class="px-4 py-3">
                            <button type="button" x-on:click="removeDetail(${index})" class="text-red-600 hover:text-red-800">
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
                }
            }
        }
    </script>
</x-app-layout>
