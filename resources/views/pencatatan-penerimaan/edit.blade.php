<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Pencatatan Penerimaan</h1>
                <p class="text-gray-600 mt-1">Ubah data pencatatan penerimaan dana</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('pencatatan-penerimaan.show', $pencatatan) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    Lihat Detail
                </a>
                @if($pencatatan->perencanaanPenerimaan)
                    <a href="{{ route('perencanaan-penerimaan.show', $pencatatan->perencanaanPenerimaan) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali ke Perencanaan
                    </a>
                @else
                    <a href="{{ route('pencatatan-penerimaan.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto py-8">
        <form method="POST" action="{{ route('pencatatan-penerimaan.update', $pencatatan) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-6">Informasi Penerimaan</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Referensi Perencanaan -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Referensi Perencanaan</label>
                        <select name="perencanaan_penerimaan_id" id="perencanaan_penerimaan_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Pilih Perencanaan</option>
                            @foreach($perencanaanPenerimaans ?? [] as $perencanaan)
                                <option value="{{ $perencanaan->id }}"
                                        data-periode-anggaran-id="{{ $perencanaan->periode_anggaran_id }}"
                                        data-periode-anggaran-name="{{ $perencanaan->periodeAnggaran->nama_periode ?? '-' }} ({{ $perencanaan->periodeAnggaran->tahun_anggaran ?? '-' }})"
                                        data-sumber-dana-id="{{ $perencanaan->sumber_dana_id }}"
                                        {{ old('perencanaan_penerimaan_id', $pencatatan->perencanaan_penerimaan_id) == $perencanaan->id ? 'selected' : '' }}>
                                    {{ $perencanaan->uraian }} - {{ $perencanaan->periodeAnggaran->nama_periode ?? '-' }}
                                </option>
                            @endforeach
                        </select>
                        @error('perencanaan_penerimaan_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Periode Anggaran (Hidden, auto-filled from perencanaan) -->
                    <input type="hidden" name="periode_anggaran_id" id="periode_anggaran_id" value="{{ old('periode_anggaran_id', $pencatatan->periode_anggaran_id) }}">

                    <!-- Display Periode Anggaran (read-only) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Periode Anggaran</label>
                        <div class="px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700">
                            <span id="periode_anggaran_display">{{ $pencatatan->periodeAnggaran->nama_periode ?? '-' }} ({{ $pencatatan->periodeAnggaran->tahun_anggaran ?? '-' }})</span>
                        </div>
                    </div>

                    <!-- Sumber Dana (Hidden, auto-filled from perencanaan) -->
                    <input type="hidden" name="sumber_dana_id" id="sumber_dana_id" value="{{ old('sumber_dana_id', $pencatatan->sumber_dana_id) }}">

                    <!-- Display Sumber Dana (read-only) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sumber Dana</label>
                        <div class="px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700">
                            <span id="sumber_dana_display">{{ $pencatatan->sumberDana->nama_sumber ?? '-' }}</span>
                        </div>
                    </div>

                    <!-- Tanggal Penerimaan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Penerimaan <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_penerimaan" value="{{ old('tanggal_penerimaan', $pencatatan->tanggal_penerimaan?->format('Y-m-d')) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('tanggal_penerimaan')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jumlah Diterima -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Diterima <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-medium">Rp</span>
                            <input type="text" id="jumlah_diterima_display" class="currency-input w-full pl-12 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-right" placeholder="0" data-target="jumlah_diterima" value="{{ $pencatatan->jumlah_diterima }}">
                            <input type="hidden" name="jumlah_diterima" id="jumlah_diterima" value="{{ old('jumlah_diterima', $pencatatan->jumlah_diterima) }}">
                        </div>
                        @error('jumlah_diterima')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Uraian -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Uraian Penerimaan <span class="text-red-500">*</span></label>
                        <textarea name="uraian" rows="3" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Jelaskan sumber dan tujuan penerimaan dana...">{{ old('uraian', $pencatatan->uraian) }}</textarea>
                        @error('uraian')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Bukti Penerimaan -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bukti Penerimaan</label>
                        @if($pencatatan->bukti_penerimaan)
                            <div class="flex items-center justify-between bg-gray-50 rounded-lg px-4 py-3 mb-2">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    <span class="text-sm text-gray-700">{{ basename($pencatatan->bukti_penerimaan) }}</span>
                                </div>
                                <a href="{{ asset('storage/' . $pencatatan->bukti_penerimaan) }}" target="_blank" class="text-blue-600 hover:underline text-sm">Lihat</a>
                            </div>
                        @endif
                        <div class="flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-8 w-8 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500">
                                        <span>Ganti file</span>
                                        <input type="file" name="bukti_penerimaan" class="sr-only" accept=".pdf,.jpg,.jpeg,.png">
                                    </label>
                                </div>
                                <p class="text-xs text-gray-500">PDF, JPG, PNG hingga 5MB</p>
                            </div>
                        </div>
                        @error('bukti_penerimaan')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-4">
                <a href="{{ route('pencatatan-penerimaan.show', $pencatatan) }}" class="px-6 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const perencanaanSelect = document.getElementById('perencanaan_penerimaan_id');
            const periodeAnggaranInput = document.getElementById('periode_anggaran_id');
            const periodeAnggaranDisplay = document.getElementById('periode_anggaran_display');
            const sumberDanaInput = document.getElementById('sumber_dana_id');
            const sumberDanaDisplay = document.getElementById('sumber_dana_display');

            // Data sumber dana for lookup
            const sumberDanaData = @json($sumberDanas->map(function($s) {
                return ['id' => $s->id, 'nama_sumber' => $s->nama_sumber];
            }));

            perencanaanSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const periodeAnggaranId = selectedOption.getAttribute('data-periode-anggaran-id');
                const periodeAnggaranName = selectedOption.getAttribute('data-periode-anggaran-name');
                const sumberDanaId = selectedOption.getAttribute('data-sumber-dana-id');

                if (periodeAnggaranId) {
                    periodeAnggaranInput.value = periodeAnggaranId;
                    periodeAnggaranDisplay.textContent = periodeAnggaranName || '-';
                }

                if (sumberDanaId) {
                    sumberDanaInput.value = sumberDanaId;
                    const sumberDana = sumberDanaData.find(s => s.id == sumberDanaId);
                    sumberDanaDisplay.textContent = sumberDana ? sumberDana.nama_sumber : '-';
                }
            });
        });
    </script>
</x-app-layout>
