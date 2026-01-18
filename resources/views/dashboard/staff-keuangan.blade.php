<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black gradient-text">Dashboard Staff Keuangan</h1>
                <p class="text-secondary-600 mt-1">Proses pencairan dana dan verifikasi LPJ & Refund</p>
            </div>
            <div class="flex items-center space-x-4">
                <div class="hidden sm:flex items-center space-x-2 bg-white/60 backdrop-blur-sm px-4 py-2 rounded-xl border border-secondary-200/50">
                    <svg class="w-4 h-4 text-secondary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="text-sm font-semibold text-secondary-900">{{ now()->locale('id')->isoFormat('DD MMMM YYYY') }}</span>
                </div>
            </div>
        </div>
    </x-slot>

    @if($data['activePeriode'])
    <!-- Periode Info Banner -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-2xl shadow-soft p-6 mb-8 text-white">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-sm text-blue-100 mb-1">Periode Anggaran Aktif</div>
                <div class="text-2xl font-bold">{{ $data['activePeriode']->nama_periode }}</div>
            </div>
            <div class="text-right">
                <div class="text-sm text-blue-100 mb-1">Total Pagu Perusahaan</div>
                <div class="text-2xl font-bold">{{ formatRupiah($data['totalPagu']) }}</div>
            </div>
        </div>
    </div>
    @endif

    <!-- Today's Summary -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 lg:gap-6 mb-8">
        <div class="bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl p-6 text-white shadow-soft">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Diproses Hari Ini</p>
                    <p class="text-3xl font-bold mt-1">{{ $data['pencairanProcessedToday'] }}</p>
                    <p class="text-blue-100 text-sm mt-2">Pencairan</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl p-6 text-white shadow-soft">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total Pencairan</p>
                    <p class="text-2xl font-bold mt-1">{{ formatRupiah($data['totalPencairanHariIni']) }}</p>
                    <p class="text-blue-100 text-sm mt-2">Hari ini</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-orange-400 to-orange-600 rounded-2xl p-6 text-white shadow-soft">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm font-medium">Pagu Terpakai</p>
                    <p class="text-2xl font-bold mt-1">{{ $data['totalPagu'] > 0 ? round(($data['totalTerpakai'] / $data['totalPagu']) * 100, 1) : 0 }}%</p>
                    <p class="text-orange-100 text-sm mt-2">{{ formatRupiah($data['totalTerpakai']) }}</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Verification Pending Counts -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 lg:gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-soft p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-secondary-500 text-sm font-medium">Pencairan Menunggu</p>
                    <p class="text-3xl font-bold text-blue-600 mt-1">{{ $data['pencairanPending']->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-soft p-6 border-l-4 border-orange-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-secondary-500 text-sm font-medium">LPJ Menunggu</p>
                    <p class="text-3xl font-bold text-orange-600 mt-1">{{ $data['lpjPending']->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-soft p-6 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-secondary-500 text-sm font-medium">Refund Menunggu</p>
                    <p class="text-3xl font-bold text-red-600 mt-1">{{ $data['refundPending']->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Pencairan -->
    @if($data['pencairanPending']->count() > 0)
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-2xl shadow-soft mb-8 overflow-hidden">
        <div class="px-6 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-white">Pencairan Menunggu Verifikasi</h3>
                    <p class="text-blue-100 text-sm">{{ $data['pencairanPending']->count() }} pencairan perlu diproses</p>
                </div>
            </div>
            <a href="{{ route('pencairan-dana.index') }}" class="bg-white text-blue-600 px-4 py-2 rounded-xl font-semibold hover:bg-blue-50 transition-colors">
                Proses Sekarang →
            </a>
        </div>
        <div class="bg-white p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($data['pencairanPending']->take(4) as $pencairan)
                    <a href="{{ route('pencairan-dana.show', $pencairan) }}" class="flex justify-between items-center p-4 bg-blue-50 rounded-xl hover:bg-blue-100 transition-colors">
                        <div>
                            <p class="font-semibold text-gray-900">{{ $pencairan->nomor_pencairan }}</p>
                            <p class="text-sm text-gray-500">{{ $pencairan->pengajuanDana->divisi->nama_divisi ?? '-' }} - {{ $pencairan->pengajuanDana->judul_pengajuan }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-blue-600">{{ formatRupiah($pencairan->jumlah_pencairan) }}</p>
                            <p class="text-sm text-gray-500">{{ $pencairan->tanggal_pencairan->format('d/m/Y') }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
            @if($data['pencairanPending']->count() > 4)
                <div class="mt-4 text-center">
                    <a href="{{ route('pencairan-dana.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Lihat {{ $data['pencairanPending']->count() - 4 }} pencairan lagi →
                    </a>
                </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Pengajuan Ready to Process -->
    @if($data['pengajuanNeedProcessing']->count() > 0)
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-2xl shadow-soft mb-8 overflow-hidden">
        <div class="px-6 py-4 flex justify-between items-center flex-wrap gap-4">
            <div class="flex items-center space-x-3">
                <div class="relative">
                    <div class="absolute inset-0 bg-blue-400 rounded-full animate-ping"></div>
                    <div class="relative w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-white">Pengajuan Siap Dicairkan</h3>
                    <p class="text-blue-100 text-sm">{{ $data['countPengajuanNeedProcessing'] }} pengajuan dengan total {{ formatRupiah($data['totalPengajuanNeedProcessing']) }}</p>
                </div>
            </div>
            <a href="{{ route('pencairan-dana.create') }}" class="bg-white text-blue-600 px-6 py-3 rounded-xl font-bold hover:bg-blue-50 transition-colors shadow-lg">
                Buat Pencairan Sekarang →
            </a>
        </div>
        <div class="bg-white p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($data['pengajuanNeedProcessing']->take(4) as $pengajuan)
                    <a href="{{ route('pencairan-dana.create', ['pengajuan_dana_id' => $pengajuan->id]) }}" class="flex justify-between items-center p-4 bg-blue-50 rounded-xl hover:bg-blue-100 transition-colors border-2 border-blue-200">
                        <div class="flex-1">
                            <p class="font-bold text-gray-900">{{ $pengajuan->nomor_pengajuan ?? '-' }}</p>
                            <p class="text-sm text-gray-600">{{ $pengajuan->divisi->nama_divisi ?? '-' }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $pengajuan->judul_pengajuan }}</p>
                        </div>
                        <div class="text-right ml-4">
                            <p class="text-xl font-black text-blue-600">{{ formatRupiah($pengajuan->total_pengajuan) }}</p>
                            <p class="text-xs text-blue-600 font-medium">{{ \Carbon\Carbon::parse($pengajuan->updated_at)->diffForHumans() }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
            @if($data['pengajuanNeedProcessing']->count() > 4)
                <div class="mt-6 text-center">
                    <a href="{{ route('pengajuan-dana.index', ['status' => 'disetujui']) }}" class="inline-flex items-center px-6 py-3 bg-blue-50 text-blue-700 rounded-xl font-bold hover:bg-blue-100 transition-colors border-2 border-blue-300">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                        Lihat Semua {{ $data['countPengajuanNeedProcessing'] }} Pengajuan
                    </a>
                </div>
            @endif
        </div>
    </div>
    @else
    <div class="bg-gradient-to-r from-gray-100 to-gray-200 rounded-2xl p-8 mb-8 text-center border-2 border-dashed border-gray-300">
        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <h3 class="text-lg font-bold text-gray-700 mb-2">Tidak Ada Pengajuan Siap Dicairkan</h3>
        <p class="text-gray-500">Semua pengajuan yang disetujui telah diproses pencairannya</p>
    </div>
    @endif

    <!-- LPJ & Refund Verification -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- LPJ Pending -->
        @if($data['lpjPending']->count() > 0)
        <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
            <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-white">LPJ Menunggu Verifikasi</h3>
                        <p class="text-orange-100 text-sm">{{ $data['lpjPending']->count() }} LPJ perlu diverifikasi</p>
                    </div>
                </div>
                <a href="{{ route('lpj.index', ['status' => 'menunggu_verifikasi']) }}" class="bg-white text-orange-600 px-3 py-1 rounded-lg text-sm font-semibold hover:bg-orange-50 transition-colors">
                    Lihat Semua
                </a>
            </div>
            <div class="p-4 max-h-80 overflow-y-auto">
                <div class="space-y-3">
                    @foreach($data['lpjPending']->take(5) as $lpj)
                        <a href="{{ route('lpj.show', $lpj) }}" class="block p-3 bg-orange-50 rounded-xl hover:bg-orange-100 transition-colors">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $lpj->nomor_lpj }}</p>
                                    <p class="text-sm text-gray-500">{{ $lpj->pencairanDana->pengajuanDana->divisi->nama_divisi ?? '-' }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-orange-600">{{ formatRupiah($lpj->total_pengeluaran) }}</p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Refund Pending -->
        @if($data['refundPending']->count() > 0)
        <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
            <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-white">Refund Menunggu Approval</h3>
                        <p class="text-red-100 text-sm">{{ $data['refundPending']->count() }} refund perlu diproses</p>
                    </div>
                </div>
                <a href="{{ route('refund.index', ['status' => 'menunggu_approval']) }}" class="bg-white text-red-600 px-3 py-1 rounded-lg text-sm font-semibold hover:bg-red-50 transition-colors">
                    Lihat Semua
                </a>
            </div>
            <div class="p-4 max-h-80 overflow-y-auto">
                <div class="space-y-3">
                    @foreach($data['refundPending']->take(5) as $refund)
                        <a href="{{ route('refund.show', $refund) }}" class="block p-3 bg-red-50 rounded-xl hover:bg-red-100 transition-colors">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $refund->nomor_refund }}</p>
                                    <p class="text-sm text-gray-500">{{ $refund->lpj->pencairanDana->pengajuanDana->divisi->nama_divisi ?? '-' }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-red-600">{{ formatRupiah($refund->jumlah_refund) }}</p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <a href="{{ route('pencatatan-penerimaan.create') }}" class="flex items-center p-6 bg-white rounded-2xl shadow-soft hover:shadow-medium transition-all group">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-4 group-hover:scale-110 transition-transform">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
            </div>
            <div class="ml-5">
                <h3 class="text-lg font-bold text-gray-900 group-hover:text-blue-600">Pencatatan Penerimaan</h3>
                <p class="text-sm text-gray-500 mt-1">Catat penerimaan dana masuk</p>
            </div>
            <svg class="w-5 h-5 text-gray-400 ml-auto group-hover:text-blue-600 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>

        <a href="{{ route('pencairan-dana.create') }}" class="flex items-center p-6 bg-white rounded-2xl shadow-soft hover:shadow-medium transition-all group">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-4 group-hover:scale-110 transition-transform">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div class="ml-5">
                <h3 class="text-lg font-bold text-gray-900 group-hover:text-blue-600">Buat Pencairan Baru</h3>
                <p class="text-sm text-gray-500 mt-1">Proses pencairan dana</p>
            </div>
            <svg class="w-5 h-5 text-gray-400 ml-auto group-hover:text-blue-600 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>
    </div>

    <!-- Empty State -->
    @if($data['pencairanPending']->count() == 0 && $data['pengajuanNeedProcessing']->count() == 0 && $data['lpjPending']->count() == 0 && $data['refundPending']->count() == 0)
        <div class="bg-white rounded-2xl shadow-soft p-12 text-center">
            <div class="w-20 h-20 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Semua Tugas Selesai!</h3>
            <p class="text-gray-500">Tidak ada pencairan, LPJ, atau refund yang menunggu proses saat ini.</p>
        </div>
    @endif
</x-app-layout>
