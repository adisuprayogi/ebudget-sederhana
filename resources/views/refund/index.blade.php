<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">Pengembalian Dana (Refund)</h1>
                <p class="text-sm text-gray-500 mt-0.5">Kelola pengembalian dana dari pencairan atau pengajuan</p>
            </div>
            @if(auth()->user()->hasAnyRole(['direktur_keuangan', 'direktur_utama', 'kepala_divisi', 'staff_keuangan']))
                <a href="{{ route('refund.select-lpj') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Buat Refund Baru
                </a>
            @endif
        </div>
    </x-slot>

    <!-- Quick Stats -->
    <div class="grid grid-cols-4 gap-3 mb-4">
        <div class="bg-white rounded-xl border border-blue-100 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xl font-semibold text-gray-900">{{ $statistics['total_count'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500">Total Refund</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-blue-100 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-amber-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xl font-semibold text-gray-900">{{ $statistics['pending_count'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500">Menunggu Approval</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-blue-100 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-emerald-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xl font-semibold text-gray-900">{{ ($statistics['approved_count'] ?? 0) + ($statistics['processed_count'] ?? 0) }}</p>
                    <p class="text-xs text-gray-500">Disetujui/Selesai</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-blue-100 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-cyan-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-900">{{ formatRupiah($statistics['total_amount'] ?? 0) }}</p>
                    <p class="text-xs text-gray-500">Total Nilai</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-xl border border-blue-100 p-3 mb-4">
        <form method="GET" action="{{ route('refund.index') }}" class="flex flex-wrap items-center gap-3">
            <div class="min-w-[200px] flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor atau alasan refund..." class="w-full px-3 py-2 text-sm border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
            </div>
            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 2.586V4z" />
                </svg>
                Filter
            </button>
            @if(request()->filled('search'))
                <a href="{{ route('refund.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm font-medium rounded-lg transition-colors">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Tabs -->
    <div class="bg-white rounded-xl border border-blue-100 overflow-hidden mb-4">
        <div class="flex flex-wrap border-b border-blue-100 overflow-x-auto">
            <button onclick="showTab('menunggu-refund')" id="tab-menunggu-refund" class="flex-1 min-w-[120px] px-3 py-3 text-sm font-semibold border-b-2 border-orange-500 text-orange-600 bg-orange-50 transition-colors">
                <div class="flex items-center justify-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="hidden md:inline">Menunggu Refund</span>
                    <span class="md:hidden">Refund</span>
                    <span class="bg-orange-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $stats['menunggu_refund'] ?? 0 }}</span>
                </div>
            </button>
            <button onclick="showTab('draft')" id="tab-draft" class="flex-1 min-w-[100px] px-3 py-3 text-sm font-semibold border-b-2 border-transparent text-gray-600 hover:bg-blue-50 transition-colors">
                <div class="flex items-center justify-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="hidden md:inline">Draft</span>
                    <span class="bg-gray-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $stats['draft'] ?? 0 }}</span>
                </div>
            </button>
            <button onclick="showTab('menunggu-approval')" id="tab-menunggu-approval" class="flex-1 min-w-[100px] px-3 py-3 text-sm font-semibold border-b-2 border-transparent text-gray-600 hover:bg-blue-50 transition-colors">
                <div class="flex items-center justify-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="hidden md:inline">Approval</span>
                    <span class="bg-amber-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $stats['menunggu_approval'] ?? 0 }}</span>
                </div>
            </button>
            <button onclick="showTab('approved')" id="tab-approved" class="flex-1 min-w-[100px] px-3 py-3 text-sm font-semibold border-b-2 border-transparent text-gray-600 hover:bg-blue-50 transition-colors">
                <div class="flex items-center justify-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="hidden md:inline">Disetujui</span>
                    <span class="bg-blue-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $stats['approved'] ?? 0 }}</span>
                </div>
            </button>
            <button onclick="showTab('processed')" id="tab-processed" class="flex-1 min-w-[100px] px-3 py-3 text-sm font-semibold border-b-2 border-transparent text-gray-600 hover:bg-blue-50 transition-colors">
                <div class="flex items-center justify-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span class="hidden md:inline">Selesai</span>
                    <span class="bg-emerald-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $stats['processed'] ?? 0 }}</span>
                </div>
            </button>
            <button onclick="showTab('rejected')" id="tab-rejected" class="flex-1 min-w-[100px] px-3 py-3 text-sm font-semibold border-b-2 border-transparent text-gray-600 hover:bg-blue-50 transition-colors">
                <div class="flex items-center justify-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="hidden md:inline">Ditolak</span>
                    <span class="bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $stats['rejected'] ?? 0 }}</span>
                </div>
            </button>
        </div>
    </div>

    <!-- Tab Content: Menunggu Refund -->
    <div id="content-menunggu-refund" class="tab-content">
        @if($lpjsMenungguRefund && $lpjsMenungguRefund->count() > 0)
            <div class="bg-white rounded-xl border border-blue-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-orange-50 border-b border-orange-100">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-orange-700 uppercase">Nomor LPJ</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-orange-700 uppercase">Judul LPJ</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-orange-700 uppercase">Divisi</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-orange-700 uppercase">Pengaju</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-orange-700 uppercase">Sisa Dana</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-orange-700 uppercase">Tgl Approve LPJ</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-orange-700 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-orange-50">
                            @foreach($lpjsMenungguRefund as $lpj)
                            <tr class="hover:bg-orange-50/50 transition-colors">
                                <td class="px-4 py-3">
                                    <span class="font-mono font-semibold text-orange-600 text-sm">{{ $lpj->nomor_lpj }}</span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $lpj->judul_lpj ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">
                                    @if($lpj->pencairanDana && $lpj->pencairanDana->pengajuanDana && $lpj->pencairanDana->pengajuanDana->divisi)
                                        {{ $lpj->pencairanDana->pengajuanDana->divisi->nama_divisi }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700">
                                    @if($lpj->pencairanDana && $lpj->pencairanDana->pengajuanDana && $lpj->pencairanDana->pengajuanDana->createdBy)
                                        {{ $lpj->pencairanDana->pengajuanDana->createdBy->name }}
                                    @elseif($lpj->createdBy)
                                        {{ $lpj->createdBy->name }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm font-semibold text-green-600">{{ formatRupiah($lpj->sisa_dana) }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ \Carbon\Carbon::parse($lpj->approved_at)->format('d/m/Y') }}</td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <a href="{{ route('lpj.show', $lpj) }}" target="_blank" class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Lihat Detail LPJ">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        <a href="{{ route('refund.create') }}?lpj_id={{ $lpj->id }}" class="inline-flex items-center gap-1 px-2 py-1.5 bg-orange-500 hover:bg-orange-600 text-white text-xs font-medium rounded-lg transition-colors" title="Buat Refund">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                            </svg>
                                            Buat Refund
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="bg-gray-50 px-4 py-3 border-t border-blue-100 flex items-center justify-between">
                    <p class="text-sm text-gray-600">
                        Menampilkan {{ $lpjsMenungguRefund->firstItem() }} sampai {{ $lpjsMenungguRefund->lastItem() }} dari {{ $lpjsMenungguRefund->total() }} LPJ
                    </p>
                    {{ $lpjsMenungguRefund->appends(request()->except('page'))->links() }}
                </div>

                <!-- Total Sisa Dana -->
                <div class="bg-orange-50 border-t border-orange-100 px-4 py-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-sm font-semibold text-orange-900">Total Sisa Dana:</span>
                        </div>
                        <span class="text-lg font-bold text-orange-600">{{ formatRupiah($totalSisaDanaRefund ?? 0) }}</span>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white rounded-xl border border-blue-100 p-12 text-center">
                <div class="w-12 h-12 bg-orange-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="text-gray-700 font-medium">Tidak Ada LPJ Menunggu Refund</p>
                <p class="text-gray-400 text-sm mt-1">LPJ dengan sisa dana akan ditampilkan di sini.</p>
            </div>
        @endif
    </div>

    <!-- Tab Content: Draft -->
    <div id="content-draft" class="tab-content hidden">
        @if($refundsDraft->count() > 0)
            <div class="bg-white rounded-xl border border-blue-100 overflow-hidden">
                @include('refund.partials.table', ['refunds' => $refundsDraft])
            </div>
        @else
            <div class="bg-white rounded-xl border border-blue-100 p-12 text-center">
                <div class="w-12 h-12 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <p class="text-gray-700 font-medium">Tidak Ada Draft</p>
                <p class="text-gray-400 text-sm mt-1">Refund draft akan ditampilkan di sini.</p>
            </div>
        @endif
    </div>

    <!-- Tab Content: Menunggu Approval -->
    <div id="content-menunggu-approval" class="tab-content hidden">
        @if($refundsMenungguApproval->count() > 0)
            <div class="bg-white rounded-xl border border-blue-100 overflow-hidden">
                @include('refund.partials.table', ['refunds' => $refundsMenungguApproval])
            </div>
        @else
            <div class="bg-white rounded-xl border border-blue-100 p-12 text-center">
                <div class="w-12 h-12 bg-amber-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="text-gray-700 font-medium">Tidak Ada Refund Menunggu Approval</p>
                <p class="text-gray-400 text-sm mt-1">Refund yang menunggu approval akan ditampilkan di sini.</p>
            </div>
        @endif
    </div>

    <!-- Tab Content: Disetujui -->
    <div id="content-approved" class="tab-content hidden">
        @if($refundsApproved->count() > 0)
            <div class="bg-white rounded-xl border border-blue-100 overflow-hidden">
                @include('refund.partials.table', ['refunds' => $refundsApproved])
            </div>
        @else
            <div class="bg-white rounded-xl border border-blue-100 p-12 text-center">
                <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="text-gray-700 font-medium">Belum Ada Refund Disetujui</p>
                <p class="text-gray-400 text-sm mt-1">Refund yang disetujui akan ditampilkan di sini.</p>
            </div>
        @endif
    </div>

    <!-- Tab Content: Selesai -->
    <div id="content-processed" class="tab-content hidden">
        @if($refundsProcessed->count() > 0)
            <div class="bg-white rounded-xl border border-blue-100 overflow-hidden">
                @include('refund.partials.table', ['refunds' => $refundsProcessed])
            </div>
        @else
            <div class="bg-white rounded-xl border border-blue-100 p-12 text-center">
                <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <p class="text-gray-700 font-medium">Belum Ada Refund Selesai</p>
                <p class="text-gray-400 text-sm mt-1">Refund yang selesai akan ditampilkan di sini.</p>
            </div>
        @endif
    </div>

    <!-- Tab Content: Ditolak -->
    <div id="content-rejected" class="tab-content hidden">
        @if($refundsRejected->count() > 0)
            <div class="bg-white rounded-xl border border-blue-100 overflow-hidden">
                @include('refund.partials.table', ['refunds' => $refundsRejected])
            </div>
        @else
            <div class="bg-white rounded-xl border border-blue-100 p-12 text-center">
                <div class="w-12 h-12 bg-red-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="text-gray-700 font-medium">Tidak Ada Refund Ditolak</p>
                <p class="text-gray-400 text-sm mt-1">Refund yang ditolak akan ditampilkan di sini.</p>
            </div>
        @endif
    </div>

    <script>
        function showTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(function(el) {
                el.classList.add('hidden');
            });

            // Remove active state from all tabs
            document.querySelectorAll('[id^="tab-"]').forEach(function(el) {
                el.classList.remove('border-gray-500', 'border-amber-500', 'border-blue-500', 'border-emerald-500', 'border-red-500', 'border-orange-500', 'text-gray-600', 'text-amber-600', 'text-blue-600', 'text-emerald-600', 'text-red-600', 'text-orange-600', 'bg-gray-50', 'bg-amber-50', 'bg-blue-50', 'bg-emerald-50', 'bg-red-50', 'bg-orange-50');
                el.classList.add('border-transparent', 'text-gray-600');
            });

            // Show selected tab content
            document.getElementById('content-' + tabName).classList.remove('hidden');

            // Set active state for selected tab
            var activeTab = document.getElementById('tab-' + tabName);
            activeTab.classList.remove('border-transparent', 'text-gray-600');

            if (tabName === 'menunggu-refund') {
                activeTab.classList.add('border-orange-500', 'text-orange-600', 'bg-orange-50');
            } else if (tabName === 'draft') {
                activeTab.classList.add('border-gray-500', 'text-gray-600', 'bg-gray-50');
            } else if (tabName === 'menunggu-approval') {
                activeTab.classList.add('border-amber-500', 'text-amber-600', 'bg-amber-50');
            } else if (tabName === 'approved') {
                activeTab.classList.add('border-blue-500', 'text-blue-600', 'bg-blue-50');
            } else if (tabName === 'processed') {
                activeTab.classList.add('border-emerald-500', 'text-emerald-600', 'bg-emerald-50');
            } else if (tabName === 'rejected') {
                activeTab.classList.add('border-red-500', 'text-red-600', 'bg-red-50');
            }
        }
    </script>
</x-app-layout>
