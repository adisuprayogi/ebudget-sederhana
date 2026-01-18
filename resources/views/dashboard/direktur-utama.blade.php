<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg shadow-amber-500/30">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Dashboard Direktur Utama</h1>
                    <p class="text-gray-500 text-sm mt-0.5">Monitoring dan approval level tertinggi</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="hidden sm:flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-300 rounded-xl shadow-sm">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="text-sm font-medium text-gray-700">{{ now()->translatedFormat('l, d F Y') }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                    <span class="text-xs text-gray-500">Live Data</span>
                </div>
            </div>
        </div>
    </x-slot>

    <!-- Executive Overview Banner -->
    <div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 rounded-2xl shadow-xl p-8 mb-8 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/10 rounded-full translate-y-1/2 -translate-x-1/2"></div>
        <div class="relative z-10">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-3 h-3 bg-green-400 rounded-full animate-pulse"></div>
                <span class="text-blue-100 text-sm font-medium tracking-wide">SYSTEM ACTIVE</span>
            </div>
            <h2 class="text-4xl font-bold mb-2">Executive Command Center</h2>
            <p class="text-blue-100 max-w-2xl">Sistem monitoring dan pengelolaan anggaran perusahaan dengan real-time analytics dan intelligent approvals</p>
        </div>
    </div>

    <!-- Key Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
        <!-- Total Pagu -->
        <div class="bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 p-6 border border-gray-100">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Total Pagu</p>
                    <p class="text-2xl font-bold text-gray-900 leading-tight mt-1">{{ formatRupiah($data['totalPagu']) }}</p>
                </div>
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-3 shadow-lg shadow-blue-500/30 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2zm0 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2z" />
                    </svg>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <div class="flex-1 bg-blue-100 rounded-full h-2">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2 rounded-full" style="width: 75%"></div>
                </div>
                <span class="text-xs text-gray-500 font-medium">{{ now()->year }}</span>
            </div>
        </div>

        <!-- Menunggu Approval -->
        <div class="bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 p-6 border border-gray-100">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Menunggu</p>
                    <p class="text-2xl font-bold text-amber-600 leading-tight mt-1">{{ $data['pengajuanMenunggu'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">Pengajuan</p>
                </div>
                <div class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl p-3 shadow-lg shadow-amber-500/30 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-2 h-2 bg-amber-500 rounded-full animate-pulse"></div>
                <span class="text-xs text-amber-600 font-medium">Need Action</span>
            </div>
        </div>

        <!-- Disetujui -->
        <div class="bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 p-6 border border-gray-100">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Disetujui</p>
                    <p class="text-2xl font-bold text-emerald-600 leading-tight mt-1">{{ $data['pengajuanDisetujui'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">Pengajuan</p>
                </div>
                <div class="bg-gradient-to-br from-emerald-500 to-green-600 rounded-xl p-3 shadow-lg shadow-emerald-500/30 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                <span class="text-xs text-emerald-600 font-medium">Completed</span>
            </div>
        </div>

        <!-- Ditolak -->
        <div class="bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 p-6 border border-gray-100">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Ditolak</p>
                    <p class="text-2xl font-bold text-red-600 leading-tight mt-1">{{ $data['pengajuanDitolak'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">Pengajuan</p>
                </div>
                <div class="bg-gradient-to-br from-red-500 to-rose-600 rounded-xl p-3 shadow-lg shadow-red-500/30 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                <span class="text-xs text-red-600 font-medium">Rejected</span>
            </div>
        </div>

        <!-- Pencairan Pending -->
        <div class="bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 p-6 border border-gray-100">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Pencairan</p>
                    <p class="text-2xl font-bold text-indigo-600 leading-tight mt-1">{{ $data['pencairanPending'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">Pending</p>
                </div>
                <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl p-3 shadow-lg shadow-indigo-500/30 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-2 h-2 bg-indigo-500 rounded-full animate-pulse"></div>
                <span class="text-xs text-indigo-600 font-medium">Processing</span>
            </div>
        </div>
    </div>

    <!-- High Value Pengajuan Alert -->
    @if($data['highValuePengajuan']->count() > 0)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-red-500 to-rose-600 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="relative">
                            <div class="absolute inset-0 bg-red-400 rounded-full animate-ping"></div>
                            <div class="relative bg-white rounded-full p-2">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-1.333-1.964-.833-2.694 0L3.34 16c-.77.833-1.964.833-2.694 0L3.34 7c-.77.833.192-2.694 1.732-2.5L12.998 3c.77-.833 1.964-.833 2.694 0l6.938 6.938c.77.833 1.964.833 2.694 0l1.732-2.5c.77-.833.192-2.694-1.732-2.5z" />
                                </svg>
                            </div>
                        </div>
                        <div class="text-white">
                            <h3 class="text-lg font-bold">High Value Transactions</h3>
                            <p class="text-red-100 text-sm">Pengajuan di atas 50 juta memerlukan persetujuan Anda</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="bg-white/20 text-white px-4 py-2 rounded-full text-sm font-semibold">
                            {{ $data['highValuePengajuan']->count() }} Items
                        </span>
                        <span class="text-red-100 text-sm">
                            Total: {{ formatRupiah($data['highValuePengajuan']->sum('total_pengajuan')) }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nomor</th>
                                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Judul</th>
                                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Divisi</th>
                                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Total</th>
                                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="text-right py-3 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($data['highValuePengajuan'] as $pengajuan)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-4 px-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-lg bg-red-100 text-red-800 text-xs font-bold">
                                            {{ $pengajuan->nomor_pengajuan ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-4">
                                        <p class="font-medium text-gray-900">{{ $pengajuan->judul_pengajuan }}</p>
                                        <p class="text-xs text-gray-500 mt-1">{{ \Carbon\Carbon::parse($pengajuan->created_at)->format('d/m/Y') }}</p>
                                    </td>
                                    <td class="py-4 px-4">
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                <span class="text-xs font-bold text-blue-600">{{ substr($pengajuan->divisi->nama_divisi ?? 'U', 0, 1) }}</span>
                                            </div>
                                            <span class="text-sm font-medium text-gray-700">{{ $pengajuan->divisi->nama_divisi ?? '-' }}</span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4">
                                        <p class="font-bold text-red-600">{{ formatRupiah($pengajuan->total_pengajuan) }}</p>
                                        <p class="text-xs text-red-500 mt-1">{{ round(($pengajuan->total_pengajuan / 50000000) - 1, 1) }}x limit</p>
                                    </td>
                                    <td class="py-4 px-4">
                                        @php
                                            $statusColors = [
                                                'menunggu_approval' => 'bg-amber-100 text-amber-800',
                                                'disetujui' => 'bg-emerald-100 text-emerald-800',
                                                'ditolak' => 'bg-red-100 text-red-800',
                                            ];
                                            $statusColor = $statusColors[$pengajuan->status] ?? 'bg-gray-100 text-gray-800';
                                        @endphp
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $statusColor }}">
                                            {{ ucfirst(str_replace('_', ' ', $pengajuan->status)) }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-4 text-right">
                                        <a href="{{ route('pengajuan-dana.show', $pengajuan) }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm">Detail</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- Pending Approvals & Quick Actions -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8 mb-8">
        <!-- Pending Approvals -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-4 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Pending Approvals</h3>
                    <span class="bg-amber-100 text-amber-800 px-3 py-1 rounded-full text-sm font-semibold">
                        {{ $data['pendingApprovals']->count() }} Items
                    </span>
                </div>
            </div>
            <div class="p-6">
                @if($data['pendingApprovals']->count() > 0)
                    <div class="space-y-4">
                        @foreach($data['pendingApprovals'] as $approval)
                            <a href="{{ route('approvals.show', $approval) }}" class="flex items-start gap-4 p-4 bg-amber-50 rounded-xl hover:bg-amber-100 transition-colors">
                                <div class="flex-shrink-0 bg-amber-100 rounded-full p-2">
                                    <svg class="w-5 h-5 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-bold text-gray-900">{{ $approval->pengajuanDana->nomor_pengajuan ?? '-' }}</p>
                                        <span class="text-xs text-amber-600 font-medium">Level {{ $approval->level }}</span>
                                    </div>
                                    <p class="text-sm text-gray-700 mt-1">{{ $approval->pengajuanDana->judul_pengajuan }}</p>
                                    <p class="text-xs text-gray-500 mt-2">Menunggu: {{ $approval->approver->full_name ?? 'N/A' }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="w-16 h-16 text-emerald-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-gray-500 font-medium">Tidak ada approval pending</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 gap-4">
                    <a href="{{ route('approvals.index') }}" class="flex items-center gap-4 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl hover:from-blue-100 hover:to-indigo-100 transition-all group">
                        <div class="flex-shrink-0 bg-blue-600 rounded-xl p-3 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-base font-bold text-gray-900">All Approvals</h4>
                            <p class="text-sm text-gray-500">Kelola semua persetujuan</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>

                    <a href="{{ route('reports.index') }}" class="flex items-center gap-4 p-4 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl hover:from-purple-100 hover:to-pink-100 transition-all group">
                        <div class="flex-shrink-0 bg-purple-600 rounded-xl p-3 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-base font-bold text-gray-900">Generate Reports</h4>
                            <p class="text-sm text-gray-500">Download laporan lengkap</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>

                    <a href="{{ route('pengajuan-dana.create') }}" class="flex items-center gap-4 p-4 bg-gradient-to-r from-emerald-50 to-green-50 rounded-xl hover:from-emerald-100 hover:to-green-100 transition-all group">
                        <div class="flex-shrink-0 bg-emerald-600 rounded-xl p-3 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-base font-bold text-gray-900">Buat Pengajuan</h4>
                            <p class="text-sm text-gray-500">Ajukan dana baru</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Menunggu Approval Saya -->
    @if(isset($data['myPendingApprovals']) && $data['myPendingApprovals']->count() > 0)
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl shadow-xl p-6 mb-8 text-white">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center mr-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold">Menunggu Approval Anda</h3>
                    <p class="text-blue-100 text-sm">{{ $data['myPendingApprovals']->count() }} pengajuan memerlukan persetujuan Anda</p>
                </div>
            </div>
            <a href="{{ route('approvals.index') }}" class="bg-white text-blue-600 px-4 py-2 rounded-xl font-semibold hover:bg-blue-50 transition-colors">
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
                    <p class="text-sm text-blue-100 mb-2">{{ $approval->pengajuanDana->judul_pengajuan }}</p>
                    <div class="flex justify-between items-center text-sm">
                        <span>{{ $approval->pengajuanDana->divisi->nama_divisi ?? '-' }}</span>
                        <span class="font-bold">{{ formatRupiah($approval->pengajuanDana->total_pengajuan) }}</span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Recent Activity -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900">Recent Pengajuan</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-3 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nomor</th>
                        <th class="text-left py-3 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Judul</th>
                        <th class="text-left py-3 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Divisi</th>
                        <th class="text-left py-3 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="text-left py-3 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($data['recentPengajuan'] as $pengajuan)
                        <tr class="hover:bg-gray-50">
                            <td class="py-4 px-6">
                                <span class="font-medium text-gray-900">{{ $pengajuan->nomor_pengajuan ?? '-' }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <p class="text-gray-900">{{ $pengajuan->judul_pengajuan }}</p>
                            </td>
                            <td class="py-4 px-6">
                                <span class="text-gray-700">{{ $pengajuan->divisi->nama_divisi ?? '-' }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="font-bold text-gray-900">{{ formatRupiah($pengajuan->total_pengajuan) }}</span>
                            </td>
                            <td class="py-4 px-6">
                                @php
                                    $statusColors = [
                                        'menunggu_approval' => 'bg-amber-100 text-amber-800',
                                        'disetujui' => 'bg-emerald-100 text-emerald-800',
                                        'ditolak' => 'bg-red-100 text-red-800',
                                    ];
                                    $statusColor = $statusColors[$pengajuan->status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $statusColor }}">
                                    {{ ucfirst(str_replace('_', ' ', $pengajuan->status)) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
