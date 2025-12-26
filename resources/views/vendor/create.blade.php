<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-secondary-900">Tambah Vendor Baru</h1>
                <p class="text-secondary-600 mt-1">Registrasi vendor baru</p>
            </div>
            <a href="{{ route('vendors.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-secondary-300 text-secondary-700 rounded-xl hover:bg-secondary-50 transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto py-8">
        <form method="POST" action="{{ route('vendors.store') }}">
            @csrf

            <!-- Basic Information -->
            <div class="bg-white rounded-2xl shadow-soft p-8 mb-6">
                <h2 class="text-lg font-semibold text-secondary-900 mb-6">Informasi Dasar</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Kode Vendor -->
                    <div>
                        <label class="block text-sm font-medium text-secondary-700 mb-2">Kode Vendor <span class="text-red-500">*</span></label>
                        <input type="text" name="kode_vendor" value="{{ old('kode_vendor') }}" required placeholder="Contoh: VENDOR-001" class="w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        @error('kode_vendor')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nama Vendor -->
                    <div>
                        <label class="block text-sm font-medium text-secondary-700 mb-2">Nama Vendor <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_vendor" value="{{ old('nama_vendor') }}" required placeholder="Nama perusahaan/vendor" class="w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        @error('nama_vendor')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jenis Vendor -->
                    <div>
                        <label class="block text-sm font-medium text-secondary-700 mb-2">Jenis Vendor <span class="text-red-500">*</span></label>
                        <select name="jenis_vendor" required class="w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            <option value="">Pilih Jenis Vendor</option>
                            <option value="supplier" {{ old('jenis_vendor') === 'supplier' ? 'selected' : '' }}>Supplier</option>
                            <option value="kontraktor" {{ old('jenis_vendor') === 'kontraktor' ? 'selected' : '' }}>Kontraktor</option>
                            <option value="konsultan" {{ old('jenis_vendor') === 'konsultan' ? 'selected' : '' }}>Konsultan</option>
                            <option value="lainnya" {{ old('jenis_vendor') === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('jenis_vendor')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- NPWP -->
                    <div>
                        <label class="block text-sm font-medium text-secondary-700 mb-2">NPWP</label>
                        <input type="text" name="npwp" value="{{ old('npwp') }}" placeholder="XX.XXX.XXX.X-XXX.XXX" class="w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
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
                        <textarea name="alamat" rows="2" class="w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="Nama jalan, nomor, gedung...">{{ old('alamat') }}</textarea>
                    </div>

                    <!-- Kota -->
                    <div>
                        <label class="block text-sm font-medium text-secondary-700 mb-2">Kota</label>
                        <input type="text" name="kota" value="{{ old('kota') }}" class="w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="Nama kota">
                    </div>

                    <!-- Propinsi -->
                    <div>
                        <label class="block text-sm font-medium text-secondary-700 mb-2">Propinsi</label>
                        <input type="text" name="propinsi" value="{{ old('propinsi') }}" class="w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="Nama propinsi">
                    </div>

                    <!-- Kode Pos -->
                    <div>
                        <label class="block text-sm font-medium text-secondary-700 mb-2">Kode Pos</label>
                        <input type="text" name="kode_pos" value="{{ old('kode_pos') }}" class="w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="XXXXX">
                    </div>

                    <!-- Negara -->
                    <div>
                        <label class="block text-sm font-medium text-secondary-700 mb-2">Negara</label>
                        <input type="text" name="negara" value="{{ old('negara', 'Indonesia') }}" class="w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
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
                        <input type="text" name="telepon" value="{{ old('telepon') }}" placeholder="08xxxxxxxxxx" class="w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-secondary-700 mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="email@vendor.com" class="w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kontak Person -->
                    <div>
                        <label class="block text-sm font-medium text-secondary-700 mb-2">Kontak Person</label>
                        <input type="text" name="kontak_person" value="{{ old('kontak_person') }}" placeholder="Nama kontak person" class="w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
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
                        <input type="text" name="nama_bank" value="{{ old('nama_bank') }}" placeholder="Contoh: Bank BCA" class="w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    </div>

                    <!-- Nomor Rekening -->
                    <div>
                        <label class="block text-sm font-medium text-secondary-700 mb-2">Nomor Rekening</label>
                        <input type="text" name="nomor_rekening" value="{{ old('nomor_rekening') }}" placeholder="Nomor rekening vendor" class="w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
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
                            <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Non-Aktif</option>
                            <option value="blacklisted" {{ old('status') === 'blacklisted' ? 'selected' : '' }}>Blacklist</option>
                        </select>
                    </div>

                    <!-- Rating -->
                    <div>
                        <label class="block text-sm font-medium text-secondary-700 mb-2">Rating (1-5)</label>
                        <input type="number" name="rating" value="{{ old('rating', 0) }}" min="0" max="5" step="0.1" class="w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="0">
                    </div>

                    <!-- Catatan -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-secondary-700 mb-2">Catatan</label>
                        <textarea name="catatan" rows="3" class="w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="Catatan tambahan tentang vendor...">{{ old('catatan') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-4">
                <a href="{{ route('vendors.index') }}" class="px-6 py-3 bg-white border border-secondary-300 text-secondary-700 rounded-xl hover:bg-secondary-50 transition-all duration-200">
                    Batal
                </a>
                <button type="submit" class="px-6 py-3 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-all duration-200 shadow-soft hover:shadow-medium">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan Vendor
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
