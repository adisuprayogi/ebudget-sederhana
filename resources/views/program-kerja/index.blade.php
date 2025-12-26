<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-secondary-900">Program Kerja</h1>
                <p class="text-secondary-600 mt-1">Kelola program kerja per divisi untuk periode anggaran aktif</p>
            </div>
        </div>
    </x-slot>

    @if(!$activePeriode)
        <div class="bg-amber-50 border border-amber-200 rounded-2xl p-8 text-center">
            <svg class="w-16 h-16 text-amber-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <h3 class="text-lg font-semibold text-amber-800 mb-2">Tidak Ada Periode Anggaran Aktif</h3>
            <p class="text-amber-600">Silakan atur periode anggaran aktif terlebih dahulu sebelum mengelola program kerja.</p>
        </div>
    @else
        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-2xl shadow-soft p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm text-secondary-500 mb-1">Total Divisi</div>
                        <div class="text-2xl font-bold text-secondary-900">{{ $penetapanPagus->count() }}</div>
                    </div>
                    <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-soft p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm text-secondary-500 mb-1">Total Pagu</div>
                        <div class="text-2xl font-bold text-primary-600">{{ formatRupiah($penetapanPagus->sum('jumlah_pagu')) }}</div>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-soft p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm text-secondary-500 mb-1">Periode Aktif</div>
                        <div class="text-lg font-bold text-secondary-900">{{ $activePeriode->nama_periode }}</div>
                        <div class="text-xs text-secondary-500">{{ $activePeriode->tanggal_mulai_perencanaan_anggaran->format('M Y') }} - {{ $activePeriode->tanggal_selesai_perencanaan_anggaran->format('M Y') }}</div>
                    </div>
                    <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Periode Anggaran Info -->
        <div class="bg-gradient-to-r from-primary-500 to-primary-600 rounded-2xl shadow-soft p-6 mb-8 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm text-primary-100 mb-1">Periode Anggaran Aktif</div>
                    <div class="text-2xl font-bold">{{ $activePeriode->nama_periode }}</div>
                    <div class="text-primary-100 mt-1">
                        {{ $activePeriode->tanggal_mulai_perencanaan_anggaran->translatedFormat('d F Y') }} - {{ $activePeriode->tanggal_selesai_perencanaan_anggaran->translatedFormat('d F Y') }}
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-sm text-primary-100 mb-1">Total Pagu Periode</div>
                    <div class="text-2xl font-bold">{{ formatRupiah($penetapanPagus->sum('jumlah_pagu')) }}</div>
                </div>
            </div>
        </div>

        <!-- Divisi List -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($penetapanPagus as $penetapanPagu)
                @if($penetapanPagu->divisi)
                    <a href="{{ route('program-kerja.divisi-show', $penetapanPagu->divisi) }}" class="block bg-white rounded-2xl shadow-soft hover:shadow-medium transition-all duration-200 overflow-hidden group">
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center mr-4 group-hover:bg-primary-200 transition-colors">
                                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-secondary-900 group-hover:text-primary-600 transition-colors">{{ $penetapanPagu->divisi->nama_divisi }}</h3>
                                        <div class="text-sm text-secondary-500">{{ $penetapanPagu->divisi->singkatan ?? '' }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-secondary-500">Pagu Ditetaapkan</span>
                                    <span class="font-semibold text-secondary-900">{{ formatRupiah($penetapanPagu->jumlah_pagu) }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-secondary-500">Program Kerja</span>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-700">
                                        {{ $penetapanPagu->divisi->program_kerjas_count ?? 0 }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-secondary-500">Sub Program</span>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
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
                                    <div class="pt-3 border-t border-secondary-100">
                                        <div class="flex justify-between items-center mb-2">
                                            <span class="text-sm text-secondary-500">Pagu Terpakai</span>
                                            <span class="text-sm font-semibold {{ $percentage > 90 ? 'text-red-600' : ($percentage > 70 ? 'text-amber-600' : 'text-green-600') }}">
                                                {{ round($percentage, 1) }}%
                                            </span>
                                        </div>
                                        <div class="w-full bg-secondary-200 rounded-full h-2">
                                            <div class="h-2 rounded-full {{ $percentage > 90 ? 'bg-red-500' : ($percentage > 70 ? 'bg-amber-500' : 'bg-green-500') }}" style="width: {{ $percentage }}%"></div>
                                        </div>
                                        <div class="flex justify-between items-center mt-2">
                                            <span class="text-xs text-secondary-400">{{ formatRupiah($usedPagu) }}</span>
                                            <span class="text-xs text-secondary-400">{{ formatRupiah($penetapanPagu->jumlah_pagu - $usedPagu) }} tersisa</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="px-6 py-3 bg-secondary-50 border-t border-secondary-100 flex items-center justify-between">
                            <span class="text-sm text-secondary-600">Kelola Program</span>
                            <svg class="w-5 h-5 text-secondary-400 group-hover:text-primary-600 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </a>
                @endif
            @empty
                <div class="col-span-full">
                    <div class="bg-white rounded-2xl shadow-soft p-12 text-center">
                        <svg class="w-20 h-20 text-secondary-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <h3 class="text-lg font-semibold text-secondary-900 mb-2">Tidak Ada Divisi</h3>
                        <p class="text-secondary-500">Belum ada penetapan pagu untuk periode anggaran ini.</p>
                    </div>
                </div>
            @endforelse
        </div>
    @endif
</x-app-layout>
