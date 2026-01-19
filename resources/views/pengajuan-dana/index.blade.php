<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">Pengajuan Dana</h1>
                <p class="text-sm text-gray-500 mt-0.5">Kelola pengajuan dana untuk keperluan operasional</p>
            </div>
            @if(auth()->user()->hasAnyRole(['kepala_divisi', 'staff_divisi']))
                <a href="{{ route('pengajuan-dana.select-jenis') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Buat Pengajuan Baru
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
                    <p class="text-xl font-semibold text-gray-900">{{ $statistics['total'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500">Total Pengajuan</p>
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
                    <p class="text-xl font-semibold text-gray-900">{{ $statistics['menunggu_approval'] ?? 0 }}</p>
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
                    <p class="text-xl font-semibold text-gray-900">{{ $statistics['disetujui'] ?? 0 }}</p>
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
                    <p class="text-sm font-semibold text-gray-900">{{ formatRupiah($statistics['total_nilai'] ?? 0) }}</p>
                    <p class="text-xs text-gray-500">Total Nilai</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-xl border border-blue-100 p-3 mb-4">
        <form method="GET" action="{{ route('pengajuan-dana.index') }}" class="flex flex-wrap items-center gap-3">
            <div class="min-w-[140px]">
                <select name="jenis_pengajuan" class="w-full px-3 py-2 text-sm border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all bg-white">
                    <option value="">Semua Jenis</option>
                    @foreach($filterOptions['jenisPengajuans'] ?? [] as $jenis)
                        <option value="{{ $jenis }}" {{ request('jenis_pengajuan') == $jenis ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $jenis)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="min-w-[140px]">
                <select name="divisi_id" class="w-full px-3 py-2 text-sm border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all bg-white">
                    <option value="">Semua Divisi</option>
                    @foreach($filterOptions['divisis'] ?? [] as $divisi)
                        <option value="{{ $divisi->id }}" {{ request('divisi_id') == $divisi->id ? 'selected' : '' }}>{{ $divisi->nama_divisi }}</option>
                    @endforeach
                </select>
            </div>
            <div class="min-w-[200px] flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor atau judul..." class="w-full px-3 py-2 text-sm border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
            </div>
            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                Filter
            </button>
            @if(request()->anyFilled(['jenis_pengajuan', 'divisi_id', 'search']))
                <a href="{{ route('pengajuan-dana.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm font-medium rounded-lg transition-colors">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Tabs -->
    <div class="bg-white rounded-xl border border-blue-100 overflow-hidden mb-4">
        <div class="flex flex-wrap border-b border-blue-100 overflow-x-auto">
            <button onclick="showTab('draft')" id="tab-draft" class="flex-1 min-w-[100px] px-3 py-3 text-sm font-semibold border-b-2 border-gray-500 text-gray-600 bg-gray-50 transition-colors">
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
                    <span class="hidden md:inline">Menunggu</span>
                    <span class="bg-amber-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $stats['menunggu_approval'] ?? 0 }}</span>
                </div>
            </button>
            <button onclick="showTab('menunggu-pencairan')" id="tab-menunggu-pencairan" class="flex-1 min-w-[100px] px-3 py-3 text-sm font-semibold border-b-2 border-transparent text-gray-600 hover:bg-blue-50 transition-colors">
                <div class="flex items-center justify-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="hidden md:inline">Siap Cair</span>
                    <span class="bg-blue-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $stats['menunggu_pencairan'] ?? 0 }}</span>
                </div>
            </button>
            <button onclick="showTab('dicairkan')" id="tab-dicairkan" class="flex-1 min-w-[100px] px-3 py-3 text-sm font-semibold border-b-2 border-transparent text-gray-600 hover:bg-blue-50 transition-colors">
                <div class="flex items-center justify-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    <span class="hidden md:inline">Dicairkan</span>
                    <span class="bg-emerald-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $stats['dicairkan'] ?? 0 }}</span>
                </div>
            </button>
            <button onclick="showTab('proses')" id="tab-proses" class="flex-1 min-w-[100px] px-3 py-3 text-sm font-semibold border-b-2 border-transparent text-gray-600 hover:bg-blue-50 transition-colors">
                <div class="flex items-center justify-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span class="hidden md:inline">Proses LPJ</span>
                    <span class="bg-amber-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $stats['proses'] ?? 0 }}</span>
                </div>
            </button>
            <button onclick="showTab('selesai')" id="tab-selesai" class="flex-1 min-w-[100px] px-3 py-3 text-sm font-semibold border-b-2 border-transparent text-gray-600 hover:bg-blue-50 transition-colors">
                <div class="flex items-center justify-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="hidden md:inline">Selesai</span>
                    <span class="bg-cyan-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $stats['selesai'] ?? 0 }}</span>
                </div>
            </button>
            <button onclick="showTab('ditolak')" id="tab-ditolak" class="flex-1 min-w-[100px] px-3 py-3 text-sm font-semibold border-b-2 border-transparent text-gray-600 hover:bg-blue-50 transition-colors">
                <div class="flex items-center justify-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="hidden md:inline">Ditolak</span>
                    <span class="bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $stats['ditolak'] ?? 0 }}</span>
                </div>
            </button>
            <button onclick="showTab('cancelled')" id="tab-cancelled" class="flex-1 min-w-[100px] px-3 py-3 text-sm font-semibold border-b-2 border-transparent text-gray-600 hover:bg-blue-50 transition-colors">
                <div class="flex items-center justify-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                    </svg>
                    <span class="hidden md:inline">Batal</span>
                    <span class="bg-slate-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $stats['cancelled'] ?? 0 }}</span>
                </div>
            </button>
        </div>
    </div>

    @php
        $jenisLabels = [
            'kegiatan' => 'Kegiatan',
            'pengadaan' => 'Pengadaan',
            'pembayaran' => 'Pembayaran',
            'honorarium' => 'Honorarium',
            'sewa' => 'Sewa',
            'konsumsi' => 'Konsumsi',
            'konsumi' => 'Konsumi',
            'reimbursement' => 'Reimbursement',
            'lainnya' => 'Lainnya',
        ];
        $jenisColors = [
            'kegiatan' => 'bg-blue-100 text-blue-700',
            'pengadaan' => 'bg-blue-100 text-blue-700',
            'pembayaran' => 'bg-orange-100 text-orange-700',
            'honorarium' => 'bg-blue-100 text-blue-700',
            'sewa' => 'bg-orange-100 text-orange-700',
            'konsumsi' => 'bg-blue-100 text-blue-700',
            'konsumi' => 'bg-blue-100 text-blue-700',
            'reimbursement' => 'bg-blue-100 text-blue-700',
            'lainnya' => 'bg-gray-100 text-gray-700',
        ];
    @endphp

    <!-- Tab Content: Draft -->
    <div id="content-draft" class="tab-content">
        @if($pengajuansDraft->count() > 0)
            <div class="bg-white rounded-xl border border-blue-100 overflow-hidden">
                @include('pengajuan-dana.partials.table', ['pengajuans' => $pengajuansDraft])
            </div>
        @else
            <div class="bg-white rounded-xl border border-blue-100 p-12 text-center">
                <div class="w-12 h-12 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <p class="text-gray-700 font-medium">Tidak Ada Draft</p>
                <p class="text-gray-400 text-sm mt-1">Pengajuan draft akan ditampilkan di sini.</p>
            </div>
        @endif
    </div>

    <!-- Tab Content: Menunggu Approval -->
    <div id="content-menunggu-approval" class="tab-content hidden">
        @if($pengajuansMenungguApproval->count() > 0)
            <div class="bg-white rounded-xl border border-blue-100 overflow-hidden">
                @include('pengajuan-dana.partials.table', ['pengajuans' => $pengajuansMenungguApproval])
            </div>
        @else
            <div class="bg-white rounded-xl border border-blue-100 p-12 text-center">
                <div class="w-12 h-12 bg-amber-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="text-gray-700 font-medium">Tidak Ada Pengajuan Menunggu Approval</p>
                <p class="text-gray-400 text-sm mt-1">Pengajuan yang menunggu approval akan ditampilkan di sini.</p>
            </div>
        @endif
    </div>

    <!-- Tab Content: Menunggu Pencairan -->
    <div id="content-menunggu-pencairan" class="tab-content hidden">
        @if($pengajuansMenungguPencairan->count() > 0)
            <div class="bg-white rounded-xl border border-blue-100 overflow-hidden">
                @include('pengajuan-dana.partials.table', ['pengajuans' => $pengajuansMenungguPencairan])
            </div>
        @else
            <div class="bg-white rounded-xl border border-blue-100 p-12 text-center">
                <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <p class="text-gray-700 font-medium">Tidak Ada Pengajuan Menunggu Pencairan</p>
                <p class="text-gray-400 text-sm mt-1">Pengajuan yang siap dicairkan akan ditampilkan di sini.</p>
            </div>
        @endif
    </div>

    <!-- Tab Content: Dicairkan -->
    <div id="content-dicairkan" class="tab-content hidden">
        @if($pengajuansDicairkan->count() > 0)
            <div class="bg-white rounded-xl border border-blue-100 overflow-hidden">
                @include('pengajuan-dana.partials.table', ['pengajuans' => $pengajuansDicairkan])
            </div>
        @else
            <div class="bg-white rounded-xl border border-blue-100 p-12 text-center">
                <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <p class="text-gray-700 font-medium">Belum Ada Dana Dicairkan</p>
                <p class="text-gray-400 text-sm mt-1">Pengajuan yang dananya sudah dicairkan akan ditampilkan di sini.</p>
            </div>
        @endif
    </div>

    <!-- Tab Content: Proses LPJ -->
    <div id="content-proses" class="tab-content hidden">
        @if($pengajuansProses->count() > 0)
            <div class="bg-white rounded-xl border border-blue-100 overflow-hidden">
                @include('pengajuan-dana.partials.table', ['pengajuans' => $pengajuansProses])
            </div>
        @else
            <div class="bg-white rounded-xl border border-blue-100 p-12 text-center">
                <div class="w-12 h-12 bg-amber-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </div>
                <p class="text-gray-700 font-medium">Tidak Ada Pengajuan Diproses</p>
                <p class="text-gray-400 text-sm mt-1">Pengajuan yang sedang diproses LPJ-nya akan ditampilkan di sini.</p>
            </div>
        @endif
    </div>

    <!-- Tab Content: Selesai -->
    <div id="content-selesai" class="tab-content hidden">
        @if($pengajuansSelesai->count() > 0)
            <div class="bg-white rounded-xl border border-blue-100 overflow-hidden">
                @include('pengajuan-dana.partials.table', ['pengajuans' => $pengajuansSelesai])
            </div>
        @else
            <div class="bg-white rounded-xl border border-blue-100 p-12 text-center">
                <div class="w-12 h-12 bg-cyan-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="text-gray-700 font-medium">Belum Ada Pengajuan Selesai</p>
                <p class="text-gray-400 text-sm mt-1">Pengajuan yang selesai akan ditampilkan di sini.</p>
            </div>
        @endif
    </div>

    <!-- Tab Content: Ditolak -->
    <div id="content-ditolak" class="tab-content hidden">
        @if($pengajuansDitolak->count() > 0)
            <div class="bg-white rounded-xl border border-blue-100 overflow-hidden">
                @include('pengajuan-dana.partials.table', ['pengajuans' => $pengajuansDitolak])
            </div>
        @else
            <div class="bg-white rounded-xl border border-blue-100 p-12 text-center">
                <div class="w-12 h-12 bg-red-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="text-gray-700 font-medium">Tidak Ada Pengajuan Ditolak</p>
                <p class="text-gray-400 text-sm mt-1">Pengajuan yang ditolak akan ditampilkan di sini.</p>
            </div>
        @endif
    </div>

    <!-- Tab Content: Dibatalkan -->
    <div id="content-cancelled" class="tab-content hidden">
        @if($pengajuansCancelled->count() > 0)
            <div class="bg-white rounded-xl border border-blue-100 overflow-hidden">
                @include('pengajuan-dana.partials.table', ['pengajuans' => $pengajuansCancelled])
            </div>
        @else
            <div class="bg-white rounded-xl border border-blue-100 p-12 text-center">
                <div class="w-12 h-12 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                    </svg>
                </div>
                <p class="text-gray-700 font-medium">Tidak Ada Pengajuan Dibatalkan</p>
                <p class="text-gray-400 text-sm mt-1">Pengajuan yang dibatalkan akan ditampilkan di sini.</p>
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
                el.classList.remove('border-gray-500', 'border-amber-500', 'border-blue-500', 'border-emerald-500', 'border-cyan-500', 'border-red-500', 'border-slate-500', 'text-gray-600', 'text-amber-600', 'text-blue-600', 'text-emerald-600', 'text-cyan-600', 'text-red-600', 'text-slate-600', 'bg-gray-50', 'bg-amber-50', 'bg-blue-50', 'bg-emerald-50', 'bg-cyan-50', 'bg-red-50', 'bg-slate-50');
                el.classList.add('border-transparent', 'text-gray-600');
            });

            // Show selected tab content
            document.getElementById('content-' + tabName).classList.remove('hidden');

            // Set active state for selected tab
            var activeTab = document.getElementById('tab-' + tabName);
            activeTab.classList.remove('border-transparent', 'text-gray-600');

            if (tabName === 'draft') {
                activeTab.classList.add('border-gray-500', 'text-gray-600', 'bg-gray-50');
            } else if (tabName === 'menunggu-approval') {
                activeTab.classList.add('border-amber-500', 'text-amber-600', 'bg-amber-50');
            } else if (tabName === 'menunggu-pencairan') {
                activeTab.classList.add('border-blue-500', 'text-blue-600', 'bg-blue-50');
            } else if (tabName === 'dicairkan') {
                activeTab.classList.add('border-emerald-500', 'text-emerald-600', 'bg-emerald-50');
            } else if (tabName === 'proses') {
                activeTab.classList.add('border-amber-500', 'text-amber-600', 'bg-amber-50');
            } else if (tabName === 'selesai') {
                activeTab.classList.add('border-cyan-500', 'text-cyan-600', 'bg-cyan-50');
            } else if (tabName === 'ditolak') {
                activeTab.classList.add('border-red-500', 'text-red-600', 'bg-red-50');
            } else if (tabName === 'cancelled') {
                activeTab.classList.add('border-slate-500', 'text-slate-600', 'bg-slate-50');
            }
        }
    </script>
</x-app-layout>
