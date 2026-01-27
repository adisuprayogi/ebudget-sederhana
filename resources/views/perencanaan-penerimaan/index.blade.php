<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">Perencanaan Penerimaan</h1>
                <p class="text-sm text-gray-500 mt-0.5">{{ $perencanaanPenerimaans->total() ?? 0 }} perencanaan</p>
            </div>
            <a href="{{ route('perencanaan-penerimaan.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Buat Perencanaan
            </a>
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
                    <p class="text-sm font-semibold text-gray-900">{{ formatRupiah($summary['total_estimasi'] ?? 0) }}</p>
                    <p class="text-xs text-gray-500">Total Estimasi</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-blue-100 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-emerald-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-900">{{ formatRupiah($summary['total_realisasi'] ?? 0) }}</p>
                    <p class="text-xs text-gray-500">Total Realisasi</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-blue-100 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-amber-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-900">{{ formatRupiah($summary['sisa'] ?? 0) }}</p>
                    <p class="text-xs text-gray-500">Sisa Estimasi</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-blue-100 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-violet-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xl font-semibold text-gray-900">{{ \App\Models\PerencanaanPenerimaan::count() }}</p>
                    <p class="text-xs text-gray-500">Total Perencanaan</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-xl border border-blue-100 p-3 mb-4">
        <form method="GET" action="{{ route('perencanaan-penerimaan.index') }}" class="flex flex-wrap items-center gap-3">
            <div class="w-full md:min-w-[160px] md:w-auto">
                <select name="periode_anggaran_id" class="w-full px-3 py-2 text-sm border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all bg-white">
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
            <div class="w-full md:min-w-[140px] md:w-auto">
                <select name="sumber_dana_id" class="w-full px-3 py-2 text-sm border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all bg-white">
                    <option value="">Semua Sumber</option>
                    @foreach($filterOptions['sumberDanas'] ?? [] as $sumber)
                        <option value="{{ $sumber->id }}" {{ request('sumber_dana_id') == $sumber->id ? 'selected' : '' }}>
                            {{ $sumber->nama_sumber }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="w-full md:min-w-[200px] md:flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari uraian atau kode rekening..." class="w-full px-3 py-2 text-sm border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
            </div>
            <div class="flex items-center gap-2 w-full md:w-auto">
                <button type="submit" class="flex-1 md:flex-none inline-flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filter
                </button>
                @if(request()->hasAny(['divisi_id', 'sumber_dana_id', 'search']))
                    <a href="{{ route('perencanaan-penerimaan.index') }}" class="flex-1 md:flex-none inline-flex items-center justify-center px-4 py-2 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm font-medium rounded-lg transition-colors">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Perencanaan List -->
    <div class="space-y-3">
        @forelse($perencanaanPenerimaans ?? [] as $index => $perencanaan)
            @php
                $nomorUrut = ($perencanaanPenerimaans->currentPage() - 1) * $perencanaanPenerimaans->perPage() + $index + 1;
                $progressColor = $perencanaan->persentase_realisasi >= 90 ? 'red' : ($perencanaan->persentase_realisasi >= 70 ? 'amber' : 'emerald');
            @endphp
            <div class="bg-white rounded-xl border border-blue-100 p-3 md:p-4 hover:border-blue-200 transition-colors">
                <!-- Header: Number + Badges -->
                <div class="flex items-start gap-3 mb-3">
                    <!-- Number -->
                    <div class="flex-shrink-0">
                        <div class="w-9 h-9 md:w-10 md:h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <span class="text-blue-700 font-bold text-xs md:text-sm">{{ $nomorUrut }}</span>
                        </div>
                    </div>

                    <!-- Badges -->
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-1.5 md:gap-2">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-mono font-semibold bg-blue-100 text-blue-700 max-w-full truncate">
                                {{ $perencanaan->kode_rekening ?? '-' }}
                            </span>
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700">
                                <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span class="truncate">{{ $perencanaan->periodeAnggaran->tahun_anggaran ?? '-' }}</span>
                            </span>
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-medium bg-emerald-50 text-emerald-700">
                                <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="truncate max-w-[120px] md:max-w-full">{{ $perencanaan->sumberDana->nama_sumber ?? '-' }}</span>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Title -->
                <div class="mb-3 pl-12 md:pl-14">
                    <h3 class="font-semibold text-gray-900 text-sm md:text-base line-clamp-2">{{ $perencanaan->uraian }}</h3>
                </div>

                <!-- Amounts Row -->
                <div class="flex items-center gap-3 md:gap-4 mb-3 pl-12 md:pl-14">
                    <div class="flex items-center gap-1.5 min-w-0 flex-1">
                        <svg class="w-4 h-4 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs text-gray-500">Estimasi</p>
                            <p class="text-base md:text-lg font-bold text-blue-600 truncate">{{ formatRupiah($perencanaan->jumlah_estimasi) }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-1.5 min-w-0 flex-1">
                        <svg class="w-4 h-4 text-amber-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs text-gray-500">Realisasi</p>
                            <p class="text-base md:text-lg font-bold text-amber-600 truncate">{{ formatRupiah($perencanaan->realisasi) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div class="mb-3 pl-12 md:pl-14">
                    <div class="p-2.5 rounded-lg border bg-{{ $progressColor }}-50 border-{{ $progressColor }}-200">
                        <div class="flex items-center justify-between mb-1.5">
                            <p class="text-xs font-semibold text-{{ $progressColor }}-700">Realisasi</p>
                            <p class="text-sm font-bold text-{{ $progressColor }}-700">{{ number_format($perencanaan->persentase_realisasi, 1) }}%</p>
                        </div>
                        <div class="w-full bg-white rounded-full h-2 overflow-hidden">
                            <div class="h-2 rounded-full bg-{{ $progressColor }}-500 transition-all" style="width: {{ min($perencanaan->persentase_realisasi, 100) }}%"></div>
                        </div>
                    </div>
                </div>

                <!-- Meta Info & Actions -->
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 pl-12 md:pl-14">
                    <!-- Meta Info -->
                    <div class="flex flex-wrap items-center gap-2 text-xs">
                        <div class="flex items-center gap-1.5">
                            <div class="w-5 h-5 bg-indigo-50 rounded flex items-center justify-center flex-shrink-0">
                                <svg class="w-2.5 h-2.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <span class="text-gray-600 truncate max-w-[150px]">{{ $perencanaan->divisi->nama_divisi ?? '-' }}</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <div class="w-5 h-5 bg-gray-100 rounded flex items-center justify-center flex-shrink-0">
                                <svg class="w-2.5 h-2.5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <span class="text-gray-600 truncate max-w-[120px]">{{ $perencanaan->periodeAnggaran->nama_periode ?? '-' }}</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <div class="w-5 h-5 bg-gray-100 rounded flex items-center justify-center flex-shrink-0">
                                <svg class="w-2.5 h-2.5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <span class="text-gray-600">{{ \Carbon\Carbon::parse($perencanaan->created_at)->format('d/m/Y') }}</span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center gap-2 w-full md:w-auto">
                        <a href="{{ route('perencanaan-penerimaan.show', $perencanaan) }}" class="flex-1 md:flex-none inline-flex items-center justify-center gap-1.5 px-4 py-2 text-blue-700 bg-blue-50 hover:bg-blue-100 font-medium rounded-lg transition-colors text-xs">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            Lihat
                        </a>
                        <a href="{{ route('perencanaan-penerimaan.edit', $perencanaan) }}" class="flex-1 md:flex-none inline-flex items-center justify-center gap-1.5 px-4 py-2 text-amber-700 bg-amber-50 hover:bg-amber-100 font-medium rounded-lg transition-colors text-xs">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl border border-blue-100 p-16 text-center">
                <div class="flex flex-col items-center">
                    <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <p class="text-gray-700 font-medium">Belum ada perencanaan penerimaan</p>
                    <p class="text-gray-400 text-sm mt-1">Mulai dengan membuat perencanaan penerimaan pertama Anda</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if(isset($perencanaanPenerimaans) && $perencanaanPenerimaans->hasPages())
        <div class="bg-white rounded-xl border border-blue-100 px-4 py-3 mt-4">
            {{ $perencanaanPenerimaans->appends(request()->query())->links() }}
        </div>
    @endif
</x-app-layout>
