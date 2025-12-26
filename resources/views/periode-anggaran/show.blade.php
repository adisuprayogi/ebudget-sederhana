<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('periode-anggaran.index') }}" class="p-2 text-secondary-600 hover:text-secondary-900 hover:bg-secondary-100 rounded-lg transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div>
                    <div class="flex items-center space-x-3">
                        <h1 class="text-2xl font-bold text-secondary-900">{{ $periodeAnggaran->nama_periode }}</h1>
                        @if($periodeAnggaran->status === 'active')
                            <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-semibold">Aktif</span>
                        @elseif($periodeAnggaran->status === 'draft')
                            <span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-semibold">Draft</span>
                        @else
                            <span class="px-3 py-1 bg-secondary-100 text-secondary-700 rounded-full text-xs font-semibold">Ditutup</span>
                        @endif
                    </div>
                    <p class="text-secondary-600 mt-1">{{ $periodeAnggaran->kode_periode }} | Tahun: {{ $periodeAnggaran->tahun_anggaran }}</p>
                </div>
            </div>
            @if(auth()->user()->hasAnyRole(['direktur_keuangan', 'staff_keuangan']))
                <div class="flex items-center space-x-3">
                    @if($periodeAnggaran->status === 'draft')
                        <a href="{{ route('periode-anggaran.edit', $periodeAnggaran) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit
                        </a>
                        <form method="POST" action="{{ route('periode-anggaran.activate', $periodeAnggaran) }}" class="inline" onsubmit="return confirm('Aktifkan periode ini?');">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Aktifkan
                            </button>
                        </form>
                    @elseif($periodeAnggaran->status === 'active')
                        <form method="POST" action="{{ route('periode-anggaran.close', $periodeAnggaran) }}" class="inline" onsubmit="return confirm('Tutup periode ini? Tindakan ini tidak dapat dibatalkan.');">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Tutup Periode
                            </button>
                        </form>
                    @endif
                </div>
            @endif
        </div>
    </x-slot>

    <div class="py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Main Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Current Fase & Progress -->
                <div class="bg-white rounded-2xl shadow-soft p-6">
                    <h2 class="text-lg font-semibold text-secondary-900 mb-4">Status & Progress</h2>
                    
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <div class="text-sm text-secondary-500 mb-1">Fase Saat Ini</div>
                            <div class="flex items-center space-x-2">
                                @if($periodeAnggaran->fase === 'perencangan')
                                    <span class="text-xl font-bold text-blue-600">{{ $periodeAnggaran->nama_fase }}</span>
                                @elseif($periodeAnggaran->fase === 'penggunaan')
                                    <span class="text-xl font-bold text-green-600">{{ $periodeAnggaran->nama_fase }}</span>
                                @else
                                    <span class="text-xl font-bold text-secondary-600">{{ $periodeAnggaran->nama_fase }}</span>
                                @endif
                                @if($periodeAnggaran->is_active)
                                    <span class="px-2 py-1 bg-emerald-100 text-emerald-700 rounded text-xs font-semibold">BERJALAN</span>
                                @endif
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm text-secondary-500 mb-1">Sisa Hari</div>
                            <div class="text-3xl font-bold {{ $periodeAnggaran->days_remaining <= 30 ? 'text-red-600' : 'text-secondary-900' }}">
                                {{ $periodeAnggaran->days_remaining_formatted }}
                            </div>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-secondary-700">Progress Fase</span>
                            <span class="text-sm font-medium text-primary-600">{{ number_format($periodeAnggaran->progress_percentage, 1) }}%</span>
                        </div>
                        <div class="w-full bg-secondary-200 rounded-full h-3">
                            <div class="bg-gradient-to-r from-primary-500 to-primary-600 h-3 rounded-full transition-all duration-500" style="width: {{ $periodeAnggaran->progress_percentage }}%"></div>
                        </div>
                    </div>
                </div>

                <!-- Timeline -->
                <div class="bg-white rounded-2xl shadow-soft p-6">
                    <h2 class="text-lg font-semibold text-secondary-900 mb-4">Timeline Periode</h2>
                    
                    <div class="relative">
                        <!-- Timeline Line -->
                        <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-secondary-200"></div>

                        <!-- Perencanaan Phase -->
                        <div class="relative flex items-start mb-8">
                            <div class="w-8 h-8 rounded-full {{ $periodeAnggaran->fase === 'perencangan' && $periodeAnggaran->is_active ? 'bg-blue-600' : ($periodeAnggaran->fase === 'perencangan' ? 'bg-blue-200' : 'bg-secondary-200') }} flex items-center justify-center z-10">
                                <svg class="w-4 h-4 {{ $periodeAnggaran->fase === 'perencangan' && $periodeAnggaran->is_active ? 'text-white' : 'text-secondary-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <div class="ml-6 flex-1">
                                <div class="flex items-center justify-between">
                                    <h3 class="font-semibold text-secondary-900">Fase Perencanaan</h3>
                                    @if($periodeAnggaran->fase === 'perencangan' && $periodeAnggaran->is_active)
                                        <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">BERJALAN</span>
                                    @elseif($periodeAnggaran->fase !== 'perencangan')
                                        <span class="px-2 py-1 bg-secondary-100 text-secondary-600 rounded-full text-xs font-semibold">{{ $periodeAnggaran->fase === 'penggunaan' || $periodeAnggaran->fase === 'closed' ? 'SELESAI' : 'MENUNGGU' }}</span>
                                    @endif
                                </div>
                                <div class="text-sm text-secondary-600 mt-1">
                                    {{ \Carbon\Carbon::parse($periodeAnggaran->tanggal_mulai_perencanaan_anggaran)->translatedFormat('d F Y') }} - {{ \Carbon\Carbon::parse($periodeAnggaran->tanggal_selesai_perencanaan_anggaran)->translatedFormat('d F Y') }}
                                </div>
                                <p class="text-xs text-secondary-500 mt-2">Perencanaan penerimaan, penetapan pagu, dan program kerja</p>
                            </div>
                        </div>

                        <!-- Penggunaan Phase -->
                        <div class="relative flex items-start">
                            <div class="w-8 h-8 rounded-full {{ $periodeAnggaran->fase === 'penggunaan' && $periodeAnggaran->is_active ? 'bg-green-600' : ($periodeAnggaran->fase === 'penggunaan' ? 'bg-green-200' : 'bg-secondary-200') }} flex items-center justify-center z-10">
                                <svg class="w-4 h-4 {{ $periodeAnggaran->fase === 'penggunaan' && $periodeAnggaran->is_active ? 'text-white' : 'text-secondary-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div class="ml-6 flex-1">
                                <div class="flex items-center justify-between">
                                    <h3 class="font-semibold text-secondary-900">Fase Penggunaan</h3>
                                    @if($periodeAnggaran->fase === 'penggunaan' && $periodeAnggaran->is_active)
                                        <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">BERJALAN</span>
                                    @elseif($periodeAnggaran->fase === 'closed')
                                        <span class="px-2 py-1 bg-secondary-100 text-secondary-600 rounded-full text-xs font-semibold">SELESAI</span>
                                    @endif
                                </div>
                                <div class="text-sm text-secondary-600 mt-1">
                                    {{ \Carbon\Carbon::parse($periodeAnggaran->tanggal_mulai_penggunaan_anggaran)->translatedFormat('d F Y') }} - {{ \Carbon\Carbon::parse($periodeAnggaran->tanggal_selesai_penggunaan_anggaran)->translatedFormat('d F Y') }}
                                </div>
                                <p class="text-xs text-secondary-500 mt-2">Pengajuan dana, pencairan, dan penggunaan anggaran</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="bg-white rounded-2xl shadow-soft p-6">
                    <h2 class="text-lg font-semibold text-secondary-900 mb-4">Statistik</h2>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4 text-center">
                            <div class="text-2xl font-bold text-blue-600">{{ $statistics['jumlah_program'] ?? 0 }}</div>
                            <div class="text-xs text-blue-600 mt-1">Program Kerja</div>
                        </div>
                        <div class="bg-gradient-to-br from-amber-50 to-amber-100 rounded-xl p-4 text-center">
                            <div class="text-2xl font-bold text-amber-600">{{ $statistics['jumlah_pengajuan'] ?? 0 }}</div>
                            <div class="text-xs text-amber-600 mt-1">Pengajuan</div>
                        </div>
                        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-4 text-center">
                            <div class="text-2xl font-bold text-green-600">{{ $statistics['jumlah_pencairan'] ?? 0 }}</div>
                            <div class="text-xs text-green-600 mt-1">Pencairan</div>
                        </div>
                        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-4 text-center">
                            <div class="text-2xl font-bold text-purple-600">{{ $statistics['jumlah_lpj'] ?? 0 }}</div>
                            <div class="text-xs text-purple-600 mt-1">LPJ</div>
                        </div>
                    </div>

                    @isset($statistics['total_pagu'])
                        <div class="mt-6 pt-6 border-t border-secondary-200">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-secondary-50 rounded-xl p-4">
                                    <div class="text-sm text-secondary-600 mb-1">Total Pagu</div>
                                    <div class="text-xl font-bold text-secondary-900">{{ formatRupiah($statistics['total_pagu']) }}</div>
                                </div>
                                <div class="bg-secondary-50 rounded-xl p-4">
                                    <div class="text-sm text-secondary-600 mb-1">Realisasi</div>
                                    <div class="flex items-baseline justify-between">
                                        <div class="text-xl font-bold text-primary-600">{{ formatRupiah($statistics['total_pencairan']) }}</div>
                                        <div class="text-sm font-semibold {{ $statistics['realisasi_percentage'] >= 90 ? 'text-red-600' : 'text-green-600' }}">
                                            {{ number_format($statistics['realisasi_percentage'], 1) }}%
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endisset
                </div>

                <!-- Warnings -->
                @if(!empty($warnings))
                    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-6">
                        <h2 class="text-lg font-semibold text-amber-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
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
                    <div class="bg-white rounded-2xl shadow-soft p-6">
                        <h2 class="text-lg font-semibold text-secondary-900 mb-3">Deskripsi</h2>
                        <p class="text-secondary-600 text-sm">{{ $periodeAnggaran->deskripsi }}</p>
                    </div>
                @endif

                <!-- Meta Information -->
                <div class="bg-white rounded-2xl shadow-soft p-6">
                    <h2 class="text-lg font-semibold text-secondary-900 mb-4">Informasi</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <div class="text-xs text-secondary-500 uppercase tracking-wide mb-1">Dibuat Oleh</div>
                            <div class="text-sm font-medium text-secondary-900">{{ $periodeAnggaran->createdBy->name ?? '-' }}</div>
                            <div class="text-xs text-secondary-500">{{ $periodeAnggaran->created_at?->diffForHumans() }}</div>
                        </div>

                        @if($periodeAnggaran->approvedBy)
                            <div>
                                <div class="text-xs text-secondary-500 uppercase tracking-wide mb-1">Disetujui Oleh</div>
                                <div class="text-sm font-medium text-secondary-900">{{ $periodeAnggaran->approvedBy->name }}</div>
                                <div class="text-xs text-secondary-500">{{ $periodeAnggaran->approved_at?->diffForHumans() }}</div>
                            </div>
                        @endif

                        <div>
                            <div class="text-xs text-secondary-500 uppercase tracking-wide mb-1">Kode Periode</div>
                            <div class="text-sm font-mono font-medium text-primary-600">{{ $periodeAnggaran->kode_periode }}</div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-2xl shadow-soft p-6">
                    <h2 class="text-lg font-semibold text-secondary-900 mb-4">Aksi Cepat</h2>
                    
                    <div class="space-y-3">
                        <a href="{{ route('periode-anggaran.index') }}" class="flex items-center px-4 py-3 text-secondary-700 hover:bg-secondary-50 rounded-xl transition-colors">
                            <svg class="w-5 h-5 mr-3 text-secondary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                            </svg>
                            Lihat Semua Periode
                        </a>
                        <a href="{{ route('reports.budget-realization', ['periode_anggaran_id' => $periodeAnggaran->id]) }}" class="flex items-center px-4 py-3 text-secondary-700 hover:bg-secondary-50 rounded-xl transition-colors">
                            <svg class="w-5 h-5 mr-3 text-secondary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            Lihat Laporan Budget
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
