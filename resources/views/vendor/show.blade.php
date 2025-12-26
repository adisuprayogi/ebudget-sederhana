<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-secondary-900">Detail Vendor</h1>
                <p class="text-secondary-600 mt-1">Informasi lengkap vendor</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('vendors.edit', $vendor) }}" class="inline-flex items-center px-4 py-2 bg-amber-500 text-white rounded-xl hover:bg-amber-600 transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
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

    <div class="max-w-6xl mx-auto py-8">
        <!-- Status & Info Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-2xl shadow-soft p-6">
                <div class="text-sm text-secondary-500 mb-1">Status</div>
                @if($vendor->status === 'active')
                    <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-700">Aktif</div>
                @elseif($vendor->status === 'inactive')
                    <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-slate-100 text-slate-700">Non-Aktif</div>
                @elseif($vendor->status === 'blacklisted')
                    <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-700">Blacklist</div>
                @endif
            </div>
            <div class="bg-white rounded-2xl shadow-soft p-6">
                <div class="text-sm text-secondary-500 mb-1">Jenis Vendor</div>
                <div class="text-xl font-bold text-primary-600">{{ ucfirst($vendor->jenis_vendor) }}</div>
            </div>
            <div class="bg-white rounded-2xl shadow-soft p-6">
                <div class="text-sm text-secondary-500 mb-1">Rating</div>
                <div class="flex items-center">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="w-6 h-6 {{ $i <= $vendor->rating ? 'text-amber-400' : 'text-secondary-200' }}" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    @endfor
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-soft p-6">
                <div class="text-sm text-secondary-500 mb-1">Kode Vendor</div>
                <div class="text-xl font-bold text-primary-600 font-mono">{{ $vendor->kode_vendor }}</div>
            </div>
        </div>

        <!-- Detail Vendor -->
        <div class="bg-white rounded-2xl shadow-soft p-8 mb-6">
            <h2 class="text-lg font-semibold text-secondary-900 mb-6">Informasi Vendor</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-sm text-secondary-500">Nama Vendor</label>
                    <div class="mt-1 font-medium text-secondary-900">{{ $vendor->nama_vendor }}</div>
                </div>
                <div>
                    <label class="text-sm text-secondary-500">NPWP</label>
                    <div class="mt-1 text-secondary-900">{{ $vendor->npwp ?? '-' }}</div>
                </div>
                <div class="md:col-span-2">
                    <label class="text-sm text-secondary-500">Alamat</label>
                    <div class="mt-1 text-secondary-900">{{ $vendor->alamat ?? '-' }}</div>
                </div>
                <div>
                    <label class="text-sm text-secondary-500">Kota</label>
                    <div class="mt-1 text-secondary-900">{{ $vendor->kota ?? '-' }}</div>
                </div>
                <div>
                    <label class="text-sm text-secondary-500">Propinsi</label>
                    <div class="mt-1 text-secondary-900">{{ $vendor->propinsi ?? '-' }}</div>
                </div>
                <div>
                    <label class="text-sm text-secondary-500">Kode Pos</label>
                    <div class="mt-1 text-secondary-900">{{ $vendor->kode_pos ?? '-' }}</div>
                </div>
                <div>
                    <label class="text-sm text-secondary-500">Negara</label>
                    <div class="mt-1 text-secondary-900">{{ $vendor->negara ?? '-' }}</div>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="bg-white rounded-2xl shadow-soft p-8 mb-6">
            <h2 class="text-lg font-semibold text-secondary-900 mb-6">Informasi Kontak</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-sm text-secondary-500">Telepon</label>
                    <div class="mt-1 text-secondary-900">{{ $vendor->telepon ?? '-' }}</div>
                </div>
                <div>
                    <label class="text-sm text-secondary-500">Email</label>
                    <div class="mt-1 text-secondary-900">{{ $vendor->email ?? '-' }}</div>
                </div>
                <div class="md:col-span-2">
                    <label class="text-sm text-secondary-500">Kontak Person</label>
                    <div class="mt-1 text-secondary-900">{{ $vendor->kontak_person ?? '-' }}</div>
                </div>
            </div>
        </div>

        <!-- Bank Information -->
        <div class="bg-white rounded-2xl shadow-soft p-8 mb-6">
            <h2 class="text-lg font-semibold text-secondary-900 mb-6">Informasi Bank</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-sm text-secondary-500">Nama Bank</label>
                    <div class="mt-1 text-secondary-900">{{ $vendor->nama_bank ?? '-' }}</div>
                </div>
                <div>
                    <label class="text-sm text-secondary-500">Nomor Rekening</label>
                    <div class="mt-1 text-secondary-900 font-mono">{{ $vendor->nomor_rekening ?? '-' }}</div>
                </div>
            </div>
        </div>

        <!-- Catatan -->
        @if($vendor->catatan)
            <div class="bg-white rounded-2xl shadow-soft p-8 mb-6">
                <h2 class="text-lg font-semibold text-secondary-900 mb-4">Catatan</h2>
                <p class="text-secondary-700">{{ $vendor->catatan }}</p>
            </div>
        @endif

        <!-- Audit Info -->
        <div class="flex items-center justify-between text-sm text-secondary-500">
            <div>Dibuat oleh: {{ $vendor->createdBy->name ?? '-' }}</div>
            <div>{{ \Carbon\Carbon::parse($vendor->created_at)->format('d F Y, H:i') }}</div>
        </div>
    </div>
</x-app-layout>
