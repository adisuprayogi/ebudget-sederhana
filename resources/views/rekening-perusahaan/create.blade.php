<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Tambah Rekening Perusahaan</h1>
                <p class="text-slate-600 mt-1">Isi formulir di bawah untuk menambah rekening perusahaan baru</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="mb-4">
            <a href="{{ route('rekening-perusahaan.index') }}"
               class="inline-flex items-center text-slate-600 hover:text-slate-900">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali ke Daftar Rekening
            </a>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <form action="{{ route('rekening-perusahaan.store') }}" method="POST">
                @csrf

                <!-- Bank -->
                <div class="mb-6">
                    <label for="bank_id" class="block text-sm font-medium text-slate-700 mb-2">
                        Bank <span class="text-red-500">*</span>
                    </label>
                    <select name="bank_id" id="bank_id" required
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('bank_id') border-red-500 @enderror">
                        <option value="">-- Pilih Bank --</option>
                        @foreach($banks as $bank)
                            <option value="{{ $bank->id }}" {{ old('bank_id') == $bank->id ? 'selected' : '' }}>
                                {{ $bank->nama_bank }}
                            </option>
                        @endforeach
                    </select>
                    @error('bank_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nomor Rekening -->
                <div class="mb-6">
                    <label for="nomor_rekening" class="block text-sm font-medium text-slate-700 mb-2">
                        Nomor Rekening <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nomor_rekening" id="nomor_rekening"
                           value="{{ old('nomor_rekening') }}"
                           placeholder="Contoh: 1234567890"
                           required
                           class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nomor_rekening') border-red-500 @enderror">
                    @error('nomor_rekening')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Atas Nama -->
                <div class="mb-6">
                    <label for="atas_nama" class="block text-sm font-medium text-slate-700 mb-2">
                        Atas Nama <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="atas_nama" id="atas_nama"
                           value="{{ old('atas_nama') }}"
                           placeholder="Contoh: PT Contoh Indonesia"
                           required
                           class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('atas_nama') border-red-500 @enderror">
                    @error('atas_nama')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Cabang -->
                <div class="mb-6">
                    <label for="cabang" class="block text-sm font-medium text-slate-700 mb-2">
                        Cabang
                    </label>
                    <input type="text" name="cabang" id="cabang"
                           value="{{ old('cabang') }}"
                           placeholder="Contoh: Jakarta Pusat"
                           class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('cabang') border-red-500 @enderror">
                    @error('cabang')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Mata Uang -->
                <div class="mb-6">
                    <label for="mata_uang" class="block text-sm font-medium text-slate-700 mb-2">
                        Mata Uang
                    </label>
                    <select name="mata_uang" id="mata_uang"
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('mata_uang') border-red-500 @enderror">
                        <option value="IDR" {{ old('mata_uang', 'IDR') == 'IDR' ? 'selected' : '' }}>IDR - Rupiah</option>
                        <option value="USD" {{ old('mata_uang') == 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                        <option value="EUR" {{ old('mata_uang') == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                        <option value="SGD" {{ old('mata_uang') == 'SGD' ? 'selected' : '' }}>SGD - Singapore Dollar</option>
                    </select>
                    @error('mata_uang')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Saldo Awal -->
                <div class="mb-6">
                    <label for="saldo_awal" class="block text-sm font-medium text-slate-700 mb-2">
                        Saldo Awal
                    </label>
                    <input type="number" name="saldo_awal" id="saldo_awal"
                           value="{{ old('saldo_awal', 0) }}"
                           step="0.01"
                           min="0"
                           placeholder="0"
                           class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('saldo_awal') border-red-500 @enderror">
                    @error('saldo_awal')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Toggles -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                    <!-- Is Default -->
                    <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl">
                        <div>
                            <label for="is_default" class="block text-sm font-medium text-slate-700">
                                Jadikan Default
                            </label>
                            <p class="text-xs text-slate-500 mt-1">Rekening ini akan dipilih otomatis</p>
                        </div>
                        <input type="checkbox" name="is_default" id="is_default" value="1"
                               {{ old('is_default') ? 'checked' : '' }}
                               class="w-5 h-5 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                    </div>

                    <!-- Is Active -->
                    <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl">
                        <div>
                            <label for="is_active" class="block text-sm font-medium text-slate-700">
                                Status Aktif
                            </label>
                            <p class="text-xs text-slate-500 mt-1">Rekening dapat digunakan untuk transaksi</p>
                        </div>
                        <input type="checkbox" name="is_active" id="is_active" value="1"
                               {{ old('is_active', '1') ? 'checked' : '' }}
                               class="w-5 h-5 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                    </div>
                </div>

                <!-- Catatan -->
                <div class="mb-6">
                    <label for="catatan" class="block text-sm font-medium text-slate-700 mb-2">
                        Catatan
                    </label>
                    <textarea name="catatan" id="catatan" rows="3"
                              placeholder="Catatan tambahan..."
                              class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('catatan') border-red-500 @enderror">{{ old('catatan') }}</textarea>
                    @error('catatan')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row gap-4 justify-end pt-4 border-t border-slate-200">
                    <a href="{{ route('rekening-perusahaan.index') }}"
                       class="inline-flex items-center justify-center px-6 py-3 border border-slate-300 text-slate-700 font-medium rounded-xl hover:bg-slate-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                            class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition-colors shadow-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Rekening
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
