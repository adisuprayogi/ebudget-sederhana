<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <nav class="flex text-xs text-gray-500 mb-2">
                    <a href="{{ route('program-kerja.index') }}" class="hover:text-blue-600">Program Kerja</a>
                    <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <span class="text-gray-900">{{ $divisi->nama_divisi }}</span>
                </nav>
                <h1 class="text-xl font-semibold text-gray-900">Program Kerja - {{ $divisi->nama_divisi }}</h1>
                <p class="text-xs text-gray-500 mt-0.5">Periode: {{ $activePeriode->nama_periode }}</p>
            </div>
            @if(auth()->user()->hasAnyRole(['superadmin', 'direktur_utama', 'kepala_divisi']))
                <a href="{{ route('program-kerja.create', $divisi) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Program
                </a>
            @endif
        </div>
    </x-slot>

    <div class="space-y-4">
        <!-- Quick Stats -->
        <div class="grid grid-cols-4 gap-3">
            <div class="bg-white rounded-xl border border-blue-100 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xl font-semibold text-gray-900">{{ $statistics['total_program'] }}</p>
                        <p class="text-xs text-gray-500">Total Program</p>
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
                        <p class="text-sm font-semibold text-gray-900">{{ formatRupiah($statistics['total_pagu']) }}</p>
                        <p class="text-xs text-gray-500">Total Pagu</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-blue-100 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-violet-50 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xl font-semibold text-gray-900">{{ $statistics['total_sub_program'] }}</p>
                        <p class="text-xs text-gray-500">Sub Program</p>
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
                        <p class="text-sm font-semibold text-gray-900">{{ $activePeriode->nama_periode }}</p>
                        <p class="text-xs text-gray-500">{{ $activePeriode->tanggal_mulai_perencanaan_anggaran->format('M Y') }} - {{ $activePeriode->tanggal_selesai_perencanaan_anggaran->format('M Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl border border-blue-100 p-4">
            <form method="GET" action="{{ route('program-kerja.divisi-show', $divisi) }}" class="flex flex-wrap gap-3">
                <div class="flex-1 min-w-[160px]">
                    <select name="status" class="w-full px-3 py-2 border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Non-Aktif</option>
                        <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Ditangguhkan</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Kode atau nama program..." class="w-full px-3 py-2 border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 3.293A1 1 0 013 2.586V4z" />
                    </svg>
                    Filter
                </button>
            </form>
        </div>

        <!-- Program Kerja List -->
        <div class="bg-white rounded-xl border border-blue-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-blue-50 border-b border-blue-100">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Kode</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Nama Program</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Pagu Anggaran</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Sub Program</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-blue-700 uppercase">Status</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-blue-700 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-blue-50">
                        @forelse($programKerjas as $program)
                            <tr class="hover:bg-blue-50/50 transition-colors">
                                <td class="px-4 py-3">
                                    <span class="font-mono text-sm font-semibold text-blue-600">{{ $program->kode_program }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <p class="font-medium text-gray-900 text-sm">{{ $program->nama_program }}</p>
                                    @if($program->target_output)
                                        <p class="text-xs text-gray-500">Target: {{ $program->target_output }}</p>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <p class="font-semibold text-gray-900 text-sm">{{ formatRupiah($program->calculated_pagu ?? 0) }}</p>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-violet-100 text-violet-700">
                                        {{ $program->subPrograms->count() ?? 0 }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    @if($program->status === 'active')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                                            Aktif
                                        </span>
                                    @elseif($program->status === 'inactive')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                            Non-Aktif
                                        </span>
                                    @elseif($program->status === 'suspended')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-700">
                                            Ditangguhkan
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                            {{ $program->status }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('program-kerja.show', [$divisi, $program]) }}" class="p-1.5 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Lihat">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        @if(auth()->user()->hasAnyRole(['superadmin', 'direktur_utama', 'kepala_divisi']))
                                            @php
                                                $canEdit = true;
                                                $periode = $program->periodeAnggaran;
                                                if($periode && $periode->fase !== 'perencanan') {
                                                    $canEdit = false;
                                                }
                                            @endphp
                                            @if($canEdit)
                                                <a href="{{ route('program-kerja.edit', [$divisi, $program]) }}" class="p-1.5 text-gray-600 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Edit">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                        <p class="text-gray-500 text-sm">Belum ada program kerja untuk divisi ini</p>
                                        @if(auth()->user()->hasAnyRole(['superadmin', 'direktur_utama']))
                                            <a href="{{ route('program-kerja.create', $divisi) }}" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
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
                <div class="bg-gray-50 px-4 py-3 border-t border-blue-100">
                    {{ $programKerjas->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
