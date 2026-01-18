<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-violet-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-violet-500/30">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Riwayat Approval</h1>
                    <p class="text-gray-500 text-sm mt-0.5">Daftar persetujuan yang sudah diproses</p>
                </div>
            </div>
            <a href="{{ route('approvals.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 font-medium rounded-xl shadow-sm transition-all duration-200 hover:shadow-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Filters -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-violet-50 to-purple-50 px-6 py-4 border-b border-violet-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-sm">
                        <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 3.293A1 1 0 013 2.586V4z" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-violet-900">Filter Riwayat</p>
                        <p class="text-sm text-violet-700 mt-0.5">Cari riwayat approval berdasarkan kriteria</p>
                    </div>
                </div>
            </div>
            <form method="GET" action="{{ route('approvals.history') }}" class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Status Filter -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Status</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <select name="status" class="w-full pl-10 pr-10 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 transition-all appearance-none bg-white">
                                <option value="">Semua Status</option>
                                <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                                <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Level Filter -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Level</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                            </div>
                            <select name="level" class="w-full pl-10 pr-10 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 transition-all appearance-none bg-white">
                                <option value="">Semua Level</option>
                                @foreach($filterOptions['levels'] ?? [] as $level)
                                    <option value="{{ $level }}" {{ request('level') == $level ? 'selected' : '' }}>Level {{ $level }}</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Tanggal Mulai -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Tanggal Mulai</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}" class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 transition-all">
                        </div>
                    </div>

                    <!-- Tanggal Selesai -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Tanggal Selesai</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <input type="date" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}" class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 transition-all">
                        </div>
                    </div>
                </div>

                <div class="mt-4 flex items-center justify-end gap-3">
                    <a href="{{ route('approvals.history') }}" class="inline-flex items-center gap-2 px-5 py-2.5 text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 font-medium rounded-xl shadow-sm transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Reset
                    </a>
                    <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-violet-600 to-purple-600 hover:from-violet-700 hover:to-purple-700 text-white font-medium rounded-xl shadow-lg shadow-violet-500/30 transition-all duration-200 hover:scale-[1.02]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 3.293A1 1 0 013 2.586V4z" />
                        </svg>
                        Terapkan Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Approval History List -->
        <div class="space-y-4">
            @forelse($approvals ?? [] as $index => $approval)
                @php
                    $nomorUrut = ($approvals->currentPage() - 1) * $approvals->perPage() + $index + 1;
                    $jenisLabels = [
                        'kegiatan' => 'Kegiatan',
                        'pengadaan' => 'Pengadaan',
                        'pembayaran' => 'Pembayaran',
                        'honorarium' => 'Honorarium',
                        'sewa' => 'Sewa',
                        'konsumsi' => 'Konsumsi',
                        'konsumi' => 'Konsumsi',
                        'reimbursement' => 'Reimbursement',
                        'lainnya' => 'Lainnya',
                    ];
                    $jenisGradients = [
                        'kegiatan' => 'from-blue-500 to-blue-600 shadow-blue-500/30',
                        'pengadaan' => 'from-emerald-500 to-green-600 shadow-emerald-500/30',
                        'pembayaran' => 'from-amber-500 to-yellow-500 shadow-amber-500/30',
                        'honorarium' => 'from-purple-500 to-violet-600 shadow-purple-500/30',
                        'sewa' => 'from-orange-500 to-orange-600 shadow-orange-500/30',
                        'konsumsi' => 'from-pink-500 to-rose-600 shadow-pink-500/30',
                        'konsumi' => 'from-pink-500 to-rose-600 shadow-pink-500/30',
                        'reimbursement' => 'from-teal-500 to-cyan-600 shadow-teal-500/30',
                        'lainnya' => 'from-gray-400 to-gray-500 shadow-gray-400/30',
                    ];
                    $jenis = $approval->pengajuanDana->jenis_pengajuan;
                    $label = $jenisLabels[$jenis] ?? ucfirst($jenis);
                    $gradient = $jenisGradients[$jenis] ?? 'from-gray-400 to-gray-500 shadow-gray-400/30';
                @endphp
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md hover:border-violet-200 transition-all duration-200">
                    <div class="p-6">
                        <div class="flex gap-3">
                            <!-- Number -->
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-gradient-to-br from-violet-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-violet-500/30">
                                    <span class="text-white font-bold text-xs">{{ $nomorUrut }}</span>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="flex-1">
                                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                                    <!-- Left Section -->
                                    <div class="flex-1 space-y-4">
                                        <!-- Header -->
                                        <div class="flex flex-wrap items-center gap-3">
                                            <span class="inline-flex items-center px-4 py-2 rounded-xl bg-gradient-to-r from-violet-100 to-purple-100 font-mono text-sm font-bold text-violet-700">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                {{ $approval->pengajuanDana->nomor_pengajuan }}
                                            </span>
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-gradient-to-r {{ $gradient }} text-white shadow-md">
                                                {{ $label }}
                                            </span>
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-gradient-to-r from-blue-500 to-indigo-500 text-white shadow-md shadow-blue-500/20">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                                </svg>
                                                Level {{ $approval->level }}
                                            </span>
                                        </div>

                                        <!-- Title & Amount -->
                                        <div>
                                            <h3 class="text-lg font-bold text-gray-900">{{ $approval->pengajuanDana->judul_pengajuan }}</h3>
                                            <p class="text-xl font-bold text-violet-600 mt-1">{{ formatRupiah($approval->pengajuanDana->total_pengajuan) }}</p>
                                        </div>

                                        <!-- Meta Info -->
                                        <div class="flex flex-wrap items-center gap-6 text-sm">
                                            <div class="flex items-center gap-2">
                                                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center shadow-sm">
                                                    <span class="text-white font-bold text-xs">{{ strtoupper(substr($approval->pengajuanDana->createdBy->name ?? '-', 0, 1)) }}</span>
                                                </div>
                                                <div>
                                                    <p class="text-xs text-gray-500">Pengaju</p>
                                                    <p class="font-medium text-gray-900">{{ $approval->pengajuanDana->createdBy->name ?? '-' }}</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center shadow-sm">
                                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="text-xs text-gray-500">Divisi</p>
                                                    <p class="font-medium text-gray-900">{{ $approval->pengajuanDana->divisi->nama_divisi ?? '-' }}</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <div class="w-8 h-8 bg-gradient-to-br from-gray-400 to-gray-500 rounded-lg flex items-center justify-center shadow-sm">
                                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="text-xs text-gray-500">Tanggal Proses</p>
                                                    @if($approval->approved_at)
                                                        <p class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($approval->approved_at)->format('d/m/Y H:i') }}</p>
                                                    @else
                                                        <p class="font-medium text-gray-400">-</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Right Section -->
                                    <div class="lg:w-auto flex-shrink-0 space-y-3">
                                        <!-- Status Card -->
                                        <div class="w-56">
                                            <div class="p-2.5 rounded-xl border @if($approval->status === 'disetujui') bg-gradient-to-br from-emerald-50 to-green-50 border-emerald-200 @elseif($approval->status === 'ditolak') bg-gradient-to-br from-red-50 to-rose-50 border-red-200 @endif">
                                                <div class="flex items-center gap-2">
                                                    @if($approval->status === 'disetujui')
                                                        <div class="w-7 h-7 bg-gradient-to-br from-emerald-500 to-green-500 rounded-lg flex items-center justify-center flex-shrink-0 shadow-lg shadow-emerald-500/30">
                                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                            </svg>
                                                        </div>
                                                        <p class="text-xs font-bold text-emerald-700">Disetujui</p>
                                                    @elseif($approval->status === 'ditolak')
                                                        <div class="w-7 h-7 bg-gradient-to-br from-red-500 to-rose-500 rounded-lg flex items-center justify-center flex-shrink-0 shadow-lg shadow-red-500/30">
                                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                            </svg>
                                                        </div>
                                                        <p class="text-xs font-bold text-red-700">Ditolak</p>
                                                    @endif
                                                    @if($approval->notes)
                                                        <div class="flex items-center gap-1 text-gray-400">|</div>
                                                        <div class="flex items-center gap-1">
                                                            <svg class="w-3 h-3 flex-shrink-0 @if($approval->status === 'disetujui') text-emerald-500 @elseif($approval->status === 'ditolak') text-red-500 @endif" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                            <p class="text-xs text-gray-600 truncate">{{ $approval->notes }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Lampiran Card -->
                                        @if($approval->pengajuanDana->attachments && $approval->pengajuanDana->attachments->count() > 0)
                                            <div class="w-56">
                                                <div class="p-4 rounded-xl border border-blue-200 bg-gradient-to-br from-blue-50 to-indigo-50">
                                                    <div class="flex items-center gap-2 mb-3">
                                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                                        </svg>
                                                        <p class="text-xs font-bold text-blue-700">Lampiran</p>
                                                    </div>
                                                    <div class="space-y-2">
                                                        @foreach($approval->pengajuanDana->attachments as $attachment)
                                                            <a href="{{ asset('storage/' . $attachment->file_path) }}" target="_blank" class="flex items-center gap-2 p-2 rounded-lg bg-white hover:bg-blue-50 transition-colors">
                                                                <svg class="w-4 h-4 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                                </svg>
                                                                <p class="text-xs text-gray-700 truncate flex-1">{{ $attachment->file_name }}</p>
                                                                <svg class="w-3 h-3 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                                </svg>
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-16">
                    <div class="flex flex-col items-center">
                        <div class="w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mb-6">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <p class="text-gray-500 font-semibold text-xl">Belum ada riwayat approval</p>
                        <p class="text-gray-400 text-sm mt-2">Pengajuan yang sudah diproses akan muncul di sini</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if(isset($approvals) && $approvals->hasPages())
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-500">
                        Menampilkan <span class="font-semibold text-gray-700">{{ $approvals->firstItem() ?? 0 }}</span> sampai <span class="font-semibold text-gray-700">{{ $approvals->lastItem() ?? 0 }}</span> dari <span class="font-semibold text-gray-700">{{ $approvals->total() }}</span> data
                    </div>
                    {{ $approvals->appends(request()->query())->links('pagination::tailwind', ['theme' => 'purple']) }}
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
