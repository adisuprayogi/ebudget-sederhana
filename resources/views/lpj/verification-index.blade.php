<x-app-layout>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-secondary-900">Verifikasi LPJ</h1>
        <p class="text-secondary-600 mt-1">Verifikasi Laporan Pertanggung Jawaban dan pantau pencairan yang belum membuat LPJ</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-gradient-to-br from-amber-400 to-amber-600 rounded-2xl p-6 text-white shadow-soft">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-amber-100 text-sm font-medium">Menunggu Verifikasi</p>
                    <p class="text-3xl font-bold mt-1">{{ $stats['menunggu_verifikasi'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-orange-400 to-orange-600 rounded-2xl p-6 text-white shadow-soft">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm font-medium">Belum Buat LPJ</p>
                    <p class="text-3xl font-bold mt-1">{{ $stats['belum_buat_lpj'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-400 to-purple-600 rounded-2xl p-6 text-white shadow-soft">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Menunggu Revisi</p>
                    <p class="text-3xl font-bold mt-1">{{ $stats['menunggu_revisi'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-400 to-green-600 rounded-2xl p-6 text-white shadow-soft">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">LPJ Selesai</p>
                    <p class="text-3xl font-bold mt-1">{{ $stats['lpj_selesai'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-teal-400 to-teal-600 rounded-2xl p-6 text-white shadow-soft">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-teal-100 text-sm font-medium">Disetujui Hari Ini</p>
                    <p class="text-3xl font-bold mt-1">{{ $stats['approved_today'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl p-6 text-white shadow-soft">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Disetujui Bulan Ini</p>
                    <p class="text-3xl font-bold mt-1">{{ $stats['approved_this_month'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl shadow-soft p-6 mb-6">
        <form method="GET" action="{{ route('lpj-verification.index') }}" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-secondary-700 mb-2">Divisi</label>
                <select name="divisi_id" class="w-full px-4 py-2 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <option value="">Semua Divisi</option>
                    @foreach(\App\Models\Divisi::orderBy('nama_divisi')->get() as $divisi)
                        <option value="{{ $divisi->id }}" {{ request('divisi_id') == $divisi->id ? 'selected' : '' }}>{{ $divisi->nama_divisi }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-secondary-700 mb-2">Periode Anggaran</label>
                <select name="periode_anggaran_id" class="w-full px-4 py-2 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <option value="">Semua Periode</option>
                    @foreach(\App\Models\PeriodeAnggaran::orderBy('tahun_anggaran', 'desc')->get() as $periode)
                        <option value="{{ $periode->id }}" {{ request('periode_anggaran_id') == $periode->id ? 'selected' : '' }}>{{ $periode->nama_periode }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-secondary-700 mb-2">Cari</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nomor LPJ/Pencairan atau uraian..." class="w-full px-4 py-2 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            </div>
            <div class="flex items-end">
                <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 3.293A1 1 0 013 2.586V4z" />
                    </svg>
                    Filter
                </button>
                @if(request()->anyFilled(['divisi_id', 'periode_anggaran_id', 'search']))
                <a href="{{ route('lpj-verification.index') }}" class="px-4 py-2 border border-secondary-200 text-secondary-600 rounded-xl hover:bg-secondary-50 transition-all duration-200 ml-2">
                    Reset
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Tabs -->
    <div class="bg-white rounded-2xl shadow-soft overflow-hidden mb-6">
        <div class="flex flex-wrap border-b border-secondary-200">
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
            <button onclick="showTab('menunggu-revisi')" id="tab-menunggu-revisi" class="flex-1 min-w-[120px] px-3 py-3 text-sm font-semibold border-b-2 border-transparent text-secondary-600 hover:bg-secondary-50 transition-colors">
                <div class="flex items-center justify-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <span class="hidden md:inline">Menunggu Revisi</span>
                    <span class="md:hidden">Revisi</span>
                    <span class="bg-purple-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $stats['menunggu_revisi'] ?? 0 }}</span>
                </div>
            </button>
            <button onclick="showTab('lpj-selesai')" id="tab-lpj-selesai" class="flex-1 min-w-[120px] px-3 py-3 text-sm font-semibold border-b-2 border-transparent text-secondary-600 hover:bg-secondary-50 transition-colors">
                <div class="flex items-center justify-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="hidden md:inline">LPJ Selesai</span>
                    <span class="md:hidden">Selesai</span>
                    <span class="bg-green-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $stats['lpj_selesai'] ?? 0 }}</span>
                </div>
            </button>
            <button onclick="showTab('belum-lpj')" id="tab-belum-lpj" class="flex-1 min-w-[120px] px-3 py-3 text-sm font-semibold border-b-2 border-transparent text-secondary-600 hover:bg-secondary-50 transition-colors">
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
            <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-secondary-50 border-b border-secondary-200">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Nomor LPJ</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Uraian Kegiatan</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Divisi</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Nomor Pencairan</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Total Digunakan</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Sisa Dana</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Dibuat Oleh</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-secondary-600 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-secondary-100">
                            @foreach($lpjs as $lpj)
                            <tr class="hover:bg-secondary-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div>
                                        <span class="font-medium text-secondary-900">{{ $lpj->nomor_lpj }}</span>
                                        <div class="text-xs text-secondary-500 mt-1">{{ $lpj->submitted_at ? \Carbon\Carbon::parse($lpj->submitted_at)->format('d/m/Y H:i') : '-' }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-secondary-600 max-w-xs truncate">{{ $lpj->uraian_kegiatan }}</td>
                                <td class="px-6 py-4 text-sm text-secondary-600">{{ $lpj->pencairanDana->pengajuanDana->divisi->nama_divisi ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-secondary-600">{{ $lpj->pencairanDana->nomor_pencairan }}</td>
                                <td class="px-6 py-4 text-sm font-medium text-secondary-900">Rp {{ number_format($lpj->total_digunakan, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-sm">
                                    @if($lpj->sisa_dana > 0)
                                        <span class="text-orange-600 font-medium">Rp {{ number_format($lpj->sisa_dana, 0, ',', '.') }}</span>
                                    @else
                                        <span class="text-green-600">Rp 0</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-secondary-600">{{ $lpj->createdBy->name ?? '-' }}</td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <a href="{{ route('lpj.show', $lpj) }}" class="p-2 text-secondary-600 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors" title="Lihat Detail">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        <button onclick="quickApprove({{ $lpj->id }}, '{{ $lpj->nomor_lpj }}')" class="p-2 text-green-600 hover:text-green-700 hover:bg-green-50 rounded-lg transition-colors" title="Setujui">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </button>
                                        <button onclick="quickReject({{ $lpj->id }}, '{{ $lpj->nomor_lpj }}')" class="p-2 text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors" title="Tolak/Revisi">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                <div class="bg-secondary-50 px-6 py-4 border-t border-secondary-200 flex items-center justify-between">
                    <p class="text-sm text-secondary-600">
                        Menampilkan {{ $lpjs->firstItem() }} sampai {{ $lpjs->lastItem() }} dari {{ $lpjs->total() }} LPJ
                    </p>
                    {{ $lpjs->appends(request()->except('page'))->links() }}
                </div>
            </div>
        @else
            <div class="bg-white rounded-2xl shadow-soft p-12 text-center">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-secondary-900 mb-2">Tidak Ada LPJ Menunggu Verifikasi</h3>
                <p class="text-secondary-500">Semua LPJ telah diverifikasi.</p>
            </div>
        @endif
    </div>

    <!-- Tab Content: Menunggu Revisi -->
    <div id="content-menunggu-revisi" class="tab-content hidden">
        @if($lpjRevisi && $lpjRevisi->count() > 0)
            <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-secondary-50 border-b border-secondary-200">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Nomor LPJ</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Uraian Kegiatan</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Divisi</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Total Digunakan</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Dibuat Oleh</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Alasan Penolakan</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-secondary-600 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-secondary-100">
                            @foreach($lpjRevisi as $lpj)
                            <tr class="hover:bg-secondary-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div>
                                        <span class="font-medium text-secondary-900">{{ $lpj->nomor_lpj }}</span>
                                        <div class="text-xs text-secondary-500 mt-1">{{ $lpj->updated_at ? \Carbon\Carbon::parse($lpj->updated_at)->format('d/m/Y H:i') : '-' }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-secondary-600 max-w-xs truncate">{{ $lpj->uraian_kegiatan }}</td>
                                <td class="px-6 py-4 text-sm text-secondary-600">{{ $lpj->pencairanDana->pengajuanDana->divisi->nama_divisi ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm font-medium text-secondary-900">Rp {{ number_format($lpj->total_digunakan, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-sm text-secondary-600">{{ $lpj->createdBy->name ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-red-600 max-w-xs truncate" title="{{ $lpj->rejection_reason }}">
                                    {{ \Illuminate\Support\Str::limit($lpj->rejection_reason, 50) }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <a href="{{ route('lpj.show', $lpj) }}" class="p-2 text-secondary-600 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors" title="Lihat Detail">
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
                        Menampilkan {{ $lpjRevisi->firstItem() }} sampai {{ $lpjRevisi->lastItem() }} dari {{ $lpjRevisi->total() }} LPJ
                    </p>
                    {{ $lpjRevisi->appends(request()->except('page'))->links() }}
                </div>
            </div>
        @else
            <div class="bg-white rounded-2xl shadow-soft p-12 text-center">
                <div class="w-20 h-20 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-secondary-900 mb-2">Tidak Ada LPJ Menunggu Revisi</h3>
                <p class="text-secondary-500">Semua LPJ telah direvisi atau tidak ada LPJ yang ditolak.</p>
            </div>
        @endif
    </div>

    <!-- Tab Content: LPJ Selesai -->
    <div id="content-lpj-selesai" class="tab-content hidden">
        @if($lpjSelesai && $lpjSelesai->count() > 0)
            <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-secondary-50 border-b border-secondary-200">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Nomor LPJ</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Uraian Kegiatan</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Divisi</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Total Digunakan</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Sisa Dana</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Dibuat Oleh</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Disetujui Oleh</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Tgl Disetujui</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-secondary-600 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-secondary-100">
                            @foreach($lpjSelesai as $lpj)
                            <tr class="hover:bg-secondary-50 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="font-medium text-secondary-900">{{ $lpj->nomor_lpj }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-secondary-600 max-w-xs truncate">{{ $lpj->uraian_kegiatan }}</td>
                                <td class="px-6 py-4 text-sm text-secondary-600">{{ $lpj->pencairanDana->pengajuanDana->divisi->nama_divisi ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm font-medium text-secondary-900">Rp {{ number_format($lpj->total_digunakan, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-sm @if($lpj->sisa_dana > 0) text-orange-600 font-medium @else text-green-600 @endif">
                                    Rp {{ number_format($lpj->sisa_dana, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-secondary-600">{{ $lpj->createdBy->name ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-secondary-600">{{ $lpj->approvedBy->name ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-secondary-600">{{ $lpj->approved_at ? \Carbon\Carbon::parse($lpj->approved_at)->format('d/m/Y') : '-' }}</td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <a href="{{ route('lpj.show', $lpj) }}" class="p-2 text-secondary-600 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors" title="Lihat Detail">
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
                        Menampilkan {{ $lpjSelesai->firstItem() }} sampai {{ $lpjSelesai->lastItem() }} dari {{ $lpjSelesai->total() }} LPJ
                    </p>
                    {{ $lpjSelesai->appends(request()->except('page'))->links() }}
                </div>
            </div>
        @else
            <div class="bg-white rounded-2xl shadow-soft p-12 text-center">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-secondary-900 mb-2">Belum Ada LPJ Selesai</h3>
                <p class="text-secondary-500">LPJ yang sudah disetujui akan muncul di sini.</p>
            </div>
        @endif
    </div>

    <!-- Tab Content: Belum LPJ -->
    <div id="content-belum-lpj" class="tab-content hidden">
        @if($pencairanBelumLpj->count() > 0)
            <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-secondary-50 border-b border-secondary-200">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Nomor Pencairan</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Judul Pengajuan</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Divisi</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Jumlah Pencairan</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Tanggal Pencairan</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Pengaju</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Status LPJ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-secondary-100">
                            @foreach($pencairanBelumLpj as $pencairan)
                            <tr class="hover:bg-secondary-50 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="font-medium text-secondary-900">{{ $pencairan->nomor_pencairan }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-secondary-600 max-w-xs truncate">{{ $pencairan->pengajuanDana->judul_pengajuan }}</td>
                                <td class="px-6 py-4 text-sm text-secondary-600">{{ $pencairan->pengajuanDana->divisi->nama_divisi ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm font-medium text-secondary-900">Rp {{ number_format($pencairan->jumlah_pencairan, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-sm text-secondary-600">{{ \Carbon\Carbon::parse($pencairan->tanggal_pencairan)->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 text-sm text-secondary-600">{{ $pencairan->pengajuanDana->createdBy->name ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-700">
                                        Belum Buat LPJ
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="bg-secondary-50 px-6 py-4 border-t border-secondary-200 flex items-center justify-between">
                    <p class="text-sm text-secondary-600">
                        Menampilkan {{ $pencairanBelumLpj->firstItem() }} sampai {{ $pencairanBelumLpj->lastItem() }} dari {{ $pencairanBelumLpj->total() }} pencairan
                    </p>
                    {{ $pencairanBelumLpj->appends(request()->except('page'))->links() }}
                </div>
            </div>
        @else
            <div class="bg-white rounded-2xl shadow-soft p-12 text-center">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-secondary-900 mb-2">Semua Transaksi Sudah Punya LPJ</h3>
                <p class="text-secondary-500">Tidak ada transaksi yang belum membuat LPJ (Honorarium & Pembayaran tidak memerlukan LPJ).</p>
            </div>
        @endif
    </div>

    <!-- Quick Approve Modal -->
    <div id="quickApproveModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full mx-4 overflow-hidden">
            <div class="px-6 py-4 border-b border-secondary-200 bg-green-50">
                <h3 class="text-lg font-semibold text-green-900">Setujui LPJ</h3>
                <p class="text-sm text-green-600 mt-1" id="approveLpjNumber"></p>
            </div>
            <form id="quickApproveForm" method="POST" action="" class="p-6">
                @csrf
                <input type="hidden" name="status" value="approved">
                <div class="mb-4">
                    <p class="text-sm text-secondary-600">Apakah Anda yakin ingin menyetujui LPJ ini?</p>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-secondary-700 mb-1">Catatan (opsional)</label>
                    <textarea name="catatan_verifikasi" rows="2" class="w-full px-3 py-2 border border-secondary-200 rounded-lg text-sm" placeholder="Catatan verifikasi..."></textarea>
                </div>
                <div class="flex space-x-3">
                    <button type="button" onclick="closeApproveModal()" class="flex-1 px-4 py-2 border border-secondary-200 text-secondary-700 rounded-lg hover:bg-secondary-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        Ya, Setujui
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Quick Reject Modal -->
    <div id="quickRejectModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full mx-4 overflow-hidden">
            <div class="px-6 py-4 border-b border-secondary-200 bg-red-50">
                <h3 class="text-lg font-semibold text-red-900">Tolak LPJ / Minta Revisi</h3>
                <p class="text-sm text-red-600 mt-1" id="rejectLpjNumber"></p>
            </div>
            <form id="quickRejectForm" method="POST" action="" class="p-6">
                @csrf
                <input type="hidden" name="status" value="rejected">
                <div class="mb-4">
                    <p class="text-sm text-secondary-600">LPJ akan dikembalikan ke pengaju untuk direvisi.</p>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-red-700 mb-1">Alasan Penolakan *</label>
                    <textarea name="catatan_verifikasi" rows="3" required class="w-full px-3 py-2 border border-red-200 rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Jelaskan alasan penolakan agar pengaju dapat merevisi..."></textarea>
                </div>
                <div class="flex space-x-3">
                    <button type="button" onclick="closeRejectModal()" class="flex-1 px-4 py-2 border border-secondary-200 text-secondary-700 rounded-lg hover:bg-secondary-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
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
                el.classList.remove('border-purple-500', 'text-purple-600', 'bg-purple-50');
                el.classList.remove('border-green-500', 'text-green-600', 'bg-green-50');
                el.classList.remove('border-orange-500', 'text-orange-600', 'bg-orange-50');
                el.classList.add('border-transparent', 'text-secondary-600');
            });

            // Show selected tab
            document.getElementById('content-' + tabName).classList.remove('hidden');
            const activeTab = document.getElementById('tab-' + tabName);
            activeTab.classList.remove('border-transparent', 'text-secondary-600');

            // Set active color based on tab
            if (tabName === 'menunggu-verifikasi') {
                activeTab.classList.add('border-amber-500', 'text-amber-600', 'bg-amber-50');
            } else if (tabName === 'menunggu-revisi') {
                activeTab.classList.add('border-purple-500', 'text-purple-600', 'bg-purple-50');
            } else if (tabName === 'lpj-selesai') {
                activeTab.classList.add('border-green-500', 'text-green-600', 'bg-green-50');
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
