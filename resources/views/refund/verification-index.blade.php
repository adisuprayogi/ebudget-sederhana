<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">Verifikasi Refund</h1>
                <p class="text-sm text-gray-500 mt-0.5">Verifikasi pengajuan refund dan lihat history</p>
            </div>
        </div>
    </x-slot>

    <!-- Quick Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
        <div class="bg-white rounded-xl border border-blue-100 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xl font-semibold text-gray-900">{{ $stats['menunggu_refund'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500">Menunggu Refund</p>
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
                    <p class="text-xl font-semibold text-gray-900">{{ $stats['menunggu_approval'] ?? 0 }}</p>
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
                    <p class="text-xl font-semibold text-gray-900">{{ $stats['processed'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500">Selesai</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-blue-100 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-red-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xl font-semibold text-gray-900">{{ $stats['rejected'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500">Ditolak</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-xl border border-blue-100 p-3 mb-4">
        <form method="GET" action="{{ route('refund-verification.index') }}" class="flex flex-col sm:flex-row flex-wrap items-center gap-3">
            <div class="w-full sm:min-w-[140px] sm:w-auto">
                <select name="divisi_id" class="w-full px-3 py-2 text-sm border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all bg-white">
                    <option value="">Semua Divisi</option>
                    @foreach(\App\Models\Divisi::orderBy('nama_divisi')->get() as $divisi)
                        <option value="{{ $divisi->id }}" {{ request('divisi_id') == $divisi->id ? 'selected' : '' }}>{{ $divisi->nama_divisi }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-full sm:min-w-[140px] sm:w-auto">
                <select name="periode_anggaran_id" class="w-full px-3 py-2 text-sm border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all bg-white">
                    <option value="">Semua Periode</option>
                    @foreach(\App\Models\PeriodeAnggaran::orderBy('tahun_anggaran', 'desc')->get() as $periode)
                        <option value="{{ $periode->id }}" {{ request('periode_anggaran_id') == $periode->id ? 'selected' : '' }}>{{ $periode->nama_periode }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-full sm:min-w-[200px] sm:flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor atau alasan refund..." class="w-full px-3 py-2 text-sm border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
            </div>
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <button type="submit" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filter
                </button>
                @if(request()->anyFilled(['divisi_id', 'periode_anggaran_id', 'search']))
                    <a href="{{ route('refund-verification.index') }}" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-4 py-2 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm font-medium rounded-lg transition-colors">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Tabs -->
    <div class="bg-white rounded-xl border border-blue-100 overflow-hidden mb-4">
        <div class="flex flex-wrap border-b border-blue-100">
            <button onclick="showTab('menunggu-refund')" id="tab-menunggu-refund" class="flex-1 min-w-[120px] px-3 py-3 text-sm font-semibold border-b-2 border-blue-500 text-blue-600 bg-blue-50 transition-colors">
                <div class="flex items-center justify-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="hidden md:inline">Menunggu Refund</span>
                    <span class="md:hidden">Refund</span>
                    <span class="bg-blue-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $stats['menunggu_refund'] ?? 0 }}</span>
                </div>
            </button>
            <button onclick="showTab('menunggu-approval')" id="tab-menunggu-approval" class="flex-1 min-w-[120px] px-3 py-3 text-sm font-semibold border-b-2 border-transparent text-gray-600 hover:bg-blue-50 transition-colors">
                <div class="flex items-center justify-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="hidden md:inline">Menunggu Approval</span>
                    <span class="md:hidden">Approval</span>
                    <span class="bg-amber-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $stats['menunggu_approval'] ?? 0 }}</span>
                </div>
            </button>
            <button onclick="showTab('processed')" id="tab-processed" class="flex-1 min-w-[120px] px-3 py-3 text-sm font-semibold border-b-2 border-transparent text-gray-600 hover:bg-blue-50 transition-colors">
                <div class="flex items-center justify-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="hidden md:inline">Selesai</span>
                    <span class="md:hidden">Selesai</span>
                    <span class="bg-emerald-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $stats['processed'] ?? 0 }}</span>
                </div>
            </button>
            <button onclick="showTab('rejected')" id="tab-rejected" class="flex-1 min-w-[120px] px-3 py-3 text-sm font-semibold border-b-2 border-transparent text-gray-600 hover:bg-blue-50 transition-colors">
                <div class="flex items-center justify-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="hidden md:inline">Ditolak</span>
                    <span class="md:hidden">Ditolak</span>
                    <span class="bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $stats['rejected'] ?? 0 }}</span>
                </div>
            </button>
        </div>
    </div>

    <!-- Tab Content: Menunggu Refund -->
    <div id="content-menunggu-refund" class="tab-content">
        @if(isset($lpjsMenungguRefund) && $lpjsMenungguRefund->count() > 0)
            <div class="bg-white rounded-xl border border-blue-100 overflow-hidden">
                <!-- Mobile Card View -->
                <div class="md:hidden space-y-3">
                    @foreach($lpjsMenungguRefund as $lpj)
                        <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
                            <!-- Header -->
                            <div class="px-3 py-3 bg-gradient-to-r from-blue-50 to-blue-50/50 border-b border-blue-100">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="min-w-0 flex-1">
                                        <span class="font-mono text-xs font-bold text-blue-600 block truncate">{{ $lpj->nomor_lpj }}</span>
                                        <p class="text-xs text-slate-400 mt-0.5">{{ \Carbon\Carbon::parse($lpj->approved_at)->format('d/m/Y') }}</p>
                                    </div>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700 flex-shrink-0">
                                        {{ formatRupiah($lpj->sisa_dana) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Body -->
                            <div class="p-3 space-y-3">
                                <div>
                                    <p class="text-sm font-semibold text-slate-900 line-clamp-2">{{ $lpj->judul_lpj ?? '-' }}</p>
                                </div>

                                <div class="text-xs">
                                    <span class="text-slate-500">Divisi:</span>
                                    <span class="ml-1 font-medium text-slate-700 truncate block">
                                        @if($lpj->pencairanDana && $lpj->pencairanDana->pengajuanDana && $lpj->pencairanDana->pengajuanDana->divisi)
                                            {{ $lpj->pencairanDana->pengajuanDana->divisi->nama_divisi }}
                                        @else
                                            -
                                        @endif
                                    </span>
                                </div>

                                <div class="text-xs">
                                    <span class="text-slate-500">Pengajuan:</span>
                                    <span class="ml-1 font-medium text-slate-700 truncate block">
                                        @if($lpj->pencairanDana && $lpj->pencairanDana->pengajuanDana)
                                            {{ $lpj->pencairanDana->pengajuanDana->judul_pengajuan ?? '-' }}
                                        @else
                                            -
                                        @endif
                                    </span>
                                </div>

                                <div class="text-xs">
                                    <span class="text-slate-500">Pengaju:</span>
                                    <span class="ml-1 font-medium text-slate-700">
                                        @if($lpj->pencairanDana && $lpj->pencairanDana->pengajuanDana && $lpj->pencairanDana->pengajuanDana->createdBy)
                                            {{ $lpj->pencairanDana->pengajuanDana->createdBy->name }}
                                        @elseif($lpj->createdBy)
                                            {{ $lpj->createdBy->name }}
                                        @else
                                            -
                                        @endif
                                    </span>
                                </div>

                                <div class="flex items-center justify-end pt-2 border-t border-slate-100 gap-1.5">
                                    <a href="{{ route('lpj.show', $lpj) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-2 text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors text-xs font-medium">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <span class="hidden sm:inline">Lihat LPJ</span>
                                    </a>
                                    <button onclick="sendRefundReminder({{ $lpj->id }}, '{{ $lpj->nomor_lpj }}', '{{ $lpj->pencairanDana->pengajuanDana->createdBy->name ?? 'Pengaju' }}')" class="inline-flex items-center gap-1.5 px-3 py-2 text-amber-600 bg-amber-50 rounded-lg hover:bg-amber-100 transition-colors text-xs font-medium">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                        </svg>
                                        <span class="hidden sm:inline">Ingatkan</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Desktop Table View -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-blue-50 border-b border-blue-100">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Nomor LPJ</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Judul LPJ</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Divisi</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Pengajuan</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-blue-700 uppercase">Sisa Dana</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Tgl Approve LPJ</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-blue-700 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-blue-50">
                            @foreach($lpjsMenungguRefund as $lpj)
                            <tr class="hover:bg-blue-50/50 transition-colors">
                                <td class="px-4 py-3">
                                    <span class="font-mono font-semibold text-blue-600 text-sm">{{ $lpj->nomor_lpj }}</span>
                                    <div class="text-xs text-gray-500 mt-0.5">
                                        @if($lpj->pencairanDana && $lpj->pencairanDana->pengajuanDana && $lpj->pencairanDana->pengajuanDana->createdBy)
                                            Pengaju: {{ $lpj->pencairanDana->pengajuanDana->createdBy->name }}
                                        @elseif($lpj->createdBy)
                                            Pengaju: {{ $lpj->createdBy->name }}
                                        @else
                                            Pengaju: -
                                        @endif
                                    </div>
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
                                    @if($lpj->pencairanDana && $lpj->pencairanDana->pengajuanDana)
                                        {{ $lpj->pencairanDana->pengajuanDana->judul_pengajuan ?? '-' }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm font-semibold text-green-600">{{ formatRupiah($lpj->sisa_dana) }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ \Carbon\Carbon::parse($lpj->approved_at)->format('d/m/Y') }}</td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <a href="{{ route('lpj.show', $lpj) }}" target="_blank" class="inline-flex items-center gap-1 px-2 py-1.5 text-blue-600 hover:text-blue-700 hover:bg-blue-50 text-xs font-medium rounded-lg transition-colors" title="Lihat Detail LPJ">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        <button onclick="sendRefundReminder({{ $lpj->id }}, '{{ $lpj->nomor_lpj }}', '{{ $lpj->pencairanDana->pengajuanDana->createdBy->name ?? 'Pengaju' }}')" class="inline-flex items-center gap-1.5 px-2 py-1.5 bg-amber-500 hover:bg-amber-600 text-white text-xs font-medium rounded-lg transition-colors" title="Ingatkan Pengaju">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="bg-gray-50 px-4 py-3 border-t border-blue-100">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                        <p class="text-xs md:text-sm text-gray-600 text-center md:text-left">
                            <span class="hidden md:inline">Menampilkan {{ $lpjsMenungguRefund->firstItem() }} sampai {{ $lpjsMenungguRefund->lastItem() }} dari {{ $lpjsMenungguRefund->total() }} LPJ</span>
                            <span class="md:hidden">{{ $lpjsMenungguRefund->total() }} LPJ</span>
                        </p>
                        {{ $lpjsMenungguRefund->appends(request()->except('page'))->links() }}
                    </div>
                </div>

                <!-- Total Sisa Dana -->
                <div class="bg-blue-50 border-t border-blue-100 px-3 md:px-4 py-2 md:py-3">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-sm font-semibold text-blue-900">Total Sisa Dana Belum Di-Refund:</span>
                        </div>
                        <span class="text-base md:text-lg font-bold text-blue-600 truncate">{{ formatRupiah($totalSisaDana ?? 0) }}</span>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white rounded-xl border border-blue-100 p-16 text-center">
                <div class="flex flex-col items-center">
                    <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p class="text-gray-700 font-medium">Tidak Ada LPJ dengan Sisa Dana</p>
                    <p class="text-gray-400 text-sm mt-1">LPJ yang sudah disetujui dengan sisa dana akan ditampilkan di sini.</p>
                </div>
            </div>
        @endif
    </div>

    <!-- Tab Content: Menunggu Approval -->
    <div id="content-menunggu-approval" class="tab-content hidden">
        @if($refunds->count() > 0)
            <div class="bg-white rounded-xl border border-blue-100 overflow-hidden">
                <!-- Mobile Card View -->
                <div class="md:hidden space-y-3">
                    @foreach($refunds as $refund)
                        <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
                            <!-- Header -->
                            <div class="px-3 py-3 bg-gradient-to-r from-amber-50 to-amber-50/50 border-b border-amber-100">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="min-w-0 flex-1">
                                        <span class="font-mono text-xs font-bold text-amber-600 block truncate">{{ $refund->nomor_refund }}</span>
                                        <p class="text-xs text-slate-400 mt-0.5">{{ \Carbon\Carbon::parse($refund->created_at)->format('d/m/Y') }}</p>
                                    </div>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-700 flex-shrink-0">
                                        Approval
                                    </span>
                                </div>
                            </div>

                            <!-- Body -->
                            <div class="p-3 space-y-3">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center px-2 py-0.5 bg-blue-100 text-blue-700 text-xs font-medium rounded-lg">
                                        {{ ucfirst(str_replace('_', ' ', $refund->jenis_refund)) }}
                                    </span>
                                    <span class="text-sm font-semibold text-slate-900">{{ formatRupiah($refund->jumlah_refund) }}</span>
                                </div>

                                <div class="text-xs">
                                    <span class="text-slate-500">Divisi:</span>
                                    <span class="ml-1 font-medium text-slate-700 truncate block">
                                        @if($refund->pencairanDana && $refund->pencairanDana->pengajuanDana)
                                            {{ $refund->pencairanDana->pengajuanDana->divisi->nama_divisi ?? '-' }}
                                        @elseif($refund->pengajuanDana)
                                            {{ $refund->pengajuanDana->divisi->nama_divisi ?? '-' }}
                                        @else
                                            -
                                        @endif
                                    </span>
                                </div>

                                <div class="text-xs">
                                    <span class="text-slate-500">Oleh:</span>
                                    <span class="ml-1 font-medium text-slate-700">{{ $refund->createdBy->name ?? '-' }}</span>
                                </div>

                                <div class="flex items-center justify-end pt-2 border-t border-slate-100 gap-1.5">
                                    <a href="{{ route('refund.show', $refund) }}" class="inline-flex items-center gap-1.5 px-3 py-2 text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors text-xs font-medium">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <span class="hidden sm:inline">Lihat</span>
                                    </a>
                                    <button onclick="quickApprove({{ $refund->id }}, '{{ $refund->nomor_refund }}')" class="inline-flex items-center gap-1.5 px-3 py-2 text-emerald-600 bg-emerald-50 rounded-lg hover:bg-emerald-100 transition-colors text-xs font-medium">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span class="hidden sm:inline">Setujui</span>
                                    </button>
                                    <button onclick="quickReject({{ $refund->id }}, '{{ $refund->nomor_refund }}')" class="inline-flex items-center gap-1.5 px-3 py-2 text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors text-xs font-medium">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        <span class="hidden sm:inline">Tolak</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Desktop Table View -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-blue-50 border-b border-blue-100">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Nomor Refund</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Jenis</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Divisi</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Jumlah</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Diajukan Oleh</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Tgl Pengajuan</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-blue-700 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-blue-50">
                            @foreach($refunds as $refund)
                            <tr class="hover:bg-blue-50/50 transition-colors">
                                <td class="px-4 py-3">
                                    <span class="font-mono font-semibold text-blue-600 text-sm">{{ $refund->nomor_refund }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2 py-0.5 bg-blue-100 text-blue-700 text-xs font-medium rounded-lg">
                                        {{ ucfirst(str_replace('_', ' ', $refund->jenis_refund)) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700">
                                    @if($refund->pencairanDana && $refund->pencairanDana->pengajuanDana)
                                        {{ $refund->pencairanDana->pengajuanDana->divisi->nama_divisi ?? '-' }}
                                    @elseif($refund->pengajuanDana)
                                        {{ $refund->pengajuanDana->divisi->nama_divisi ?? '-' }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm font-semibold text-gray-900">{{ formatRupiah($refund->jumlah_refund) }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $refund->createdBy->name ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ \Carbon\Carbon::parse($refund->created_at)->format('d/m/Y') }}</td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-0.5">
                                        <a href="{{ route('refund.show', $refund) }}" class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Lihat Detail">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        <button onclick="quickApprove({{ $refund->id }}, '{{ $refund->nomor_refund }}')" class="p-1.5 text-emerald-500 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" title="Setujui">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </button>
                                        <button onclick="quickReject({{ $refund->id }}, '{{ $refund->nomor_refund }}')" class="p-1.5 text-red-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Tolak">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="bg-gray-50 px-4 py-3 border-t border-blue-100">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                        <p class="text-xs md:text-sm text-gray-600 text-center md:text-left">
                            <span class="hidden md:inline">Menampilkan {{ $refunds->firstItem() }} sampai {{ $refunds->lastItem() }} dari {{ $refunds->total() }} refund</span>
                            <span class="md:hidden">{{ $refunds->total() }} refund</span>
                        </p>
                        {{ $refunds->appends(request()->except('page'))->links() }}
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white rounded-xl border border-blue-100 p-16 text-center">
                <div class="flex flex-col items-center">
                    <div class="w-16 h-16 bg-emerald-50 rounded-2xl flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p class="text-gray-700 font-medium">Tidak Ada Refund Menunggu Verifikasi</p>
                    <p class="text-gray-400 text-sm mt-1">Semua refund telah diverifikasi.</p>
                </div>
            </div>
        @endif
    </div>

    <!-- Tab Content: Disetujui -->
    <div id="content-processed" class="tab-content hidden">
        @if($refundsProcessed->count() > 0)
            <div class="bg-white rounded-xl border border-blue-100 overflow-hidden">
                <!-- Mobile Card View -->
                <div class="md:hidden space-y-3">
                    @foreach($refundsProcessed as $refund)
                        <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
                            <!-- Header -->
                            <div class="px-3 py-3 bg-gradient-to-r from-emerald-50 to-emerald-50/50 border-b border-emerald-100">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="min-w-0 flex-1">
                                        <span class="font-mono text-xs font-bold text-emerald-600 block truncate">{{ $refund->nomor_refund }}</span>
                                        <p class="text-xs text-slate-400 mt-0.5">{{ \Carbon\Carbon::parse($refund->approved_at)->format('d/m/Y H:i') }}</p>
                                    </div>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700 flex-shrink-0">
                                        Selesai
                                    </span>
                                </div>
                            </div>

                            <!-- Body -->
                            <div class="p-3 space-y-3">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center px-2 py-0.5 bg-blue-100 text-blue-700 text-xs font-medium rounded-lg">
                                        {{ ucfirst(str_replace('_', ' ', $refund->jenis_refund)) }}
                                    </span>
                                    <span class="text-sm font-semibold text-slate-900">{{ formatRupiah($refund->jumlah_refund) }}</span>
                                </div>

                                <div class="text-xs">
                                    <span class="text-slate-500">Divisi:</span>
                                    <span class="ml-1 font-medium text-slate-700 truncate block">
                                        @if($refund->pencairanDana && $refund->pencairanDana->pengajuanDana)
                                            {{ $refund->pencairanDana->pengajuanDana->divisi->nama_divisi ?? '-' }}
                                        @elseif($refund->pengajuanDana)
                                            {{ $refund->pengajuanDana->divisi->nama_divisi ?? '-' }}
                                        @else
                                            -
                                        @endif
                                    </span>
                                </div>

                                <div class="text-xs">
                                    <span class="text-slate-500">Disetujui oleh:</span>
                                    <span class="ml-1 font-medium text-slate-700">{{ $refund->approvedBy->name ?? '-' }}</span>
                                </div>

                                <div class="flex items-center justify-end pt-2 border-t border-slate-100 gap-1.5">
                                    <a href="{{ route('refund.show', $refund) }}" class="inline-flex items-center gap-1.5 px-3 py-2 text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors text-xs font-medium">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <span class="hidden sm:inline">Lihat</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Desktop Table View -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-blue-50 border-b border-blue-100">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Nomor Refund</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Jenis</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Divisi</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Jumlah</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Disetujui Oleh</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Tgl Disetujui</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-blue-700 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-blue-50">
                            @foreach($refundsProcessed as $refund)
                            <tr class="hover:bg-blue-50/50 transition-colors">
                                <td class="px-4 py-3">
                                    <span class="font-mono font-semibold text-blue-600 text-sm">{{ $refund->nomor_refund }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2 py-0.5 bg-blue-100 text-blue-700 text-xs font-medium rounded-lg">
                                        {{ ucfirst(str_replace('_', ' ', $refund->jenis_refund)) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700">
                                    @if($refund->pencairanDana && $refund->pencairanDana->pengajuanDana)
                                        {{ $refund->pencairanDana->pengajuanDana->divisi->nama_divisi ?? '-' }}
                                    @elseif($refund->pengajuanDana)
                                        {{ $refund->pengajuanDana->divisi->nama_divisi ?? '-' }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm font-semibold text-gray-900">{{ formatRupiah($refund->jumlah_refund) }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $refund->approvedBy->name ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ \Carbon\Carbon::parse($refund->approved_at)->format('d/m/Y H:i') }}</td>
                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('refund.show', $refund) }}" class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Lihat Detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="bg-gray-50 px-4 py-3 border-t border-blue-100">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                        <p class="text-xs md:text-sm text-gray-600 text-center md:text-left">
                            <span class="hidden md:inline">Menampilkan {{ $refundsProcessed->firstItem() }} sampai {{ $refundsProcessed->lastItem() }} dari {{ $refundsProcessed->total() }} refund</span>
                            <span class="md:hidden">{{ $refundsProcessed->total() }} refund</span>
                        </p>
                        {{ $refundsProcessed->appends(request()->except('page'))->links() }}
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white rounded-xl border border-blue-100 p-16 text-center">
                <div class="flex flex-col items-center">
                    <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p class="text-gray-700 font-medium">Belum Ada Refund Disetujui</p>
                    <p class="text-gray-400 text-sm mt-1">Refund yang disetujui akan ditampilkan di sini.</p>
                </div>
            </div>
        @endif
    </div>

    <!-- Tab Content: Ditolak -->
    <div id="content-rejected" class="tab-content hidden">
        @if($refundsRejected->count() > 0)
            <div class="bg-white rounded-xl border border-blue-100 overflow-hidden">
                <!-- Mobile Card View -->
                <div class="md:hidden space-y-3">
                    @foreach($refundsRejected as $refund)
                        <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
                            <!-- Header -->
                            <div class="px-3 py-3 bg-gradient-to-r from-red-50 to-red-50/50 border-b border-red-100">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="min-w-0 flex-1">
                                        <span class="font-mono text-xs font-bold text-red-600 block truncate">{{ $refund->nomor_refund }}</span>
                                        <p class="text-xs text-slate-400 mt-0.5">{{ \Carbon\Carbon::parse($refund->approved_at)->format('d/m/Y H:i') }}</p>
                                    </div>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700 flex-shrink-0">
                                        Ditolak
                                    </span>
                                </div>
                            </div>

                            <!-- Body -->
                            <div class="p-3 space-y-3">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center px-2 py-0.5 bg-blue-100 text-blue-700 text-xs font-medium rounded-lg">
                                        {{ ucfirst(str_replace('_', ' ', $refund->jenis_refund)) }}
                                    </span>
                                    <span class="text-sm font-semibold text-slate-900">{{ formatRupiah($refund->jumlah_refund) }}</span>
                                </div>

                                <div class="text-xs">
                                    <span class="text-slate-500">Divisi:</span>
                                    <span class="ml-1 font-medium text-slate-700 truncate block">
                                        @if($refund->pencairanDana && $refund->pencairanDana->pengajuanDana)
                                            {{ $refund->pencairanDana->pengajuanDana->divisi->nama_divisi ?? '-' }}
                                        @elseif($refund->pengajuanDana)
                                            {{ $refund->pengajuanDana->divisi->nama_divisi ?? '-' }}
                                        @else
                                            -
                                        @endif
                                    </span>
                                </div>

                                <div class="text-xs">
                                    <span class="text-slate-500">Ditolak oleh:</span>
                                    <span class="ml-1 font-medium text-slate-700">{{ $refund->approvedBy->name ?? '-' }}</span>
                                </div>

                                <div class="flex items-center justify-end pt-2 border-t border-slate-100 gap-1.5">
                                    <a href="{{ route('refund.show', $refund) }}" class="inline-flex items-center gap-1.5 px-3 py-2 text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors text-xs font-medium">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <span class="hidden sm:inline">Lihat</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Desktop Table View -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-blue-50 border-b border-blue-100">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Nomor Refund</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Jenis</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Divisi</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Jumlah</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Ditolak Oleh</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Tgl Ditolak</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-blue-700 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-blue-50">
                            @foreach($refundsRejected as $refund)
                            <tr class="hover:bg-blue-50/50 transition-colors">
                                <td class="px-4 py-3">
                                    <span class="font-mono font-semibold text-blue-600 text-sm">{{ $refund->nomor_refund }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2 py-0.5 bg-blue-100 text-blue-700 text-xs font-medium rounded-lg">
                                        {{ ucfirst(str_replace('_', ' ', $refund->jenis_refund)) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700">
                                    @if($refund->pencairanDana && $refund->pencairanDana->pengajuanDana)
                                        {{ $refund->pencairanDana->pengajuanDana->divisi->nama_divisi ?? '-' }}
                                    @elseif($refund->pengajuanDana)
                                        {{ $refund->pengajuanDana->divisi->nama_divisi ?? '-' }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm font-semibold text-gray-900">{{ formatRupiah($refund->jumlah_refund) }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $refund->approvedBy->name ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ \Carbon\Carbon::parse($refund->approved_at)->format('d/m/Y H:i') }}</td>
                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('refund.show', $refund) }}" class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Lihat Detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="bg-gray-50 px-4 py-3 border-t border-blue-100">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                        <p class="text-xs md:text-sm text-gray-600 text-center md:text-left">
                            <span class="hidden md:inline">Menampilkan {{ $refundsRejected->firstItem() }} sampai {{ $refundsRejected->lastItem() }} dari {{ $refundsRejected->total() }} refund</span>
                            <span class="md:hidden">{{ $refundsRejected->total() }} refund</span>
                        </p>
                        {{ $refundsRejected->appends(request()->except('page'))->links() }}
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white rounded-xl border border-blue-100 p-16 text-center">
                <div class="flex flex-col items-center">
                    <div class="w-16 h-16 bg-red-50 rounded-2xl flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p class="text-gray-700 font-medium">Belum Ada Refund Ditolak</p>
                    <p class="text-gray-400 text-sm mt-1">Refund yang ditolak akan ditampilkan di sini.</p>
                </div>
            </div>
        @endif
    </div>

    <!-- Quick Approve Modal -->
    <div id="quickApproveModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl shadow-lg max-w-md w-full overflow-hidden">
            <div class="px-4 md:px-5 py-3 md:py-4 border-b border-blue-100 bg-emerald-50">
                <h3 class="text-base font-semibold text-emerald-900">Setujui Refund</h3>
                <p class="text-xs text-emerald-600 mt-0.5" id="approveRefundNumber"></p>
            </div>
            <form id="quickApproveForm" method="POST" action="" class="p-4 md:p-5">
                @csrf
                <input type="hidden" name="status" value="approved">
                <div class="mb-4">
                    <p class="text-sm text-gray-600">Apakah Anda yakin ingin menyetujui refund ini?</p>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan (opsional)</label>
                    <textarea name="catatan_approval" rows="2" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500" placeholder="Catatan approval..."></textarea>
                </div>
                <div class="flex flex-col sm:flex-row gap-2">
                    <button type="button" onclick="closeApproveModal()" class="flex-1 px-4 py-2 border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-sm">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors text-sm">
                        Ya, Setujui
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Quick Reject Modal -->
    <div id="quickRejectModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl shadow-lg max-w-md w-full overflow-hidden">
            <div class="px-4 md:px-5 py-3 md:py-4 border-b border-blue-100 bg-red-50">
                <h3 class="text-base font-semibold text-red-900">Tolak Refund</h3>
                <p class="text-xs text-red-600 mt-0.5" id="rejectRefundNumber"></p>
            </div>
            <form id="quickRejectForm" method="POST" action="" class="p-4 md:p-5">
                @csrf
                <input type="hidden" name="status" value="rejected">
                <div class="mb-4">
                    <p class="text-sm text-gray-600">Refund akan ditolak dan dikembalikan ke pengaju.</p>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-red-700 mb-1">Alasan Penolakan *</label>
                    <textarea name="catatan_approval" rows="3" required class="w-full px-3 py-2 border border-red-200 rounded-lg text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-500" placeholder="Jelaskan alasan penolakan..."></textarea>
                </div>
                <div class="flex flex-col sm:flex-row gap-2">
                    <button type="button" onclick="closeRejectModal()" class="flex-1 px-4 py-2 border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-sm">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm">
                        Ya, Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Quick Process Modal -->
    <div id="quickProcessModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl shadow-lg max-w-md w-full overflow-hidden">
            <div class="px-4 md:px-5 py-3 md:py-4 border-b border-blue-100 bg-violet-50">
                <h3 class="text-base font-semibold text-violet-900">Proses Refund</h3>
                <p class="text-xs text-violet-600 mt-0.5" id="processRefundNumber"></p>
            </div>
            <form id="quickProcessForm" method="POST" action="" enctype="multipart/form-data" class="p-4 md:p-5">
                @csrf
                <div class="mb-4">
                    <p class="text-sm text-gray-600">Konfirmasi bahwa refund ini telah selesai diproses dan dana telah dikembalikan.</p>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Transfer *</label>
                    <input type="date" name="tanggal_transfer" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bukti Transfer *</label>
                    <input type="file" name="bukti_transfer" required accept=".pdf,.jpg,.jpeg,.png" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100">
                    <p class="text-xs text-gray-500 mt-1">PDF, JPG, JPEG, PNG (max 5MB)</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-2">
                    <button type="button" onclick="closeProcessModal()" class="flex-1 px-4 py-2 border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-sm">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-violet-600 text-white rounded-lg hover:bg-violet-700 transition-colors text-sm">
                        Ya, Proses
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(function(el) {
                el.classList.add('hidden');
            });

            // Remove active state from all tabs
            document.querySelectorAll('[id^="tab-"]').forEach(function(el) {
                el.classList.remove('border-blue-500', 'border-amber-500', 'border-emerald-500', 'border-red-500', 'text-blue-600', 'text-amber-600', 'text-emerald-600', 'text-red-600', 'bg-blue-50', 'bg-amber-50', 'bg-emerald-50', 'bg-red-50');
                el.classList.add('border-transparent', 'text-gray-600');
            });

            // Show selected tab content
            document.getElementById('content-' + tabName).classList.remove('hidden');

            // Set active state for selected tab
            var activeTab = document.getElementById('tab-' + tabName);
            activeTab.classList.remove('border-transparent', 'text-gray-600');

            if (tabName === 'menunggu-refund') {
                activeTab.classList.add('border-blue-500', 'text-blue-600', 'bg-blue-50');
            } else if (tabName === 'menunggu-approval') {
                activeTab.classList.add('border-amber-500', 'text-amber-600', 'bg-amber-50');
            } else if (tabName === 'processed') {
                activeTab.classList.add('border-emerald-500', 'text-emerald-600', 'bg-emerald-50');
            } else if (tabName === 'rejected') {
                activeTab.classList.add('border-red-500', 'text-red-600', 'bg-red-50');
            }
        }

        function quickApprove(refundId, nomorRefund) {
            const form = document.getElementById('quickApproveForm');
            form.action = '/refund/' + refundId + '/approve';
            document.getElementById('approveRefundNumber').textContent = nomorRefund;
            document.getElementById('quickApproveModal').classList.remove('hidden');
            document.getElementById('quickApproveModal').classList.add('flex');
        }

        function quickReject(refundId, nomorRefund) {
            const form = document.getElementById('quickRejectForm');
            form.action = '/refund/' + refundId + '/approve';
            document.getElementById('rejectRefundNumber').textContent = nomorRefund;
            document.getElementById('quickRejectModal').classList.remove('hidden');
            document.getElementById('quickRejectModal').classList.add('flex');
        }

        function closeApproveModal() {
            document.getElementById('quickApproveModal').classList.add('hidden');
            document.getElementById('quickApproveModal').classList.remove('flex');
            document.getElementById('quickApproveForm').reset();
        }

        function closeRejectModal() {
            document.getElementById('quickRejectModal').classList.add('hidden');
            document.getElementById('quickRejectModal').classList.remove('flex');
            document.getElementById('quickRejectForm').reset();
        }

        function quickProcess(refundId, nomorRefund) {
            const form = document.getElementById('quickProcessForm');
            form.action = '/refund-verification/' + refundId + '/process';
            document.getElementById('processRefundNumber').textContent = nomorRefund;
            document.getElementById('quickProcessModal').classList.remove('hidden');
            document.getElementById('quickProcessModal').classList.add('flex');
        }

        function closeProcessModal() {
            document.getElementById('quickProcessModal').classList.add('hidden');
            document.getElementById('quickProcessModal').classList.remove('flex');
            document.getElementById('quickProcessForm').reset();
        }

        // Close modals on outside click
        document.getElementById('quickApproveModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeApproveModal();
            }
        });

        document.getElementById('quickRejectModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeRejectModal();
            }
        });

        document.getElementById('quickProcessModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeProcessModal();
            }
        });

        function sendRefundReminder(lpjId, nomorLpj, pengajuName) {
            if (confirm('Kirim notifikasi ke ' + pengajuName + ' bahwa LPJ ' + nomorLpj + ' memiliki sisa dana yang bisa di-refund?')) {
                fetch('/refund-reminder/' + lpjId, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({})
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Notifikasi berhasil dikirim ke ' + pengajuName);
                        location.reload();
                    } else {
                        alert('Gagal mengirim notifikasi: ' + (data.message || 'Terjadi kesalahan'));
                    }
                })
                .catch(error => {
                    alert('Gagal mengirim notifikasi: ' + error.message);
                });
            }
        }
    </script>
</x-app-layout>
