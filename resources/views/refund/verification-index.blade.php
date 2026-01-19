<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">Verifikasi Refund</h1>
                <p class="text-sm text-gray-500 mt-0.5">Verifikasi pengajuan refund dan lihat history</p>
            </div>
        </div>
    </x-slot>

    <!-- Quick Stats -->
    <div class="grid grid-cols-3 gap-3 mb-4">
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
                    <p class="text-xs text-gray-500">Disetujui/Selesai</p>
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
        <form method="GET" action="{{ route('refund-verification.index') }}" class="flex flex-wrap items-center gap-3">
            <div class="min-w-[140px]">
                <select name="divisi_id" class="w-full px-3 py-2 text-sm border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all bg-white">
                    <option value="">Semua Divisi</option>
                    @foreach(\App\Models\Divisi::orderBy('nama_divisi')->get() as $divisi)
                        <option value="{{ $divisi->id }}" {{ request('divisi_id') == $divisi->id ? 'selected' : '' }}>{{ $divisi->nama_divisi }}</option>
                    @endforeach
                </select>
            </div>
            <div class="min-w-[140px]">
                <select name="periode_anggaran_id" class="w-full px-3 py-2 text-sm border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all bg-white">
                    <option value="">Semua Periode</option>
                    @foreach(\App\Models\PeriodeAnggaran::orderBy('tahun_anggaran', 'desc')->get() as $periode)
                        <option value="{{ $periode->id }}" {{ request('periode_anggaran_id') == $periode->id ? 'selected' : '' }}>{{ $periode->nama_periode }}</option>
                    @endforeach
                </select>
            </div>
            <div class="min-w-[200px] flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor atau alasan refund..." class="w-full px-3 py-2 text-sm border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
            </div>
            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                Filter
            </button>
            @if(request()->anyFilled(['divisi_id', 'periode_anggaran_id', 'search']))
                <a href="{{ route('refund-verification.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm font-medium rounded-lg transition-colors">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Tabs -->
    <div class="bg-white rounded-xl border border-blue-100 overflow-hidden mb-4">
        <div class="flex flex-wrap border-b border-blue-100">
            <button onclick="showTab('menunggu-approval')" id="tab-menunggu-approval" class="flex-1 min-w-[120px] px-3 py-3 text-sm font-semibold border-b-2 border-amber-500 text-amber-600 bg-amber-50 transition-colors">
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
                    <span class="hidden md:inline">Disetujui</span>
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

    <!-- Tab Content: Menunggu Approval -->
    <div id="content-menunggu-approval" class="tab-content">
        @if($refunds->count() > 0)
            <div class="bg-white rounded-xl border border-blue-100 overflow-hidden">
                <div class="overflow-x-auto">
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
                <div class="bg-gray-50 px-4 py-3 border-t border-blue-100 flex items-center justify-between">
                    <p class="text-sm text-gray-600">
                        Menampilkan {{ $refunds->firstItem() }} sampai {{ $refunds->lastItem() }} dari {{ $refunds->total() }} refund
                    </p>
                    {{ $refunds->appends(request()->except('page'))->links() }}
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
                <div class="overflow-x-auto">
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
                <div class="bg-gray-50 px-4 py-3 border-t border-blue-100 flex items-center justify-between">
                    <p class="text-sm text-gray-600">
                        Menampilkan {{ $refundsProcessed->firstItem() }} sampai {{ $refundsProcessed->lastItem() }} dari {{ $refundsProcessed->total() }} refund
                    </p>
                    {{ $refundsProcessed->appends(request()->except('page'))->links() }}
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
                <div class="overflow-x-auto">
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
                <div class="bg-gray-50 px-4 py-3 border-t border-blue-100 flex items-center justify-between">
                    <p class="text-sm text-gray-600">
                        Menampilkan {{ $refundsRejected->firstItem() }} sampai {{ $refundsRejected->lastItem() }} dari {{ $refundsRejected->total() }} refund
                    </p>
                    {{ $refundsRejected->appends(request()->except('page'))->links() }}
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
    <div id="quickApproveModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-lg max-w-md w-full mx-4 overflow-hidden">
            <div class="px-5 py-4 border-b border-blue-100 bg-emerald-50">
                <h3 class="text-base font-semibold text-emerald-900">Setujui Refund</h3>
                <p class="text-xs text-emerald-600 mt-0.5" id="approveRefundNumber"></p>
            </div>
            <form id="quickApproveForm" method="POST" action="" class="p-5">
                @csrf
                <input type="hidden" name="status" value="approved">
                <div class="mb-4">
                    <p class="text-sm text-gray-600">Apakah Anda yakin ingin menyetujui refund ini?</p>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan (opsional)</label>
                    <textarea name="catatan_approval" rows="2" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500" placeholder="Catatan approval..."></textarea>
                </div>
                <div class="flex gap-2">
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
    <div id="quickRejectModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-lg max-w-md w-full mx-4 overflow-hidden">
            <div class="px-5 py-4 border-b border-blue-100 bg-red-50">
                <h3 class="text-base font-semibold text-red-900">Tolak Refund</h3>
                <p class="text-xs text-red-600 mt-0.5" id="rejectRefundNumber"></p>
            </div>
            <form id="quickRejectForm" method="POST" action="" class="p-5">
                @csrf
                <input type="hidden" name="status" value="rejected">
                <div class="mb-4">
                    <p class="text-sm text-gray-600">Refund akan ditolak dan dikembalikan ke pengaju.</p>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-red-700 mb-1">Alasan Penolakan *</label>
                    <textarea name="catatan_approval" rows="3" required class="w-full px-3 py-2 border border-red-200 rounded-lg text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-500" placeholder="Jelaskan alasan penolakan..."></textarea>
                </div>
                <div class="flex gap-2">
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

    <script>
        function showTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(function(el) {
                el.classList.add('hidden');
            });

            // Remove active state from all tabs
            document.querySelectorAll('[id^="tab-"]').forEach(function(el) {
                el.classList.remove('border-amber-500', 'border-emerald-500', 'border-red-500', 'text-amber-600', 'text-emerald-600', 'text-red-600', 'bg-amber-50', 'bg-emerald-50', 'bg-red-50');
                el.classList.add('border-transparent', 'text-gray-600');
            });

            // Show selected tab content
            document.getElementById('content-' + tabName).classList.remove('hidden');

            // Set active state for selected tab
            var activeTab = document.getElementById('tab-' + tabName);
            activeTab.classList.remove('border-transparent', 'text-gray-600');

            if (tabName === 'menunggu-approval') {
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
    </script>
</x-app-layout>
