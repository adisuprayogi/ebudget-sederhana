<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-secondary-900">Pengajuan Dana</h1>
                <p class="text-secondary-600 mt-1">Kelola pengajuan dana untuk keperluan operasional</p>
            </div>
            @if(auth()->user()->hasAnyRole(['kepala_divisi', 'staff_divisi']))
                <a href="{{ route('pengajuan-dana.select-jenis') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-all duration-200 shadow-soft hover:shadow-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Buat Pengajuan Baru
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
                        <p class="text-blue-100 text-sm font-medium">Total Pengajuan</p>
                        <p class="text-3xl font-bold mt-1">{{ $statistics['total'] ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-amber-400 to-amber-600 rounded-2xl p-6 text-white shadow-soft">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-amber-100 text-sm font-medium">Menunggu Approval</p>
                        <p class="text-3xl font-bold mt-1">{{ $statistics['menunggu_approval'] ?? 0 }}</p>
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
                        <p class="text-green-100 text-sm font-medium">Disetujui/Selesai</p>
                        <p class="text-3xl font-bold mt-1">{{ $statistics['disetujui'] ?? 0 }}</p>
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
                        <p class="text-2xl font-bold mt-1">{{ formatRupiah($statistics['total_nilai'] ?? 0) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-2xl shadow-soft p-6 mb-6">
            <form method="GET" action="{{ route('pengajuan-dana.index') }}" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-secondary-700 mb-2">Jenis Pengajuan</label>
                    <select name="jenis_pengajuan" class="w-full px-4 py-2 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <option value="">Semua Jenis</option>
                        @foreach($filterOptions['jenisPengajuans'] ?? [] as $jenis)
                            <option value="{{ $jenis }}" {{ request('jenis_pengajuan') == $jenis ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $jenis)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-secondary-700 mb-2">Divisi</label>
                    <select name="divisi_id" class="w-full px-4 py-2 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <option value="">Semua Divisi</option>
                        @foreach($filterOptions['divisis'] ?? [] as $divisi)
                            <option value="{{ $divisi->id }}" {{ request('divisi_id') == $divisi->id ? 'selected' : '' }}>{{ $divisi->nama_divisi }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-secondary-700 mb-2">Cari</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nomor atau judul..." class="w-full px-4 py-2 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 3.293A1 1 0 013 2.586V4z" />
                        </svg>
                        Filter
                    </button>
                    @if(request()->anyFilled(['jenis_pengajuan', 'divisi_id', 'search']))
                    <a href="{{ route('pengajuan-dana.index') }}" class="px-4 py-2 border border-secondary-200 text-secondary-600 rounded-xl hover:bg-secondary-50 transition-all duration-200 ml-2">
                        Reset
                    </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Tabs -->
        <div class="bg-white rounded-2xl shadow-soft overflow-hidden mb-6">
            <div class="flex flex-wrap border-b border-secondary-200 overflow-x-auto">
                <button onclick="showTab('draft')" id="tab-draft" class="flex-1 min-w-[120px] px-3 py-3 text-sm font-semibold border-b-2 border-slate-500 text-slate-600 bg-slate-50 transition-colors">
                    <div class="flex items-center justify-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span class="hidden md:inline">Draft</span>
                        <span class="bg-slate-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $stats['draft'] ?? 0 }}</span>
                    </div>
                </button>
                <button onclick="showTab('menunggu-approval')" id="tab-menunggu-approval" class="flex-1 min-w-[120px] px-3 py-3 text-sm font-semibold border-b-2 border-transparent text-secondary-600 hover:bg-secondary-50 transition-colors">
                    <div class="flex items-center justify-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="hidden md:inline">Menunggu Approval</span>
                        <span class="bg-amber-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $stats['menunggu_approval'] ?? 0 }}</span>
                    </div>
                </button>
                <button onclick="showTab('menunggu-pencairan')" id="tab-menunggu-pencairan" class="flex-1 min-w-[120px] px-3 py-3 text-sm font-semibold border-b-2 border-transparent text-secondary-600 hover:bg-secondary-50 transition-colors">
                    <div class="flex items-center justify-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span class="hidden md:inline">Menunggu Pencairan</span>
                        <span class="bg-blue-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $stats['menunggu_pencairan'] ?? 0 }}</span>
                    </div>
                </button>
                <button onclick="showTab('dicairkan')" id="tab-dicairkan" class="flex-1 min-w-[120px] px-3 py-3 text-sm font-semibold border-b-2 border-transparent text-secondary-600 hover:bg-secondary-50 transition-colors">
                    <div class="flex items-center justify-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        <span class="hidden md:inline">Dicairkan</span>
                        <span class="bg-purple-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $stats['dicairkan'] ?? 0 }}</span>
                    </div>
                </button>
                <button onclick="showTab('proses')" id="tab-proses" class="flex-1 min-w-[120px] px-3 py-3 text-sm font-semibold border-b-2 border-transparent text-secondary-600 hover:bg-secondary-50 transition-colors">
                    <div class="flex items-center justify-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span class="hidden md:inline">Proses LPJ</span>
                        <span class="bg-cyan-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $stats['proses'] ?? 0 }}</span>
                    </div>
                </button>
                <button onclick="showTab('selesai')" id="tab-selesai" class="flex-1 min-w-[120px] px-3 py-3 text-sm font-semibold border-b-2 border-transparent text-secondary-600 hover:bg-secondary-50 transition-colors">
                    <div class="flex items-center justify-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="hidden md:inline">Selesai</span>
                        <span class="bg-green-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $stats['selesai'] ?? 0 }}</span>
                    </div>
                </button>
                <button onclick="showTab('ditolak')" id="tab-ditolak" class="flex-1 min-w-[120px] px-3 py-3 text-sm font-semibold border-b-2 border-transparent text-secondary-600 hover:bg-secondary-50 transition-colors">
                    <div class="flex items-center justify-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="hidden md:inline">Ditolak</span>
                        <span class="bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $stats['ditolak'] ?? 0 }}</span>
                    </div>
                </button>
                <button onclick="showTab('cancelled')" id="tab-cancelled" class="flex-1 min-w-[120px] px-3 py-3 text-sm font-semibold border-b-2 border-transparent text-secondary-600 hover:bg-secondary-50 transition-colors">
                    <div class="flex items-center justify-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                        </svg>
                        <span class="hidden md:inline">Dibatalkan</span>
                        <span class="bg-gray-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $stats['cancelled'] ?? 0 }}</span>
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
                'pengadaan' => 'bg-green-100 text-green-700',
                'pembayaran' => 'bg-yellow-100 text-yellow-700',
                'honorarium' => 'bg-purple-100 text-purple-700',
                'sewa' => 'bg-orange-100 text-orange-700',
                'konsumsi' => 'bg-pink-100 text-pink-700',
                'konsumi' => 'bg-pink-100 text-pink-700',
                'reimbursement' => 'bg-teal-100 text-teal-700',
                'lainnya' => 'bg-gray-100 text-gray-700',
            ];
        @endphp

        <!-- Tab Content: Draft -->
        <div id="content-draft" class="tab-content">
            @if($pengajuansDraft->count() > 0)
                <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                    @include('pengajuan-dana.partials.table', ['pengajuans' => $pengajuansDraft])
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-soft p-12 text-center">
                    <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-secondary-900 mb-2">Tidak Ada Draft</h3>
                    <p class="text-secondary-500">Pengajuan draft akan ditampilkan di sini.</p>
                </div>
            @endif
        </div>

        <!-- Tab Content: Menunggu Approval -->
        <div id="content-menunggu-approval" class="tab-content hidden">
            @if($pengajuansMenungguApproval->count() > 0)
                <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                    @include('pengajuan-dana.partials.table', ['pengajuans' => $pengajuansMenungguApproval])
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-soft p-12 text-center">
                    <div class="w-20 h-20 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-secondary-900 mb-2">Tidak Ada Pengajuan Menunggu Approval</h3>
                    <p class="text-secondary-500">Pengajuan yang menunggu approval akan ditampilkan di sini.</p>
                </div>
            @endif
        </div>

        <!-- Tab Content: Menunggu Pencairan -->
        <div id="content-menunggu-pencairan" class="tab-content hidden">
            @if($pengajuansMenungguPencairan->count() > 0)
                <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                    @include('pengajuan-dana.partials.table', ['pengajuans' => $pengajuansMenungguPencairan])
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-soft p-12 text-center">
                    <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-secondary-900 mb-2">Tidak Ada Pengajuan Menunggu Pencairan</h3>
                    <p class="text-secondary-500">Pengajuan yang siap dicairkan akan ditampilkan di sini.</p>
                </div>
            @endif
        </div>

        <!-- Tab Content: Dicairkan -->
        <div id="content-dicairkan" class="tab-content hidden">
            @if($pengajuansDicairkan->count() > 0)
                <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                    @include('pengajuan-dana.partials.table', ['pengajuans' => $pengajuansDicairkan])
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-soft p-12 text-center">
                    <div class="w-20 h-20 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-secondary-900 mb-2">Belum Ada Dana Dicairkan</h3>
                    <p class="text-secondary-500">Pengajuan yang dananya sudah dicairkan akan ditampilkan di sini.</p>
                </div>
            @endif
        </div>

        <!-- Tab Content: Proses LPJ -->
        <div id="content-proses" class="tab-content hidden">
            @if($pengajuansProses->count() > 0)
                <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                    @include('pengajuan-dana.partials.table', ['pengajuans' => $pengajuansProses])
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-soft p-12 text-center">
                    <div class="w-20 h-20 bg-cyan-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-secondary-900 mb-2">Tidak Ada Pengajuan Diproses</h3>
                    <p class="text-secondary-500">Pengajuan yang sedang diproses LPJ-nya akan ditampilkan di sini.</p>
                </div>
            @endif
        </div>

        <!-- Tab Content: Selesai -->
        <div id="content-selesai" class="tab-content hidden">
            @if($pengajuansSelesai->count() > 0)
                <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                    @include('pengajuan-dana.partials.table', ['pengajuans' => $pengajuansSelesai])
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-soft p-12 text-center">
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-secondary-900 mb-2">Belum Ada Pengajuan Selesai</h3>
                    <p class="text-secondary-500">Pengajuan yang selesai akan ditampilkan di sini.</p>
                </div>
            @endif
        </div>

        <!-- Tab Content: Ditolak -->
        <div id="content-ditolak" class="tab-content hidden">
            @if($pengajuansDitolak->count() > 0)
                <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                    @include('pengajuan-dana.partials.table', ['pengajuans' => $pengajuansDitolak])
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-soft p-12 text-center">
                    <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-secondary-900 mb-2">Tidak Ada Pengajuan Ditolak</h3>
                    <p class="text-secondary-500">Pengajuan yang ditolak akan ditampilkan di sini.</p>
                </div>
            @endif
        </div>

        <!-- Tab Content: Dibatalkan -->
        <div id="content-cancelled" class="tab-content hidden">
            @if($pengajuansCancelled->count() > 0)
                <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                    @include('pengajuan-dana.partials.table', ['pengajuans' => $pengajuansCancelled])
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-soft p-12 text-center">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-secondary-900 mb-2">Tidak Ada Pengajuan Dibatalkan</h3>
                    <p class="text-secondary-500">Pengajuan yang dibatalkan akan ditampilkan di sini.</p>
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
                    el.classList.remove('border-slate-500', 'border-amber-500', 'border-blue-500', 'border-purple-500', 'border-cyan-500', 'border-green-500', 'border-red-500', 'border-gray-500', 'text-slate-600', 'text-amber-600', 'text-blue-600', 'text-purple-600', 'text-cyan-600', 'text-green-600', 'text-red-600', 'text-gray-600', 'bg-slate-50', 'bg-amber-50', 'bg-blue-50', 'bg-purple-50', 'bg-cyan-50', 'bg-green-50', 'bg-red-50', 'bg-gray-50');
                    el.classList.add('border-transparent', 'text-secondary-600');
                });

                // Show selected tab content
                document.getElementById('content-' + tabName).classList.remove('hidden');

                // Set active state for selected tab
                var activeTab = document.getElementById('tab-' + tabName);
                activeTab.classList.remove('border-transparent', 'text-secondary-600');

                if (tabName === 'draft') {
                    activeTab.classList.add('border-slate-500', 'text-slate-600', 'bg-slate-50');
                } else if (tabName === 'menunggu-approval') {
                    activeTab.classList.add('border-amber-500', 'text-amber-600', 'bg-amber-50');
                } else if (tabName === 'menunggu-pencairan') {
                    activeTab.classList.add('border-blue-500', 'text-blue-600', 'bg-blue-50');
                } else if (tabName === 'dicairkan') {
                    activeTab.classList.add('border-purple-500', 'text-purple-600', 'bg-purple-50');
                } else if (tabName === 'proses') {
                    activeTab.classList.add('border-cyan-500', 'text-cyan-600', 'bg-cyan-50');
                } else if (tabName === 'selesai') {
                    activeTab.classList.add('border-green-500', 'text-green-600', 'bg-green-50');
                } else if (tabName === 'ditolak') {
                    activeTab.classList.add('border-red-500', 'text-red-600', 'bg-red-50');
                } else if (tabName === 'cancelled') {
                    activeTab.classList.add('border-gray-500', 'text-gray-600', 'bg-gray-50');
                }
            }
        </script>
    </div>
</x-app-layout>
