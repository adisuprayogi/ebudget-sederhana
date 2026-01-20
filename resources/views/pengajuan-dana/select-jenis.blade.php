<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('pengajuan-dana.index') }}" class="p-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-xl font-semibold text-gray-900">Buat Pengajuan Dana Baru</h1>
                <p class="text-xs text-gray-500 mt-0.5">Pilih jenis pengajuan dana yang akan diajukan</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-6xl mx-auto">
        <!-- Info LPJ -->
        <div class="mb-4 bg-blue-50 border border-blue-100 rounded-xl p-4">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="flex-1">
                    <p class="text-sm font-medium text-blue-900">Keterangan LPJ (Laporan Pertanggung Jawaban)</p>
                    <div class="mt-2 flex flex-wrap gap-4 text-xs">
                        <div class="flex items-center gap-1.5">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Tidak Butuh LPJ
                            </span>
                            <span class="text-gray-600">Honorarium, Pembayaran, Reimbursement</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-700">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Butuh LPJ
                            </span>
                            <span class="text-gray-600">Pengadaan, Kegiatan, Sewa, Konsumi, Lainnya</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Jenis Pengajuan Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
            <!-- Kegiatan -->
            <a href="{{ route('pengajuan-dana.create', ['jenis' => 'kegiatan']) }}"
               class="group bg-white rounded-xl border border-blue-100 hover:border-blue-200 transition-all overflow-hidden">
                <div class="p-4">
                    <div class="flex items-start justify-between mb-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                        </div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-700">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Butuh LPJ
                        </span>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-900 mb-1">Kegiatan</h3>
                    <p class="text-xs text-gray-500">Pengajuan dana untuk kegiatan operasional dan aktivitas bisnis</p>
                </div>
                <div class="px-4 py-2 bg-blue-50 border-t border-blue-100 flex items-center justify-between">
                    <span class="text-xs text-gray-600">Buat Pengajuan</span>
                    <svg class="w-4 h-4 text-gray-400 group-hover:text-blue-600 group-hover:translate-x-0.5 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
            </a>

            <!-- Pengadaan -->
            <a href="{{ route('pengajuan-dana.create', ['jenis' => 'pengadaan']) }}"
               class="group bg-white rounded-xl border border-blue-100 hover:border-blue-200 transition-all overflow-hidden">
                <div class="p-4">
                    <div class="flex items-start justify-between mb-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        </div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-700">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Butuh LPJ
                        </span>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-900 mb-1">Pengadaan</h3>
                    <p class="text-xs text-gray-500">Pengajuan dana untuk pembelian barang dan pengadaan aset</p>
                </div>
                <div class="px-4 py-2 bg-blue-50 border-t border-blue-100 flex items-center justify-between">
                    <span class="text-xs text-gray-600">Buat Pengajuan</span>
                    <svg class="w-4 h-4 text-gray-400 group-hover:text-blue-600 group-hover:translate-x-0.5 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
            </a>

            <!-- Pembayaran -->
            <a href="{{ route('pengajuan-dana.create', ['jenis' => 'pembayaran']) }}"
               class="group bg-white rounded-xl border border-blue-100 hover:border-blue-200 transition-all overflow-hidden">
                <div class="p-4">
                    <div class="flex items-start justify-between mb-3">
                        <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center group-hover:bg-emerald-200 transition-colors">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0zM9 12h.01" />
                            </svg>
                        </div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Tidak Butuh LPJ
                        </span>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-900 mb-1">Pembayaran</h3>
                    <p class="text-xs text-gray-500">Pengajuan dana untuk pembayaran kepada pihak ketiga</p>
                </div>
                <div class="px-4 py-2 bg-blue-50 border-t border-blue-100 flex items-center justify-between">
                    <span class="text-xs text-gray-600">Buat Pengajuan</span>
                    <svg class="w-4 h-4 text-gray-400 group-hover:text-blue-600 group-hover:translate-x-0.5 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
            </a>

            <!-- Honorarium -->
            <a href="{{ route('pengajuan-dana.create', ['jenis' => 'honorarium']) }}"
               class="group bg-white rounded-xl border border-blue-100 hover:border-blue-200 transition-all overflow-hidden">
                <div class="p-4">
                    <div class="flex items-start justify-between mb-3">
                        <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center group-hover:bg-emerald-200 transition-colors">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Tidak Butuh LPJ
                        </span>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-900 mb-1">Honorarium</h3>
                    <p class="text-xs text-gray-500">Pengajuan dana untuk honorarium dan jasa profesional</p>
                </div>
                <div class="px-4 py-2 bg-blue-50 border-t border-blue-100 flex items-center justify-between">
                    <span class="text-xs text-gray-600">Buat Pengajuan</span>
                    <svg class="w-4 h-4 text-gray-400 group-hover:text-blue-600 group-hover:translate-x-0.5 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
            </a>

            <!-- Sewa -->
            <a href="{{ route('pengajuan-dana.create', ['jenis' => 'sewa']) }}"
               class="group bg-white rounded-xl border border-blue-100 hover:border-blue-200 transition-all overflow-hidden">
                <div class="p-4">
                    <div class="flex items-start justify-between mb-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                        </div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-700">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Butuh LPJ
                        </span>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-900 mb-1">Sewa</h3>
                    <p class="text-xs text-gray-500">Pengajuan dana untuk sewa tempat, peralatan, atau kendaraan</p>
                </div>
                <div class="px-4 py-2 bg-blue-50 border-t border-blue-100 flex items-center justify-between">
                    <span class="text-xs text-gray-600">Buat Pengajuan</span>
                    <svg class="w-4 h-4 text-gray-400 group-hover:text-blue-600 group-hover:translate-x-0.5 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
            </a>

            <!-- Konsumi -->
            <a href="{{ route('pengajuan-dana.create', ['jenis' => 'konsumi']) }}"
               class="group bg-white rounded-xl border border-blue-100 hover:border-blue-200 transition-all overflow-hidden">
                <div class="p-4">
                    <div class="flex items-start justify-between mb-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-700">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Butuh LPJ
                        </span>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-900 mb-1">Konsumi</h3>
                    <p class="text-xs text-gray-500">Pengajuan dana untuk konsumsi rapat dan acara</p>
                </div>
                <div class="px-4 py-2 bg-blue-50 border-t border-blue-100 flex items-center justify-between">
                    <span class="text-xs text-gray-600">Buat Pengajuan</span>
                    <svg class="w-4 h-4 text-gray-400 group-hover:text-blue-600 group-hover:translate-x-0.5 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
            </a>

            <!-- Reimbursement -->
            <a href="{{ route('pengajuan-dana.create', ['jenis' => 'reimbursement']) }}"
               class="group bg-white rounded-xl border border-blue-100 hover:border-blue-200 transition-all overflow-hidden">
                <div class="p-4">
                    <div class="flex items-start justify-between mb-3">
                        <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center group-hover:bg-emerald-200 transition-colors">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" />
                            </svg>
                        </div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Tidak Butuh LPJ
                        </span>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-900 mb-1">Reimbursement</h3>
                    <p class="text-xs text-gray-500">Pengajuan dana untuk penggantian biaya yang sudah dikeluarkan</p>
                </div>
                <div class="px-4 py-2 bg-blue-50 border-t border-blue-100 flex items-center justify-between">
                    <span class="text-xs text-gray-600">Buat Pengajuan</span>
                    <svg class="w-4 h-4 text-gray-400 group-hover:text-blue-600 group-hover:translate-x-0.5 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
            </a>

            <!-- Lainnya -->
            <a href="{{ route('pengajuan-dana.create', ['jenis' => 'lainnya']) }}"
               class="group bg-white rounded-xl border border-blue-100 hover:border-blue-200 transition-all overflow-hidden">
                <div class="p-4">
                    <div class="flex items-start justify-between mb-3">
                        <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center group-hover:bg-gray-200 transition-colors">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" />
                            </svg>
                        </div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-700">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Butuh LPJ
                        </span>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-900 mb-1">Lainnya</h3>
                    <p class="text-xs text-gray-500">Pengajuan dana untuk keperluan lainnya</p>
                </div>
                <div class="px-4 py-2 bg-blue-50 border-t border-blue-100 flex items-center justify-between">
                    <span class="text-xs text-gray-600">Buat Pengajuan</span>
                    <svg class="w-4 h-4 text-gray-400 group-hover:text-blue-600 group-hover:translate-x-0.5 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
            </a>
        </div>
    </div>
</x-app-layout>
