<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-secondary-900">Periode Anggaran</h1>
                <p class="text-secondary-600 mt-1">Kelola periode anggaran dan fase perencanaan/penggunaan</p>
            </div>
            @if(auth()->user()->hasAnyRole(['direktur_keuangan', 'staff_keuangan']))
                <a href="{{ route('periode-anggaran.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Buat Periode Baru
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-8">
        <!-- Current Periode Alert -->
        @if($currentPeriode)
            <div class="mb-6 bg-gradient-to-r from-primary-500 to-primary-600 rounded-2xl p-6 text-white shadow-medium">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="flex items-center space-x-2 mb-2">
                            <span class="px-3 py-1 bg-white/20 rounded-full text-xs font-semibold uppercase tracking-wide">
                                Periode Aktif
                            </span>
                            <span class="px-3 py-1 bg-white/20 rounded-full text-xs font-semibold uppercase tracking-wide">
                                {{ $currentPeriode->nama_fase }}
                            </span>
                        </div>
                        <h2 class="text-2xl font-bold">{{ $currentPeriode->nama_periode }}</h2>
                        <p class="text-white/80 mt-1">{{ $currentPeriode->kode_periode }} | Tahun: {{ $currentPeriode->tahun_anggaran }}</p>
                    </div>
                    <div class="text-right">
                        <div class="text-3xl font-bold">{{ $currentPeriode->days_remaining_formatted }}</div>
                        <div class="text-white/80 text-sm">tersisa</div>
                    </div>
                </div>
            </div>
        @else
            <div class="mb-6 bg-amber-50 border border-amber-200 rounded-2xl p-6">
                <div class="flex items-center">
                    <svg class="w-8 h-8 text-amber-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <div>
                        <h3 class="text-amber-900 font-semibold">Tidak ada periode aktif</h3>
                        <p class="text-amber-700 text-sm">Hubungi administrator untuk mengatur periode anggaran</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Filters -->
        <div class="bg-white rounded-2xl shadow-soft p-6 mb-6">
            <form method="GET" action="{{ route('periode-anggaran.index') }}" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-secondary-700 mb-2">Status</label>
                    <select name="status" class="w-full px-4 py-2 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <option value="">Semua Status</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Ditutup</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-secondary-700 mb-2">Tahun</label>
                    <input type="number" name="tahun" value="{{ request('tahun') }}" placeholder="2025" class="w-full px-4 py-2 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-secondary-700 mb-2">Fase</label>
                    <select name="fase" class="w-full px-4 py-2 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <option value="">Semua Fase</option>
                        <option value="perencangan" {{ request('fase') == 'perencangan' ? 'selected' : '' }}>Perencanaan</option>
                        <option value="penggunaan" {{ request('fase') == 'penggunaan' ? 'selected' : '' }}>Penggunaan</option>
                        <option value="closed" {{ request('fase') == 'closed' ? 'selected' : '' }}>Ditutup</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-all duration-200">
                        Filter
                    </button>
                    <a href="{{ route('periode-anggaran.index') }}" class="px-6 py-2 bg-secondary-100 text-secondary-700 rounded-xl hover:bg-secondary-200 transition-all duration-200 ml-2">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Periode List -->
        <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-secondary-50 border-b border-secondary-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Kode</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Nama Periode</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Tahun</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Fase</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Progress</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-secondary-600 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-secondary-100">
                        @forelse($periodes as $periode)
                            <tr class="hover:bg-secondary-50 transition-colors duration-150">
                                <td class="px-6 py-4">
                                    <span class="font-mono text-sm font-semibold text-primary-600">{{ $periode->kode_periode }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-secondary-900">{{ $periode->nama_periode }}</div>
                                    @if($periode->deskripsi)
                                        <div class="text-sm text-secondary-500 truncate max-w-xs">{{ $periode->deskripsi }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-secondary-700">{{ $periode->tahun_anggaran }}</td>
                                <td class="px-6 py-4">
                                    @if($periode->fase === 'perencangan')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                            Perencanaan
                                        </span>
                                    @elseif($periode->fase === 'penggunaan')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                            Penggunaan
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-secondary-100 text-secondary-700">
                                            Ditutup
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($periode->status === 'active')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                                            Aktif
                                        </span>
                                    @elseif($periode->status === 'draft')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                            Draft
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-secondary-100 text-secondary-700">
                                            Ditutup
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-secondary-600">
                                    <div class="text-xs">{{ \Carbon\Carbon::parse($periode->tanggal_mulai_perencanaan_anggaran)->format('M Y') }} - {{ \Carbon\Carbon::parse($periode->tanggal_selesai_penggunaan_anggaran)->format('M Y') }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="w-full bg-secondary-200 rounded-full h-2">
                                        <div class="bg-primary-600 h-2 rounded-full" style="width: {{ $periode->progress_percentage }}%"></div>
                                    </div>
                                    <div class="text-xs text-secondary-500 mt-1">{{ number_format($periode->progress_percentage, 0) }}%</div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('periode-anggaran.show', $periode) }}" class="p-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg" title="Lihat">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        @if(auth()->user()->hasAnyRole(['direktur_keuangan', 'staff_keuangan']) && $periode->status === 'draft')
                                            <a href="{{ route('periode-anggaran.edit', $periode) }}" class="p-2 text-gray-600 hover:text-amber-600 hover:bg-amber-50 rounded-lg" title="Edit">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                            <form method="POST" action="{{ route('periode-anggaran.destroy', $periode) }}" class="inline" onsubmit="return confirm('Yakin ingin menghapus periode ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-lg" title="Hapus">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                        </svg>
                                        <p class="text-gray-500">Belum ada periode anggaran</p>
                                        @if(auth()->user()->hasAnyRole(['direktur_keuangan', 'staff_keuangan']))
                                            <a href="{{ route('periode-anggaran.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                                Buat Periode Baru
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($periodes->hasPages())
                <div class="bg-secondary-50 px-6 py-4 border-t border-secondary-200">
                    {{ $periodes->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
