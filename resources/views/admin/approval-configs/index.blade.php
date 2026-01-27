<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">Konfigurasi Approval</h1>
                <p class="text-sm text-gray-500 mt-0.5">{{ $configs->total() }} konfigurasi • {{ $jenisPengajuan === 'pengajuan_dana' ? 'Pengajuan Dana' : ($jenisPengajuan === 'lpj' ? 'Laporan LPJ' : ($jenisPengajuan === 'refund' ? 'Refund' : 'Pencairan Dana')) }}</p>
            </div>
            <a href="{{ route('admin.approval-configs.create') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah
            </a>
        </div>
    </x-slot>

    <!-- Quick Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
        <div class="bg-white rounded-xl border border-blue-100 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2H9a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V9a2 2 0 00-2-2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xl font-semibold text-gray-900">{{ $configs->total() }}</p>
                    <p class="text-xs text-gray-500">Total Config</p>
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
                    <p class="text-xl font-semibold text-gray-900">{{ \App\Models\ApprovalConfig::where('is_active', true)->count() }}</p>
                    <p class="text-xs text-gray-500">Config Aktif</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-blue-100 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-violet-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9l14 0M6 15l14 0m0 0v6m2-6v6m1-10v6m1 10v6m1-10v6m1 10v6m1-10v6m1 10v6" />
                    </svg>
                </div>
                <div>
                    <p class="text-xl font-semibold text-gray-900">{{ $configs->where('level', 'kepala_divisi')->count() }}</p>
                    <p class="text-xs text-gray-500">Kepala Divisi</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-blue-100 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-cyan-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2H9a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V9a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xl font-semibold text-gray-900">{{ $configs->where('level', 'direktur_keuangan')->count() }}</p>
                    <p class="text-xs text-gray-500">Dir. Keuangan</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs & Summary -->
    <div class="bg-white rounded-xl border border-blue-100 mb-4 overflow-hidden">
        <!-- Tabs -->
        <div class="flex overflow-x-auto border-b border-blue-50">
            <a href="{{ route('admin.approval-configs.index', ['jenis' => 'pengajuan_dana']) }}"
               class="flex-1 min-w-max px-4 py-3 text-center text-sm font-medium border-b-2 {{ $jenisPengajuan === 'pengajuan_dana' ? 'border-blue-600 text-blue-600 bg-blue-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">
                <span class="hidden sm:inline">Pengajuan Dana</span>
                <span class="sm:hidden">Pengajuan</span>
            </a>
            <a href="{{ route('admin.approval-configs.index', ['jenis' => 'lpj']) }}"
               class="flex-1 min-w-max px-4 py-3 text-center text-sm font-medium border-b-2 {{ $jenisPengajuan === 'lpj' ? 'border-blue-600 text-blue-600 bg-blue-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">
                LPJ
            </a>
            <a href="{{ route('admin.approval-configs.index', ['jenis' => 'refund']) }}"
               class="flex-1 min-w-max px-4 py-3 text-center text-sm font-medium border-b-2 {{ $jenisPengajuan === 'refund' ? 'border-blue-600 text-blue-600 bg-blue-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">
                Refund
            </a>
            <a href="{{ route('admin.approval-configs.index', ['jenis' => 'pencairan_dana']) }}"
               class="flex-1 min-w-max px-4 py-3 text-center text-sm font-medium border-b-2 {{ $jenisPengajuan === 'pencairan_dana' ? 'border-blue-600 text-blue-600 bg-blue-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">
                <span class="hidden sm:inline">Pencairan</span>
                <span class="sm:hidden">Pencairan</span>
            </a>
        </div>

        <!-- Summary -->
        @if($configs->isNotEmpty())
            @php
                $activeCount = $configs->where('is_active', true)->count();
                $sorted = $configs->sortBy('minimal_nominal');
                $min = $sorted->first()->minimal_nominal;
                $max = $sorted->last()->minimal_nominal;
            @endphp
            <div class="px-3 md:px-4 py-2 md:py-3 bg-blue-50/50 border-b border-blue-100">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 md:gap-4">
                    <div class="flex items-center gap-2 md:gap-4 text-xs md:text-sm">
                        <span class="text-gray-600"><span class="font-semibold text-blue-600">{{ $activeCount }}</span> level aktif</span>
                        <span class="text-blue-200 hidden sm:inline">|</span>
                        <span class="text-gray-600 truncate">Range: <span class="font-mono font-medium text-gray-900">Rp {{ number_format($min, 0, ',', '.') }}</span> — <span class="font-mono font-medium text-gray-900">Rp {{ number_format($max, 0, ',', '.') }}</span></span>
                    </div>
                    <div class="flex items-center gap-0.5 md:gap-1 overflow-x-auto">
                        @foreach($sorted->take(5) as $idx => $c)
                            @if($idx > 0)<span class="text-blue-300 flex-shrink-0">→</span>@endif
                            <span class="w-6 h-6 rounded-full flex-shrink-0 {{ $c->is_active ? 'bg-blue-600 text-white' : 'bg-blue-100 text-blue-400' }} flex items-center justify-center text-xs font-semibold">
                                {{ $c->urutan }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Mobile Card View -->
    <div class="md:hidden space-y-3">
        @forelse($configs as $config)
            @php
                $next = $configs->where('minimal_nominal', '>', $config->minimal_nominal)->sortBy('minimal_nominal')->first();
                $maxNominal = $next ? $next->minimal_nominal - 1 : null;
                $levelInfo = [
                    'kepala_divisi' => ['label' => 'Kepala Divisi', 'color' => 'blue'],
                    'direktur_keuangan' => ['label' => 'Dir. Keuangan', 'color' => 'cyan'],
                    'direktur_utama' => ['label' => 'Dir. Utama', 'color' => 'indigo'],
                ];
                $info = $levelInfo[$config->level] ?? ['label' => $config->level, 'color' => 'gray'];
            @endphp
            <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden {{ $config->is_active ? '' : 'opacity-60' }}">
                <!-- Header -->
                <div class="px-3 py-3 bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-100">
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2 flex-1 min-w-0">
                            <div class="w-9 h-9 bg-blue-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="font-mono font-semibold text-gray-900 text-sm truncate">Rp {{ number_format($config->minimal_nominal, 0, ',', '.') }}</p>
                                @if($maxNominal)
                                    <p class="text-xs text-gray-400 truncate">s/d Rp {{ number_format($maxNominal, 0, ',', '.') }}</p>
                                @else
                                    <p class="text-xs text-blue-500">ke atas</p>
                                @endif
                            </div>
                        </div>
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-100 text-sm font-bold text-blue-700 flex-shrink-0">
                            {{ $config->urutan }}
                        </span>
                    </div>
                </div>

                <!-- Body -->
                <div class="p-3 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-{{ $info['color'] }}-100 text-{{ $info['color'] }}-700">
                            {{ $info['label'] }}
                        </span>
                        @if($config->is_active)
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700 flex-shrink-0">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                                <span class="hidden sm:inline">Aktif</span>
                                <span class="sm:hidden">ON</span>
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500 flex-shrink-0">
                                <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                <span class="hidden sm:inline">Nonaktif</span>
                                <span class="sm:hidden">OFF</span>
                            </span>
                        @endif
                    </div>

                    <div class="flex items-center justify-end gap-1.5 pt-2 border-t border-slate-100">
                        <form method="POST" action="{{ route('admin.approval-configs.toggle-status', $config) }}" class="inline">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg transition-colors text-xs font-medium {{ $config->is_active ? 'text-amber-600 bg-amber-50 hover:bg-amber-100' : 'text-blue-600 bg-blue-50 hover:bg-blue-100' }}">
                                @if($config->is_active)
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="hidden sm:inline">Nonaktifkan</span>
                                @else
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M8 5v14l11-7z" />
                                    </svg>
                                    <span class="hidden sm:inline">Aktifkan</span>
                                @endif
                            </button>
                        </form>
                        <a href="{{ route('admin.approval-configs.edit', $config) }}" class="inline-flex items-center gap-1.5 px-3 py-2 text-amber-600 bg-amber-50 rounded-lg hover:bg-amber-100 transition-colors text-xs font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            <span class="hidden sm:inline">Edit</span>
                        </a>
                        <form method="POST" action="{{ route('admin.approval-configs.destroy', $config) }}" class="inline" onsubmit="return confirm('Hapus konfigurasi ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-2 text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors text-xs font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                <span class="hidden sm:inline">Hapus</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl border border-slate-100 p-6 md:p-8 text-center">
                <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <p class="text-slate-500 text-sm">Belum ada konfigurasi</p>
                <a href="{{ route('admin.approval-configs.create') }}" class="mt-3 inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                    Tambah Konfigurasi
                </a>
            </div>
        @endforelse
    </div>

    <!-- Desktop Table View -->
    <div class="hidden md:block bg-white rounded-xl border border-blue-100 overflow-hidden">
        <table class="w-full">
            <thead class="bg-blue-50 border-b border-blue-100">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Nominal</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Approver</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold text-blue-700 uppercase">Urutan</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold text-blue-700 uppercase">Status</th>
                    <th class="px-5 py-3 text-right text-xs font-semibold text-blue-700 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-blue-50">
                @forelse($configs as $config)
                    @php
                        $next = $configs->where('minimal_nominal', '>', $config->minimal_nominal)->sortBy('minimal_nominal')->first();
                        $maxNominal = $next ? $next->minimal_nominal - 1 : null;
                        $levelInfo = [
                            'kepala_divisi' => ['label' => 'Kepala Divisi', 'color' => 'blue'],
                            'direktur_keuangan' => ['label' => 'Dir. Keuangan', 'color' => 'cyan'],
                            'direktur_utama' => ['label' => 'Dir. Utama', 'color' => 'indigo'],
                        ];
                        $info = $levelInfo[$config->level] ?? ['label' => $config->level, 'color' => 'gray'];
                    @endphp
                    <tr class="hover:bg-blue-50/50 transition-colors {{ $config->is_active ? '' : 'opacity-50' }}">
                        <td class="px-5 py-4">
                            <div>
                                <p class="font-mono font-medium text-gray-900">Rp {{ number_format($config->minimal_nominal, 0, ',', '.') }}</p>
                                @if($maxNominal)
                                    <p class="text-xs text-gray-400">s/d Rp {{ number_format($maxNominal, 0, ',', '.') }}</p>
                                @else
                                    <p class="text-xs text-blue-500">ke atas</p>
                                @endif
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-{{ $info['color'] }}-100 text-{{ $info['color'] }}-700">
                                {{ $info['label'] }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-center">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-100 text-sm font-bold text-blue-700">
                                {{ $config->urutan }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-center">
                            @if($config->is_active)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                    Nonaktif
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <form method="POST" action="{{ route('admin.approval-configs.toggle-status', $config) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="p-1.5 rounded-lg transition-colors {{ $config->is_active ? 'text-amber-500 hover:bg-amber-50' : 'text-blue-500 hover:bg-blue-50' }}" title="{{ $config->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                        @if($config->is_active)
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M8 5v14l11-7z" />
                                            </svg>
                                        @endif
                                    </button>
                                </form>
                                <a href="{{ route('admin.approval-configs.edit', $config) }}" class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('admin.approval-configs.destroy', $config) }}" class="inline" onsubmit="return confirm('Hapus konfigurasi ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-16 text-center">
                            <div class="flex flex-col items-center text-center">
                                <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </div>
                                <p class="text-gray-700 font-medium">Belum ada konfigurasi</p>
                                <p class="text-gray-400 text-sm mt-1">Tambahkan konfigurasi approval untuk memulai</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($configs->hasPages())
        <div class="mt-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3 text-xs md:text-sm">
            <span class="text-gray-500 text-center md:text-left">
                <span class="hidden md:inline">Menampilkan {{ $configs->firstItem() ?? 0 }}–{{ $configs->lastItem() ?? 0 }} dari {{ $configs->total() }}</span>
                <span class="md:hidden">{{ $configs->total() }} konfigurasi</span>
            </span>
            {{ $configs->links() }}
        </div>
    @endif
</x-app-layout>
