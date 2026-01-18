<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black gradient-text">Dashboard Direktur Keuangan</h1>
                <p class="text-secondary-600 mt-1">Kelola keuangan, verifikasi pencairan, LPJ, dan refund</p>
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
                <div class="text-blue-100 mt-1">
                    {{ $data['activePeriode']->tanggal_mulai_perencanaan_anggaran->translatedFormat('d F Y') }} - {{ $data['activePeriode']->tanggal_selesai_perencanaan_anggaran->translatedFormat('d F Y') }}
                </div>
            </div>
            <div class="text-right">
                <div class="text-sm text-blue-100 mb-1">Total Pagu Periode</div>
                <div class="text-2xl font-bold">{{ formatRupiah($data['totalPagu']) }}</div>
            </div>
        </div>
    </div>
    @endif

    <!-- Key Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-8">
        <!-- Total Pagu -->
        <div class="bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl p-6 text-white shadow-soft">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total Pagu</p>
                    <p class="text-2xl font-bold mt-1">{{ formatRupiah($data['totalPagu']) }}</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Terpakai -->
        <div class="bg-gradient-to-br from-orange-400 to-orange-600 rounded-2xl p-6 text-white shadow-soft">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm font-medium">Total Terpakai</p>
                    <p class="text-2xl font-bold mt-1">{{ formatRupiah($data['terpakai']) }}</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Sisa Pagu -->
        <div class="bg-gradient-to-br from-green-400 to-green-600 rounded-2xl p-6 text-white shadow-soft">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Sisa Pagu</p>
                    <p class="text-2xl font-bold mt-1">{{ formatRupiah($data['sisaPagu']) }}</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pencairan Hari Ini -->
        <div class="bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl p-6 text-white shadow-soft">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Pencairan Hari Ini</p>
                    <p class="text-2xl font-bold mt-1">{{ formatRupiah($data['totalPencairanHariIni']) }}</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Pengajuan Statistics -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 lg:gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-soft p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-secondary-500 text-sm font-medium">Menunggu Approval</p>
                    <p class="text-3xl font-bold text-orange-600 mt-1">{{ $data['pengajuanMenunggu'] }}</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-soft p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-secondary-500 text-sm font-medium">Disetujui</p>
                    <p class="text-3xl font-bold text-green-600 mt-1">{{ $data['pengajuanDisetujui'] }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-soft p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-secondary-500 text-sm font-medium">Total Pengajuan</p>
                    <p class="text-3xl font-bold text-blue-600 mt-1">{{ $data['pengajuanTotal'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Menunggu Approval Saya -->
    @if(isset($data['myPendingApprovals']) && $data['myPendingApprovals']->count() > 0)
    <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-2xl shadow-soft p-6 mb-8 text-white">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center mr-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold">Menunggu Approval Saya</h3>
                    <p class="text-orange-100 text-sm">{{ $data['myPendingApprovals']->count() }} pengajuan memerlukan persetujuan Anda</p>
                </div>
            </div>
            <a href="{{ route('approvals.index') }}" class="bg-white text-orange-600 px-4 py-2 rounded-xl font-semibold hover:bg-orange-50 transition-colors">
                Lihat Semua
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($data['myPendingApprovals'] as $approval)
                <a href="{{ route('approvals.show', $approval) }}" class="bg-white/10 backdrop-blur-sm rounded-xl p-4 hover:bg-white/20 transition-colors block">
                    <div class="flex justify-between items-start mb-2">
                        <span class="font-bold">{{ $approval->pengajuanDana->nomor_pengajuan }}</span>
                        <span class="text-xs bg-white/20 px-2 py-1 rounded-full">{{ $approval->level }}</span>
                    </div>
                    <p class="text-sm text-orange-100 mb-2">{{ $approval->pengajuanDana->judul_pengajuan }}</p>
                    <div class="flex justify-between items-center text-sm">
                        <span>{{ $approval->pengajuanDana->createdBy->full_name ?? '-' }}</span>
                        <span class="font-bold">{{ formatRupiah($approval->pengajuanDana->total_pengajuan) }}</span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    @endif

    <!-- LPJ & Refund Statistics -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-soft p-6 border-l-4 border-orange-500">
            <p class="text-secondary-500 text-sm font-medium">LPJ Pending</p>
            <p class="text-2xl font-bold text-orange-600 mt-1">{{ $data['lpjPending'] }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-soft p-6 border-l-4 border-blue-500">
            <p class="text-secondary-500 text-sm font-medium">LPJ Revisi</p>
            <p class="text-2xl font-bold text-blue-600 mt-1">{{ $data['lpjRevisi'] }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-soft p-6 border-l-4 border-red-500">
            <p class="text-secondary-500 text-sm font-medium">Refund Pending</p>
            <p class="text-2xl font-bold text-red-600 mt-1">{{ $data['refundPending'] }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-soft p-6 border-l-4 border-blue-500">
            <p class="text-secondary-500 text-sm font-medium">Pencairan Pending</p>
            <p class="text-2xl font-bold text-blue-600 mt-1">{{ $data['pencairanPending'] }}</p>
        </div>
    </div>

    <!-- Verification Lists -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Pencairan Need Verification -->
        <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4 flex items-center justify-between">
                <h3 class="text-lg font-bold text-white flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Pencairan Menunggu
                </h3>
                <span class="bg-white/20 text-white px-3 py-1 rounded-full text-sm font-semibold">{{ $data['pencairanNeedVerification']->count() }}</span>
            </div>
            <div class="p-4 max-h-80 overflow-y-auto">
                @if($data['pencairanNeedVerification']->count() > 0)
                    <div class="space-y-3">
                        @foreach($data['pencairanNeedVerification'] as $pencairan)
                            <a href="{{ route('pencairan-dana.show', $pencairan) }}" class="block p-3 bg-blue-50 rounded-xl hover:bg-blue-100 transition-colors">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $pencairan->nomor_pencairan }}</p>
                                        <p class="text-sm text-gray-500">{{ $pencairan->pengajuanDana->divisi->nama_divisi ?? '-' }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold text-blue-600">{{ formatRupiah($pencairan->jumlah_pencairan) }}</p>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-sm">Tidak ada pencairan pending</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- LPJ Need Verification -->
        <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
            <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-4 flex items-center justify-between">
                <h3 class="text-lg font-bold text-white flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    LPJ Menunggu
                </h3>
                <span class="bg-white/20 text-white px-3 py-1 rounded-full text-sm font-semibold">{{ $data['lpjNeedVerification']->count() }}</span>
            </div>
            <div class="p-4 max-h-80 overflow-y-auto">
                @if($data['lpjNeedVerification']->count() > 0)
                    <div class="space-y-3">
                        @foreach($data['lpjNeedVerification'] as $lpj)
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
                @else
                    <div class="text-center py-8 text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-sm">Tidak ada LPJ pending</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Refund Need Verification -->
        <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
            <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-4 flex items-center justify-between">
                <h3 class="text-lg font-bold text-white flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                    Refund Menunggu
                </h3>
                <span class="bg-white/20 text-white px-3 py-1 rounded-full text-sm font-semibold">{{ $data['refundNeedVerification']->count() }}</span>
            </div>
            <div class="p-4 max-h-80 overflow-y-auto">
                @if($data['refundNeedVerification']->count() > 0)
                    <div class="space-y-3">
                        @foreach($data['refundNeedVerification'] as $refund)
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
                @else
                    <div class="text-center py-8 text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-sm">Tidak ada refund pending</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Pengajuan -->
    <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-bold text-secondary-900">Pengajuan Terbaru</h3>
            <a href="{{ route('pengajuan-dana.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Lihat Semua →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Nomor</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Judul</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Divisi</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($data['recentPengajuan'] as $pengajuan)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <span class="font-medium text-gray-900">{{ $pengajuan->nomor_pengajuan ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-medium text-gray-900">{{ $pengajuan->judul_pengajuan }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                    {{ $pengajuan->divisi->nama_divisi ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="font-bold text-gray-900">{{ formatRupiah($pengajuan->total_pengajuan) }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold
                                    @if($pengajuan->status == 'menunggu_approval') bg-orange-100 text-orange-800
                                    @elseif($pengajuan->status == 'disetujui') bg-green-100 text-green-800
                                    @elseif($pengajuan->status == 'ditolak') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $pengajuan->status)) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Divisi Overview -->
    <div class="mt-8 bg-white rounded-2xl shadow-soft overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-bold text-secondary-900">Overview Divisi</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($data['divisis'] as $divisi)
                    @php
                        $percentage = $divisi->pagu_periode > 0 ? round(($divisi->real_time_terpakai / $divisi->pagu_periode) * 100, 0) : 0;
                    @endphp
                    <div class="border border-gray-200 rounded-xl p-4 hover:shadow-soft transition-shadow">
                        <div class="text-sm font-bold text-gray-900">{{ $divisi->nama_divisi }}</div>
                        <div class="mt-1 text-xs text-gray-500">{{ $divisi->pengajuan_dana_count ?? 0 }} pengajuan bulan ini</div>
                        <div class="mt-3 flex justify-between text-xs">
                            <span class="text-gray-600">Pagu: {{ formatRupiah($divisi->pagu_periode) }}</span>
                            <span class="font-semibold {{ $percentage > 90 ? 'text-red-600' : ($percentage > 70 ? 'text-amber-600' : 'text-green-600') }}">{{ $percentage }}%</span>
                        </div>
                        <div class="mt-2 bg-gray-200 rounded-full h-2">
                            <div class="h-2 rounded-full {{ $percentage > 90 ? 'bg-red-500' : ($percentage > 70 ? 'bg-amber-500' : 'bg-green-500') }}" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
