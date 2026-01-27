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

                    <a href="{{ route('reports.index') }}" class="flex flex-col items-center p-2 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl hover:from-blue-100 hover:to-blue-200 transition-all duration-200">
                        <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center shadow-sm mb-1">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <span class="text-[10px] font-medium text-slate-700 text-center leading-tight">Laporan</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
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

            <!-- Menunggu -->
            <a href="{{ route('approvals.index') }}" class="bg-white rounded-2xl border border-slate-100 p-5 hover:border-blue-200 hover:shadow-lg hover:shadow-blue-100/50 transition-all duration-300">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl flex items-center justify-center shadow-lg shadow-amber-500/20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ $data['pengajuanMenunggu'] }}</p>
                        <p class="text-xs text-slate-500">Menunggu</p>
                    </div>
                </div>
            </a>

            <!-- Disetujui -->
            <div class="bg-white rounded-2xl border border-slate-100 p-5">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ $data['pengajuanDisetujui'] }}</p>
                        <p class="text-xs text-slate-500">Disetujui</p>
                    </div>
                </div>
            </div>

            <!-- Ditolak -->
            <div class="bg-white rounded-2xl border border-slate-100 p-5">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center shadow-lg shadow-red-500/20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ $data['pengajuanDitolak'] }}</p>
                        <p class="text-xs text-slate-500">Ditolak</p>
                    </div>
                </div>
            </div>

            <!-- Pencairan -->
            <div class="bg-white rounded-2xl border border-slate-100 p-5">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-xl flex items-center justify-center shadow-lg shadow-cyan-500/20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ $data['pencairanPending'] }}</p>
                        <p class="text-xs text-slate-500">Pencairan</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- High Value Alert -->
        @if($data['highValuePengajuan']->count() > 0)
            <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-2xl p-5 shadow-lg shadow-red-500/20">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-1.333-1.964-.833-2.694 0L3.34 16c-.77.833-1.964.833-2.694 0L3.34 7c-.77.833.192-2.694 1.732-2.5L12.998 3c.77-.833 1.964-.833 2.694 0l6.938 6.938c.77.833 1.964.833 2.694 0l1.732-2.5c.77-.833.192-2.694-1.732-2.5z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-base font-semibold text-white">High Value Transactions</p>
                            <p class="text-sm text-red-100">{{ $data['highValuePengajuan']->count() }} items • {{ formatRupiah($data['highValuePengajuan']->sum('total_pengajuan')) }}</p>
                        </div>
                    </div>
                    <span class="px-4 py-2 text-sm font-semibold rounded-full bg-white text-red-600">> 50 Million</span>
                </div>
            </div>
        @endif

        <!-- Pending Approvals & Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <!-- Pending Approvals -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <span class="text-sm font-semibold text-slate-900">Pending Approvals</span>
                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-700">{{ $data['pendingApprovals']->count() }} Items</span>
                </div>
                <div class="p-4 max-h-72 overflow-y-auto">
                    @if($data['pendingApprovals']->count() > 0)
                        <div class="space-y-3">
                            @foreach($data['pendingApprovals'] as $approval)
                                <a href="{{ route('approvals.show', $approval) }}" class="flex items-start gap-4 p-4 bg-gradient-to-r from-amber-50 to-amber-100/50 rounded-xl hover:from-amber-100 hover:to-amber-200/50 transition-all duration-200">
                                    <div class="flex-shrink-0 w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-sm">
                                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-1.333-1.964-.833-2.694 0L3.34 16c-.77.833-1.964.833-2.694 0L3.34 7c-.77.833.192-2.694 1.732-2.5L12.998 3c.77-.833 1.964-.833 2.694 0l6.938 6.938c.77.833 1.964.833 2.694 0l1.732-2.5c.77-.833.192-2.694-1.732-2.5z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <p class="font-semibold text-slate-900 text-sm truncate">{{ $approval->pengajuanDana->nomor_pengajuan ?? '-' }}</p>
                                            <span class="text-xs text-amber-700 font-semibold ml-2">Level {{ $approval->level }}</span>
                                        </div>
                                        <p class="text-sm text-slate-600 truncate">{{ $approval->pengajuanDana->judul_pengajuan }}</p>
                                        <p class="text-xs text-slate-500">Waiting: {{ $approval->approver->full_name ?? 'N/A' }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-emerald-500 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-sm text-slate-500">No pending approvals</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
                <div class="px-6 py-4 border-b border-slate-100">
                    <span class="text-sm font-semibold text-slate-900">Quick Actions</span>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-1 gap-4">
                        <a href="{{ route('approvals.index') }}" class="flex items-center gap-4 p-4 bg-gradient-to-r from-blue-50 to-blue-100/50 rounded-xl hover:from-blue-100 hover:to-blue-200/50 transition-all duration-200">
                            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-sm">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <span class="text-sm font-semibold text-slate-700">All Approvals</span>
                        </a>

                        <a href="{{ route('reports.refund') }}" class="flex items-center gap-4 p-4 bg-gradient-to-r from-cyan-50 to-cyan-100/50 rounded-xl hover:from-cyan-100 hover:to-cyan-200/50 transition-all duration-200">
                            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-sm">
                                <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <span class="text-sm font-semibold text-slate-700">Refund Report</span>
                        </a>

                        <a href="{{ route('pengajuan-dana.create') }}" class="flex items-center gap-4 p-4 bg-gradient-to-r from-emerald-50 to-emerald-100/50 rounded-xl hover:from-emerald-100 hover:to-emerald-200/50 transition-all duration-200">
                            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-sm">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </div>
                            <span class="text-sm font-semibold text-slate-700">Buat Pengajuan</span>
                        </a>
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
                                <span class="text-xs text-slate-500">{{ $approval->pengajuanDana->divisi->nama_divisi ?? '-' }}</span>
                                <span class="text-sm font-bold text-slate-900">{{ formatRupiah($approval->pengajuanDana->total_pengajuan) }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Recent Pengajuan -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-4 md:px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <span class="text-sm font-semibold text-slate-900">Recent Pengajuan</span>
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
                            <th class="px-5 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Total</th>
                            <th class="px-5 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($data['recentPengajuan'] as $pengajuan)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-5 py-4"><span class="font-semibold text-slate-900 text-sm">{{ $pengajuan->nomor_pengajuan ?? '-' }}</span></td>
                                <td class="px-5 py-4"><p class="text-slate-900 text-sm">{{ $pengajuan->judul_pengajuan }}</p></td>
                                <td class="px-5 py-4"><span class="text-slate-700 text-sm">{{ $pengajuan->divisi->nama_divisi ?? '-' }}</span></td>
                                <td class="px-5 py-4"><span class="font-bold text-slate-900 text-sm">{{ formatRupiah($pengajuan->total_pengajuan) }}</span></td>
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
