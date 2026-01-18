<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/30">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Perencanaan Penerimaan</h1>
                    <p class="text-gray-500 text-sm mt-0.5">Kelola perencanaan penerimaan dana untuk periode anggaran</p>
                </div>
            </div>
            <a href="{{ route('perencanaan-penerimaan.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-medium rounded-xl shadow-lg shadow-blue-500/30 transition-all duration-200 hover:scale-[1.02]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Buat Perencanaan Baru
            </a>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-xl shadow-blue-500/30 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full translate-y-1/2 -translate-x-1/2"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm font-medium">Total Estimasi</p>
                            <p class="text-3xl font-bold mt-2">{{ formatRupiah($summary['total_estimasi'] ?? 0) }}</p>
                        </div>
                        <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl p-6 text-white shadow-xl shadow-amber-500/30 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full translate-y-1/2 -translate-x-1/2"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-amber-100 text-sm font-medium">Total Realisasi</p>
                            <p class="text-3xl font-bold mt-2">{{ formatRupiah($summary['total_realisasi'] ?? 0) }}</p>
                        </div>
                        <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-emerald-500 to-green-600 rounded-2xl p-6 text-white shadow-xl shadow-emerald-500/30 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full translate-y-1/2 -translate-x-1/2"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-emerald-100 text-sm font-medium">Sisa Estimasi</p>
                            <p class="text-3xl font-bold mt-2">{{ formatRupiah($summary['sisa'] ?? 0) }}</p>
                        </div>
                        <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-blue-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-sm flex-shrink-0">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 3.293A1 1 0 013 2.586V4z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-blue-900">Filter Data</h3>
                </div>
            </div>
            <form method="GET" action="{{ route('perencanaan-penerimaan.index') }}" class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Periode Anggaran</label>
                        <select name="periode_anggaran_id" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            @foreach($filterOptions['periodeAnggarans'] ?? [] as $periode)
                                @php
                                    $isSelected = isset($filters['periode_anggaran_id']) && $filters['periode_anggaran_id'] == $periode->id;
                                @endphp
                                <option value="{{ $periode->id }}" {{ $isSelected ? 'selected' : '' }}>
                                    {{ $periode->nama_periode }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Sumber Dana</label>
                        <select name="sumber_dana_id" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            <option value="">Semua Sumber</option>
                            @foreach($filterOptions['sumberDanas'] ?? [] as $sumber)
                                <option value="{{ $sumber->id }}" {{ request('sumber_dana_id') == $sumber->id ? 'selected' : '' }}>
                                    {{ $sumber->nama_sumber }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Cari</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Uraian atau kode rekening..." class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                        </div>
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit" class="flex-1 inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-medium rounded-xl shadow-lg shadow-blue-500/30 transition-all duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 3.293A1 1 0 013 2.586V4z" />
                            </svg>
                            Filter
                        </button>
                        @if(request()->hasAny(['divisi_id', 'sumber_dana_id', 'search']))
                            <a href="{{ route('perencanaan-penerimaan.index') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 font-medium rounded-xl shadow-sm transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Reset
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <!-- Perencanaan List -->
        <div class="space-y-4">
            @forelse($perencanaanPenerimaans ?? [] as $index => $perencanaan)
                @php
                    $nomorUrut = ($perencanaanPenerimaans->currentPage() - 1) * $perencanaanPenerimaans->perPage() + $index + 1;
                @endphp
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md hover:border-blue-200 transition-all duration-200">
                    <div class="p-6">
                        <div class="flex gap-3">
                            <!-- Number -->
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/30">
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
                                            <span class="inline-flex items-center px-4 py-2 rounded-xl bg-gradient-to-r from-blue-100 to-indigo-100 font-mono text-sm font-bold text-blue-700">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                                </svg>
                                                {{ $perencanaan->kode_rekening ?? '-' }}
                                            </span>
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-gradient-to-r from-blue-500 to-indigo-500 text-white shadow-md shadow-blue-500/20">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                {{ $perencanaan->periodeAnggaran->tahun_anggaran ?? '-' }}
                                            </span>
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-gradient-to-r from-emerald-500 to-green-500 text-white shadow-md shadow-emerald-500/20">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                {{ $perencanaan->sumberDana->nama_sumber ?? '-' }}
                                            </span>
                                        </div>

                                        <!-- Title & Amount -->
                                        <div>
                                            <h3 class="text-lg font-bold text-gray-900">{{ $perencanaan->uraian }}</h3>
                                            <div class="flex flex-wrap items-center gap-4 mt-1">
                                                <p class="text-xl font-bold text-blue-600">{{ formatRupiah($perencanaan->jumlah_estimasi) }}</p>
                                                <div class="flex items-center gap-1 text-gray-400">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                                    </svg>
                                                    <span class="text-sm font-medium text-amber-600">{{ formatRupiah($perencanaan->realisasi) }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Meta Info -->
                                        <div class="flex flex-wrap items-center gap-6 text-sm">
                                            <div class="flex items-center gap-2">
                                                <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center shadow-sm">
                                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="text-xs text-gray-500">Divisi</p>
                                                    <p class="font-medium text-gray-900">{{ $perencanaan->divisi->nama_divisi ?? '-' }}</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center shadow-sm">
                                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="text-xs text-gray-500">Periode</p>
                                                    <p class="font-medium text-gray-900">{{ $perencanaan->periodeAnggaran->nama_periode ?? '-' }}</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <div class="w-8 h-8 bg-gradient-to-br from-gray-400 to-gray-500 rounded-lg flex items-center justify-center shadow-sm">
                                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="text-xs text-gray-500">Dibuat</p>
                                                    <p class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($perencanaan->created_at)->format('d/m/Y') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Right Section -->
                                    <div class="lg:w-auto flex-shrink-0 space-y-3">
                                        <!-- Progress Card -->
                                        <div class="w-56">
                                            <div class="p-3 rounded-xl border @if($perencanaan->persentase_realisasi >= 90) bg-gradient-to-br from-red-50 to-rose-50 border-red-200 @elseif($perencanaan->persentase_realisasi >= 70) bg-gradient-to-br from-amber-50 to-orange-50 border-amber-200 @else bg-gradient-to-br from-emerald-50 to-green-50 border-emerald-200 @endif">
                                                <div class="flex items-center justify-between mb-2">
                                                    <p class="text-xs font-semibold @if($perencanaan->persentase_realisasi >= 90) text-red-700 @elseif($perencanaan->persentase_realisasi >= 70) text-amber-700 @else text-emerald-700 @endif">Realisasi</p>
                                                    <p class="text-lg font-bold @if($perencanaan->persentase_realisasi >= 90) text-red-700 @elseif($perencanaan->persentase_realisasi >= 70) text-amber-700 @else text-emerald-700 @endif">{{ number_format($perencanaan->persentase_realisasi, 1) }}%</p>
                                                </div>
                                                <div class="w-full bg-white rounded-full h-2 overflow-hidden">
                                                    <div class="h-2 rounded-full transition-all duration-500 @if($perencanaan->persentase_realisasi >= 90) bg-gradient-to-r from-red-500 to-rose-500 @elseif($perencanaan->persentase_realisasi >= 70) bg-gradient-to-r from-amber-500 to-orange-500 @else bg-gradient-to-r from-emerald-500 to-green-500 @endif" style="width: {{ min($perencanaan->persentase_realisasi, 100) }}%"></div>
                                                </div>
                                                <div class="mt-2 flex items-center gap-2">
                                                    @if($perencanaan->persentase_realisasi >= 90)
                                                        <div class="w-5 h-5 bg-gradient-to-br from-red-500 to-rose-500 rounded flex items-center justify-center flex-shrink-0">
                                                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                            </svg>
                                                        </div>
                                                        <p class="text-xs font-medium text-red-600">Hampir mencapai target</p>
                                                    @elseif($perencanaan->persentase_realisasi >= 70)
                                                        <div class="w-5 h-5 bg-gradient-to-br from-amber-500 to-orange-500 rounded flex items-center justify-center flex-shrink-0">
                                                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                        </div>
                                                        <p class="text-xs font-medium text-amber-600">Progress baik</p>
                                                    @else
                                                        <div class="w-5 h-5 bg-gradient-to-br from-emerald-500 to-green-500 rounded flex items-center justify-center flex-shrink-0">
                                                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                                            </svg>
                                                        </div>
                                                        <p class="text-xs font-medium text-emerald-600">Dalam progress</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Action Buttons -->
                                        <div class="w-56">
                                            <div class="p-3 rounded-xl border border-gray-200 bg-gradient-to-br from-gray-50 to-white">
                                                <div class="flex items-center gap-2">
                                                    <a href="{{ route('perencanaan-penerimaan.show', $perencanaan) }}" class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 text-blue-700 bg-blue-50 hover:bg-blue-100 font-medium rounded-lg transition-all text-sm">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                        Lihat
                                                    </a>
                                                    <a href="{{ route('perencanaan-penerimaan.edit', $perencanaan) }}" class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 text-amber-700 bg-amber-50 hover:bg-amber-100 font-medium rounded-lg transition-all text-sm">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                        Edit
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        @empty
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="flex flex-col items-center justify-center py-20 px-6">
                    <div class="w-24 h-24 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-3xl flex items-center justify-center mb-6">
                        <svg class="w-12 h-12 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Belum ada perencanaan penerimaan</h3>
                    <p class="text-gray-500 mb-8 text-center">Mulai dengan membuat perencanaan penerimaan pertama Anda</p>
                    <a href="{{ route('perencanaan-penerimaan.create') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-medium rounded-xl shadow-lg shadow-blue-500/30 transition-all duration-200 hover:scale-[1.02]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Buat Perencanaan Baru
                    </a>
                </div>
            </div>
        @endforelse

        <!-- Pagination -->
        @if(isset($perencanaanPenerimaans) && $perencanaanPenerimaans->hasPages())
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-500">
                        Menampilkan <span class="font-semibold text-gray-700">{{ $perencanaanPenerimaans->firstItem() ?? 0 }}</span> sampai <span class="font-semibold text-gray-700">{{ $perencanaanPenerimaans->lastItem() ?? 0 }}</span> dari <span class="font-semibold text-gray-700">{{ $perencanaanPenerimaans->total() }}</span> data
                    </div>
                    {{ $perencanaanPenerimaans->appends(request()->query())->links('pagination::tailwind', ['theme' => 'blue']) }}
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
