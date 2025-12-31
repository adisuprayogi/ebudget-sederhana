<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-secondary-900">Pencairan Dana</h1>
                <p class="text-secondary-600 mt-1">Kelola pencairan dana untuk pengajuan yang disetujui</p>
            </div>
            @if(auth()->user()->hasRole('staff_keuangan'))
                <a href="{{ route('pencairan-dana.select-pengajuan') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-all duration-200 shadow-soft hover:shadow-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Buat Pencairan Baru
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-8">
        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl p-6 text-white shadow-soft">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Total Pencairan</p>
                        <p class="text-3xl font-bold mt-1">{{ $statistics['total_count'] ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-amber-400 to-amber-600 rounded-2xl p-6 text-white shadow-soft">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-amber-100 text-sm font-medium">Menunggu Proses</p>
                        <p class="text-3xl font-bold mt-1">{{ $statistics['pending_count'] ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-400 to-green-600 rounded-2xl p-6 text-white shadow-soft">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">Berhasil Dicairkan</p>
                        <p class="text-3xl font-bold mt-1">{{ $statistics['completed_count'] ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-indigo-400 to-indigo-600 rounded-2xl p-6 text-white shadow-soft">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-indigo-100 text-sm font-medium">Total Nilai</p>
                        <p class="text-2xl font-bold mt-1">{{ formatRupiah($statistics['total_amount'] ?? 0) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-2xl shadow-soft p-6 mb-6">
            <form method="GET" action="{{ route('pencairan-dana.index') }}" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-secondary-700 mb-2">Metode Pencairan</label>
                    <select name="metode_pencairan" class="w-full px-4 py-2 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <option value="">Semua Metode</option>
                        <option value="transfer" {{ request('metode_pencairan') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                        <option value="cash" {{ request('metode_pencairan') == 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="reimburse" {{ request('metode_pencairan') == 'reimburse' ? 'selected' : '' }}>Reimburse</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-secondary-700 mb-2">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}" class="w-full px-4 py-2 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-secondary-700 mb-2">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}" class="w-full px-4 py-2 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-secondary-700 mb-2">Cari</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nomor pencairan..." class="w-full px-4 py-2 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 3.293A1 1 0 013 2.586V4z" />
                        </svg>
                        Filter
                    </button>
                    @if(request()->anyFilled(['metode_pencairan', 'tanggal_mulai', 'tanggal_selesai', 'search']))
                    <a href="{{ route('pencairan-dana.index') }}" class="px-4 py-2 border border-secondary-200 text-secondary-600 rounded-xl hover:bg-secondary-50 transition-all duration-200 ml-2">
                        Reset
                    </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Tabs -->
        <div class="bg-white rounded-2xl shadow-soft overflow-hidden mb-6">
            <div class="flex flex-wrap border-b border-secondary-200 overflow-x-auto">
                <button onclick="showTab('menunggu')" id="tab-menunggu" class="flex-1 min-w-[120px] px-3 py-3 text-sm font-semibold border-b-2 border-purple-500 text-purple-600 bg-purple-50 transition-colors">
                    <div class="flex items-center justify-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="hidden md:inline">Menunggu Verifikasi</span>
                        <span class="md:hidden">Verifikasi</span>
                        <span class="bg-purple-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $stats['menunggu'] ?? 0 }}</span>
                    </div>
                </button>
                <button onclick="showTab('pending')" id="tab-pending" class="flex-1 min-w-[120px] px-3 py-3 text-sm font-semibold border-b-2 border-transparent text-secondary-600 hover:bg-secondary-50 transition-colors">
                    <div class="flex items-center justify-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="hidden md:inline">Menunggu Proses</span>
                        <span class="md:hidden">Proses</span>
                        <span class="bg-amber-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $stats['pending'] ?? 0 }}</span>
                    </div>
                </button>
                <button onclick="showTab('processed')" id="tab-processed" class="flex-1 min-w-[120px] px-3 py-3 text-sm font-semibold border-b-2 border-transparent text-secondary-600 hover:bg-secondary-50 transition-colors">
                    <div class="flex items-center justify-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span class="hidden md:inline">Diproses</span>
                        <span class="md:hidden">Diproses</span>
                        <span class="bg-blue-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $stats['processed'] ?? 0 }}</span>
                    </div>
                </button>
                <button onclick="showTab('selesai')" id="tab-selesai" class="flex-1 min-w-[120px] px-3 py-3 text-sm font-semibold border-b-2 border-transparent text-secondary-600 hover:bg-secondary-50 transition-colors">
                    <div class="flex items-center justify-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="hidden md:inline">Selesai</span>
                        <span class="md:hidden">Selesai</span>
                        <span class="bg-green-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $stats['selesai'] ?? 0 }}</span>
                    </div>
                </button>
                <button onclick="showTab('revisi')" id="tab-revisi" class="flex-1 min-w-[120px] px-3 py-3 text-sm font-semibold border-b-2 border-transparent text-secondary-600 hover:bg-secondary-50 transition-colors">
                    <div class="flex items-center justify-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span class="hidden md:inline">Revisi</span>
                        <span class="md:hidden">Revisi</span>
                        <span class="bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $stats['revisi'] ?? 0 }}</span>
                    </div>
                </button>
                <button onclick="showTab('cancelled')" id="tab-cancelled" class="flex-1 min-w-[120px] px-3 py-3 text-sm font-semibold border-b-2 border-transparent text-secondary-600 hover:bg-secondary-50 transition-colors">
                    <div class="flex items-center justify-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="hidden md:inline">Dibatalkan</span>
                        <span class="md:hidden">Batal</span>
                        <span class="bg-slate-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $stats['cancelled'] ?? 0 }}</span>
                    </div>
                </button>
            </div>
        </div>

        <!-- Tab Content: Menunggu Verifikasi -->
        <div id="content-menunggu" class="tab-content">
            @if($pencairansMenunggu->count() > 0)
                <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-secondary-50 border-b border-secondary-200">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Nomor</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Pengajuan</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Divisi</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Jumlah</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Metode</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-6 py-4 text-right text-xs font-semibold text-secondary-600 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-secondary-100">
                                @foreach($pencairansMenunggu as $pencairan)
                                    <tr class="hover:bg-secondary-50 transition-colors duration-150">
                                        <td class="px-6 py-4">
                                            <span class="font-mono text-sm font-semibold text-primary-600">{{ $pencairan->nomor_pencairan }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-secondary-900">{{ $pencairan->pengajuanDana->judul_pengajuan ?? '-' }}</div>
                                            <div class="text-sm text-secondary-500 font-mono">{{ $pencairan->pengajuanDana->nomor_pengajuan ?? '-' }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm text-secondary-700">{{ $pencairan->pengajuanDana->divisi->nama_divisi ?? '-' }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-semibold text-secondary-900">{{ formatRupiah($pencairan->jumlah_pencairan) }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium
                                                @if($pencairan->metode_pencairan === 'transfer') bg-blue-100 text-blue-700
                                                @elseif($pencairan->metode_pencairan === 'cash') bg-green-100 text-green-700
                                                @else bg-purple-100 text-purple-700 @endif">
                                                {{ ucfirst($pencairan->metode_pencairan) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-secondary-600">
                                            {{ \Carbon\Carbon::parse($pencairan->tanggal_pencairan)->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex items-center justify-end space-x-2">
                                                <a href="{{ route('pencairan-dana.show', $pencairan) }}" class="p-2 text-secondary-600 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors" title="Lihat">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="bg-secondary-50 px-6 py-4 border-t border-secondary-200 flex items-center justify-between">
                        <p class="text-sm text-secondary-600">
                            Menampilkan {{ $pencairansMenunggu->firstItem() }} sampai {{ $pencairansMenunggu->lastItem() }} dari {{ $pencairansMenunggu->total() }} pencairan
                        </p>
                        {{ $pencairansMenunggu->appends(request()->except('page'))->links() }}
                    </div>
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-soft p-12 text-center">
                    <div class="w-20 h-20 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-secondary-900 mb-2">Tidak Ada Pencairan Menunggu Verifikasi</h3>
                    <p class="text-secondary-500">Semua pencairan telah diverifikasi.</p>
                </div>
            @endif
        </div>

        <!-- Tab Content: Menunggu Proses -->
        <div id="content-pending" class="tab-content hidden">
            @if($pencairansPending->count() > 0)
                <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-secondary-50 border-b border-secondary-200">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Nomor</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Pengajuan</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Divisi</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Jumlah</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Metode</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-6 py-4 text-right text-xs font-semibold text-secondary-600 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-secondary-100">
                                @foreach($pencairansPending as $pencairan)
                                    <tr class="hover:bg-secondary-50 transition-colors duration-150">
                                        <td class="px-6 py-4">
                                            <span class="font-mono text-sm font-semibold text-primary-600">{{ $pencairan->nomor_pencairan }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-secondary-900">{{ $pencairan->pengajuanDana->judul_pengajuan ?? '-' }}</div>
                                            <div class="text-sm text-secondary-500 font-mono">{{ $pencairan->pengajuanDana->nomor_pengajuan ?? '-' }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm text-secondary-700">{{ $pencairan->pengajuanDana->divisi->nama_divisi ?? '-' }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-semibold text-secondary-900">{{ formatRupiah($pencairan->jumlah_pencairan) }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium
                                                @if($pencairan->metode_pencairan === 'transfer') bg-blue-100 text-blue-700
                                                @elseif($pencairan->metode_pencairan === 'cash') bg-green-100 text-green-700
                                                @else bg-purple-100 text-purple-700 @endif">
                                                {{ ucfirst($pencairan->metode_pencairan) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-secondary-600">
                                            {{ \Carbon\Carbon::parse($pencairan->tanggal_pencairan)->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex items-center justify-end space-x-2">
                                                <a href="{{ route('pencairan-dana.show', $pencairan) }}" class="p-2 text-secondary-600 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors" title="Lihat">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                                @if(auth()->user()->hasRole('staff_keuangan'))
                                                    <a href="{{ route('pencairan-dana.edit', $pencairan) }}" class="p-2 text-secondary-600 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Edit">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

                    <!-- Pagination -->
                    <div class="bg-secondary-50 px-6 py-4 border-t border-secondary-200 flex items-center justify-between">
                        <p class="text-sm text-secondary-600">
                            Menampilkan {{ $pencairansPending->firstItem() }} sampai {{ $pencairansPending->lastItem() }} dari {{ $pencairansPending->total() }} pencairan
                        </p>
                        {{ $pencairansPending->appends(request()->except('page'))->links() }}
                    </div>
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-soft p-12 text-center">
                    <div class="w-20 h-20 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-secondary-900 mb-2">Tidak Ada Pencairan Menunggu Proses</h3>
                    <p class="text-secondary-500">Pencairan yang menunggu diproses akan ditampilkan di sini.</p>
                </div>
            @endif
        </div>

        <!-- Tab Content: Diproses -->
        <div id="content-processed" class="tab-content hidden">
            @if($pencairansProcessed->count() > 0)
                <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-secondary-50 border-b border-secondary-200">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Nomor</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Pengajuan</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Divisi</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Jumlah</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Metode</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-6 py-4 text-right text-xs font-semibold text-secondary-600 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-secondary-100">
                                @foreach($pencairansProcessed as $pencairan)
                                    <tr class="hover:bg-secondary-50 transition-colors duration-150">
                                        <td class="px-6 py-4">
                                            <span class="font-mono text-sm font-semibold text-primary-600">{{ $pencairan->nomor_pencairan }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-secondary-900">{{ $pencairan->pengajuanDana->judul_pengajuan ?? '-' }}</div>
                                            <div class="text-sm text-secondary-500 font-mono">{{ $pencairan->pengajuanDana->nomor_pengajuan ?? '-' }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm text-secondary-700">{{ $pencairan->pengajuanDana->divisi->nama_divisi ?? '-' }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-semibold text-secondary-900">{{ formatRupiah($pencairan->jumlah_pencairan) }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium
                                                @if($pencairan->metode_pencairan === 'transfer') bg-blue-100 text-blue-700
                                                @elseif($pencairan->metode_pencairan === 'cash') bg-green-100 text-green-700
                                                @else bg-purple-100 text-purple-700 @endif">
                                                {{ ucfirst($pencairan->metode_pencairan) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-secondary-600">
                                            {{ \Carbon\Carbon::parse($pencairan->tanggal_pencairan)->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex items-center justify-end space-x-2">
                                                <a href="{{ route('pencairan-dana.show', $pencairan) }}" class="p-2 text-secondary-600 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors" title="Lihat">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="bg-secondary-50 px-6 py-4 border-t border-secondary-200 flex items-center justify-between">
                        <p class="text-sm text-secondary-600">
                            Menampilkan {{ $pencairansProcessed->firstItem() }} sampai {{ $pencairansProcessed->lastItem() }} dari {{ $pencairansProcessed->total() }} pencairan
                        </p>
                        {{ $pencairansProcessed->appends(request()->except('page'))->links() }}
                    </div>
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-soft p-12 text-center">
                    <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-secondary-900 mb-2">Belum Ada Pencairan Diproses</h3>
                    <p class="text-secondary-500">Pencairan yang sedang diproses akan ditampilkan di sini.</p>
                </div>
            @endif
        </div>

        <!-- Tab Content: Selesai -->
        <div id="content-selesai" class="tab-content hidden">
            @if($pencairansSelesai->count() > 0)
                <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-secondary-50 border-b border-secondary-200">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Nomor</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Pengajuan</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Divisi</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Jumlah</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Metode</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-6 py-4 text-right text-xs font-semibold text-secondary-600 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-secondary-100">
                                @foreach($pencairansSelesai as $pencairan)
                                    <tr class="hover:bg-secondary-50 transition-colors duration-150">
                                        <td class="px-6 py-4">
                                            <span class="font-mono text-sm font-semibold text-primary-600">{{ $pencairan->nomor_pencairan }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-secondary-900">{{ $pencairan->pengajuanDana->judul_pengajuan ?? '-' }}</div>
                                            <div class="text-sm text-secondary-500 font-mono">{{ $pencairan->pengajuanDana->nomor_pengajuan ?? '-' }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm text-secondary-700">{{ $pencairan->pengajuanDana->divisi->nama_divisi ?? '-' }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-semibold text-secondary-900">{{ formatRupiah($pencairan->jumlah_pencairan) }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium
                                                @if($pencairan->metode_pencairan === 'transfer') bg-blue-100 text-blue-700
                                                @elseif($pencairan->metode_pencairan === 'cash') bg-green-100 text-green-700
                                                @else bg-purple-100 text-purple-700 @endif">
                                                {{ ucfirst($pencairan->metode_pencairan) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-secondary-600">
                                            {{ \Carbon\Carbon::parse($pencairan->tanggal_pencairan)->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex items-center justify-end space-x-2">
                                                <a href="{{ route('pencairan-dana.show', $pencairan) }}" class="p-2 text-secondary-600 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors" title="Lihat">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="bg-secondary-50 px-6 py-4 border-t border-secondary-200 flex items-center justify-between">
                        <p class="text-sm text-secondary-600">
                            Menampilkan {{ $pencairansSelesai->firstItem() }} sampai {{ $pencairansSelesai->lastItem() }} dari {{ $pencairansSelesai->total() }} pencairan
                        </p>
                        {{ $pencairansSelesai->appends(request()->except('page'))->links() }}
                    </div>
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-soft p-12 text-center">
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-secondary-900 mb-2">Belum Ada Pencairan Selesai</h3>
                    <p class="text-secondary-500">Pencairan yang selesai akan ditampilkan di sini.</p>
                </div>
            @endif
        </div>

        <!-- Tab Content: Revisi -->
        <div id="content-revisi" class="tab-content hidden">
            @if($pencairansRevisi->count() > 0)
                <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-secondary-50 border-b border-secondary-200">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Nomor</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Pengajuan</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Divisi</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Jumlah</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Metode</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-6 py-4 text-right text-xs font-semibold text-secondary-600 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-secondary-100">
                                @foreach($pencairansRevisi as $pencairan)
                                    <tr class="hover:bg-secondary-50 transition-colors duration-150">
                                        <td class="px-6 py-4">
                                            <span class="font-mono text-sm font-semibold text-primary-600">{{ $pencairan->nomor_pencairan }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-secondary-900">{{ $pencairan->pengajuanDana->judul_pengajuan ?? '-' }}</div>
                                            <div class="text-sm text-secondary-500 font-mono">{{ $pencairan->pengajuanDana->nomor_pengajuan ?? '-' }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm text-secondary-700">{{ $pencairan->pengajuanDana->divisi->nama_divisi ?? '-' }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-semibold text-secondary-900">{{ formatRupiah($pencairan->jumlah_pencairan) }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium
                                                @if($pencairan->metode_pencairan === 'transfer') bg-blue-100 text-blue-700
                                                @elseif($pencairan->metode_pencairan === 'cash') bg-green-100 text-green-700
                                                @else bg-purple-100 text-purple-700 @endif">
                                                {{ ucfirst($pencairan->metode_pencairan) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-secondary-600">
                                            {{ \Carbon\Carbon::parse($pencairan->tanggal_pencairan)->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex items-center justify-end space-x-2">
                                                <a href="{{ route('pencairan-dana.show', $pencairan) }}" class="p-2 text-secondary-600 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors" title="Lihat">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                                @if(auth()->user()->hasRole('staff_keuangan'))
                                                    <a href="{{ route('pencairan-dana.retry', $pencairan) }}" class="p-2 text-amber-600 hover:text-amber-700 hover:bg-amber-50 rounded-lg transition-colors" title="Buat Ulang">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

                    <!-- Pagination -->
                    <div class="bg-secondary-50 px-6 py-4 border-t border-secondary-200 flex items-center justify-between">
                        <p class="text-sm text-secondary-600">
                            Menampilkan {{ $pencairansRevisi->firstItem() }} sampai {{ $pencairansRevisi->lastItem() }} dari {{ $pencairansRevisi->total() }} pencairan
                        </p>
                        {{ $pencairansRevisi->appends(request()->except('page'))->links() }}
                    </div>
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-soft p-12 text-center">
                    <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-secondary-900 mb-2">Tidak Ada Pencairan Revisi</h3>
                    <p class="text-secondary-500">Pencairan yang perlu direvisi akan ditampilkan di sini.</p>
                </div>
            @endif
        </div>

        <!-- Tab Content: Dibatalkan -->
        <div id="content-cancelled" class="tab-content hidden">
            @if($pencairansCancelled->count() > 0)
                <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-secondary-50 border-b border-secondary-200">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Nomor</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Pengajuan</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Divisi</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Jumlah</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Metode</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-6 py-4 text-right text-xs font-semibold text-secondary-600 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-secondary-100">
                                @foreach($pencairansCancelled as $pencairan)
                                    <tr class="hover:bg-secondary-50 transition-colors duration-150">
                                        <td class="px-6 py-4">
                                            <span class="font-mono text-sm font-semibold text-secondary-400">{{ $pencairan->nomor_pencairan }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-secondary-700">{{ $pencairan->pengajuanDana->judul_pengajuan ?? '-' }}</div>
                                            <div class="text-sm text-secondary-500 font-mono">{{ $pencairan->pengajuanDana->nomor_pengajuan ?? '-' }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm text-secondary-500">{{ $pencairan->pengajuanDana->divisi->nama_divisi ?? '-' }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-semibold text-secondary-600">{{ formatRupiah($pencairan->jumlah_pencairan) }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-slate-100 text-slate-600">
                                                {{ ucfirst($pencairan->metode_pencairan) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-secondary-500">
                                            {{ \Carbon\Carbon::parse($pencairan->tanggal_pencairan)->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex items-center justify-end space-x-2">
                                                <a href="{{ route('pencairan-dana.show', $pencairan) }}" class="p-2 text-secondary-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors" title="Lihat">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="bg-secondary-50 px-6 py-4 border-t border-secondary-200 flex items-center justify-between">
                        <p class="text-sm text-secondary-600">
                            Menampilkan {{ $pencairansCancelled->firstItem() }} sampai {{ $pencairansCancelled->lastItem() }} dari {{ $pencairansCancelled->total() }} pencairan
                        </p>
                        {{ $pencairansCancelled->appends(request()->except('page'))->links() }}
                    </div>
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-soft p-12 text-center">
                    <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-secondary-900 mb-2">Tidak Ada Pencairan Dibatalkan</h3>
                    <p class="text-secondary-500">Pencairan yang dibatalkan akan ditampilkan di sini.</p>
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
                    el.classList.remove('border-purple-500', 'border-amber-500', 'border-blue-500', 'border-green-500', 'border-red-500', 'border-slate-500', 'text-purple-600', 'text-amber-600', 'text-blue-600', 'text-green-600', 'text-red-600', 'text-slate-600', 'bg-purple-50', 'bg-amber-50', 'bg-blue-50', 'bg-green-50', 'bg-red-50', 'bg-slate-50');
                    el.classList.add('border-transparent', 'text-secondary-600');
                });

                // Show selected tab content
                document.getElementById('content-' + tabName).classList.remove('hidden');

                // Set active state for selected tab
                var activeTab = document.getElementById('tab-' + tabName);
                activeTab.classList.remove('border-transparent', 'text-secondary-600');

                if (tabName === 'menunggu') {
                    activeTab.classList.add('border-purple-500', 'text-purple-600', 'bg-purple-50');
                } else if (tabName === 'pending') {
                    activeTab.classList.add('border-amber-500', 'text-amber-600', 'bg-amber-50');
                } else if (tabName === 'processed') {
                    activeTab.classList.add('border-blue-500', 'text-blue-600', 'bg-blue-50');
                } else if (tabName === 'selesai') {
                    activeTab.classList.add('border-green-500', 'text-green-600', 'bg-green-50');
                } else if (tabName === 'revisi') {
                    activeTab.classList.add('border-red-500', 'text-red-600', 'bg-red-50');
                } else if (tabName === 'cancelled') {
                    activeTab.classList.add('border-slate-500', 'text-slate-600', 'bg-slate-50');
                }
            }
        </script>
    </div>
</x-app-layout>
