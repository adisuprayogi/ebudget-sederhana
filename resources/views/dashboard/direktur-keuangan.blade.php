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
                    <a href="{{ route('periode-anggaran.index') }}" class="flex flex-col items-center p-2 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl hover:from-blue-100 hover:to-blue-200 transition-all duration-200">
                        <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center shadow-sm mb-1">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <span class="text-[10px] font-medium text-slate-700 text-center leading-tight">Anggaran</span>
                    </a>

                    <a href="{{ route('sumber-dana.index') }}" class="flex flex-col items-center p-2 bg-gradient-to-br from-cyan-50 to-cyan-100 rounded-xl hover:from-cyan-100 hover:to-cyan-200 transition-all duration-200">
                        <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center shadow-sm mb-1">
                            <svg class="w-4 h-4 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span class="text-[10px] font-medium text-slate-700 text-center leading-tight">Sumber Dana</span>
                    </a>

                    <a href="{{ route('rekening-perusahaan.index') }}" class="flex flex-col items-center p-2 bg-gradient-to-br from-violet-50 to-violet-100 rounded-xl hover:from-violet-100 hover:to-violet-200 transition-all duration-200">
                        <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center shadow-sm mb-1">
                            <svg class="w-4 h-4 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                        </div>
                        <span class="text-[10px] font-medium text-slate-700 text-center leading-tight">Rekening</span>
                    </a>

                    <a href="{{ route('perencanaan-penerimaan.index') }}" class="flex flex-col items-center p-2 bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-xl hover:from-emerald-100 hover:to-emerald-200 transition-all duration-200">
                        <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center shadow-sm mb-1">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span class="text-[10px] font-medium text-slate-700 text-center leading-tight">Penerimaan</span>
                    </a>

                    <a href="{{ route('pencatatan-penerimaan.index') }}" class="flex flex-col items-center p-2 bg-gradient-to-br from-teal-50 to-teal-100 rounded-xl hover:from-teal-100 hover:to-teal-200 transition-all duration-200">
                        <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center shadow-sm mb-1">
                            <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

                    <a href="{{ route('approvals.index') }}" class="flex flex-col items-center p-2 bg-gradient-to-br from-amber-50 to-amber-100 rounded-xl hover:from-amber-100 hover:to-amber-200 transition-all duration-200">
                        <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center shadow-sm mb-1">
                            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span class="text-[10px] font-medium text-slate-700 text-center leading-tight">Approval</span>
                    </a>

                    <a href="{{ route('reports.index') }}" class="flex flex-col items-center p-2 bg-gradient-to-br from-slate-50 to-slate-100 rounded-xl hover:from-slate-100 hover:to-slate-200 transition-all duration-200">
                        <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center shadow-sm mb-1">
                            <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <span class="text-[10px] font-medium text-slate-700 text-center leading-tight">Laporan</span>
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
                    <p class="text-sm text-blue-100 mt-1">
                        {{ $data['activePeriode']->tanggal_mulai_perencanaan_anggaran->translatedFormat('d F Y') }} - {{ $data['activePeriode']->tanggal_selesai_perencanaan_anggaran->translatedFormat('d F Y') }}
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-cyan-100 mb-1">Total Pagu Periode</p>
                    <p class="text-xl font-bold text-white">{{ formatRupiah($data['totalPagu']) }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total Pagu -->
            <div class="bg-white rounded-2xl border border-slate-100 p-5">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-lg font-bold text-slate-900">{{ formatRupiah($data['totalPagu']) }}</p>
                        <p class="text-xs text-slate-500">Total Pagu</p>
                    </div>
                </div>
            </div>

            <!-- Terpakai -->
            <div class="bg-white rounded-2xl border border-slate-100 p-5">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl flex items-center justify-center shadow-lg shadow-amber-500/20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-lg font-bold text-slate-900">{{ formatRupiah($data['terpakai']) }}</p>
                        <p class="text-xs text-slate-500">Terpakai ({{ $data['totalPagu'] > 0 ? round(($data['terpakai'] / $data['totalPagu']) * 100, 1) : 0 }}%)</p>
                    </div>
                </div>
            </div>

            <!-- Sisa Pagu -->
            <div class="bg-white rounded-2xl border border-slate-100 p-5">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-lg font-bold text-slate-900">{{ formatRupiah($data['sisaPagu']) }}</p>
                        <p class="text-xs text-slate-500">Sisa Pagu</p>
                    </div>
                </div>
            </div>

            <!-- Today's Disbursement -->
            <div class="bg-white rounded-2xl border border-slate-100 p-5">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-xl flex items-center justify-center shadow-lg shadow-cyan-500/20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-lg font-bold text-slate-900">{{ formatRupiah($data['totalPencairanHariIni']) }}</p>
                        <p class="text-xs text-slate-500">Pencairan Hari Ini</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- LPJ & Refund Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- LPJ Pending -->
            <a href="{{ route('lpj.index', ['status' => 'menunggu_verifikasi']) }}" class="bg-white rounded-2xl border border-slate-100 p-5 hover:border-blue-200 hover:shadow-lg hover:shadow-blue-100/50 transition-all duration-300">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl flex items-center justify-center shadow-lg shadow-amber-500/20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ $data['lpjPending'] }}</p>
                        <p class="text-xs text-slate-500">LPJ Pending</p>
                    </div>
                </div>
            </a>

            <!-- LPJ Revisi -->
            <div class="bg-white rounded-2xl border border-slate-100 p-5">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-violet-500 to-violet-600 rounded-xl flex items-center justify-center shadow-lg shadow-violet-500/20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ $data['lpjRevisi'] }}</p>
                        <p class="text-xs text-slate-500">LPJ Revisi</p>
                    </div>
                </div>
            </div>

            <!-- Refund Pending -->
            <a href="{{ route('refund.index', ['status' => 'menunggu_approval']) }}" class="bg-white rounded-2xl border border-slate-100 p-5 hover:border-red-200 hover:shadow-lg hover:shadow-red-100/50 transition-all duration-300">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center shadow-lg shadow-red-500/20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ $data['refundPending'] }}</p>
                        <p class="text-xs text-slate-500">Refund Pending</p>
                    </div>
                </div>
            </a>

            <!-- Pencairan Pending -->
            <a href="{{ route('pencairan-dana.index') }}" class="bg-white rounded-2xl border border-slate-100 p-5 hover:border-blue-200 hover:shadow-lg hover:shadow-blue-100/50 transition-all duration-300">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ $data['pencairanPending'] }}</p>
                        <p class="text-xs text-slate-500">Pencairan Pending</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Verification Lists -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Pencairan Need Verification -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
                <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <span class="text-sm font-semibold text-slate-900">Pencairan</span>
                    </div>
                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">{{ $data['pencairanNeedVerification']->count() }}</span>
                </div>
                <div class="p-4 max-h-72 overflow-y-auto">
                    @if($data['pencairanNeedVerification']->count() > 0)
                        <div class="space-y-3">
                            @foreach($data['pencairanNeedVerification'] as $pencairan)
                                <a href="{{ route('pencairan-dana.show', $pencairan) }}" class="block p-4 bg-gradient-to-r from-blue-50 to-blue-100/50 rounded-xl hover:from-blue-100 hover:to-blue-200/50 transition-all duration-200">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="font-bold text-slate-900 text-sm">{{ $pencairan->nomor_pencairan }}</p>
                                            <p class="text-xs text-slate-500">{{ $pencairan->pengajuanDana->divisi->nama_divisi ?? '-' }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-bold text-blue-600 text-sm">{{ formatRupiah($pencairan->jumlah_pencairan) }}</p>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6">
                            <p class="text-xs text-slate-500">No pending pencairan</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- LPJ Need Verification -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
                <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <span class="text-sm font-semibold text-slate-900">LPJ</span>
                    </div>
                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-700">{{ $data['lpjNeedVerification']->count() }}</span>
                </div>
                <div class="p-4 max-h-72 overflow-y-auto">
                    @if($data['lpjNeedVerification']->count() > 0)
                        <div class="space-y-3">
                            @foreach($data['lpjNeedVerification'] as $lpj)
                                <a href="{{ route('lpj.show', $lpj) }}" class="block p-4 bg-gradient-to-r from-amber-50 to-amber-100/50 rounded-xl hover:from-amber-100 hover:to-amber-200/50 transition-all duration-200">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="font-bold text-slate-900 text-sm">{{ $lpj->nomor_lpj }}</p>
                                            <p class="text-xs text-slate-500">{{ $lpj->pencairanDana->pengajuanDana->divisi->nama_divisi ?? '-' }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-bold text-amber-600 text-sm">{{ formatRupiah($lpj->total_pengeluaran) }}</p>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6">
                            <p class="text-xs text-slate-500">No LPJ pending</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Refund Need Verification -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
                <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                        </div>
                        <span class="text-sm font-semibold text-slate-900">Refund</span>
                    </div>
                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">{{ $data['refundNeedVerification']->count() }}</span>
                </div>
                <div class="p-4 max-h-72 overflow-y-auto">
                    @if($data['refundNeedVerification']->count() > 0)
                        <div class="space-y-3">
                            @foreach($data['refundNeedVerification'] as $refund)
                                <a href="{{ route('refund.show', $refund) }}" class="block p-4 bg-gradient-to-r from-red-50 to-red-100/50 rounded-xl hover:from-red-100 hover:to-red-200/50 transition-all duration-200">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="font-bold text-slate-900 text-sm">{{ $refund->nomor_refund }}</p>
                                            <p class="text-xs text-slate-500">{{ $refund->lpj->pencairanDana->pengajuanDana->divisi->nama_divisi ?? '-' }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-bold text-red-600 text-sm">{{ formatRupiah($refund->jumlah_refund) }}</p>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6">
                            <p class="text-xs text-slate-500">No refund pending</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Pengajuan -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-4 md:px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-slate-900">Recent Pengajuan</h3>
                <a href="{{ route('pengajuan-dana.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-semibold">View All →</a>
            </div>

            <!-- Mobile Card View -->
            <div class="md:hidden p-4 space-y-3">
                @foreach($data['recentPengajuan'] as $pengajuan)
                    @php
                        $statusColors = ['menunggu_approval' => 'bg-amber-100 text-amber-700', 'disetujui' => 'bg-emerald-100 text-emerald-700', 'ditolak' => 'bg-red-100 text-red-700'];
                        $statusColor = $statusColors[$pengajuan->status] ?? 'bg-slate-100 text-slate-600';
                    @endphp
                    <a href="{{ route('pengajuan-dana.show', $pengajuan) }}" class="block bg-gradient-to-br from-slate-50 to-slate-100 rounded-xl p-4 hover:from-slate-100 hover:to-slate-200 transition-all">
                        <div class="flex items-start justify-between gap-2 mb-2">
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-slate-900 text-sm">{{ $pengajuan->nomor_pengajuan ?? '-' }}</p>
                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-semibold rounded-full bg-blue-100 text-blue-700 mt-1">{{ $pengajuan->divisi->nama_divisi ?? '-' }}</span>
                            </div>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusColor }} flex-shrink-0">{{ ucfirst(str_replace('_', ' ', $pengajuan->status)) }}</span>
                        </div>
                        <p class="font-semibold text-slate-900 text-sm mb-2">{{ $pengajuan->judul_pengajuan }}</p>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500 text-xs">Total</span>
                            <span class="font-bold text-slate-900 text-sm">{{ formatRupiah($pengajuan->total_pengajuan) }}</span>
                        </div>
                    </a>
                @endforeach
            </div>

            <!-- Desktop Table View -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-slate-50 to-slate-100/50 border-b border-slate-100">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Nomor</th>
                            <th class="px-5 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Judul</th>
                            <th class="px-5 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Divisi</th>
                            <th class="px-5 py-3 text-right text-xs font-bold text-slate-700 uppercase tracking-wider">Total</th>
                            <th class="px-5 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($data['recentPengajuan'] as $pengajuan)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-5 py-4"><span class="font-semibold text-slate-900 text-sm">{{ $pengajuan->nomor_pengajuan ?? '-' }}</span></td>
                                <td class="px-5 py-4"><p class="font-semibold text-slate-900 text-sm">{{ $pengajuan->judul_pengajuan }}</p></td>
                                <td class="px-5 py-4"><span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">{{ $pengajuan->divisi->nama_divisi ?? '-' }}</span></td>
                                <td class="px-5 py-4 text-right"><span class="font-bold text-slate-900 text-sm">{{ formatRupiah($pengajuan->total_pengajuan) }}</span></td>
                                <td class="px-5 py-4">
                                    @php
                                        $statusColors = ['menunggu_approval' => 'bg-amber-100 text-amber-700', 'disetujui' => 'bg-emerald-100 text-emerald-700', 'ditolak' => 'bg-red-100 text-red-700'];
                                        $statusColor = $statusColors[$pengajuan->status] ?? 'bg-slate-100 text-slate-600';
                                    @endphp
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $statusColor }}">{{ ucfirst(str_replace('_', ' ', $pengajuan->status)) }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
