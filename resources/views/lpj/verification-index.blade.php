<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">Verifikasi LPJ</h1>
                <p class="text-sm text-gray-500 mt-0.5">Verifikasi Laporan Pertanggung Jawaban</p>
            </div>
        </div>
    </x-slot>

    <!-- Quick Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
        <div class="bg-white rounded-xl border border-blue-100 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-amber-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xl font-semibold text-gray-900">{{ $stats['menunggu_verifikasi'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500">Menunggu Verifikasi</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-blue-100 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-orange-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xl font-semibold text-gray-900">{{ $stats['belum_buat_lpj'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500">Belum Buat LPJ</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-blue-100 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-violet-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xl font-semibold text-gray-900">{{ $stats['menunggu_revisi'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500">Menunggu Revisi</p>
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
                    <p class="text-xl font-semibold text-gray-900">{{ $stats['lpj_selesai'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500">LPJ Selesai</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-xl border border-blue-100 p-3 mb-4">
        <form method="GET" action="{{ route('lpj-verification.index') }}" class="flex flex-col sm:flex-row flex-wrap items-center gap-3">
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
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor LPJ/Pencairan atau uraian..." class="w-full px-3 py-2 text-sm border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
            </div>
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <button type="submit" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filter
                </button>
                @if(request()->anyFilled(['divisi_id', 'periode_anggaran_id', 'search']))
                    <a href="{{ route('lpj-verification.index') }}" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-4 py-2 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm font-medium rounded-lg transition-colors">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Tabs -->
    <div class="bg-white rounded-xl border border-blue-100 overflow-hidden mb-4">
        <div class="flex flex-wrap border-b border-blue-100">
            <button onclick="showTab('menunggu-verifikasi')" id="tab-menunggu-verifikasi" class="flex-1 min-w-[120px] px-3 py-3 text-sm font-semibold border-b-2 border-amber-500 text-amber-600 bg-amber-50 transition-colors">
                <div class="flex items-center justify-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="hidden md:inline">Menunggu Verifikasi</span>
                    <span class="md:hidden">Verifikasi</span>
                    <span class="bg-amber-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $stats['menunggu_verifikasi'] ?? 0 }}</span>
                </div>
            </button>
            <button onclick="showTab('menunggu-revisi')" id="tab-menunggu-revisi" class="flex-1 min-w-[120px] px-3 py-3 text-sm font-semibold border-b-2 border-transparent text-gray-600 hover:bg-blue-50 transition-colors">
                <div class="flex items-center justify-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <span class="hidden md:inline">Menunggu Revisi</span>
                    <span class="md:hidden">Revisi</span>
                    <span class="bg-violet-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $stats['menunggu_revisi'] ?? 0 }}</span>
                </div>
            </button>
            <button onclick="showTab('lpj-selesai')" id="tab-lpj-selesai" class="flex-1 min-w-[120px] px-3 py-3 text-sm font-semibold border-b-2 border-transparent text-gray-600 hover:bg-blue-50 transition-colors">
                <div class="flex items-center justify-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="hidden md:inline">LPJ Selesai</span>
                    <span class="md:hidden">Selesai</span>
                    <span class="bg-emerald-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $stats['lpj_selesai'] ?? 0 }}</span>
                </div>
            </button>
            <button onclick="showTab('belum-lpj')" id="tab-belum-lpj" class="flex-1 min-w-[120px] px-3 py-3 text-sm font-semibold border-b-2 border-transparent text-gray-600 hover:bg-blue-50 transition-colors">
                <div class="flex items-center justify-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="hidden md:inline">Belum Buat LPJ</span>
                    <span class="md:hidden">Belum LPJ</span>
                    <span class="bg-orange-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $stats['belum_buat_lpj'] ?? 0 }}</span>
                </div>
            </button>
        </div>
    </div>

    <!-- Tab Content: Menunggu Verifikasi -->
    <div id="content-menunggu-verifikasi" class="tab-content">
        @if($lpjs->count() > 0)
            <div class="bg-white rounded-xl border border-blue-100 overflow-hidden">
                <!-- Mobile Card View -->
                <div class="md:hidden space-y-3">
                    @foreach($lpjs as $lpj)
                        <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
                            <!-- Header -->
                            <div class="px-3 py-3 bg-gradient-to-r from-amber-50 to-amber-50/50 border-b border-amber-100">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="min-w-0 flex-1">
                                        <span class="font-mono text-xs font-bold text-amber-600 block truncate">{{ $lpj->nomor_lpj }}</span>
                                        <p class="text-xs text-slate-400 mt-0.5">{{ $lpj->submitted_at ? \Carbon\Carbon::parse($lpj->submitted_at)->format('d/m/Y H:i') : '-' }}</p>
                                    </div>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-700 flex-shrink-0">
                                        Verifikasi
                                    </span>
                                </div>
                            </div>

                            <!-- Body -->
                            <div class="p-3 space-y-3">
                                <div>
                                    <p class="text-sm font-semibold text-slate-900 line-clamp-2">{{ $lpj->uraian_kegiatan }}</p>
                                </div>

                                <div class="grid grid-cols-2 gap-2 text-xs">
                                    <div>
                                        <span class="text-slate-500">Divisi:</span>
                                        <span class="ml-1 font-medium text-slate-700 truncate block">{{ $lpj->pencairanDana->pengajuanDana->divisi->nama_divisi ?? '-' }}</span>
                                    </div>
                                    <div>
                                        <span class="text-slate-500">Pencairan:</span>
                                        <span class="ml-1 font-mono font-medium text-slate-700 truncate block">{{ $lpj->pencairanDana->nomor_pencairan ?? '-' }}</span>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-2 text-xs">
                                    <div>
                                        <span class="text-slate-500">Digunakan:</span>
                                        <span class="ml-1 font-medium text-slate-900">{{ formatRupiah($lpj->total_digunakan) }}</span>
                                    </div>
                                    <div>
                                        <span class="text-slate-500">Sisa:</span>
                                        <span class="ml-1 font-medium @if($lpj->sisa_dana > 0) text-orange-600 @else text-emerald-600 @endif">{{ formatRupiah($lpj->sisa_dana) }}</span>
                                    </div>
                                </div>

                                <div class="text-xs">
                                    <span class="text-slate-500">Oleh:</span>
                                    <span class="ml-1 font-medium text-slate-700">{{ $lpj->createdBy->name ?? '-' }}</span>
                                </div>

                                <div class="flex items-center justify-end pt-2 border-t border-slate-100 gap-1.5">
                                    <a href="{{ route('lpj.show', $lpj) }}" class="inline-flex items-center gap-1.5 px-3 py-2 text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors text-xs font-medium">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <span class="hidden sm:inline">Lihat</span>
                                    </a>
                                    <button onclick="quickApprove({{ $lpj->id }}, '{{ $lpj->nomor_lpj }}')" class="inline-flex items-center gap-1.5 px-3 py-2 text-emerald-600 bg-emerald-50 rounded-lg hover:bg-emerald-100 transition-colors text-xs font-medium">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span class="hidden sm:inline">Setujui</span>
                                    </button>
                                    <button onclick="quickReject({{ $lpj->id }}, '{{ $lpj->nomor_lpj }}')" class="inline-flex items-center gap-1.5 px-3 py-2 text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors text-xs font-medium">
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
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Nomor LPJ</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Uraian Kegiatan</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Divisi</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Nomor Pencairan</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Total Digunakan</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Sisa Dana</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Dibuat Oleh</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-blue-700 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-blue-50">
                            @foreach($lpjs as $lpj)
                            <tr class="hover:bg-blue-50/50 transition-colors">
                                <td class="px-4 py-3">
                                    <div>
                                        <span class="font-medium text-gray-900 text-sm">{{ $lpj->nomor_lpj }}</span>
                                        <div class="text-xs text-gray-400 mt-0.5">{{ $lpj->submitted_at ? \Carbon\Carbon::parse($lpj->submitted_at)->format('d/m/Y H:i') : '-' }}</div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700 max-w-xs truncate">{{ $lpj->uraian_kegiatan }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $lpj->pencairanDana->pengajuanDana->divisi->nama_divisi ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $lpj->pencairanDana->nomor_pencairan }}</td>
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ formatRupiah($lpj->total_digunakan) }}</td>
                                <td class="px-4 py-3 text-sm">
                                    @if($lpj->sisa_dana > 0)
                                        <span class="text-orange-600 font-medium">{{ formatRupiah($lpj->sisa_dana) }}</span>
                                    @else
                                        <span class="text-emerald-600">{{ formatRupiah(0) }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $lpj->createdBy->name ?? '-' }}</td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-0.5">
                                        <a href="{{ route('lpj.show', $lpj) }}" class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Lihat Detail">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        <button onclick="quickApprove({{ $lpj->id }}, '{{ $lpj->nomor_lpj }}')" class="p-1.5 text-emerald-500 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" title="Setujui">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </button>
                                        <button onclick="quickReject({{ $lpj->id }}, '{{ $lpj->nomor_lpj }}')" class="p-1.5 text-red-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Tolak/Revisi">
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
                            <span class="hidden md:inline">Menampilkan {{ $lpjs->firstItem() }} sampai {{ $lpjs->lastItem() }} dari {{ $lpjs->total() }} LPJ</span>
                            <span class="md:hidden">{{ $lpjs->total() }} LPJ</span>
                        </p>
                        {{ $lpjs->appends(request()->except('page'))->links() }}
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
                    <p class="text-gray-700 font-medium">Tidak Ada LPJ Menunggu Verifikasi</p>
                    <p class="text-gray-400 text-sm mt-1">Semua LPJ telah diverifikasi.</p>
                </div>
            </div>
        @endif
    </div>

    <!-- Tab Content: Menunggu Revisi -->
    <div id="content-menunggu-revisi" class="tab-content hidden">
        @if($lpjRevisi && $lpjRevisi->count() > 0)
            <div class="bg-white rounded-xl border border-blue-100 overflow-hidden">
                <!-- Mobile Card View -->
                <div class="md:hidden space-y-3">
                    @foreach($lpjRevisi as $lpj)
                        <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
                            <!-- Header -->
                            <div class="px-3 py-3 bg-gradient-to-r from-violet-50 to-violet-50/50 border-b border-violet-100">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="min-w-0 flex-1">
                                        <span class="font-mono text-xs font-bold text-violet-600 block truncate">{{ $lpj->nomor_lpj }}</span>
                                        <p class="text-xs text-slate-400 mt-0.5">{{ $lpj->updated_at ? \Carbon\Carbon::parse($lpj->updated_at)->format('d/m/Y H:i') : '-' }}</p>
                                    </div>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-violet-100 text-violet-700 flex-shrink-0">
                                        Revisi
                                    </span>
                                </div>
                            </div>

                            <!-- Body -->
                            <div class="p-3 space-y-3">
                                <div>
                                    <p class="text-sm font-semibold text-slate-900 line-clamp-2">{{ $lpj->uraian_kegiatan }}</p>
                                </div>

                                <div class="grid grid-cols-2 gap-2 text-xs">
                                    <div>
                                        <span class="text-slate-500">Divisi:</span>
                                        <span class="ml-1 font-medium text-slate-700 truncate block">{{ $lpj->pencairanDana->pengajuanDana->divisi->nama_divisi ?? '-' }}</span>
                                    </div>
                                    <div>
                                        <span class="text-slate-500">Digunakan:</span>
                                        <span class="ml-1 font-medium text-slate-900">{{ formatRupiah($lpj->total_digunakan) }}</span>
                                    </div>
                                </div>

                                <div class="text-xs">
                                    <span class="text-slate-500">Oleh:</span>
                                    <span class="ml-1 font-medium text-slate-700">{{ $lpj->createdBy->name ?? '-' }}</span>
                                </div>

                                <div class="bg-red-50 rounded-lg p-2">
                                    <p class="text-xs text-red-600 font-medium mb-0.5">Alasan Penolakan:</p>
                                    <p class="text-xs text-red-700 line-clamp-2">{{ $lpj->rejection_reason }}</p>
                                </div>

                                <div class="flex items-center justify-end pt-2 border-t border-slate-100 gap-1.5">
                                    <a href="{{ route('lpj.show', $lpj) }}" class="inline-flex items-center gap-1.5 px-3 py-2 text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors text-xs font-medium">
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
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Nomor LPJ</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Uraian Kegiatan</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Divisi</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Total Digunakan</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Dibuat Oleh</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Alasan Penolakan</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-blue-700 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-blue-50">
                            @foreach($lpjRevisi as $lpj)
                            <tr class="hover:bg-blue-50/50 transition-colors">
                                <td class="px-4 py-3">
                                    <div>
                                        <span class="font-medium text-gray-900 text-sm">{{ $lpj->nomor_lpj }}</span>
                                        <div class="text-xs text-gray-400 mt-0.5">{{ $lpj->updated_at ? \Carbon\Carbon::parse($lpj->updated_at)->format('d/m/Y H:i') : '-' }}</div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700 max-w-xs truncate">{{ $lpj->uraian_kegiatan }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $lpj->pencairanDana->pengajuanDana->divisi->nama_divisi ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ formatRupiah($lpj->total_digunakan) }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $lpj->createdBy->name ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-red-600 max-w-xs truncate" title="{{ $lpj->rejection_reason }}">
                                    {{ \Illuminate\Support\Str::limit($lpj->rejection_reason, 50) }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-0.5">
                                        <a href="{{ route('lpj.show', $lpj) }}" class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Lihat Detail">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                <div class="bg-gray-50 px-4 py-3 border-t border-blue-100">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                        <p class="text-xs md:text-sm text-gray-600 text-center md:text-left">
                            <span class="hidden md:inline">Menampilkan {{ $lpjRevisi->firstItem() }} sampai {{ $lpjRevisi->lastItem() }} dari {{ $lpjRevisi->total() }} LPJ</span>
                            <span class="md:hidden">{{ $lpjRevisi->total() }} LPJ</span>
                        </p>
                        {{ $lpjRevisi->appends(request()->except('page'))->links() }}
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white rounded-xl border border-blue-100 p-16 text-center">
                <div class="flex flex-col items-center">
                    <div class="w-16 h-16 bg-violet-50 rounded-2xl flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    <p class="text-gray-700 font-medium">Tidak Ada LPJ Menunggu Revisi</p>
                    <p class="text-gray-400 text-sm mt-1">Semua LPJ telah direvisi atau tidak ada LPJ yang ditolak.</p>
                </div>
            </div>
        @endif
    </div>

    <!-- Tab Content: LPJ Selesai -->
    <div id="content-lpj-selesai" class="tab-content hidden">
        @if($lpjSelesai && $lpjSelesai->count() > 0)
            <div class="bg-white rounded-xl border border-blue-100 overflow-hidden">
                <!-- Mobile Card View -->
                <div class="md:hidden space-y-3">
                    @foreach($lpjSelesai as $lpj)
                        <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
                            <!-- Header -->
                            <div class="px-3 py-3 bg-gradient-to-r from-emerald-50 to-emerald-50/50 border-b border-emerald-100">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="min-w-0 flex-1">
                                        <span class="font-mono text-xs font-bold text-emerald-600 block truncate">{{ $lpj->nomor_lpj }}</span>
                                    </div>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700 flex-shrink-0">
                                        Selesai
                                    </span>
                                </div>
                            </div>

                            <!-- Body -->
                            <div class="p-3 space-y-3">
                                <div>
                                    <p class="text-sm font-semibold text-slate-900 line-clamp-2">{{ $lpj->uraian_kegiatan }}</p>
                                </div>

                                <div class="text-xs">
                                    <span class="text-slate-500">Divisi:</span>
                                    <span class="ml-1 font-medium text-slate-700 truncate block">{{ $lpj->pencairanDana->pengajuanDana->divisi->nama_divisi ?? '-' }}</span>
                                </div>

                                <div class="grid grid-cols-2 gap-2 text-xs">
                                    <div>
                                        <span class="text-slate-500">Digunakan:</span>
                                        <span class="ml-1 font-medium text-slate-900">{{ formatRupiah($lpj->total_digunakan) }}</span>
                                    </div>
                                    <div>
                                        <span class="text-slate-500">Sisa:</span>
                                        <span class="ml-1 font-medium @if($lpj->sisa_dana > 0) text-orange-600 @else text-emerald-600 @endif">{{ formatRupiah($lpj->sisa_dana) }}</span>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-2 text-xs">
                                    <div>
                                        <span class="text-slate-500">Dibuat:</span>
                                        <span class="ml-1 font-medium text-slate-700 truncate block">{{ $lpj->createdBy->name ?? '-' }}</span>
                                    </div>
                                    <div>
                                        <span class="text-slate-500">Disetujui:</span>
                                        <span class="ml-1 font-medium text-slate-700 truncate block">{{ $lpj->approvedBy->name ?? '-' }}</span>
                                    </div>
                                </div>

                                <div class="text-xs">
                                    <span class="text-slate-500">Tgl Disetujui:</span>
                                    <span class="ml-1 font-medium text-slate-700">{{ $lpj->approved_at ? \Carbon\Carbon::parse($lpj->approved_at)->format('d/m/Y') : '-' }}</span>
                                </div>

                                <div class="flex items-center justify-end pt-2 border-t border-slate-100 gap-1.5">
                                    <a href="{{ route('lpj.show', $lpj) }}" class="inline-flex items-center gap-1.5 px-3 py-2 text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors text-xs font-medium">
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
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Nomor LPJ</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Uraian Kegiatan</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Divisi</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Total Digunakan</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Sisa Dana</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Dibuat Oleh</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Disetujui Oleh</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Tgl Disetujui</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-blue-700 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-blue-50">
                            @foreach($lpjSelesai as $lpj)
                            <tr class="hover:bg-blue-50/50 transition-colors">
                                <td class="px-4 py-3">
                                    <span class="font-medium text-gray-900 text-sm">{{ $lpj->nomor_lpj }}</span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700 max-w-xs truncate">{{ $lpj->uraian_kegiatan }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $lpj->pencairanDana->pengajuanDana->divisi->nama_divisi ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ formatRupiah($lpj->total_digunakan) }}</td>
                                <td class="px-4 py-3 text-sm @if($lpj->sisa_dana > 0) text-orange-600 font-medium @else text-emerald-600 @endif">
                                    {{ formatRupiah($lpj->sisa_dana) }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $lpj->createdBy->name ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $lpj->approvedBy->name ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $lpj->approved_at ? \Carbon\Carbon::parse($lpj->approved_at)->format('d/m/Y') : '-' }}</td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-0.5">
                                        <a href="{{ route('lpj.show', $lpj) }}" class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Lihat Detail">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                <div class="bg-gray-50 px-4 py-3 border-t border-blue-100">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                        <p class="text-xs md:text-sm text-gray-600 text-center md:text-left">
                            <span class="hidden md:inline">Menampilkan {{ $lpjSelesai->firstItem() }} sampai {{ $lpjSelesai->lastItem() }} dari {{ $lpjSelesai->total() }} LPJ</span>
                            <span class="md:hidden">{{ $lpjSelesai->total() }} LPJ</span>
                        </p>
                        {{ $lpjSelesai->appends(request()->except('page'))->links() }}
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
                    <p class="text-gray-700 font-medium">Belum Ada LPJ Selesai</p>
                    <p class="text-gray-400 text-sm mt-1">LPJ yang sudah disetujui akan muncul di sini.</p>
                </div>
            </div>
        @endif
    </div>

    <!-- Tab Content: Belum LPJ -->
    <div id="content-belum-lpj" class="tab-content hidden">
        @if($pencairanBelumLpj->count() > 0)
            <div class="bg-white rounded-xl border border-blue-100 overflow-hidden">
                <!-- Mobile Card View -->
                <div class="md:hidden space-y-3">
                    @foreach($pencairanBelumLpj as $pencairan)
                        <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
                            <!-- Header -->
                            <div class="px-3 py-3 bg-gradient-to-r from-orange-50 to-orange-50/50 border-b border-orange-100">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="min-w-0 flex-1">
                                        <span class="font-mono text-xs font-bold text-orange-600 block truncate">{{ $pencairan->nomor_pencairan }}</span>
                                        <p class="text-xs text-slate-400 mt-0.5">{{ \Carbon\Carbon::parse($pencairan->tanggal_pencairan)->format('d/m/Y') }}</p>
                                    </div>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-orange-100 text-orange-700 flex-shrink-0">
                                        Belum LPJ
                                    </span>
                                </div>
                            </div>

                            <!-- Body -->
                            <div class="p-3 space-y-3">
                                <div>
                                    <p class="text-sm font-semibold text-slate-900 line-clamp-2">{{ $pencairan->pengajuanDana->judul_pengajuan }}</p>
                                </div>

                                <div class="text-xs">
                                    <span class="text-slate-500">Divisi:</span>
                                    <span class="ml-1 font-medium text-slate-700 truncate block">{{ $pencairan->pengajuanDana->divisi->nama_divisi ?? '-' }}</span>
                                </div>

                                <div class="grid grid-cols-2 gap-2 text-xs">
                                    <div>
                                        <span class="text-slate-500">Pencairan:</span>
                                        <span class="ml-1 font-medium text-slate-900">{{ formatRupiah($pencairan->jumlah_pencairan) }}</span>
                                    </div>
                                    <div>
                                        <span class="text-slate-500">Oleh:</span>
                                        <span class="ml-1 font-medium text-slate-700 truncate block">{{ $pencairan->pengajuanDana->createdBy->name ?? '-' }}</span>
                                    </div>
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
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Nomor Pencairan</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Judul Pengajuan</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Divisi</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Jumlah Pencairan</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Tanggal Pencairan</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Pengaju</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Status LPJ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-blue-50">
                            @foreach($pencairanBelumLpj as $pencairan)
                            <tr class="hover:bg-blue-50/50 transition-colors">
                                <td class="px-4 py-3">
                                    <span class="font-medium text-gray-900 text-sm">{{ $pencairan->nomor_pencairan }}</span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700 max-w-xs truncate">{{ $pencairan->pengajuanDana->judul_pengajuan }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $pencairan->pengajuanDana->divisi->nama_divisi ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ formatRupiah($pencairan->jumlah_pencairan) }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ \Carbon\Carbon::parse($pencairan->tanggal_pencairan)->format('d/m/Y') }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $pencairan->pengajuanDana->createdBy->name ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>
                                        Belum LPJ
                                    </span>
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
                            <span class="hidden md:inline">Menampilkan {{ $pencairanBelumLpj->firstItem() }} sampai {{ $pencairanBelumLpj->lastItem() }} dari {{ $pencairanBelumLpj->total() }} pencairan</span>
                            <span class="md:hidden">{{ $pencairanBelumLpj->total() }} pencairan</span>
                        </p>
                        {{ $pencairanBelumLpj->appends(request()->except('page'))->links() }}
                    </div>
                </div>

                <!-- Total Pencairan Belum LPJ -->
                <div class="bg-orange-50 border-t border-orange-100 px-3 md:px-4 py-2 md:py-3">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-orange-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-sm font-semibold text-orange-900">Total Dana Belum Buat LPJ:</span>
                        </div>
                        <span class="text-base md:text-lg font-bold text-orange-600 truncate">{{ formatRupiah($totalPencairanBelumLpj ?? 0) }}</span>
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
                    <p class="text-gray-700 font-medium">Semua Transaksi Sudah Punya LPJ</p>
                    <p class="text-gray-400 text-sm mt-1">Tidak ada transaksi yang belum membuat LPJ (Honorarium & Pembayaran tidak memerlukan LPJ).</p>
                </div>
            </div>
        @endif
    </div>

    <!-- Quick Approve Modal -->
    <div id="quickApproveModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl shadow-lg max-w-md w-full overflow-hidden">
            <div class="px-4 md:px-5 py-3 md:py-4 border-b border-blue-100 bg-emerald-50">
                <h3 class="text-base font-semibold text-emerald-900">Setujui LPJ</h3>
                <p class="text-xs text-emerald-600 mt-0.5" id="approveLpjNumber"></p>
            </div>
            <form id="quickApproveForm" method="POST" action="" class="p-4 md:p-5">
                @csrf
                <input type="hidden" name="status" value="approved">
                <div class="mb-4">
                    <p class="text-sm text-gray-600">Apakah Anda yakin ingin menyetujui LPJ ini?</p>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan (opsional)</label>
                    <textarea name="catatan_verifikasi" rows="2" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500" placeholder="Catatan verifikasi..."></textarea>
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
                <h3 class="text-base font-semibold text-red-900">Tolak LPJ / Minta Revisi</h3>
                <p class="text-xs text-red-600 mt-0.5" id="rejectLpjNumber"></p>
            </div>
            <form id="quickRejectForm" method="POST" action="" class="p-4 md:p-5">
                @csrf
                <input type="hidden" name="status" value="rejected">
                <div class="mb-4">
                    <p class="text-sm text-gray-600">LPJ akan dikembalikan ke pengaju untuk direvisi.</p>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-red-700 mb-1">Alasan Penolakan *</label>
                    <textarea name="catatan_verifikasi" rows="3" required class="w-full px-3 py-2 border border-red-200 rounded-lg text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-500" placeholder="Jelaskan alasan penolakan agar pengaju dapat merevisi..."></textarea>
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

    <script>
        function showTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('[id^="tab-"]').forEach(el => {
                el.classList.remove('border-amber-500', 'text-amber-600', 'bg-amber-50');
                el.classList.remove('border-violet-500', 'text-violet-600', 'bg-violet-50');
                el.classList.remove('border-emerald-500', 'text-emerald-600', 'bg-emerald-50');
                el.classList.remove('border-orange-500', 'text-orange-600', 'bg-orange-50');
                el.classList.add('border-transparent', 'text-gray-600');
            });

            // Show selected tab
            document.getElementById('content-' + tabName).classList.remove('hidden');
            const activeTab = document.getElementById('tab-' + tabName);
            activeTab.classList.remove('border-transparent', 'text-gray-600');

            // Set active color based on tab
            if (tabName === 'menunggu-verifikasi') {
                activeTab.classList.add('border-amber-500', 'text-amber-600', 'bg-amber-50');
            } else if (tabName === 'menunggu-revisi') {
                activeTab.classList.add('border-violet-500', 'text-violet-600', 'bg-violet-50');
            } else if (tabName === 'lpj-selesai') {
                activeTab.classList.add('border-emerald-500', 'text-emerald-600', 'bg-emerald-50');
            } else if (tabName === 'belum-lpj') {
                activeTab.classList.add('border-orange-500', 'text-orange-600', 'bg-orange-50');
            }
        }

        function quickApprove(lpjId, nomorLpj) {
            const form = document.getElementById('quickApproveForm');
            form.action = '/lpj/' + lpjId + '/verify';
            document.getElementById('approveLpjNumber').textContent = nomorLpj;
            document.getElementById('quickApproveModal').classList.remove('hidden');
            document.getElementById('quickApproveModal').classList.add('flex');
        }

        function quickReject(lpjId, nomorLpj) {
            const form = document.getElementById('quickRejectForm');
            form.action = '/lpj/' + lpjId + '/verify';
            document.getElementById('rejectLpjNumber').textContent = nomorLpj;
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
