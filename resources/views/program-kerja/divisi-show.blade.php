<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <nav class="flex text-sm text-secondary-500 mb-2">
                    <a href="{{ route('program-kerja.index') }}" class="hover:text-primary-600">Program Kerja</a>
                    <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <span class="text-secondary-900">{{ $divisi->nama_divisi }}</span>
                </nav>
                <h1 class="text-2xl font-bold text-secondary-900">Program Kerja - {{ $divisi->nama_divisi }}</h1>
                <p class="text-secondary-600 mt-1">Periode: {{ $activePeriode->nama_periode }}</p>
            </div>
            @if(auth()->user()->hasAnyRole(['superadmin', 'direktur_utama', 'kepala_divisi']))
                <a href="{{ route('program-kerja.create', $divisi) }}" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-all duration-200 shadow-soft hover:shadow-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Program
                </a>
            @endif
        </div>
    </x-slot>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-soft p-6">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm text-secondary-500 mb-1">Total Program</div>
                    <div class="text-2xl font-bold text-secondary-900">{{ $statistics['total_program'] }}</div>
                </div>
                <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-soft p-6">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm text-secondary-500 mb-1">Total Pagu</div>
                    <div class="text-lg font-bold text-primary-600">{{ formatRupiah($statistics['total_pagu']) }}</div>
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
                    <div class="text-sm text-secondary-500 mb-1">Sub Program</div>
                    <div class="text-2xl font-bold text-indigo-600">{{ $statistics['total_sub_program'] }}</div>
                </div>
                <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-soft p-6">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm text-secondary-500 mb-1">Periode</div>
                    <div class="text-sm font-semibold text-secondary-900">{{ $activePeriode->nama_periode }}</div>
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

    <!-- Filters -->
    <div class="bg-white rounded-2xl shadow-soft p-6 mb-6">
        <form method="GET" action="{{ route('program-kerja.divisi-show', $divisi) }}" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[180px]">
                <label class="block text-sm font-medium text-secondary-700 mb-2">Status</label>
                <select name="status" class="w-full px-4 py-2 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Non-Aktif</option>
                    <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Ditangguhkan</option>
                </select>
            </div>
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-secondary-700 mb-2">Cari</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Kode atau nama program..." class="w-full px-4 py-2 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            </div>
            <div class="flex items-end">
                <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 3.293A1 1 0 013 2.586V4z" />
                    </svg>
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Program Kerja List -->
    <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-secondary-50 border-b border-secondary-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Kode</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Nama Program</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Pagu Anggaran</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Sub Program</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-secondary-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-secondary-100">
                    @forelse($programKerjas as $program)
                        <tr class="hover:bg-secondary-50 transition-colors duration-150">
                            <td class="px-6 py-4">
                                <span class="font-mono text-sm font-semibold text-primary-600">{{ $program->kode_program }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-secondary-900">{{ $program->nama_program }}</div>
                                @if($program->target_output)
                                    <div class="text-sm text-secondary-500">Target: {{ $program->target_output }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-secondary-900">{{ formatRupiah($program->pagu_anggaran) }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-700">
                                    {{ $program->subPrograms->count() ?? 0 }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($program->status === 'active')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                        Aktif
                                    </span>
                                @elseif($program->status === 'inactive')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                                        Non-Aktif
                                    </span>
                                @elseif($program->status === 'suspended')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                        Ditangguhkan
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-secondary-100 text-secondary-700">
                                        {{ $program->status }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('program-kerja.show', [$divisi, $program]) }}" class="p-2 text-secondary-600 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors" title="Lihat">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    @if(auth()->user()->hasAnyRole(['superadmin', 'direktur_utama']))
                                        <a href="{{ route('program-kerja.edit', [$divisi, $program]) }}" class="p-2 text-secondary-600 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-16 h-16 text-secondary-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    <p class="text-secondary-500">Belum ada program kerja untuk divisi ini</p>
                                    @if(auth()->user()->hasAnyRole(['superadmin', 'direktur_utama']))
                                        <a href="{{ route('program-kerja.create', $divisi) }}" class="mt-4 inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-all duration-200">
                                            Tambah Program Baru
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
        @if($programKerjas->hasPages())
            <div class="bg-secondary-50 px-6 py-4 border-t border-secondary-200">
                {{ $programKerjas->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
