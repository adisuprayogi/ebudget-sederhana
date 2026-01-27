<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">Pencatatan Penerimaan</h1>
                <p class="text-sm text-gray-500 mt-0.5">{{ $pencatatanPenerimaans->total() ?? 0 }} pencatatan</p>
            </div>
            <a href="{{ route('pencatatan-penerimaan.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Catat Penerimaan
            </a>
        </div>
    </x-slot>

    <!-- Quick Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
        <div class="bg-white rounded-xl border border-blue-100 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
                <div>
                    <p class="text-xl font-semibold text-gray-900">{{ \App\Models\PencatatanPenerimaan::count() }}</p>
                    <p class="text-xs text-gray-500">Total Pencatatan</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-blue-100 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-emerald-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-900">{{ formatRupiah($summary['total_diterima'] ?? 0) }}</p>
                    <p class="text-xs text-gray-500">Total Diterima</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-blue-100 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-amber-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-900">{{ formatRupiah($summary['bulan_ini'] ?? 0) }}</p>
                    <p class="text-xs text-gray-500">Bulan Ini</p>
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
                    <p class="text-xl font-semibold text-gray-900">{{ \App\Models\PencatatanPenerimaan::whereMonth('tanggal_penerimaan', now()->month)->whereYear('tanggal_penerimaan', now()->year)->count() }}</p>
                    <p class="text-xs text-gray-500">Transaksi Bulan Ini</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-xl border border-blue-100 p-3 mb-4">
        <form method="GET" action="{{ route('pencatatan-penerimaan.index') }}" class="flex flex-wrap items-center gap-3">
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
                <select name="sumber_dana_id" class="w-full px-3 py-2 text-sm border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all bg-white">
                    <option value="">Semua Sumber Dana</option>
                    @foreach($filterOptions['sumberDanas'] ?? [] as $sumber)
                        <option value="{{ $sumber->id }}" {{ request('sumber_dana_id') == $sumber->id ? 'selected' : '' }}>
                            {{ $sumber->nama_sumber }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="w-full md:min-w-[140px] md:w-auto">
                <select name="perencanaan_penerimaan_id" class="w-full px-3 py-2 text-sm border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all bg-white">
                    <option value="">Semua Perencanaan</option>
                    @foreach($filterOptions['perencanaanPenerimaans'] ?? [] as $perencanaan)
                        <option value="{{ $perencanaan->id }}" {{ request('perencanaan_penerimaan_id') == $perencanaan->id ? 'selected' : '' }}>
                            {{ Str::limit($perencanaan->uraian, 30) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center gap-2 w-full md:w-auto">
                <button type="submit" class="flex-1 md:flex-none inline-flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filter
                </button>
                @if(request()->hasAny(['periode_anggaran_id', 'sumber_dana_id', 'perencanaan_penerimaan_id', 'search']))
                    <a href="{{ route('pencatatan-penerimaan.index') }}" class="flex-1 md:flex-none inline-flex items-center justify-center px-4 py-2 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm font-medium rounded-lg transition-colors">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Pencatatan List -->
    <div class="space-y-3">
        @forelse($pencatatanPenerimaans ?? [] as $index => $pencatatan)
            @php
                $nomorUrut = ($pencatatanPenerimaans->currentPage() - 1) * $pencatatanPenerimaans->perPage() + $index + 1;
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
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700">
                                <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span class="truncate">{{ \Carbon\Carbon::parse($pencatatan->tanggal_penerimaan)->format('d/m/Y') }}</span>
                            </span>
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-medium bg-emerald-50 text-emerald-700">
                                <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="truncate max-w-[120px] md:max-w-full">{{ $pencatatan->sumberDana->nama_sumber ?? '-' }}</span>
                            </span>
                            @if($pencatatan->perencanaanPenerimaan)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-medium bg-violet-50 text-violet-700">
                                    <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    <span class="truncate max-w-[100px] md:max-w-full">{{ Str::limit($pencatatan->perencanaanPenerimaan->uraian, 20) }}</span>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Title & Amount -->
                <div class="mb-3 pl-12 md:pl-14">
                    <h3 class="font-semibold text-gray-900 text-sm md:text-base line-clamp-2">{{ $pencatatan->uraian }}</h3>
                    <p class="text-lg md:text-xl font-bold text-emerald-600 mt-1">{{ formatRupiah($pencatatan->jumlah_diterima) }}</p>
                </div>

                <!-- Meta Info & Actions -->
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 pl-12 md:pl-14">
                    <!-- Meta Info -->
                    <div class="flex flex-wrap items-center gap-2 text-xs">
                        <div class="flex items-center gap-1.5">
                            <div class="w-5 h-5 bg-blue-50 rounded flex items-center justify-center flex-shrink-0">
                                <svg class="w-2.5 h-2.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <span class="text-gray-600 truncate max-w-[120px]">{{ $pencatatan->periodeAnggaran->nama_periode ?? '-' }}</span>
                        </div>
                        @if($pencatatan->bukti_penerimaan)
                            <div class="flex items-center gap-1.5">
                                <div class="w-5 h-5 bg-amber-50 rounded flex items-center justify-center flex-shrink-0">
                                    <svg class="w-2.5 h-2.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                    </svg>
                                </div>
                                <a href="{{ asset('storage/' . $pencatatan->bukti_penerimaan) }}" target="_blank" class="text-blue-600 hover:underline truncate max-w-[120px]">Bukti Penerimaan</a>
                            </div>
                        @endif
                        <div class="flex items-center gap-1.5">
                            <div class="w-5 h-5 bg-gray-100 rounded flex items-center justify-center flex-shrink-0">
                                <svg class="w-2.5 h-2.5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <span class="text-gray-600 truncate max-w-[100px]">{{ $pencatatan->createdBy->name ?? '-' }}</span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center gap-2 w-full md:w-auto">
                        <a href="{{ route('pencatatan-penerimaan.show', $pencatatan) }}" class="flex-1 md:flex-none inline-flex items-center justify-center gap-1.5 px-4 py-2 text-blue-700 bg-blue-50 hover:bg-blue-100 font-medium rounded-lg transition-colors text-xs">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            Lihat
                        </a>
                        <a href="{{ route('pencatatan-penerimaan.edit', $pencatatan) }}" class="flex-1 md:flex-none inline-flex items-center justify-center gap-1.5 px-4 py-2 text-amber-700 bg-amber-50 hover:bg-amber-100 font-medium rounded-lg transition-colors text-xs">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit
                        </a>
                        <form method="POST" action="{{ route('pencatatan-penerimaan.destroy', $pencatatan) }}" onsubmit="return confirm('Yakin ingin menghapus pencatatan ini?');" class="inline flex-1 md:flex-none">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full md:w-auto inline-flex items-center justify-center gap-1.5 px-4 py-2 text-red-700 bg-red-50 hover:bg-red-100 font-medium rounded-lg transition-colors text-xs">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl border border-blue-100 p-16 text-center">
                <div class="flex flex-col items-center">
                    <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                    </div>
                    <p class="text-gray-700 font-medium">Belum ada pencatatan penerimaan</p>
                    <p class="text-gray-400 text-sm mt-1">Mulai dengan mencatat penerimaan dana pertama Anda</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if(isset($pencatatanPenerimaans) && $pencatatanPenerimaans->hasPages())
        <div class="bg-white rounded-xl border border-blue-100 px-4 py-3 mt-4">
            {{ $pencatatanPenerimaans->appends(request()->query())->links() }}
        </div>
    @endif
</x-app-layout>
