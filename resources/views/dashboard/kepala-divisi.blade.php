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
                <div class="grid grid-cols-4 gap-2">
                    <a href="{{ route('approvals.index') }}" class="flex flex-col items-center p-2 bg-gradient-to-br from-amber-50 to-amber-100 rounded-xl hover:from-amber-100 hover:to-amber-200 transition-all duration-200">
                        <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center shadow-sm mb-1">
                            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span class="text-[10px] font-medium text-slate-700 text-center leading-tight">Approval</span>
                    </a>

                    <a href="{{ route('program-kerja.index') }}" class="flex flex-col items-center p-2 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl hover:from-blue-100 hover:to-blue-200 transition-all duration-200">
                        <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center shadow-sm mb-1">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                        </div>
                        <span class="text-[10px] font-medium text-slate-700 text-center leading-tight">Program</span>
                    </a>

                    <a href="{{ route('pengajuan-dana.index') }}" class="flex flex-col items-center p-2 bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-xl hover:from-emerald-100 hover:to-emerald-200 transition-all duration-200">
                        <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center shadow-sm mb-1">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        <span class="text-[10px] font-medium text-slate-700 text-center leading-tight">Pengajuan</span>
                    </a>

                    <a href="{{ route('lpj.index') }}" class="flex flex-col items-center p-2 bg-gradient-to-br from-violet-50 to-violet-100 rounded-xl hover:from-violet-100 hover:to-violet-200 transition-all duration-200">
                        <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center shadow-sm mb-1">
                            <svg class="w-4 h-4 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <span class="text-[10px] font-medium text-slate-700 text-center leading-tight">LPJ</span>
                    </a>

                    <a href="{{ route('refund.index') }}" class="flex flex-col items-center p-2 bg-gradient-to-br from-cyan-50 to-cyan-100 rounded-xl hover:from-cyan-100 hover:to-cyan-200 transition-all duration-200">
                        <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center shadow-sm mb-1">
                            <svg class="w-4 h-4 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                            </svg>
                        </div>
                        <span class="text-[10px] font-medium text-slate-700 text-center leading-tight">Refund</span>
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
                    <p class="text-sm text-cyan-100 mb-1">Divisi</p>
                    <p class="text-xl font-bold text-white">{{ $data['divisi']->nama_divisi }}</p>
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

            <!-- Menunggu Approval -->
            <div class="bg-white rounded-2xl border border-slate-100 p-5">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-xl flex items-center justify-center shadow-lg shadow-cyan-500/20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ $data['pengajuanMenunggu'] }}</p>
                        <p class="text-xs text-slate-500">Menunggu Approval</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notifications -->
        <div class="grid grid-cols-1 gap-4">
            <!-- Pencairan Menunggu -->
            <div class="bg-white rounded-2xl border border-slate-100 p-5">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ $data['pencairanMenunggu'] }}</p>
                        <p class="text-xs text-slate-500">Pencairan Menunggu</p>
                    </div>
                </div>
            </div>

            <!-- LPJ Belum Dibuat -->
            <a href="{{ route('lpj.create') }}" class="bg-white rounded-2xl border border-slate-100 p-5 hover:border-amber-200 hover:shadow-lg hover:shadow-amber-100/50 transition-all duration-300">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl flex items-center justify-center shadow-lg shadow-amber-500/20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ $data['lpjBelumDibuat'] }}</p>
                        <p class="text-xs text-slate-500">LPJ Belum Dibuat</p>
                    </div>
                </div>
            </a>

            <!-- Perlu Refund -->
            <div class="bg-white rounded-2xl border border-slate-100 p-5">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-violet-500 to-violet-600 rounded-xl flex items-center justify-center shadow-lg shadow-violet-500/20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ $data['lpjNeedRefund'] }}</p>
                        <p class="text-xs text-slate-500">Perlu Refund</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- My Pending Approvals -->
        @if(isset($data['myPendingApprovals']) && $data['myPendingApprovals']->count() > 0)
            <div class="bg-gradient-to-r from-blue-600 to-cyan-500 rounded-2xl p-5 shadow-lg shadow-blue-500/20">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-base font-semibold text-white">Awaiting Your Approval</p>
                            <p class="text-sm text-blue-100">{{ $data['myPendingApprovals']->count() }} pengajuan</p>
                        </div>
                    </div>
                    <a href="{{ route('approvals.index') }}" class="px-5 py-2.5 bg-white text-blue-600 text-sm font-semibold rounded-xl hover:bg-blue-50 transition-colors">View All</a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($data['myPendingApprovals'] as $approval)
                        <a href="{{ route('approvals.show', $approval) }}" class="p-4 bg-white rounded-xl hover:bg-blue-50 transition-all duration-200">
                            <div class="flex justify-between items-start mb-3">
                                <span class="font-bold text-slate-900 text-sm">{{ $approval->pengajuanDana->nomor_pengajuan }}</span>
                                <span class="text-xs px-2.5 py-1 rounded-full bg-blue-100 text-blue-700 font-semibold">Lvl {{ $approval->level }}</span>
                            </div>
                            <p class="text-sm text-slate-600 mb-3">{{ $approval->pengajuanDana->judul_pengajuan }}</p>
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-slate-500">{{ $approval->pengajuanDana->createdBy->full_name ?? '-' }}</span>
                                <span class="text-sm font-bold text-slate-900">{{ formatRupiah($approval->pengajuanDana->total_pengajuan) }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Quick Actions -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
            <div class="px-6 py-4 border-b border-slate-100">
                <span class="text-sm font-semibold text-slate-900">Quick Actions</span>
            </div>
            <div class="p-5">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <a href="{{ route('program-kerja.index') }}" class="flex flex-col items-center p-4 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl hover:from-blue-100 hover:to-blue-200 transition-all duration-200">
                        <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center mb-3 shadow-sm">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                        </div>
                        <span class="text-sm font-semibold text-slate-700">Program Kerja</span>
                    </a>

                    <a href="{{ route('pengajuan-dana.create') }}" class="flex flex-col items-center p-4 bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-xl hover:from-emerald-100 hover:to-emerald-200 transition-all duration-200">
                        <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center mb-3 shadow-sm">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        <span class="text-sm font-semibold text-slate-700">Create Pengajuan</span>
                    </a>

                    <a href="{{ route('pengajuan-dana.index') }}" class="flex flex-col items-center p-4 bg-gradient-to-br from-amber-50 to-amber-100 rounded-xl hover:from-amber-100 hover:to-amber-200 transition-all duration-200">
                        <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center mb-3 shadow-sm">
                            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <span class="text-sm font-semibold text-slate-700">All Pengajuan</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Pengajuan -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-4 md:px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <span class="text-sm font-semibold text-slate-900">Recent Pengajuan Divisi</span>
                <a href="{{ route('pengajuan-dana.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-semibold">View All →</a>
            </div>

            <!-- Mobile Card View -->
            <div class="md:hidden p-4 space-y-3">
                @foreach($data['pengajuanDivisi'] as $pengajuan)
                    @php
                        $statusColors = ['menunggu_approval' => 'bg-amber-100 text-amber-700', 'disetujui' => 'bg-emerald-100 text-emerald-700', 'ditolak' => 'bg-red-100 text-red-700', 'revisi' => 'bg-blue-100 text-blue-700'];
                        $statusColor = $statusColors[$pengajuan->status] ?? 'bg-slate-100 text-slate-600';
                    @endphp
                    <a href="{{ route('pengajuan-dana.show', $pengajuan) }}" class="block bg-gradient-to-br from-slate-50 to-slate-100 rounded-xl p-4 hover:from-slate-100 hover:to-slate-200 transition-all">
                        <div class="flex items-start justify-between gap-2 mb-2">
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-slate-900 text-sm">{{ $pengajuan->nomor_pengajuan ?? '-' }}</p>
                                <p class="text-xs text-slate-500 mt-0.5">{{ $pengajuan->tanggal_pengajuan ? $pengajuan->tanggal_pengajuan->format('d/m/Y') : '-' }}</p>
                            </div>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusColor }} flex-shrink-0">{{ ucfirst(str_replace('_', ' ', $pengajuan->status)) }}</span>
                        </div>
                        <p class="font-semibold text-slate-900 text-sm mb-1">{{ $pengajuan->judul_pengajuan }}</p>
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-6 h-6 bg-blue-100 rounded flex items-center justify-center">
                                <span class="text-[10px] font-bold text-blue-700">{{ strtoupper(substr($pengajuan->user->full_name ?? '-', 0, 1)) }}</span>
                            </div>
                            <span class="text-xs text-slate-600">{{ $pengajuan->user->full_name ?? '-' }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500 text-xs">Total</span>
                            <span class="font-bold text-slate-900 text-sm">{{ formatRupiah($pengajuan->total_pengajuan) }}</span>
                        </div>
                    </a>
                @endforeach
                @if($data['pengajuanDivisi']->count() == 0)
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-slate-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="text-slate-500 text-sm">Belum ada pengajuan</p>
                    </div>
                @endif
            </div>

            <!-- Desktop Table View -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-slate-50 to-slate-100/50 border-b border-slate-100">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Tanggal</th>
                            <th class="px-5 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Nomor</th>
                            <th class="px-5 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Judul</th>
                            <th class="px-5 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Pengaju</th>
                            <th class="px-5 py-3 text-right text-xs font-bold text-slate-700 uppercase tracking-wider">Total</th>
                            <th class="px-5 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Status</th>
                            <th class="px-5 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($data['pengajuanDivisi'] as $pengajuan)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-5 py-4"><span class="text-sm text-slate-700">{{ $pengajuan->tanggal_pengajuan ? $pengajuan->tanggal_pengajuan->format('d/m/Y') : '-' }}</span></td>
                                <td class="px-5 py-4"><span class="font-semibold text-slate-900 text-sm">{{ $pengajuan->nomor_pengajuan ?? '-' }}</span></td>
                                <td class="px-5 py-4"><p class="font-semibold text-slate-900 text-sm">{{ $pengajuan->judul_pengajuan }}</p></td>
                                <td class="px-5 py-4"><span class="text-sm text-slate-700">{{ $pengajuan->user->full_name ?? '-' }}</span></td>
                                <td class="px-5 py-4 text-right"><span class="font-bold text-slate-900 text-sm">{{ formatRupiah($pengajuan->total_pengajuan) }}</span></td>
                                <td class="px-5 py-4">
                                    @php
                                        $statusColors = ['menunggu_approval' => 'bg-amber-100 text-amber-700', 'disetujui' => 'bg-emerald-100 text-emerald-700', 'ditolak' => 'bg-red-100 text-red-700', 'revisi' => 'bg-blue-100 text-blue-700'];
                                        $statusColor = $statusColors[$pengajuan->status] ?? 'bg-slate-100 text-slate-600';
                                    @endphp
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $statusColor }}">{{ ucfirst(str_replace('_', ' ', $pengajuan->status)) }}</span>
                                </td>
                                <td class="px-5 py-4"><a href="{{ route('pengajuan-dana.show', $pengajuan) }}" class="text-blue-600 hover:text-blue-700 text-sm font-semibold">Detail</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @if($data['pengajuanDivisi']->count() == 0)
                    <div class="text-center py-10 text-slate-500">
                        <p>Belum ada pengajuan dana untuk divisi ini.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
