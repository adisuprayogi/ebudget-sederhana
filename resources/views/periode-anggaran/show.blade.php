<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/30">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-2xl font-bold text-gray-900">{{ $periodeAnggaran->nama_periode }}</h1>
                        @if($periodeAnggaran->status === 'active')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                Aktif
                            </span>
                        @elseif($periodeAnggaran->status === 'draft')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                                Draft
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                                Ditutup
                            </span>
                        @endif
                    </div>
                    <p class="text-gray-500 text-sm mt-0.5">{{ $periodeAnggaran->kode_periode }} | Tahun: {{ $periodeAnggaran->tahun_anggaran }}</p>
                </div>
            </div>
            @if(auth()->user()->hasAnyRole(['direktur_keuangan', 'staff_keuangan']))
                <div class="flex items-center gap-2">
                    @if($periodeAnggaran->status === 'draft')
                        <a href="{{ route('periode-anggaran.edit', $periodeAnggaran) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-white font-medium rounded-xl shadow-lg shadow-amber-500/30 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit
                        </a>
                        <form method="POST" action="{{ route('periode-anggaran.activate', $periodeAnggaran) }}" class="inline" onsubmit="return confirm('Aktifkan periode ini?');">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-medium rounded-xl shadow-lg shadow-blue-500/30 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Aktifkan
                            </button>
                        </form>
                    @elseif($periodeAnggaran->status === 'active')
                        <form method="POST" action="{{ route('periode-anggaran.close', $periodeAnggaran) }}" class="inline" onsubmit="return confirm('Tutup periode ini? Tindakan ini tidak dapat dibatalkan.');">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 text-white font-medium rounded-xl shadow-lg shadow-red-500/30 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Tutup Periode
                            </button>
                        </form>
                    @endif
                    <a href="{{ route('periode-anggaran.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 font-medium rounded-xl shadow-sm transition-all duration-200 hover:shadow-md">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali
                    </a>
                </div>
            @else
                <a href="{{ route('periode-anggaran.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 font-medium rounded-xl shadow-sm transition-all duration-200 hover:shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            @endif
        </div>
    </x-slot>

    <div class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Main Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Status & Progress Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 px-6 py-4 border-b border-indigo-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-sm flex-shrink-0">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-indigo-900">Status & Progress</h3>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <div class="text-sm text-gray-500 mb-1">Fase Saat Ini</div>
                                <div class="flex items-center gap-2">
                                    @if($periodeAnggaran->fase === 'perencangan')
                                        <span class="text-xl font-bold text-blue-600">{{ $periodeAnggaran->nama_fase }}</span>
                                    @elseif($periodeAnggaran->fase === 'penggunaan')
                                        <span class="text-xl font-bold text-emerald-600">{{ $periodeAnggaran->nama_fase }}</span>
                                    @else
                                        <span class="text-xl font-bold text-gray-600">{{ $periodeAnggaran->nama_fase }}</span>
                                    @endif
                                    @if($periodeAnggaran->is_active)
                                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-emerald-100 text-emerald-700 rounded text-xs font-semibold">
                                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                                            BERJALAN
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="text-center">
                                <div class="text-sm text-gray-500 mb-1">Sisa Hari</div>
                                <div class="text-4xl font-bold {{ $periodeAnggaran->days_remaining <= 30 ? 'text-red-600' : 'text-indigo-600' }}">
                                    {{ $periodeAnggaran->days_remaining_formatted }}
                                </div>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-gray-700">Progress Fase</span>
                                <span class="text-sm font-bold text-indigo-600">{{ number_format($periodeAnggaran->progress_percentage, 1) }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                                <div class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 h-3 rounded-full transition-all duration-500" style="width: {{ $periodeAnggaran->progress_percentage }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Timeline Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-blue-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-sm flex-shrink-0">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-blue-900">Timeline Periode</h3>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="relative">
                            <!-- Timeline Line -->
                            <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-200"></div>

                            <!-- Perencanaan Phase -->
                            <div class="relative flex items-start mb-8">
                                <div class="w-8 h-8 rounded-full {{ $periodeAnggaran->fase === 'perencangan' && $periodeAnggaran->is_active ? 'bg-gradient-to-br from-blue-500 to-blue-600 shadow-lg shadow-blue-500/30' : ($periodeAnggaran->fase === 'perencangan' ? 'bg-blue-200' : 'bg-gray-200') }} flex items-center justify-center z-10">
                                    <svg class="w-4 h-4 {{ $periodeAnggaran->fase === 'perencangan' && $periodeAnggaran->is_active ? 'text-white' : 'text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </div>
                                <div class="ml-6 flex-1">
                                    <div class="flex items-center justify-between">
                                        <h3 class="font-semibold text-gray-900">Fase Perencanaan</h3>
                                        @if($periodeAnggaran->fase === 'perencangan' && $periodeAnggaran->is_active)
                                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">
                                                <span class="w-1.5 h-1.5 bg-blue-500 rounded-full animate-pulse"></span>
                                                BERJALAN
                                            </span>
                                        @elseif($periodeAnggaran->fase !== 'perencangan')
                                            <span class="inline-flex items-center gap-1.5 px-2 py-1 bg-gray-100 text-gray-600 rounded-full text-xs font-semibold">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                </svg>
                                                SELESAI
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-sm text-gray-600 mt-1">
                                        {{ \Carbon\Carbon::parse($periodeAnggaran->tanggal_mulai_perencanaan_anggaran)->translatedFormat('d F Y') }} - {{ \Carbon\Carbon::parse($periodeAnggaran->tanggal_selesai_perencanaan_anggaran)->translatedFormat('d F Y') }}
                                    </div>
                                    <p class="text-xs text-gray-500 mt-2">Perencanaan penerimaan, penetapan pagu, dan program kerja</p>
                                </div>
                            </div>

                            <!-- Penggunaan Phase -->
                            <div class="relative flex items-start">
                                <div class="w-8 h-8 rounded-full {{ $periodeAnggaran->fase === 'penggunaan' && $periodeAnggaran->is_active ? 'bg-gradient-to-br from-emerald-500 to-green-600 shadow-lg shadow-emerald-500/30' : ($periodeAnggaran->fase === 'penggunaan' ? 'bg-emerald-200' : 'bg-gray-200') }} flex items-center justify-center z-10">
                                    <svg class="w-4 h-4 {{ $periodeAnggaran->fase === 'penggunaan' && $periodeAnggaran->is_active ? 'text-white' : 'text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <div class="ml-6 flex-1">
                                    <div class="flex items-center justify-between">
                                        <h3 class="font-semibold text-gray-900">Fase Penggunaan</h3>
                                        @if($periodeAnggaran->fase === 'penggunaan' && $periodeAnggaran->is_active)
                                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-semibold">
                                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                                                BERJALAN
                                            </span>
                                        @elseif($periodeAnggaran->fase === 'closed')
                                            <span class="inline-flex items-center gap-1.5 px-2 py-1 bg-gray-100 text-gray-600 rounded-full text-xs font-semibold">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                </svg>
                                                SELESAI
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-sm text-gray-600 mt-1">
                                        {{ \Carbon\Carbon::parse($periodeAnggaran->tanggal_mulai_penggunaan_anggaran)->translatedFormat('d F Y') }} - {{ \Carbon\Carbon::parse($periodeAnggaran->tanggal_selesai_penggunaan_anggaran)->translatedFormat('d F Y') }}
                                    </div>
                                    <p class="text-xs text-gray-500 mt-2">Pengajuan dana, pencairan, dan penggunaan anggaran</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-emerald-50 to-teal-50 px-6 py-4 border-b border-emerald-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-sm flex-shrink-0">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-emerald-900">Statistik</h3>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4 text-center">
                                <div class="text-3xl font-bold text-blue-600">{{ $statistics['jumlah_program'] ?? 0 }}</div>
                                <div class="text-xs text-blue-700 mt-1 font-medium">Program Kerja</div>
                            </div>
                            <div class="bg-gradient-to-br from-amber-50 to-amber-100 rounded-xl p-4 text-center">
                                <div class="text-3xl font-bold text-amber-600">{{ $statistics['jumlah_pengajuan'] ?? 0 }}</div>
                                <div class="text-xs text-amber-700 mt-1 font-medium">Pengajuan</div>
                            </div>
                            <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-xl p-4 text-center">
                                <div class="text-3xl font-bold text-emerald-600">{{ $statistics['jumlah_pencairan'] ?? 0 }}</div>
                                <div class="text-xs text-emerald-700 mt-1 font-medium">Pencairan</div>
                            </div>
                            <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-4 text-center">
                                <div class="text-3xl font-bold text-purple-600">{{ $statistics['jumlah_lpj'] ?? 0 }}</div>
                                <div class="text-xs text-purple-700 mt-1 font-medium">LPJ</div>
                            </div>
                        </div>

                        @isset($statistics['total_pagu'])
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-4">
                                        <div class="text-sm text-gray-600 mb-1">Total Pagu</div>
                                        <div class="text-xl font-bold text-gray-900">{{ formatRupiah($statistics['total_pagu']) }}</div>
                                    </div>
                                    <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-xl p-4">
                                        <div class="text-sm text-gray-600 mb-1">Realisasi</div>
                                        <div class="flex items-baseline justify-between">
                                            <div class="text-xl font-bold text-indigo-600">{{ formatRupiah($statistics['total_pencairan']) }}</div>
                                            <div class="text-sm font-bold {{ $statistics['realisasi_percentage'] >= 90 ? 'text-red-600' : 'text-emerald-600' }}">
                                                {{ number_format($statistics['realisasi_percentage'], 1) }}%
                                            </div>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                                            <div class="h-2 rounded-full {{ $statistics['realisasi_percentage'] >= 90 ? 'bg-red-500' : 'bg-emerald-500' }} transition-all duration-500" style="width: {{ min($statistics['realisasi_percentage'], 100) }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endisset
                    </div>
                </div>

                <!-- Warnings -->
                @if(!empty($warnings))
                    <div class="bg-gradient-to-br from-amber-50 to-orange-50 border border-amber-200 rounded-2xl p-6">
                        <h2 class="text-lg font-semibold text-amber-900 mb-4 flex items-center">
                            <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            Peringatan
                        </h2>
                        <div class="space-y-2">
                            @foreach($warnings as $warning)
                                <div class="flex items-start text-sm {{ $warning['severity'] === 'high' ? 'text-red-700' : 'text-amber-700' }}">
                                    <span class="mr-2">•</span>
                                    <span>{{ $warning['message'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right Column: Meta Info -->
            <div class="space-y-6">
                <!-- Description -->
                @if($periodeAnggaran->deskripsi)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-4 border-b border-gray-100">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-sm flex-shrink-0">
                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900">Deskripsi</h3>
                            </div>
                        </div>
                        <div class="p-6">
                            <p class="text-gray-700 text-sm">{{ $periodeAnggaran->deskripsi }}</p>
                        </div>
                    </div>
                @endif

                <!-- Meta Information -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-4 border-b border-gray-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-sm flex-shrink-0">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Informasi</h3>
                        </div>
                    </div>

                    <div class="p-6 space-y-4">
                        <div>
                            <div class="text-xs text-gray-500 uppercase tracking-wide mb-1">Dibuat Oleh</div>
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-gray-900">{{ $periodeAnggaran->createdBy->name ?? '-' }}</div>
                                    <div class="text-xs text-gray-500">{{ $periodeAnggaran->created_at?->diffForHumans() }}</div>
                                </div>
                            </div>
                        </div>

                        @if($periodeAnggaran->approvedBy)
                            <div>
                                <div class="text-xs text-gray-500 uppercase tracking-wide mb-1">Disetujui Oleh</div>
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold text-gray-900">{{ $periodeAnggaran->approvedBy->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $periodeAnggaran->approved_at?->diffForHumans() }}</div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div>
                            <div class="text-xs text-gray-500 uppercase tracking-wide mb-1">Kode Periode</div>
                            <div class="inline-flex items-center px-3 py-1.5 rounded-lg bg-indigo-100 text-indigo-700 font-mono text-sm font-bold">
                                {{ $periodeAnggaran->kode_periode }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-4 border-b border-gray-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-sm flex-shrink-0">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Aksi Cepat</h3>
                        </div>
                    </div>

                    <div class="p-3">
                        <div class="space-y-2">
                            <a href="{{ route('periode-anggaran.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-xl transition-all duration-200 group">
                                <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center mr-3 group-hover:bg-indigo-100 transition-colors">
                                    <svg class="w-5 h-5 text-gray-500 group-hover:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                    </svg>
                                </div>
                                <span class="font-medium">Lihat Semua Periode</span>
                            </a>
                            <a href="{{ route('reports.budget-realization', ['periode_anggaran_id' => $periodeAnggaran->id]) }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-xl transition-all duration-200 group">
                                <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center mr-3 group-hover:bg-emerald-100 transition-colors">
                                    <svg class="w-5 h-5 text-gray-500 group-hover:text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                </div>
                                <span class="font-medium">Lihat Laporan Budget</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
