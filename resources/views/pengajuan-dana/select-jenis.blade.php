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
                <p class="text-secondary-600 mt-1">Pilih jenis pengajuan dana yang akan diajukan</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Kegiatan -->
            <a href="{{ route('pengajuan-dana.create', ['jenis' => 'kegiatan']) }}"
               class="group bg-white rounded-2xl shadow-soft hover:shadow-medium transition-all duration-200 p-6 border-2 border-transparent hover:border-primary-500">
                <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-secondary-900 mb-2">Kegiatan</h3>
                <p class="text-secondary-600 text-sm">Pengajuan dana untuk kegiatan operasional dan aktivitas bisnis</p>
            </a>

            <!-- Pengadaan -->
            <a href="{{ route('pengajuan-dana.create', ['jenis' => 'pengadaan']) }}"
               class="group bg-white rounded-2xl shadow-soft hover:shadow-medium transition-all duration-200 p-6 border-2 border-transparent hover:border-primary-500">
                <div class="w-14 h-14 bg-green-100 text-green-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-secondary-900 mb-2">Pengadaan</h3>
                <p class="text-secondary-600 text-sm">Pengajuan dana untuk pembelian barang dan pengadaan aset</p>
            </a>

            <!-- Pembayaran -->
            <a href="{{ route('pengajuan-dana.create', ['jenis' => 'pembayaran']) }}"
               class="group bg-white rounded-2xl shadow-soft hover:shadow-medium transition-all duration-200 p-6 border-2 border-transparent hover:border-primary-500">
                <div class="w-14 h-14 bg-amber-100 text-amber-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0zM9 12h.01" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-secondary-900 mb-2">Pembayaran</h3>
                <p class="text-secondary-600 text-sm">Pengajuan dana untuk pembayaran kepada pihak ketiga</p>
            </a>

            <!-- Honorarium -->
            <a href="{{ route('pengajuan-dana.create', ['jenis' => 'honorarium']) }}"
               class="group bg-white rounded-2xl shadow-soft hover:shadow-medium transition-all duration-200 p-6 border-2 border-transparent hover:border-primary-500">
                <div class="w-14 h-14 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-secondary-900 mb-2">Honorarium</h3>
                <p class="text-secondary-600 text-sm">Pengajuan dana untuk honorarium dan jasa profesional</p>
            </a>

            <!-- Sewa -->
            <a href="{{ route('pengajuan-dana.create', ['jenis' => 'sewa']) }}"
               class="group bg-white rounded-2xl shadow-soft hover:shadow-medium transition-all duration-200 p-6 border-2 border-transparent hover:border-primary-500">
                <div class="w-14 h-14 bg-red-100 text-red-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-secondary-900 mb-2">Sewa</h3>
                <p class="text-secondary-600 text-sm">Pengajuan dana untuk sewa tempat, peralatan, atau kendaraan</p>
            </a>

            <!-- Konsumi -->
            <a href="{{ route('pengajuan-dana.create', ['jenis' => 'konsumi']) }}"
               class="group bg-white rounded-2xl shadow-soft hover:shadow-medium transition-all duration-200 p-6 border-2 border-transparent hover:border-primary-500">
                <div class="w-14 h-14 bg-orange-100 text-orange-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-secondary-900 mb-2">Konsumi</h3>
                <p class="text-secondary-600 text-sm">Pengajuan dana untuk konsumsi rapat dan acara</p>
            </a>

            <!-- Reimbursement -->
            <a href="{{ route('pengajuan-dana.create', ['jenis' => 'reimbursement']) }}"
               class="group bg-white rounded-2xl shadow-soft hover:shadow-medium transition-all duration-200 p-6 border-2 border-transparent hover:border-primary-500">
                <div class="w-14 h-14 bg-teal-100 text-teal-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-secondary-900 mb-2">Reimbursement</h3>
                <p class="text-secondary-600 text-sm">Pengajuan dana untuk penggantian biaya yang sudah dikeluarkan</p>
            </a>

            <!-- Lainnya -->
            <a href="{{ route('pengajuan-dana.create', ['jenis' => 'lainnya']) }}"
               class="group bg-white rounded-2xl shadow-soft hover:shadow-medium transition-all duration-200 p-6 border-2 border-transparent hover:border-primary-500">
                <div class="w-14 h-14 bg-gray-100 text-gray-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-secondary-900 mb-2">Lainnya</h3>
                <p class="text-secondary-600 text-sm">Pengajuan dana untuk keperluan lainnya</p>
            </a>
        </div>
    </div>
</x-app-layout>
