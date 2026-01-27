<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">Periode Anggaran</h1>
                <p class="text-sm text-gray-500 mt-0.5">{{ $periodes->total() }} periode • {{ $currentPeriode ? 'Aktif: ' . $currentPeriode->nama_periode : 'Tidak ada periode aktif' }}</p>
            </div>
            @if(auth()->user()->hasAnyRole(['direktur_keuangan', 'staff_keuangan']))
                <a href="{{ route('periode-anggaran.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Buat Periode
                </a>
            @endif
        </div>
    </x-slot>

    <!-- Current Periode Alert -->
    @if($currentPeriode)
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl p-4 mb-4 text-white">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold">{{ $currentPeriode->nama_periode }}</h2>
                        <p class="text-white/80 text-xs mt-0.5">{{ $currentPeriode->kode_periode }} | {{ $currentPeriode->nama_fase }}</p>
                    </div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold">{{ $currentPeriode->days_remaining_formatted }}</div>
                    <div class="text-white/80 text-xs">hari tersisa</div>
                </div>
            </div>
        </div>
    @else
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-3 mb-4">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-amber-800">Tidak ada periode aktif</h3>
                </div>
            </div>
        </div>
    @endif

    <!-- Quick Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
        <div class="bg-white rounded-xl border border-blue-100 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <div>
                    <p class="text-xl font-semibold text-gray-900">{{ \App\Models\PeriodeAnggaran::count() }}</p>
                    <p class="text-xs text-gray-500">Total Periode</p>
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
                    <p class="text-xl font-semibold text-gray-900">{{ \App\Models\PeriodeAnggaran::where('status', 'active')->count() }}</p>
                    <p class="text-xs text-gray-500">Periode Aktif</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-blue-100 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-amber-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xl font-semibold text-gray-900">{{ \App\Models\PeriodeAnggaran::fase('perencangan')->count() }}</p>
                    <p class="text-xs text-gray-500">Perencanaan</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-blue-100 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-violet-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19L19 5M5 12l14 0" />
                    </svg>
                </div>
                <div>
                    <p class="text-xl font-semibold text-gray-900">{{ \App\Models\PeriodeAnggaran::fase('closed')->count() }}</p>
                    <p class="text-xs text-gray-500">Ditutup</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-xl border border-blue-100 p-3 mb-4">
        <form method="GET" action="{{ route('periode-anggaran.index') }}" class="flex flex-wrap items-center gap-3">
            <div class="min-w-[140px]">
                <select name="status" class="w-full px-3 py-2 text-sm border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all bg-white">
                    <option value="">Semua Status</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Ditutup</option>
                </select>
            </div>
            <div class="min-w-[120px]">
                <input type="number" name="tahun" value="{{ request('tahun') }}" placeholder="Tahun" class="w-full px-3 py-2 text-sm border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
            </div>
            <div class="min-w-[140px]">
                <select name="fase" class="w-full px-3 py-2 text-sm border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all bg-white">
                    <option value="">Semua Fase</option>
                    <option value="perencanaan" {{ request('fase') == 'perencanaan' ? 'selected' : '' }}>Perencanaan</option>
                    <option value="penggunaan" {{ request('fase') == 'penggunaan' ? 'selected' : '' }}>Penggunaan</option>
                    <option value="closed" {{ request('fase') == 'closed' ? 'selected' : '' }}>Ditutup</option>
                </select>
            </div>
            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                Filter
            </button>
            @if(request()->hasAny(['status', 'tahun', 'fase']))
                <a href="{{ route('periode-anggaran.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm font-medium rounded-lg transition-colors">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl border border-blue-100 overflow-hidden">
        <!-- Mobile Card View -->
        <div class="md:hidden p-4 space-y-3">
            @forelse($periodes as $periode)
                <div class="bg-gradient-to-br from-slate-50 to-slate-100 rounded-xl p-4 border border-slate-200">
                    <div class="flex items-start justify-between gap-2 mb-3">
                        <div class="flex-1 min-w-0">
                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-mono font-semibold rounded bg-blue-100 text-blue-700">
                                {{ $periode->kode_periode }}
                            </span>
                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-mono font-semibold rounded bg-gray-100 text-gray-700 ml-2">
                                {{ $periode->tahun_anggaran }}
                            </span>
                        </div>
                        @if($periode->status === 'active')
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700 flex-shrink-0">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                Aktif
                            </span>
                        @elseif($periode->status === 'draft')
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700 flex-shrink-0">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                Draft
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500 flex-shrink-0">
                                <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                Tutup
                            </span>
                        @endif
                    </div>

                    <p class="font-bold text-slate-900 text-sm mb-1">{{ $periode->nama_periode }}</p>
                    @if($periode->deskripsi)
                        <p class="text-xs text-slate-500 mb-2 line-clamp-2">{{ $periode->deskripsi }}</p>
                    @endif

                    <div class="grid grid-cols-2 gap-2 text-xs mb-3">
                        @if($periode->fase === 'perencanaan')
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-700">
                                Perencanaan
                            </span>
                        @elseif($periode->fase === 'penggunaan')
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs font-medium bg-emerald-100 text-emerald-700">
                                Penggunaan
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-700">
                                Ditutup
                            </span>
                        @endif
                        <span class="text-slate-500">
                            {{ \Carbon\Carbon::parse($periode->tanggal_mulai_perencanaan_anggaran)->format('M y') }} - {{ \Carbon\Carbon::parse($periode->tanggal_selesai_penggunaan_anggaran)->format('M y') }}
                        </span>
                    </div>

                    <div class="mb-3">
                        <div class="flex items-center justify-between text-xs mb-1">
                            <span class="text-slate-600">Progress</span>
                            <span class="font-semibold text-slate-700">{{ number_format($periode->progress_percentage, 0) }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full transition-all" style="width: {{ $periode->progress_percentage }}%"></div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-200">
                        <a href="{{ route('periode-anggaran.show', $periode) }}" class="p-2 text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </a>
                        @if(auth()->user()->hasAnyRole(['direktur_keuangan', 'staff_keuangan']))
                            @if(in_array($periode->status, ['draft', 'active']))
                                <a href="{{ route('periode-anggaran.edit', $periode) }}" class="p-2 text-amber-600 bg-amber-50 rounded-lg hover:bg-amber-100 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                            @endif
                            @if($periode->status === 'draft')
                                <form method="POST" action="{{ route('periode-anggaran.destroy', $periode) }}" class="inline" onsubmit="return confirm('Hapus periode ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            @endif
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl border border-slate-100 p-8 text-center">
                    <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <p class="text-slate-500">Belum ada periode</p>
                </div>
            @endforelse
        </div>

        <!-- Desktop Table View -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full">
            <thead class="bg-blue-50 border-b border-blue-100">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Kode</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Nama Periode</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Tahun</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Fase</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Status</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Tanggal</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Progress</th>
                    <th class="px-5 py-3 text-right text-xs font-semibold text-blue-700 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-blue-50">
                @forelse($periodes as $periode)
                    <tr class="hover:bg-blue-50/50 transition-colors">
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 text-xs font-mono font-semibold rounded bg-blue-100 text-blue-700">
                                {{ $periode->kode_periode }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            <p class="font-semibold text-gray-900 text-sm">{{ $periode->nama_periode }}</p>
                            @if($periode->deskripsi)
                                <p class="text-xs text-gray-400 truncate max-w-[200px]">{{ $periode->deskripsi }}</p>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 text-xs font-mono font-semibold rounded bg-gray-100 text-gray-700">
                                {{ $periode->tahun_anggaran }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            @if($periode->fase === 'perencanaan')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded text-xs font-medium bg-blue-100 text-blue-700">
                                    Perencanaan
                                </span>
                            @elseif($periode->fase === 'penggunaan')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded text-xs font-medium bg-emerald-100 text-emerald-700">
                                    Penggunaan
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded text-xs font-medium bg-gray-100 text-gray-700">
                                    Ditutup
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            @if($periode->status === 'active')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Aktif
                                </span>
                            @elseif($periode->status === 'draft')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                    Draft
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                    Ditutup
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            <p class="text-xs text-gray-600">{{ \Carbon\Carbon::parse($periode->tanggal_mulai_perencanaan_anggaran)->format('M y') }} - {{ \Carbon\Carbon::parse($periode->tanggal_selesai_penggunaan_anggaran)->format('M y') }}</p>
                        </td>
                        <td class="px-5 py-4">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full transition-all" style="width: {{ $periode->progress_percentage }}%"></div>
                            </div>
                            <p class="text-xs text-gray-500 mt-0.5">{{ number_format($periode->progress_percentage, 0) }}%</p>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('periode-anggaran.show', $periode) }}" class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Lihat">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                @if(auth()->user()->hasAnyRole(['direktur_keuangan', 'staff_keuangan']))
                                    @if(in_array($periode->status, ['draft', 'active']))
                                        <a href="{{ route('periode-anggaran.edit', $periode) }}" class="p-1.5 text-gray-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                    @endif
                                    @if($periode->status === 'draft')
                                        <form method="POST" action="{{ route('periode-anggaran.destroy', $periode) }}" class="inline" onsubmit="return confirm('Yakin ingin menghapus periode ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-5 py-16 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                </div>
                                <p class="text-gray-700 font-medium">Belum ada periode anggaran</p>
                                <p class="text-gray-400 text-sm mt-1">Mulai dengan membuat periode anggaran pertama Anda</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        @if($periodes->hasPages())
            <div class="bg-gray-50 px-4 md:px-5 py-3 border-t border-blue-100">
                {{ $periodes->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
