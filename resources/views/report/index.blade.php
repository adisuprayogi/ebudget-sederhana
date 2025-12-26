<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-secondary-900">Laporan & Analitik</h1>
                <p class="text-secondary-600 mt-1">Dashboard laporan dan statistik keuangan</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <!-- Year Selector -->
        <div class="bg-white rounded-2xl shadow-soft p-6 mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-secondary-900">Tahun Anggaran</h2>
                    <p class="text-sm text-secondary-500">Pilih tahun untuk melihat laporan</p>
                </div>
                <select id="yearSelector" class="px-4 py-2 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    @foreach(range(date('Y') - 5, date('Y')) as $year)
                        <option value="{{ $year }}" {{ $year == $currentYear ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-2xl shadow-soft p-6 hover:shadow-medium transition-shadow cursor-pointer" onclick="window.location.href='{{ route('reports.pengajuan') }}'">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm text-secondary-500 mb-1">Total Pengajuan</div>
                        <div class="text-2xl font-bold text-secondary-900">{{ $dashboardStats['total_pengajuan'] ?? 0 }}</div>
                        <div class="text-sm text-primary-600 mt-1">{{ formatRupiah($dashboardStats['total_nominal_pengajuan'] ?? 0) }}</div>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-soft p-6 hover:shadow-medium transition-shadow cursor-pointer" onclick="window.location.href='{{ route('reports.pencairan') }}'">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm text-secondary-500 mb-1">Total Pencairan</div>
                        <div class="text-2xl font-bold text-secondary-900">{{ $dashboardStats['total_pencairan'] ?? 0 }}</div>
                        <div class="text-sm text-green-600 mt-1">{{ formatRupiah($dashboardStats['total_nominal_pencairan'] ?? 0) }}</div>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-soft p-6 hover:shadow-medium transition-shadow cursor-pointer" onclick="window.location.href='{{ route('reports.lpj') }}'">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm text-secondary-500 mb-1">LPJ Masuk</div>
                        <div class="text-2xl font-bold text-secondary-900">{{ $dashboardStats['total_lpj'] ?? 0 }}</div>
                        <div class="text-sm text-amber-600 mt-1">{{ $dashboardStats['lpj_pending'] ?? 0 }} pending</div>
                    </div>
                    <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-soft p-6 hover:shadow-medium transition-shadow cursor-pointer" onclick="window.location.href='{{ route('reports.refund') }}'">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm text-secondary-500 mb-1">Total Refund</div>
                        <div class="text-2xl font-bold text-secondary-900">{{ $dashboardStats['total_refund'] ?? 0 }}</div>
                        <div class="text-sm text-red-600 mt-1">{{ formatRupiah($dashboardStats['total_nominal_refund'] ?? 0) }}</div>
                    </div>
                    <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Report Categories -->
        <h2 class="text-xl font-bold text-secondary-900 mb-6">Kategori Laporan</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Pengajuan Report -->
            <a href="{{ route('reports.pengajuan') }}" class="bg-white rounded-2xl shadow-soft p-6 hover:shadow-medium transition-all duration-200 group">
                <div class="flex items-start">
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-secondary-900 mb-1">Laporan Pengajuan Dana</h3>
                        <p class="text-sm text-secondary-500">Analisis pengajuan dana per divisi, jenis belanja, dan periode waktu</p>
                    </div>
                </div>
            </a>

            <!-- Pencairan Report -->
            <a href="{{ route('reports.pencairan') }}" class="bg-white rounded-2xl shadow-soft p-6 hover:shadow-medium transition-all duration-200 group">
                <div class="flex items-start">
                    <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-secondary-900 mb-1">Laporan Pencairan Dana</h3>
                        <p class="text-sm text-secondary-500">Status pencairan, jadwal pembayaran, dan analisis tren bulanan</p>
                    </div>
                </div>
            </a>

            <!-- LPJ Report -->
            <a href="{{ route('reports.lpj') }}" class="bg-white rounded-2xl shadow-soft p-6 hover:shadow-medium transition-all duration-200 group">
                <div class="flex items-start">
                    <div class="w-14 h-14 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-secondary-900 mb-1">Laporan Pertanggungjawaban</h3>
                        <p class="text-sm text-secondary-500">LPJ masuk, status verifikasi, dan item yang jatuh tempo</p>
                    </div>
                </div>
            </a>

            <!-- Refund Report -->
            <a href="{{ route('reports.refund') }}" class="bg-white rounded-2xl shadow-soft p-6 hover:shadow-medium transition-all duration-200 group">
                <div class="flex items-start">
                    <div class="w-14 h-14 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-secondary-900 mb-1">Laporan Refund</h3>
                        <p class="text-sm text-secondary-500">Pengembalian dana, alasan refund, dan status proses</p>
                    </div>
                </div>
            </a>

            <!-- Budget Realization -->
            <a href="{{ route('reports.budget-realization') }}" class="bg-white rounded-2xl shadow-soft p-6 hover:shadow-medium transition-all duration-200 group">
                <div class="flex items-start">
                    <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-secondary-900 mb-1">Realisasi Anggaran</h3>
                        <p class="text-sm text-secondary-500">Perbandingan pagu vs realisasi per divisi dan program kerja</p>
                    </div>
                </div>
            </a>

            <!-- Executive Summary -->
            @if(auth()->user()->hasPermission('report.executive_summary'))
                <a href="{{ route('reports.executive-summary') }}" class="bg-white rounded-2xl shadow-soft p-6 hover:shadow-medium transition-all duration-200 group">
                    <div class="flex items-start">
                        <div class="w-14 h-14 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-secondary-900 mb-1">Eksekutif Summary</h3>
                            <p class="text-sm text-secondary-500">Ringkasan eksekutif untuk manajemen puncak</p>
                        </div>
                    </div>
                </a>
            @endif
        </div>

        <!-- Pending Items Alert -->
        @if(($dashboardStats['pengajuan_pending'] ?? 0) > 0 || ($dashboardStats['lpj_pending'] ?? 0) > 0)
            <div class="mt-8 bg-amber-50 border border-amber-200 rounded-2xl p-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="ml-3 flex-1">
                        <h3 class="text-lg font-medium text-amber-800">Item yang Memerlukan Perhatian</h3>
                        <div class="mt-2 text-sm text-amber-700">
                            @if(($dashboardStats['pengajuan_pending'] ?? 0) > 0)
                                <p>{{ $dashboardStats['pengajuan_pending'] }} pengajuan dana menunggu approval</p>
                            @endif
                            @if(($dashboardStats['lpj_pending'] ?? 0) > 0)
                                <p>{{ $dashboardStats['lpj_pending'] }} LPJ belum diverifikasi</p>
                            @endif
                        </div>
                    </div>
                    <a href="{{ route('pengajuan-dana.index', ['status' => 'proposed']) }}" class="ml-4 inline-flex items-center px-4 py-2 bg-amber-600 text-white rounded-xl hover:bg-amber-700 transition-colors">
                        Lihat Detail
                    </a>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
