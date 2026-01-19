<x-app-layout>
    <x-slot name="header">
        <x-dashboard.header :userName="Auth::user()->name" />
    </x-slot>

    <div class="space-y-4">
        <!-- Quick Stats -->
        <div class="grid grid-cols-5 gap-3">
            <!-- Total Pagu -->
            <div class="bg-white rounded-xl border border-blue-100 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">{{ formatRupiah($data['totalPagu']) }}</p>
                        <p class="text-xs text-gray-500">Total Pagu</p>
                    </div>
                </div>
            </div>

            <!-- Menunggu -->
            <a href="{{ route('approvals.index') }}" class="bg-white rounded-xl border border-blue-100 p-4 hover:border-blue-200 transition-all">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-amber-50 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xl font-semibold text-gray-900">{{ $data['pengajuanMenunggu'] }}</p>
                        <p class="text-xs text-gray-500">Menunggu</p>
                    </div>
                </div>
            </a>

            <!-- Disetujui -->
            <div class="bg-white rounded-xl border border-blue-100 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-emerald-50 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xl font-semibold text-gray-900">{{ $data['pengajuanDisetujui'] }}</p>
                        <p class="text-xs text-gray-500">Disetujui</p>
                    </div>
                </div>
            </div>

            <!-- Ditolak -->
            <div class="bg-white rounded-xl border border-blue-100 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-red-50 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xl font-semibold text-gray-900">{{ $data['pengajuanDitolak'] }}</p>
                        <p class="text-xs text-gray-500">Ditolak</p>
                    </div>
                </div>
            </div>

            <!-- Pencairan -->
            <div class="bg-white rounded-xl border border-blue-100 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-cyan-50 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xl font-semibold text-gray-900">{{ $data['pencairanPending'] }}</p>
                        <p class="text-xs text-gray-500">Pencairan</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- High Value Alert -->
        @if($data['highValuePengajuan']->count() > 0)
            <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-xl p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-1.333-1.964-.833-2.694 0L3.34 16c-.77.833-1.964.833-2.694 0L3.34 7c-.77.833.192-2.694 1.732-2.5L12.998 3c.77-.833 1.964-.833 2.694 0l6.938 6.938c.77.833 1.964.833 2.694 0l1.732-2.5c.77-.833.192-2.694-1.732-2.5z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-white">High Value Transactions</p>
                            <p class="text-xs text-red-100">{{ $data['highValuePengajuan']->count() }} items • {{ formatRupiah($data['highValuePengajuan']->sum('total_pengajuan')) }}</p>
                        </div>
                    </div>
                    <span class="px-3 py-1 text-xs font-medium rounded-full bg-white text-red-600">> 50 Million</span>
                </div>
            </div>
        @endif

        <!-- Pending Approvals & Quick Actions -->
        <div class="grid grid-cols-2 gap-4">
            <!-- Pending Approvals -->
            <div class="bg-white rounded-xl border border-blue-100">
                <div class="px-4 py-3 border-b border-blue-100 flex items-center justify-between">
                    <span class="text-sm font-semibold text-gray-900">Pending Approvals</span>
                    <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-amber-100 text-amber-700">{{ $data['pendingApprovals']->count() }} Items</span>
                </div>
                <div class="p-3 max-h-64 overflow-y-auto">
                    @if($data['pendingApprovals']->count() > 0)
                        <div class="space-y-2">
                            @foreach($data['pendingApprovals'] as $approval)
                                <a href="{{ route('approvals.show', $approval) }}" class="flex items-start gap-3 p-3 bg-amber-50 rounded-xl hover:bg-amber-100 transition-colors">
                                    <div class="flex-shrink-0 w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-1.333-1.964-.833-2.694 0L3.34 16c-.77.833-1.964.833-2.694 0L3.34 7c-.77.833.192-2.694 1.732-2.5L12.998 3c.77-.833 1.964-.833 2.694 0l6.938 6.938c.77.833 1.964.833 2.694 0l1.732-2.5c.77-.833.192-2.694-1.732-2.5z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <p class="font-semibold text-gray-900 text-xs truncate">{{ $approval->pengajuanDana->nomor_pengajuan ?? '-' }}</p>
                                            <span class="text-xs text-amber-600 font-medium ml-2">Level {{ $approval->level }}</span>
                                        </div>
                                        <p class="text-xs text-gray-600 truncate">{{ $approval->pengajuanDana->judul_pengajuan }}</p>
                                        <p class="text-xs text-gray-500">Waiting: {{ $approval->approver->full_name ?? 'N/A' }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6">
                            <svg class="w-10 h-10 text-emerald-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-xs text-gray-500">No pending approvals</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl border border-blue-100">
                <div class="px-4 py-3 border-b border-blue-100">
                    <span class="text-sm font-semibold text-gray-900">Quick Actions</span>
                </div>
                <div class="p-4">
                    <div class="grid grid-cols-1 gap-3">
                        <a href="{{ route('approvals.index') }}" class="flex items-center gap-3 p-3 bg-blue-50 rounded-xl hover:bg-blue-100 transition-colors">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-700">All Approvals</span>
                        </a>

                        <a href="{{ route('reports.refund') }}" class="flex items-center gap-3 p-3 bg-cyan-50 rounded-xl hover:bg-cyan-100 transition-colors">
                            <div class="w-8 h-8 bg-cyan-100 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-700">Refund Report</span>
                        </a>

                        <a href="{{ route('pengajuan-dana.create') }}" class="flex items-center gap-3 p-3 bg-emerald-50 rounded-xl hover:bg-emerald-100 transition-colors">
                            <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-700">Buat Pengajuan</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- My Pending Approvals -->
        @if(isset($data['myPendingApprovals']) && $data['myPendingApprovals']->count() > 0)
            <div class="bg-gradient-to-r from-blue-600 to-cyan-500 rounded-xl p-4">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-white">Awaiting Your Approval</p>
                            <p class="text-xs text-blue-100">{{ $data['myPendingApprovals']->count() }} pengajuan</p>
                        </div>
                    </div>
                    <a href="{{ route('approvals.index') }}" class="px-4 py-2 bg-white text-blue-600 text-sm font-medium rounded-lg hover:bg-blue-50 transition-colors">View All</a>
                </div>
                <div class="grid grid-cols-3 gap-3">
                    @foreach($data['myPendingApprovals'] as $approval)
                        <a href="{{ route('approvals.show', $approval) }}" class="p-3 bg-white rounded-xl hover:bg-blue-50 transition-colors">
                            <div class="flex justify-between items-start mb-2">
                                <span class="font-semibold text-gray-900 text-xs">{{ $approval->pengajuanDana->nomor_pengajuan }}</span>
                                <span class="text-xs px-2 py-0.5 rounded-full bg-blue-100 text-blue-700">Lvl {{ $approval->level }}</span>
                            </div>
                            <p class="text-xs text-gray-600 mb-2">{{ $approval->pengajuanDana->judul_pengajuan }}</p>
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-gray-500">{{ $approval->pengajuanDana->divisi->nama_divisi ?? '-' }}</span>
                                <span class="text-xs font-semibold text-gray-900">{{ formatRupiah($approval->pengajuanDana->total_pengajuan) }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Recent Pengajuan -->
        <div class="bg-white rounded-xl border border-blue-100 overflow-hidden">
            <div class="px-4 py-3 border-b border-blue-100 flex items-center justify-between">
                <span class="text-sm font-semibold text-gray-900">Recent Pengajuan</span>
                <a href="{{ route('pengajuan-dana.index') }}" class="text-blue-600 hover:text-blue-700 text-xs font-medium">View All →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-blue-50 border-b border-blue-100">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-semibold text-blue-700 uppercase">Nomor</th>
                            <th class="px-4 py-2 text-left text-xs font-semibold text-blue-700 uppercase">Judul</th>
                            <th class="px-4 py-2 text-left text-xs font-semibold text-blue-700 uppercase">Divisi</th>
                            <th class="px-4 py-2 text-left text-xs font-semibold text-blue-700 uppercase">Total</th>
                            <th class="px-4 py-2 text-left text-xs font-semibold text-blue-700 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-blue-50">
                        @foreach($data['recentPengajuan'] as $pengajuan)
                            <tr class="hover:bg-blue-50/50 transition-colors">
                                <td class="px-4 py-3"><span class="font-medium text-gray-900 text-sm">{{ $pengajuan->nomor_pengajuan ?? '-' }}</span></td>
                                <td class="px-4 py-3"><p class="text-gray-900 text-sm">{{ $pengajuan->judul_pengajuan }}</p></td>
                                <td class="px-4 py-3"><span class="text-gray-700 text-sm">{{ $pengajuan->divisi->nama_divisi ?? '-' }}</span></td>
                                <td class="px-4 py-3"><span class="font-semibold text-gray-900 text-sm">{{ formatRupiah($pengajuan->total_pengajuan) }}</span></td>
                                <td class="px-4 py-3">
                                    @php
                                        $statusColors = ['menunggu_approval' => 'bg-amber-100 text-amber-700', 'disetujui' => 'bg-emerald-100 text-emerald-700', 'ditolak' => 'bg-red-100 text-red-700'];
                                        $statusColor = $statusColors[$pengajuan->status] ?? 'bg-gray-100 text-gray-600';
                                    @endphp
                                    <span class="px-2 py-0.5 text-xs font-medium rounded-full {{ $statusColor }}">{{ ucfirst(str_replace('_', ' ', $pengajuan->status)) }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
