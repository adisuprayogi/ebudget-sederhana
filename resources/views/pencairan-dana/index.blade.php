<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">Pencairan Dana</h1>
                <p class="text-sm text-gray-500 mt-0.5">Kelola pencairan dana untuk pengajuan yang disetujui</p>
            </div>
            @if(auth()->user()->hasRole('staff_keuangan'))
                <a href="{{ route('pencairan-dana.select-pengajuan') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Buat Pencairan
                </a>
            @endif
        </div>
    </x-slot>

    <!-- Quick Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
        <div class="bg-white rounded-xl border border-blue-100 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V17a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xl font-semibold text-gray-900">{{ $statistics['total_count'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500">Total Pencairan</p>
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
                    <p class="text-xs text-gray-500">Menunggu Proses</p>
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
                    <p class="text-xl font-semibold text-gray-900">{{ $statistics['completed_count'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500">Berhasil Dicairkan</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-blue-100 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-violet-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
        <form method="GET" action="{{ route('pencairan-dana.index') }}" class="flex flex-wrap items-center gap-3">
            <div class="w-full md:min-w-[140px] md:w-auto">
                <select name="metode_pencairan" class="w-full px-3 py-2 text-sm border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all bg-white">
                    <option value="">Semua Metode</option>
                    <option value="transfer" {{ request('metode_pencairan') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                    <option value="cash" {{ request('metode_pencairan') == 'cash' ? 'selected' : '' }}>Cash</option>
                    <option value="reimburse" {{ request('metode_pencairan') == 'reimburse' ? 'selected' : '' }}>Reimburse</option>
                </select>
            </div>
            <div class="w-full md:min-w-[140px] md:w-auto">
                <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}" placeholder="Tanggal Mulai" class="w-full px-3 py-2 text-sm border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
            </div>
            <div class="w-full md:min-w-[140px] md:w-auto">
                <input type="date" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}" placeholder="Tanggal Selesai" class="w-full px-3 py-2 text-sm border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
            </div>
            <div class="w-full md:min-w-[200px] md:flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor pencairan..." class="w-full px-3 py-2 text-sm border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
            </div>
            <div class="flex items-center gap-2 w-full md:w-auto">
                <button type="submit" class="flex-1 md:flex-none inline-flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filter
                </button>
                @if(request()->anyFilled(['metode_pencairan', 'tanggal_mulai', 'tanggal_selesai', 'search']))
                    <a href="{{ route('pencairan-dana.index') }}" class="flex-1 md:flex-none inline-flex items-center justify-center px-4 py-2 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm font-medium rounded-lg transition-colors">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Tabs -->
    <div class="bg-white rounded-xl border border-blue-100 mb-4">
        <div class="flex border-b border-blue-100 overflow-x-auto">
            <button onclick="showTab('menunggu')" id="tab-menunggu" class="flex-1 min-w-[100px] px-3 py-2.5 text-xs font-semibold border-b-2 border-blue-600 text-blue-700 bg-blue-50 transition-colors">
                <div class="flex items-center justify-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Verifikasi</span>
                    <span class="bg-blue-600 text-white text-xs px-1.5 py-0.5 rounded-full">{{ $stats['menunggu'] ?? 0 }}</span>
                </div>
            </button>
            <button onclick="showTab('pending')" id="tab-pending" class="flex-1 min-w-[100px] px-3 py-2.5 text-xs font-semibold border-b-2 border-transparent text-gray-500 hover:bg-gray-50 transition-colors">
                <div class="flex items-center justify-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Proses</span>
                    <span class="bg-amber-500 text-white text-xs px-1.5 py-0.5 rounded-full">{{ $stats['pending'] ?? 0 }}</span>
                </div>
            </button>
            <button onclick="showTab('processed')" id="tab-processed" class="flex-1 min-w-[100px] px-3 py-2.5 text-xs font-semibold border-b-2 border-transparent text-gray-500 hover:bg-gray-50 transition-colors">
                <div class="flex items-center justify-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span>Diproses</span>
                    <span class="bg-orange-500 text-white text-xs px-1.5 py-0.5 rounded-full">{{ $stats['processed'] ?? 0 }}</span>
                </div>
            </button>
            <button onclick="showTab('selesai')" id="tab-selesai" class="flex-1 min-w-[100px] px-3 py-2.5 text-xs font-semibold border-b-2 border-transparent text-gray-500 hover:bg-gray-50 transition-colors">
                <div class="flex items-center justify-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Selesai</span>
                    <span class="bg-emerald-500 text-white text-xs px-1.5 py-0.5 rounded-full">{{ $stats['selesai'] ?? 0 }}</span>
                </div>
            </button>
            <button onclick="showTab('revisi')" id="tab-revisi" class="flex-1 min-w-[100px] px-3 py-2.5 text-xs font-semibold border-b-2 border-transparent text-gray-500 hover:bg-gray-50 transition-colors">
                <div class="flex items-center justify-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span>Revisi</span>
                    <span class="bg-red-500 text-white text-xs px-1.5 py-0.5 rounded-full">{{ $stats['revisi'] ?? 0 }}</span>
                </div>
            </button>
            <button onclick="showTab('cancelled')" id="tab-cancelled" class="flex-1 min-w-[100px] px-3 py-2.5 text-xs font-semibold border-b-2 border-transparent text-gray-500 hover:bg-gray-50 transition-colors">
                <div class="flex items-center justify-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Batal</span>
                    <span class="bg-gray-500 text-white text-xs px-1.5 py-0.5 rounded-full">{{ $stats['cancelled'] ?? 0 }}</span>
                </div>
            </button>
        </div>
    </div>

    <!-- Tab Content: Menunggu Verifikasi -->
    <div id="content-menunggu" class="tab-content">
        @if($pencairansMenunggu->count() > 0)
            <!-- Mobile Card View -->
            <div class="md:hidden space-y-3 mb-4">
                @foreach($pencairansMenunggu as $pencairan)
                    <a href="{{ route('pencairan-dana.show', $pencairan) }}" class="block bg-gradient-to-br from-slate-50 to-slate-100 rounded-xl p-4 border border-slate-200">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1 min-w-0">
                                <span class="font-mono text-xs font-semibold text-blue-600">{{ $pencairan->nomor_pencairan }}</span>
                                <h3 class="font-semibold text-gray-900 text-sm mt-1 truncate">{{ $pencairan->pengajuanDana->judul_pengajuan ?? '-' }}</h3>
                                <p class="text-xs text-gray-400 font-mono">{{ $pencairan->pengajuanDana->nomor_pengajuan ?? '-' }}</p>
                            </div>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-700 ml-2">
                                {{ ucfirst($pencairan->metode_pencairan) }}
                            </span>
                        </div>
                        <div class="space-y-1 text-sm mb-3">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Divisi</span>
                                <span class="text-gray-900 text-right">{{ $pencairan->pengajuanDana->divisi->nama_divisi ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Tanggal</span>
                                <span class="text-gray-900 text-right">{{ \Carbon\Carbon::parse($pencairan->tanggal_pencairan)->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Jumlah</span>
                                <span class="font-semibold text-gray-900 text-right">{{ formatRupiah($pencairan->jumlah_pencairan) }}</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <!-- Desktop Table View -->
            <div class="hidden md:block bg-white rounded-xl border border-blue-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-blue-50 border-b border-blue-100">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Nomor</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Pengajuan</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Divisi</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-blue-700 uppercase">Jumlah</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Metode</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Tanggal</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-blue-700 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-blue-50">
                            @foreach($pencairansMenunggu as $pencairan)
                                <tr class="hover:bg-blue-50/50 transition-colors">
                                    <td class="px-4 py-3">
                                        <span class="font-mono text-xs font-semibold text-blue-600">{{ $pencairan->nomor_pencairan }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <p class="font-medium text-gray-900 text-sm">{{ $pencairan->pengajuanDana->judul_pengajuan ?? '-' }}</p>
                                        <p class="text-xs text-gray-400 font-mono">{{ $pencairan->pengajuanDana->nomor_pengajuan ?? '-' }}</p>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="text-sm text-gray-600">{{ $pencairan->pengajuanDana->divisi->nama_divisi ?? '-' }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <p class="font-semibold text-gray-900 text-sm">{{ formatRupiah($pencairan->jumlah_pencairan) }}</p>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-700">
                                            {{ ucfirst($pencairan->metode_pencairan) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-600">
                                        {{ \Carbon\Carbon::parse($pencairan->tanggal_pencairan)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <a href="{{ route('pencairan-dana.show', $pencairan) }}" class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Lihat">
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
                @if($pencairansMenunggu->hasPages())
                    <div class="bg-gray-50 px-5 py-3 border-t border-blue-100">
                        {{ $pencairansMenunggu->appends(request()->except('page'))->links() }}
                    </div>
                @endif
            </div>
        @else
            <div class="bg-white rounded-xl border border-blue-100 p-12 text-center">
                <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="text-gray-700 font-medium">Tidak ada pencairan menunggu verifikasi</p>
            </div>
        @endif
    </div>

    <!-- Tab Content: Menunggu Proses -->
    <div id="content-pending" class="tab-content hidden">
        @if($pencairansPending->count() > 0)
            <!-- Mobile Card View -->
            <div class="md:hidden space-y-3 mb-4">
                @foreach($pencairansPending as $pencairan)
                    <div class="bg-gradient-to-br from-slate-50 to-slate-100 rounded-xl p-4 border border-slate-200">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1 min-w-0">
                                <span class="font-mono text-xs font-semibold text-blue-600">{{ $pencairan->nomor_pencairan }}</span>
                                <h3 class="font-semibold text-gray-900 text-sm mt-1 truncate">{{ $pencairan->pengajuanDana->judul_pengajuan ?? '-' }}</h3>
                                <p class="text-xs text-gray-400 font-mono">{{ $pencairan->pengajuanDana->nomor_pengajuan ?? '-' }}</p>
                            </div>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-700 ml-2">
                                {{ ucfirst($pencairan->metode_pencairan) }}
                            </span>
                        </div>
                        <div class="space-y-1 text-sm mb-3">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Divisi</span>
                                <span class="text-gray-900 text-right">{{ $pencairan->pengajuanDana->divisi->nama_divisi ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Tanggal</span>
                                <span class="text-gray-900 text-right">{{ \Carbon\Carbon::parse($pencairan->tanggal_pencairan)->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Jumlah</span>
                                <span class="font-semibold text-gray-900 text-right">{{ formatRupiah($pencairan->jumlah_pencairan) }}</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-end gap-1 pt-3 border-t border-slate-200">
                            <a href="{{ route('pencairan-dana.show', $pencairan) }}" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Lihat">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>
                            @if(auth()->user()->hasRole('staff_keuangan'))
                                <a href="{{ route('pencairan-dana.edit', $pencairan) }}" class="p-2 text-gray-400 hover:text-orange-600 hover:bg-orange-50 rounded-lg transition-colors" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Desktop Table View -->
            <div class="hidden md:block bg-white rounded-xl border border-blue-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-blue-50 border-b border-blue-100">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Nomor</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Pengajuan</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Divisi</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-blue-700 uppercase">Jumlah</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Metode</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Tanggal</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-blue-700 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-blue-50">
                            @foreach($pencairansPending as $pencairan)
                                <tr class="hover:bg-blue-50/50 transition-colors">
                                    <td class="px-4 py-3">
                                        <span class="font-mono text-xs font-semibold text-blue-600">{{ $pencairan->nomor_pencairan }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <p class="font-medium text-gray-900 text-sm">{{ $pencairan->pengajuanDana->judul_pengajuan ?? '-' }}</p>
                                        <p class="text-xs text-gray-400 font-mono">{{ $pencairan->pengajuanDana->nomor_pengajuan ?? '-' }}</p>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="text-sm text-gray-600">{{ $pencairan->pengajuanDana->divisi->nama_divisi ?? '-' }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <p class="font-semibold text-gray-900 text-sm">{{ formatRupiah($pencairan->jumlah_pencairan) }}</p>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-700">
                                            {{ ucfirst($pencairan->metode_pencairan) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-600">
                                        {{ \Carbon\Carbon::parse($pencairan->tanggal_pencairan)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <div class="flex items-center justify-end gap-1">
                                            <a href="{{ route('pencairan-dana.show', $pencairan) }}" class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Lihat">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>
                                            @if(auth()->user()->hasRole('staff_keuangan'))
                                                <a href="{{ route('pencairan-dana.edit', $pencairan) }}" class="p-1.5 text-gray-400 hover:text-orange-600 hover:bg-orange-50 rounded-lg transition-colors" title="Edit">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($pencairansPending->hasPages())
                    <div class="bg-gray-50 px-5 py-3 border-t border-blue-100">
                        {{ $pencairansPending->appends(request()->except('page'))->links() }}
                    </div>
                @endif
            </div>
        @else
            <div class="bg-white rounded-xl border border-blue-100 p-12 text-center">
                <div class="w-16 h-16 bg-amber-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="text-gray-700 font-medium">Tidak ada pencairan menunggu proses</p>
            </div>
        @endif
    </div>

    <!-- Tab Content: Diproses -->
    <div id="content-processed" class="tab-content hidden">
        @if($pencairansProcessed->count() > 0)
            <!-- Mobile Card View -->
            <div class="md:hidden space-y-3 mb-4">
                @foreach($pencairansProcessed as $pencairan)
                    <a href="{{ route('pencairan-dana.show', $pencairan) }}" class="block bg-gradient-to-br from-slate-50 to-slate-100 rounded-xl p-4 border border-slate-200">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1 min-w-0">
                                <span class="font-mono text-xs font-semibold text-blue-600">{{ $pencairan->nomor_pencairan }}</span>
                                <h3 class="font-semibold text-gray-900 text-sm mt-1 truncate">{{ $pencairan->pengajuanDana->judul_pengajuan ?? '-' }}</h3>
                                <p class="text-xs text-gray-400 font-mono">{{ $pencairan->pengajuanDana->nomor_pengajuan ?? '-' }}</p>
                            </div>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-700 ml-2">
                                {{ ucfirst($pencairan->metode_pencairan) }}
                            </span>
                        </div>
                        <div class="space-y-1 text-sm mb-3">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Divisi</span>
                                <span class="text-gray-900 text-right">{{ $pencairan->pengajuanDana->divisi->nama_divisi ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Tanggal</span>
                                <span class="text-gray-900 text-right">{{ \Carbon\Carbon::parse($pencairan->tanggal_pencairan)->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Jumlah</span>
                                <span class="font-semibold text-gray-900 text-right">{{ formatRupiah($pencairan->jumlah_pencairan) }}</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <!-- Desktop Table View -->
            <div class="hidden md:block bg-white rounded-xl border border-blue-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-blue-50 border-b border-blue-100">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Nomor</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Pengajuan</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Divisi</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-blue-700 uppercase">Jumlah</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Metode</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Tanggal</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-blue-700 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-blue-50">
                            @foreach($pencairansProcessed as $pencairan)
                                <tr class="hover:bg-blue-50/50 transition-colors">
                                    <td class="px-4 py-3">
                                        <span class="font-mono text-xs font-semibold text-blue-600">{{ $pencairan->nomor_pencairan }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <p class="font-medium text-gray-900 text-sm">{{ $pencairan->pengajuanDana->judul_pengajuan ?? '-' }}</p>
                                        <p class="text-xs text-gray-400 font-mono">{{ $pencairan->pengajuanDana->nomor_pengajuan ?? '-' }}</p>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="text-sm text-gray-600">{{ $pencairan->pengajuanDana->divisi->nama_divisi ?? '-' }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <p class="font-semibold text-gray-900 text-sm">{{ formatRupiah($pencairan->jumlah_pencairan) }}</p>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-700">
                                            {{ ucfirst($pencairan->metode_pencairan) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-600">
                                        {{ \Carbon\Carbon::parse($pencairan->tanggal_pencairan)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <a href="{{ route('pencairan-dana.show', $pencairan) }}" class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Lihat">
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
                @if($pencairansProcessed->hasPages())
                    <div class="bg-gray-50 px-5 py-3 border-t border-blue-100">
                        {{ $pencairansProcessed->appends(request()->except('page'))->links() }}
                    </div>
                @endif
            </div>
        @else
            <div class="bg-white rounded-xl border border-blue-100 p-12 text-center">
                <div class="w-16 h-16 bg-orange-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </div>
                <p class="text-gray-700 font-medium">Belum ada pencairan diproses</p>
            </div>
        @endif
    </div>

    <!-- Tab Content: Selesai -->
    <div id="content-selesai" class="tab-content hidden">
        @if($pencairansSelesai->count() > 0)
            <!-- Mobile Card View -->
            <div class="md:hidden space-y-3 mb-4">
                @foreach($pencairansSelesai as $pencairan)
                    <a href="{{ route('pencairan-dana.show', $pencairan) }}" class="block bg-gradient-to-br from-slate-50 to-slate-100 rounded-xl p-4 border border-slate-200">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1 min-w-0">
                                <span class="font-mono text-xs font-semibold text-blue-600">{{ $pencairan->nomor_pencairan }}</span>
                                <h3 class="font-semibold text-gray-900 text-sm mt-1 truncate">{{ $pencairan->pengajuanDana->judul_pengajuan ?? '-' }}</h3>
                                <p class="text-xs text-gray-400 font-mono">{{ $pencairan->pengajuanDana->nomor_pengajuan ?? '-' }}</p>
                            </div>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-700 ml-2">
                                {{ ucfirst($pencairan->metode_pencairan) }}
                            </span>
                        </div>
                        <div class="space-y-1 text-sm mb-3">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Divisi</span>
                                <span class="text-gray-900 text-right">{{ $pencairan->pengajuanDana->divisi->nama_divisi ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Tanggal</span>
                                <span class="text-gray-900 text-right">{{ \Carbon\Carbon::parse($pencairan->tanggal_pencairan)->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Jumlah</span>
                                <span class="font-semibold text-gray-900 text-right">{{ formatRupiah($pencairan->jumlah_pencairan) }}</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <!-- Desktop Table View -->
            <div class="hidden md:block bg-white rounded-xl border border-blue-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-blue-50 border-b border-blue-100">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Nomor</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Pengajuan</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Divisi</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-blue-700 uppercase">Jumlah</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Metode</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Tanggal</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-blue-700 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-blue-50">
                            @foreach($pencairansSelesai as $pencairan)
                                <tr class="hover:bg-blue-50/50 transition-colors">
                                    <td class="px-4 py-3">
                                        <span class="font-mono text-xs font-semibold text-blue-600">{{ $pencairan->nomor_pencairan }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <p class="font-medium text-gray-900 text-sm">{{ $pencairan->pengajuanDana->judul_pengajuan ?? '-' }}</p>
                                        <p class="text-xs text-gray-400 font-mono">{{ $pencairan->pengajuanDana->nomor_pengajuan ?? '-' }}</p>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="text-sm text-gray-600">{{ $pencairan->pengajuanDana->divisi->nama_divisi ?? '-' }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <p class="font-semibold text-gray-900 text-sm">{{ formatRupiah($pencairan->jumlah_pencairan) }}</p>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-700">
                                            {{ ucfirst($pencairan->metode_pencairan) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-600">
                                        {{ \Carbon\Carbon::parse($pencairan->tanggal_pencairan)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <a href="{{ route('pencairan-dana.show', $pencairan) }}" class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Lihat">
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
                @if($pencairansSelesai->hasPages())
                    <div class="bg-gray-50 px-5 py-3 border-t border-blue-100">
                        {{ $pencairansSelesai->appends(request()->except('page'))->links() }}
                    </div>
                @endif
            </div>
        @else
            <div class="bg-white rounded-xl border border-blue-100 p-12 text-center">
                <div class="w-16 h-16 bg-emerald-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="text-gray-700 font-medium">Belum ada pencairan selesai</p>
            </div>
        @endif
    </div>

    <!-- Tab Content: Revisi -->
    <div id="content-revisi" class="tab-content hidden">
        @if($pencairansRevisi->count() > 0)
            <!-- Mobile Card View -->
            <div class="md:hidden space-y-3 mb-4">
                @foreach($pencairansRevisi as $pencairan)
                    <div class="bg-gradient-to-br from-slate-50 to-slate-100 rounded-xl p-4 border border-slate-200">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1 min-w-0">
                                <span class="font-mono text-xs font-semibold text-blue-600">{{ $pencairan->nomor_pencairan }}</span>
                                <h3 class="font-semibold text-gray-900 text-sm mt-1 truncate">{{ $pencairan->pengajuanDana->judul_pengajuan ?? '-' }}</h3>
                                <p class="text-xs text-gray-400 font-mono">{{ $pencairan->pengajuanDana->nomor_pengajuan ?? '-' }}</p>
                            </div>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-700 ml-2">
                                {{ ucfirst($pencairan->metode_pencairan) }}
                            </span>
                        </div>
                        <div class="space-y-1 text-sm mb-3">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Divisi</span>
                                <span class="text-gray-900 text-right">{{ $pencairan->pengajuanDana->divisi->nama_divisi ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Tanggal</span>
                                <span class="text-gray-900 text-right">{{ \Carbon\Carbon::parse($pencairan->tanggal_pencairan)->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Jumlah</span>
                                <span class="font-semibold text-gray-900 text-right">{{ formatRupiah($pencairan->jumlah_pencairan) }}</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-end gap-1 pt-3 border-t border-slate-200">
                            <a href="{{ route('pencairan-dana.show', $pencairan) }}" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Lihat">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>
                            @if(auth()->user()->hasRole('staff_keuangan'))
                                <a href="{{ route('pencairan-dana.retry', $pencairan) }}" class="p-2 text-gray-400 hover:text-orange-600 hover:bg-orange-50 rounded-lg transition-colors" title="Buat Ulang">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Desktop Table View -->
            <div class="hidden md:block bg-white rounded-xl border border-blue-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-blue-50 border-b border-blue-100">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Nomor</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Pengajuan</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Divisi</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-blue-700 uppercase">Jumlah</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Metode</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Tanggal</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-blue-700 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-blue-50">
                            @foreach($pencairansRevisi as $pencairan)
                                <tr class="hover:bg-blue-50/50 transition-colors">
                                    <td class="px-4 py-3">
                                        <span class="font-mono text-xs font-semibold text-blue-600">{{ $pencairan->nomor_pencairan }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <p class="font-medium text-gray-900 text-sm">{{ $pencairan->pengajuanDana->judul_pengajuan ?? '-' }}</p>
                                        <p class="text-xs text-gray-400 font-mono">{{ $pencairan->pengajuanDana->nomor_pengajuan ?? '-' }}</p>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="text-sm text-gray-600">{{ $pencairan->pengajuanDana->divisi->nama_divisi ?? '-' }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <p class="font-semibold text-gray-900 text-sm">{{ formatRupiah($pencairan->jumlah_pencairan) }}</p>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-700">
                                            {{ ucfirst($pencairan->metode_pencairan) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-600">
                                        {{ \Carbon\Carbon::parse($pencairan->tanggal_pencairan)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <div class="flex items-center justify-end gap-1">
                                            <a href="{{ route('pencairan-dana.show', $pencairan) }}" class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Lihat">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>
                                            @if(auth()->user()->hasRole('staff_keuangan'))
                                                <a href="{{ route('pencairan-dana.retry', $pencairan) }}" class="p-1.5 text-gray-400 hover:text-orange-600 hover:bg-orange-50 rounded-lg transition-colors" title="Buat Ulang">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                    </svg>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($pencairansRevisi->hasPages())
                    <div class="bg-gray-50 px-5 py-3 border-t border-blue-100">
                        {{ $pencairansRevisi->appends(request()->except('page'))->links() }}
                    </div>
                @endif
            </div>
        @else
            <div class="bg-white rounded-xl border border-blue-100 p-12 text-center">
                <div class="w-16 h-16 bg-red-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <p class="text-gray-700 font-medium">Tidak ada pencairan revisi</p>
            </div>
        @endif
    </div>

    <!-- Tab Content: Dibatalkan -->
    <div id="content-cancelled" class="tab-content hidden">
        @if($pencairansCancelled->count() > 0)
            <!-- Mobile Card View -->
            <div class="md:hidden space-y-3 mb-4">
                @foreach($pencairansCancelled as $pencairan)
                    <a href="{{ route('pencairan-dana.show', $pencairan) }}" class="block bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-4 border border-gray-200 opacity-75">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1 min-w-0">
                                <span class="font-mono text-xs font-semibold text-gray-400">{{ $pencairan->nomor_pencairan }}</span>
                                <h3 class="font-medium text-gray-600 text-sm mt-1 truncate">{{ $pencairan->pengajuanDana->judul_pengajuan ?? '-' }}</h3>
                                <p class="text-xs text-gray-400 font-mono">{{ $pencairan->pengajuanDana->nomor_pengajuan ?? '-' }}</p>
                            </div>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600 ml-2">
                                {{ ucfirst($pencairan->metode_pencairan) }}
                            </span>
                        </div>
                        <div class="space-y-1 text-sm mb-3">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Divisi</span>
                                <span class="text-gray-500 text-right">{{ $pencairan->pengajuanDana->divisi->nama_divisi ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Tanggal</span>
                                <span class="text-gray-500 text-right">{{ \Carbon\Carbon::parse($pencairan->tanggal_pencairan)->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Jumlah</span>
                                <span class="font-medium text-gray-500 text-right">{{ formatRupiah($pencairan->jumlah_pencairan) }}</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <!-- Desktop Table View -->
            <div class="hidden md:block bg-white rounded-xl border border-blue-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-blue-50 border-b border-blue-100">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Nomor</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Pengajuan</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Divisi</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-blue-700 uppercase">Jumlah</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Metode</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Tanggal</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-blue-700 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-blue-50">
                            @foreach($pencairansCancelled as $pencairan)
                                <tr class="hover:bg-blue-50/50 transition-colors">
                                    <td class="px-4 py-3">
                                        <span class="font-mono text-xs font-semibold text-gray-400">{{ $pencairan->nomor_pencairan }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <p class="font-medium text-gray-600 text-sm">{{ $pencairan->pengajuanDana->judul_pengajuan ?? '-' }}</p>
                                        <p class="text-xs text-gray-400 font-mono">{{ $pencairan->pengajuanDana->nomor_pengajuan ?? '-' }}</p>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="text-sm text-gray-400">{{ $pencairan->pengajuanDana->divisi->nama_divisi ?? '-' }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <p class="font-semibold text-gray-500 text-sm">{{ formatRupiah($pencairan->jumlah_pencairan) }}</p>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600">
                                            {{ ucfirst($pencairan->metode_pencairan) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-400">
                                        {{ \Carbon\Carbon::parse($pencairan->tanggal_pencairan)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <a href="{{ route('pencairan-dana.show', $pencairan) }}" class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Lihat">
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
                @if($pencairansCancelled->hasPages())
                    <div class="bg-gray-50 px-5 py-3 border-t border-blue-100">
                        {{ $pencairansCancelled->appends(request()->except('page'))->links() }}
                    </div>
                @endif
            </div>
        @else
            <div class="bg-white rounded-xl border border-blue-100 p-12 text-center">
                <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="text-gray-700 font-medium">Tidak ada pencairan dibatalkan</p>
            </div>
        @endif
    </div>

    <script>
        function showTab(tabName) {
            document.querySelectorAll('.tab-content').forEach(function(el) {
                el.classList.add('hidden');
            });

            document.querySelectorAll('[id^="tab-"]').forEach(function(el) {
                el.classList.remove('border-blue-600', 'border-amber-500', 'border-orange-500', 'border-emerald-500', 'border-red-500', 'border-gray-500', 'text-blue-700', 'text-amber-700', 'text-orange-700', 'text-emerald-700', 'text-red-700', 'text-gray-700', 'bg-blue-50', 'bg-amber-50', 'bg-orange-50', 'bg-emerald-50', 'bg-red-50', 'bg-gray-50');
                el.classList.add('border-transparent', 'text-gray-500');
            });

            document.getElementById('content-' + tabName).classList.remove('hidden');

            var activeTab = document.getElementById('tab-' + tabName);
            activeTab.classList.remove('border-transparent', 'text-gray-500');

            if (tabName === 'menunggu') {
                activeTab.classList.add('border-blue-600', 'text-blue-700', 'bg-blue-50');
            } else if (tabName === 'pending') {
                activeTab.classList.add('border-amber-500', 'text-amber-700', 'bg-amber-50');
            } else if (tabName === 'processed') {
                activeTab.classList.add('border-orange-500', 'text-orange-700', 'bg-orange-50');
            } else if (tabName === 'selesai') {
                activeTab.classList.add('border-emerald-500', 'text-emerald-700', 'bg-emerald-50');
            } else if (tabName === 'revisi') {
                activeTab.classList.add('border-red-500', 'text-red-700', 'bg-red-50');
            } else if (tabName === 'cancelled') {
                activeTab.classList.add('border-gray-500', 'text-gray-700', 'bg-gray-50');
            }
        }
    </script>
</x-app-layout>
