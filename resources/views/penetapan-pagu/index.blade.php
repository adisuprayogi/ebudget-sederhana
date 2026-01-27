<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">Penetapan Pagu</h1>
                <p class="text-sm text-gray-500 mt-0.5">{{ $penetapanPagus->total() ?? 0 }} penetapan pagu</p>
            </div>
            @if(auth()->user()->hasAnyRole(['direktur_keuangan', 'direktur_utama']))
                <a href="{{ route('penetapan-pagu.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Pagu
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-900">{{ formatRupiah($summary['total_pagu'] ?? 0) }}</p>
                    <p class="text-xs text-gray-500">Total Pagu</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-blue-100 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-amber-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-900">{{ formatRupiah($summary['total_terpakai'] ?? 0) }}</p>
                    <p class="text-xs text-gray-500">Total Terpakai</p>
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
                    <p class="text-sm font-semibold text-gray-900">{{ formatRupiah($summary['sisa_pagu'] ?? 0) }}</p>
                    <p class="text-xs text-gray-500">Sisa Pagu</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-blue-100 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-violet-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div>
                    <p class="text-xl font-semibold text-gray-900">{{ \App\Models\PenetapanPagu::count() }}</p>
                    <p class="text-xs text-gray-500">Total Penetapan</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-xl border border-blue-100 p-3 mb-4">
        <form method="GET" action="{{ route('penetapan-pagu.index') }}" class="flex flex-wrap items-center gap-3">
            <div class="w-full md:min-w-[140px] md:w-auto">
                <select name="periode_anggaran_id" class="w-full px-3 py-2 text-sm border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all bg-white">
                    <option value="">Semua Periode</option>
                    @foreach($filterOptions['periodeAnggarans'] ?? [] as $periode)
                        <option value="{{ $periode->id }}" {{ ($filters['periode_anggaran_id'] ?? null) == $periode->id ? 'selected' : '' }}>
                            {{ $periode->nama_periode }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="w-full md:min-w-[140px] md:w-auto">
                <select name="divisi_id" class="w-full px-3 py-2 text-sm border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all bg-white">
                    <option value="">Semua Divisi</option>
                    @foreach($filterOptions['divisis'] ?? [] as $divisi)
                        <option value="{{ $divisi->id }}" {{ request('divisi_id') == $divisi->id ? 'selected' : '' }}>
                            {{ $divisi->nama_divisi }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="w-full md:min-w-[200px] md:flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama divisi..." class="w-full px-3 py-2 text-sm border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
            </div>
            <div class="flex items-center gap-2 w-full md:w-auto">
                <button type="submit" class="flex-1 md:flex-none inline-flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filter
                </button>
                @if(request()->hasAny(['divisi_id', 'periode_anggaran_id', 'search']))
                    <a href="{{ route('penetapan-pagu.index') }}" class="flex-1 md:flex-none inline-flex items-center justify-center px-4 py-2 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm font-medium rounded-lg transition-colors">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Penetapan Pagu Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        @forelse($penetapanPagus ?? [] as $pagu)
            @php
                $usagePercent = $pagu->usage_percentage;
                $usageColor = $usagePercent >= 90 ? 'red' : ($usagePercent >= 70 ? 'amber' : 'emerald');
            @endphp

            <div class="bg-white rounded-xl border border-blue-100 p-3 md:p-4 hover:border-blue-200 transition-colors">
                <!-- Header -->
                <div class="flex items-start gap-3 mb-3 md:mb-4">
                    <div class="w-9 h-9 md:w-10 md:h-10 bg-blue-100 rounded-lg flex items-center justify-center text-blue-700 font-bold text-xs md:text-sm flex-shrink-0">
                        {{ substr($pagu->divisi->nama_divisi ?? 'D', 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-2">
                            <div class="min-w-0 flex-1">
                                <h3 class="font-semibold text-gray-900 text-sm md:text-base truncate">{{ $pagu->divisi->nama_divisi ?? '-' }}</h3>
                                <p class="text-xs text-gray-400 truncate">{{ $pagu->divisi->kode_divisi ?? '' }} • {{ $pagu->periodeAnggaran->tahun_anggaran ?? '-' }}</p>
                            </div>
                            @if($pagu->status === 'approved')
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700 flex-shrink-0">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    <span class="hidden sm:inline">Disetujui</span>
                                    <span class="sm:hidden">✓</span>
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-700 flex-shrink-0">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                    <span class="hidden sm:inline">Pending</span>
                                    <span class="sm:hidden">⏳</span>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Numbers -->
                <div class="grid grid-cols-3 gap-2 md:gap-3 mb-3 md:mb-4">
                    <div class="text-center p-2 md:p-2 bg-blue-50 rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">Pagu</p>
                        <p class="text-xs md:text-sm font-bold text-gray-900 truncate" title="{{ formatRupiah($pagu->jumlah_pagu) }}">{{ formatRupiah($pagu->jumlah_pagu) }}</p>
                    </div>
                    <div class="text-center p-2 md:p-2 bg-amber-50 rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">Terpakai</p>
                        <p class="text-xs md:text-sm font-bold text-amber-600 truncate" title="{{ formatRupiah($pagu->used_amount) }}">{{ formatRupiah($pagu->used_amount) }}</p>
                    </div>
                    <div class="text-center p-2 md:p-2 bg-emerald-50 rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">Sisa</p>
                        <p class="text-xs md:text-sm font-bold text-emerald-600 truncate" title="{{ formatRupiah($pagu->remaining_amount) }}">{{ formatRupiah($pagu->remaining_amount) }}</p>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div class="mb-3 md:mb-4">
                    <div class="flex items-center justify-between text-xs mb-2">
                        <span class="text-gray-600">Penggunaan</span>
                        <span class="font-bold text-{{ $usageColor }}-600">{{ number_format($usagePercent, 1) }}%</span>
                    </div>
                    <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-{{ $usageColor }}-500 rounded-full transition-all" style="width: {{ min($usagePercent, 100) }}%"></div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pt-3 border-t border-blue-50">
                    <div class="text-xs text-gray-400">
                        @if($pagu->createdBy)
                            <span class="truncate block">Oleh <span class="font-medium text-gray-600">{{ $pagu->createdBy->name }}</span></span>
                        @endif
                        @if($pagu->catatan)
                            <span class="italic truncate block mt-1" title="{{ $pagu->catatan }}">"{{ \Illuminate\Support\Str::limit($pagu->catatan, 40) }}"</span>
                        @endif
                    </div>
                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        <a href="{{ route('penetapan-pagu.show', $pagu) }}" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-1.5 px-3 py-2 text-blue-700 bg-blue-50 hover:bg-blue-100 font-medium rounded-lg transition-colors text-xs">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            Lihat
                        </a>
                        @if(auth()->user()->hasAnyRole(['direktur_keuangan', 'direktur_utama']))
                            <a href="{{ route('penetapan-pagu.edit', $pagu) }}" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-1.5 px-3 py-2 text-amber-700 bg-amber-50 hover:bg-amber-100 font-medium rounded-lg transition-colors text-xs">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl border border-blue-100 p-16 text-center col-span-full">
                <div class="flex flex-col items-center">
                    <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <p class="text-gray-700 font-medium">Belum ada penetapan pagu</p>
                    <p class="text-gray-400 text-sm mt-1">Mulai dengan menetapkan pagu anggaran untuk setiap divisi</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if(isset($penetapanPagus) && $penetapanPagus->hasPages())
        <div class="bg-white rounded-xl border border-blue-100 px-4 py-3 mt-4">
            {{ $penetapanPagus->appends(request()->query())->links() }}
        </div>
    @endif
</x-app-layout>
