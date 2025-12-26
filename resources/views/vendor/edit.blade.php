<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-secondary-900">Edit Vendor</h1>
                <p class="text-secondary-600 mt-1">Ubah data vendor</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('vendors.show', $vendor) }}" class="inline-flex items-center px-4 py-2 bg-white border border-secondary-300 text-secondary-700 rounded-xl hover:bg-secondary-50 transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    Lihat Detail
                </a>
                <a href="{{ route('vendors.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-secondary-300 text-secondary-700 rounded-xl hover:bg-secondary-50 transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto py-8">
        <form method="POST" action="{{ route('vendors.update', $vendor) }}">
            @csrf
            @method('PUT')

            <!-- Basic Information -->
            <div class="bg-white rounded-2xl shadow-soft p-8 mb-6">
                <h2 class="text-lg font-semibold text-secondary-900 mb-6">Informasi Dasar</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Kode Vendor -->
                    <div>
                        <label class="block text-sm font-medium text-secondary-700 mb-2">Kode Vendor <span class="text-red-500">*</span></label>
                        <input type="text" name="kode_vendor" value="{{ old('kode_vendor', $vendor->kode_vendor) }}" required placeholder="Contoh: VENDOR-001" class="w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        @error('kode_vendor')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nama Vendor -->
                    <div>
                        <label class="block text-sm font-medium text-secondary-700 mb-2">Nama Vendor <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_vendor" value="{{ old('nama_vendor', $vendor->nama_vendor) }}" required placeholder="Nama perusahaan/vendor" class="w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        @error('nama_vendor')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jenis Vendor -->
                    <div>
                        <label class="block text-sm font-medium text-secondary-700 mb-2">Jenis Vendor <span class="text-red-500">*</span></label>
                        <select name="jenis_vendor" required class="w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            <option value="">Pilih Jenis Vendor</option>
                            <option value="supplier" {{ old('jenis_vendor', $vendor->jenis_vendor) === 'supplier' ? 'selected' : '' }}>Supplier</option>
                            <option value="kontraktor" {{ old('jenis_vendor', $vendor->jenis_vendor) === 'kontraktor' ? 'selected' : '' }}>Kontraktor</option>
                            <option value="konsultan" {{ old('jenis_vendor', $vendor->jenis_vendor) === 'konsultan' ? 'selected' : '' }}>Konsultan</option>
                            <option value="lainnya" {{ old('jenis_vendor', $vendor->jenis_vendor) === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('jenis_vendor')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- NPWP -->
                    <div>
                        <label class="block text-sm font-medium text-secondary-700 mb-2">NPWP</label>
                        <input type="text" name="npwp" value="{{ old('npwp', $vendor->npwp) }}" placeholder="XX.XXX.XXX.X-XXX.XXX" class="w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        @error('npwp')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Address -->
            <div class="bg-white rounded-2xl shadow-soft p-8 mb-6">
                <h2 class="text-lg font-semibold text-secondary-900 mb-6">Alamat</h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Alamat -->
                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium text-secondary-700 mb-2">Alamat Lengkap</label>
                        <textarea name="alamat" rows="2" class="w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="Nama jalan, nomor, gedung...">{{ old('alamat', $vendor->alamat) }}</textarea>
                    </div>

                    <!-- Kota -->
                    <div>
                        <label class="block text-sm font-medium text-secondary-700 mb-2">Kota</label>
                        <input type="text" name="kota" value="{{ old('kota', $vendor->kota) }}" class="w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="Nama kota">
                    </div>

                    <!-- Propinsi -->
                    <div>
                        <label class="block text-sm font-medium text-secondary-700 mb-2">Propinsi</label>
                        <input type="text" name="propinsi" value="{{ old('propinsi', $vendor->propinsi) }}" class="w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="Nama propinsi">
                    </div>

                    <!-- Kode Pos -->
                    <div>
                        <label class="block text-sm font-medium text-secondary-700 mb-2">Kode Pos</label>
                        <input type="text" name="kode_pos" value="{{ old('kode_pos', $vendor->kode_pos) }}" class="w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="XXXXX">
                    </div>

                    <!-- Negara -->
                    <div>
                        <label class="block text-sm font-medium text-secondary-700 mb-2">Negara</label>
                        <input type="text" name="negara" value="{{ old('negara', $vendor->negara) }}" class="w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="bg-white rounded-2xl shadow-soft p-8 mb-6">
                <h2 class="text-lg font-semibold text-secondary-900 mb-6">Informasi Kontak</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Telepon -->
                    <div>
                        <label class="block text-sm font-medium text-secondary-700 mb-2">Telepon</label>
                        <input type="text" name="telepon" value="{{ old('telepon', $vendor->telepon) }}" placeholder="08xxxxxxxxxx" class="w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-secondary-700 mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email', $vendor->email) }}" placeholder="email@vendor.com" class="w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kontak Person -->
                    <div>
                        <label class="block text-sm font-medium text-secondary-700 mb-2">Kontak Person</label>
                        <input type="text" name="kontak_person" value="{{ old('kontak_person', $vendor->kontak_person) }}" placeholder="Nama kontak person" class="w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    </div>
                </div>
            </div>

            <!-- Bank Information -->
            <div class="bg-white rounded-2xl shadow-soft p-8 mb-6">
                <h2 class="text-lg font-semibold text-secondary-900 mb-6">Informasi Bank</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Bank -->
                    <div>
                        <label class="block text-sm font-medium text-secondary-700 mb-2">Nama Bank</label>
                        <input type="text" name="nama_bank" value="{{ old('nama_bank', $vendor->nama_bank) }}" placeholder="Contoh: Bank BCA" class="w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    </div>

                    <!-- Nomor Rekening -->
                    <div>
                        <label class="block text-sm font-medium text-secondary-700 mb-2">Nomor Rekening</label>
                        <input type="text" name="nomor_rekening" value="{{ old('nomor_rekening', $vendor->nomor_rekening) }}" placeholder="Nomor rekening vendor" class="w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    </div>
                </div>
            </div>

            <!-- Additional Info -->
            <div class="bg-white rounded-2xl shadow-soft p-8 mb-6">
                <h2 class="text-lg font-semibold text-secondary-900 mb-6">Informasi Tambahan</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-secondary-700 mb-2">Status</label>
                        <select name="status" class="w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            <option value="active" {{ old('status', $vendor->status) === 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ old('status', $vendor->status) === 'inactive' ? 'selected' : '' }}>Non-Aktif</option>
                            <option value="blacklisted" {{ old('status', $vendor->status) === 'blacklisted' ? 'selected' : '' }}>Blacklist</option>
                        </select>
                    </div>

                    <!-- Rating -->
                    <div>
                        <label class="block text-sm font-medium text-secondary-700 mb-2">Rating (1-5)</label>
                        <input type="number" name="rating" value="{{ old('rating', $vendor->rating) }}" min="0" max="5" step="0.1" class="w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="0">
                    </div>

                    <!-- Catatan -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-secondary-700 mb-2">Catatan</label>
                        <textarea name="catatan" rows="3" class="w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="Catatan tambahan tentang vendor...">{{ old('catatan', $vendor->catatan) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-4">
                <a href="{{ route('vendors.show', $vendor) }}" class="px-6 py-3 bg-white border border-secondary-300 text-secondary-700 rounded-xl hover:bg-secondary-50 transition-all duration-200">
                    Batal
                </a>
                <button type="submit" class="px-6 py-3 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-all duration-200 shadow-soft hover:shadow-medium">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
