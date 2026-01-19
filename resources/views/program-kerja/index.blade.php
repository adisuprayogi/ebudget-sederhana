<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">Program Kerja</h1>
                <p class="text-sm text-gray-500 mt-0.5">Kelola program kerja per divisi untuk periode anggaran aktif</p>
            </div>
        </div>
    </x-slot>

    @if(!$activePeriode)
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-8 text-center">
            <svg class="w-12 h-12 text-amber-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <h3 class="text-base font-semibold text-amber-900 mb-1">Tidak Ada Periode Anggaran Aktif</h3>
            <p class="text-sm text-amber-700">Silakan atur periode anggaran aktif terlebih dahulu sebelum mengelola program kerja.</p>
        </div>
    @else
        <!-- Quick Stats -->
        <div class="grid grid-cols-3 gap-3 mb-4">
            <div class="bg-white rounded-xl border border-blue-100 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xl font-semibold text-gray-900">{{ $penetapanPagus->count() }}</p>
                        <p class="text-xs text-gray-500">Total Divisi</p>
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
                        <p class="text-sm font-semibold text-gray-900">{{ formatRupiah($penetapanPagus->sum('jumlah_pagu')) }}</p>
                        <p class="text-xs text-gray-500">Total Pagu</p>
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
                        <p class="text-sm font-semibold text-gray-900 truncate">{{ $activePeriode->nama_periode }}</p>
                        <p class="text-xs text-gray-500">{{ $activePeriode->tanggal_mulai_perencanaan_anggaran->format('M Y') }} - {{ $activePeriode->tanggal_selesai_perencanaan_anggaran->format('M Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Periode Anggaran Info -->
        <div class="bg-gradient-to-r from-blue-600 to-cyan-500 rounded-xl p-4 mb-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-blue-100 mb-0.5">Periode Anggaran Aktif</p>
                    <p class="text-lg font-bold text-white">{{ $activePeriode->nama_periode }}</p>
                    <p class="text-xs text-blue-100 mt-0.5">
                        {{ $activePeriode->tanggal_mulai_perencanaan_anggaran->translatedFormat('d F Y') }} - {{ $activePeriode->tanggal_selesai_perencanaan_anggaran->translatedFormat('d F Y') }}
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-cyan-100 mb-0.5">Total Pagu Periode</p>
                    <p class="text-lg font-bold text-white">{{ formatRupiah($penetapanPagus->sum('jumlah_pagu')) }}</p>
                </div>
            </div>
        </div>

        <!-- Divisi List -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
            @forelse($penetapanPagus as $penetapanPagu)
                @if($penetapanPagu->divisi)
                    <a href="{{ route('program-kerja.divisi-show', $penetapanPagu->divisi) }}" class="block bg-white rounded-xl border border-blue-100 hover:border-blue-200 transition-all overflow-hidden group">
                        <div class="p-4">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-blue-200 transition-colors">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-900 text-sm group-hover:text-blue-600 transition-colors">{{ $penetapanPagu->divisi->nama_divisi }}</h3>
                                        <p class="text-xs text-gray-500">{{ $penetapanPagu->divisi->singkatan ?? '' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <div class="flex justify-between items-center">
                                    <span class="text-xs text-gray-500">Pagu Ditetaapkan</span>
                                    <span class="font-semibold text-gray-900 text-sm">{{ formatRupiah($penetapanPagu->jumlah_pagu) }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-xs text-gray-500">Program Kerja</span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                        {{ $penetapanPagu->divisi->program_kerjas_count ?? 0 }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-xs text-gray-500">Sub Program</span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                        {{ $penetapanPagu->divisi->sub_programs_count ?? 0 }}
                                    </span>
                                </div>
                                @if($penetapanPagu->divisi->program_kerjas_count > 0)
                                    @php
                                        $usedPagu = \App\Models\ProgramKerja::where('divisi_id', $penetapanPagu->divisi->id)
                                            ->where('periode_anggaran_id', $activePeriode->id)
                                            ->sum('pagu_anggaran');
                                        $percentage = $penetapanPagu->jumlah_pagu > 0 ? ($usedPagu / $penetapanPagu->jumlah_pagu) * 100 : 0;
                                    @endphp
                                    <div class="pt-3 border-t border-blue-50">
                                        <div class="flex justify-between items-center mb-1.5">
                                            <span class="text-xs text-gray-500">Pagu Terpakai</span>
                                            <span class="text-xs font-semibold @if($percentage > 90) text-red-600 @elseif($percentage > 70) text-amber-600 @else text-emerald-600 @endif">
                                                {{ round($percentage, 1) }}%
                                            </span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-1.5">
                                            <div class="h-1.5 rounded-full @if($percentage > 90) bg-red-500 @elseif($percentage > 70) bg-amber-500 @else bg-emerald-500 @endif" style="width: {{ min($percentage, 100) }}%"></div>
                                        </div>
                                        <div class="flex justify-between items-center mt-1.5">
                                            <span class="text-xs text-gray-400">{{ formatRupiah($usedPagu) }}</span>
                                            <span class="text-xs text-gray-400">{{ formatRupiah($penetapanPagu->jumlah_pagu - $usedPagu) }} tersisa</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="px-4 py-2 bg-blue-50 border-t border-blue-100 flex items-center justify-between">
                            <span class="text-xs text-gray-600">Kelola Program</span>
                            <svg class="w-4 h-4 text-gray-400 group-hover:text-blue-600 group-hover:translate-x-0.5 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </a>
                @endif
            @empty
                <div class="col-span-full">
                    <div class="bg-white rounded-xl border border-blue-100 p-12 text-center">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <h3 class="text-base font-semibold text-gray-900 mb-1">Tidak Ada Divisi</h3>
                        <p class="text-sm text-gray-500">Belum ada penetapan pagu untuk periode anggaran ini.</p>
                    </div>
                </div>
            @endforelse
        </div>
    @endif
</x-app-layout>
