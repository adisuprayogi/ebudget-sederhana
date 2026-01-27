<x-app-layout>
    <x-slot name="header">
        <x-dashboard.header :userName="Auth::user()->name" />
    </x-slot>

    <div class="space-y-5">
        <!-- Mobile Quick Links -->
        <div class="md:hidden bg-white rounded-2xl border border-slate-100 shadow-sm">
            <div class="px-4 py-3 border-b border-slate-100">
                <h3 class="text-xs font-semibold text-slate-900">Quick Links</h3>
            </div>
            <div class="p-3">
                <div class="grid grid-cols-4 gap-2 max-h-48 overflow-y-auto">
                    <a href="{{ route('perencanaan-penerimaan.index') }}" class="flex flex-col items-center p-2 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl hover:from-blue-100 hover:to-blue-200 transition-all duration-200">
                        <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center shadow-sm mb-1">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span class="text-[10px] font-medium text-slate-700 text-center leading-tight">Penerimaan</span>
                    </a>

                    <a href="{{ route('pencatatan-penerimaan.index') }}" class="flex flex-col items-center p-2 bg-gradient-to-br from-cyan-50 to-cyan-100 rounded-xl hover:from-cyan-100 hover:to-cyan-200 transition-all duration-200">
                        <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center shadow-sm mb-1">
                            <svg class="w-4 h-4 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <span class="text-[10px] font-medium text-slate-700 text-center leading-tight">Pencatatan</span>
                    </a>

                    <a href="{{ route('penetapan-pagu.index') }}" class="flex flex-col items-center p-2 bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-xl hover:from-indigo-100 hover:to-indigo-200 transition-all duration-200">
                        <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center shadow-sm mb-1">
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                        </div>
                        <span class="text-[10px] font-medium text-slate-700 text-center leading-tight">Pagu</span>
                    </a>

                    <a href="{{ route('pencairan-dana.index') }}" class="flex flex-col items-center p-2 bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-xl hover:from-emerald-100 hover:to-emerald-200 transition-all duration-200">
                        <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center shadow-sm mb-1">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <span class="text-[10px] font-medium text-slate-700 text-center leading-tight">Pencairan</span>
                    </a>

                    <a href="{{ route('reports.index') }}" class="flex flex-col items-center p-2 bg-gradient-to-br from-violet-50 to-violet-100 rounded-xl hover:from-violet-100 hover:to-violet-200 transition-all duration-200">
                        <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center shadow-sm mb-1">
                            <svg class="w-4 h-4 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <span class="text-[10px] font-medium text-slate-700 text-center leading-tight">Laporan</span>
                    </a>

                    <a href="{{ route('lpj-verification.index') }}" class="flex flex-col items-center p-2 bg-gradient-to-br from-amber-50 to-amber-100 rounded-xl hover:from-amber-100 hover:to-amber-200 transition-all duration-200">
                        <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center shadow-sm mb-1">
                            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <span class="text-[10px] font-medium text-slate-700 text-center leading-tight">Verif LPJ</span>
                    </a>

                    <a href="{{ route('refund-verification.index') }}" class="flex flex-col items-center p-2 bg-gradient-to-br from-red-50 to-red-100 rounded-xl hover:from-red-100 hover:to-red-200 transition-all duration-200">
                        <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center shadow-sm mb-1">
                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <span class="text-[10px] font-medium text-slate-700 text-center leading-tight">Verif Refund</span>
                    </a>
                </div>
            </div>
        </div>

        @if($data['activePeriode'])
        <!-- Periode Info Banner -->
        <div class="bg-gradient-to-r from-blue-600 to-cyan-500 rounded-2xl p-5 shadow-lg shadow-blue-500/20">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-blue-100 mb-1">Periode Anggaran Aktif</p>
                    <p class="text-xl font-bold text-white">{{ $data['activePeriode']->nama_periode }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-cyan-100 mb-1">Total Pagu Periode</p>
                    <p class="text-xl font-bold text-white">{{ formatRupiah($data['totalPagu']) }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Processed Today -->
            <div class="bg-white rounded-2xl border border-slate-100 p-5">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ $data['pencairanProcessedToday'] }}</p>
                        <p class="text-xs text-slate-500">Processed Today</p>
                    </div>
                </div>
            </div>

            <!-- Total Today -->
            <div class="bg-white rounded-2xl border border-slate-100 p-5">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-lg font-bold text-slate-900">{{ formatRupiah($data['totalPencairanHariIni']) }}</p>
                        <p class="text-xs text-slate-500">Total Today</p>
                    </div>
                </div>
            </div>

            <!-- Budget Used -->
            <div class="bg-white rounded-2xl border border-slate-100 p-5">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl flex items-center justify-center shadow-lg shadow-amber-500/20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ $data['totalPagu'] > 0 ? round(($data['totalTerpakai'] / $data['totalPagu']) * 100, 1) : 0 }}%</p>
                        <p class="text-xs text-slate-500">Budget Used</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Verification Pending Counts -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Pencairan Pending -->
            <a href="{{ route('pencairan-dana.index') }}" class="bg-white rounded-2xl border border-slate-100 p-5 hover:border-blue-200 hover:shadow-lg hover:shadow-blue-100/50 transition-all duration-300">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ $data['pencairanPending']->count() }}</p>
                        <p class="text-xs text-slate-500">Pencairan Pending</p>
                    </div>
                </div>
            </a>

            <!-- LPJ Pending -->
            <a href="{{ route('lpj.index', ['status' => 'menunggu_verifikasi']) }}" class="bg-white rounded-2xl border border-slate-100 p-5 hover:border-amber-200 hover:shadow-lg hover:shadow-amber-100/50 transition-all duration-300">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl flex items-center justify-center shadow-lg shadow-amber-500/20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ $data['lpjPending']->count() }}</p>
                        <p class="text-xs text-slate-500">LPJ Pending</p>
                    </div>
                </div>
            </a>

            <!-- Refund Pending -->
            <a href="{{ route('refund.index', ['status' => 'menunggu_approval']) }}" class="bg-white rounded-2xl border border-slate-100 p-5 hover:border-red-200 hover:shadow-lg hover:shadow-red-100/50 transition-all duration-300">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center shadow-lg shadow-red-500/20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ $data['refundPending']->count() }}</p>
                        <p class="text-xs text-slate-500">Refund Pending</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Pending Pencairan Alert -->
        @if($data['pencairanPending']->count() > 0)
            <div class="bg-gradient-to-r from-blue-600 to-cyan-500 rounded-2xl p-5 shadow-lg shadow-blue-500/20">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-base font-semibold text-white">Pencairan Menunggu Verifikasi</p>
                            <p class="text-sm text-blue-100">{{ $data['pencairanPending']->count() }} pencairan perlu diproses</p>
                        </div>
                    </div>
                    <a href="{{ route('pencairan-dana.index') }}" class="px-5 py-2.5 bg-white text-blue-600 text-sm font-semibold rounded-xl hover:bg-blue-50 transition-colors">Process Now →</a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($data['pencairanPending']->take(4) as $pencairan)
                        <a href="{{ route('pencairan-dana.show', $pencairan) }}" class="flex justify-between items-center p-4 bg-white rounded-xl hover:bg-blue-50 transition-all duration-200">
                            <div>
                                <p class="font-bold text-slate-900 text-sm">{{ $pencairan->nomor_pencairan }}</p>
                                <p class="text-xs text-slate-500">{{ $pencairan->pengajuanDana->divisi->nama_divisi ?? '-' }} - {{ $pencairan->pengajuanDana->judul_pengajuan }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-blue-600 text-xs">{{ formatRupiah($pencairan->jumlah_pencairan) }}</p>
                                <p class="text-xs text-slate-500">{{ $pencairan->tanggal_pencairan->format('d/m/Y') }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Pengajuan Ready to Process -->
        @if($data['pengajuanNeedProcessing']->count() > 0)
            <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 rounded-2xl p-5 shadow-lg shadow-emerald-500/20">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-base font-semibold text-white">Pengajuan Siap Dicairkan</p>
                            <p class="text-sm text-emerald-100">{{ $data['countPengajuanNeedProcessing'] }} pengajuan • {{ formatRupiah($data['totalPengajuanNeedProcessing']) }}</p>
                        </div>
                    </div>
                    <a href="{{ route('pencairan-dana.create') }}" class="px-5 py-2.5 bg-white text-emerald-600 font-bold rounded-xl hover:bg-emerald-50 transition-colors text-sm">Create Pencairan →</a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($data['pengajuanNeedProcessing']->take(4) as $pengajuan)
                        <a href="{{ route('pencairan-dana.create', ['pengajuan_dana_id' => $pengajuan->id]) }}" class="flex justify-between items-center p-4 bg-white rounded-xl hover:bg-emerald-50 transition-all duration-200 border-2 border-transparent">
                            <div class="flex-1">
                                <p class="font-bold text-slate-900 text-sm">{{ $pengajuan->nomor_pengajuan ?? '-' }}</p>
                                <p class="text-xs text-slate-600">{{ $pengajuan->divisi->nama_divisi ?? '-' }}</p>
                                <p class="text-xs text-slate-500 mt-0.5">{{ $pengajuan->judul_pengajuan }}</p>
                            </div>
                            <div class="text-right ml-3">
                                <p class="text-lg font-black text-emerald-600">{{ formatRupiah($pengajuan->total_pengajuan) }}</p>
                                <p class="text-xs text-emerald-600 font-medium">{{ \Carbon\Carbon::parse($pengajuan->updated_at)->diffForHumans() }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @else
            <div class="bg-white rounded-2xl border border-slate-100 p-10 text-center border-2 border-dashed">
                <div class="w-14 h-14 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="text-sm font-bold text-slate-700 mb-2">Tidak Ada Pengajuan Siap Dicairkan</p>
                <p class="text-xs text-slate-500">All approved pengajuan have been processed</p>
            </div>
        @endif

        <!-- LPJ & Refund Verification -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <!-- LPJ Pending -->
            @if($data['lpjPending']->count() > 0)
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
                    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <span class="text-sm font-semibold text-slate-900">LPJ Menunggu Verifikasi</span>
                        </div>
                        <a href="{{ route('lpj.index', ['status' => 'menunggu_verifikasi']) }}" class="px-4 py-2 bg-amber-100 text-amber-700 text-xs font-semibold rounded-lg hover:bg-amber-200 transition-colors">View All</a>
                    </div>
                    <div class="p-4 max-h-72 overflow-y-auto">
                        <div class="space-y-3">
                            @foreach($data['lpjPending'] as $lpj)
                                <a href="{{ route('lpj.show', $lpj) }}" class="block p-4 bg-gradient-to-r from-amber-50 to-amber-100/50 rounded-xl hover:from-amber-100 hover:to-amber-200/50 transition-all duration-200">
                                    <div class="flex justify-between items-start">
                                        <div><p class="font-bold text-slate-900 text-sm">{{ $lpj->nomor_lpj }}</p><p class="text-xs text-slate-500">{{ $lpj->pencairanDana->pengajuanDana->divisi->nama_divisi ?? '-' }}</p></div>
                                        <div class="text-right"><p class="font-bold text-amber-600 text-sm">{{ formatRupiah($lpj->total_pengeluaran) }}</p></div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Refund Pending -->
            @if($data['refundPending']->count() > 0)
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
                    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                            </div>
                            <span class="text-sm font-semibold text-slate-900">Refund Menunggu Approval</span>
                        </div>
                        <a href="{{ route('refund.index', ['status' => 'menunggu_approval']) }}" class="px-4 py-2 bg-red-100 text-red-700 text-xs font-semibold rounded-lg hover:bg-red-200 transition-colors">View All</a>
                    </div>
                    <div class="p-4 max-h-72 overflow-y-auto">
                        <div class="space-y-3">
                            @foreach($data['refundPending'] as $refund)
                                <a href="{{ route('refund.show', $refund) }}" class="block p-4 bg-gradient-to-r from-red-50 to-red-100/50 rounded-xl hover:from-red-100 hover:to-red-200/50 transition-all duration-200">
                                    <div class="flex justify-between items-start">
                                        <div><p class="font-bold text-slate-900 text-sm">{{ $refund->nomor_refund }}</p><p class="text-xs text-slate-500">{{ $refund->lpj->pencairanDana->pengajuanDana->divisi->nama_divisi ?? '-' }}</p></div>
                                        <div class="text-right"><p class="font-bold text-red-600 text-sm">{{ formatRupiah($refund->jumlah_refund) }}</p></div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Empty State -->
        @if($data['pencairanPending']->count() == 0 && $data['pengajuanNeedProcessing']->count() == 0 && $data['lpjPending']->count() == 0 && $data['refundPending']->count() == 0)
            <div class="bg-white rounded-2xl border border-slate-100 p-14 text-center">
                <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-5">
                    <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-3">All Tasks Complete!</h3>
                <p class="text-slate-500 text-base">No pending pencairan, LPJ, or refund at this time.</p>
            </div>
        @endif
    </div>
</x-app-layout>
