<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Perencanaan Penerimaan</h1>
                <p class="text-gray-600 mt-1">Kelola perencanaan penerimaan dana untuk periode anggaran</p>
            </div>
            <a href="{{ route('perencanaan-penerimaan.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Buat Perencanaan Baru
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm text-gray-500 mb-1">Total Estimasi</div>
                        <div class="text-2xl font-bold text-gray-900">{{ formatRupiah($summary['total_estimasi'] ?? 0) }}</div>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm text-gray-500 mb-1">Total Realisasi</div>
                        <div class="text-2xl font-bold text-amber-600">{{ formatRupiah($summary['total_realisasi'] ?? 0) }}</div>
                    </div>
                    <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm text-gray-500 mb-1">Sisa Estimasi</div>
                        <div class="text-2xl font-bold text-green-600">{{ formatRupiah($summary['sisa'] ?? 0) }}</div>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <form method="GET" action="{{ route('perencanaan-penerimaan.index') }}" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[180px]">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Periode Anggaran</label>
                    <select name="periode_anggaran_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @foreach($filterOptions['periodeAnggarans'] ?? [] as $periode)
                            <option value="{{ $periode->id }}" {{ ($filters['periode_anggaran_id'] ?? null) == $periode->id ? 'selected' : '' }}>
                                {{ $periode->nama_periode }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1 min-w-[180px]">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sumber Dana</label>
                    <select name="sumber_dana_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Sumber</option>
                        @foreach($filterOptions['sumberDanas'] ?? [] as $sumber)
                            <option value="{{ $sumber->id }}" {{ request('sumber_dana_id') == $sumber->id ? 'selected' : '' }}>
                                {{ $sumber->nama_sumber }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cari</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Uraian atau kode rekening..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 3.293A1 1 0 013 2.586V4z" />
                        </svg>
                        Filter
                    </button>
                </div>
                @if(request()->hasAny(['divisi_id', 'sumber_dana_id', 'search']))
                    <div class="flex items-end">
                        <a href="{{ route('perencanaan-penerimaan.index') }}" class="px-4 py-2 text-gray-600 hover:text-gray-900">
                            Reset
                        </a>
                    </div>
                @endif
            </form>
        </div>

        <!-- Perencanaan List -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kode Rekening</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Uraian</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Periode</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Sumber</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Estimasi</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Realisasi</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Persentase</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($perencanaanPenerimaans ?? [] as $perencanaan)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <span class="font-mono text-sm font-semibold text-blue-600">{{ $perencanaan->kode_rekening ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900">{{ $perencanaan->uraian }}</div>
                                    <div class="text-sm text-gray-500">{{ $perencanaan->divisi->nama_divisi ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-700">
                                        <div>{{ $perencanaan->periodeAnggaran->nama_periode ?? '-' }}</div>
                                        <div class="text-gray-500 text-xs">{{ $perencanaan->periodeAnggaran->tahun_anggaran ?? '-' }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                        {{ $perencanaan->sumberDana->nama_sumber ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-900">{{ formatRupiah($perencanaan->jumlah_estimasi) }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-amber-600 font-medium">{{ formatRupiah($perencanaan->realisasi) }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="w-full bg-gray-200 rounded-full h-2 mr-2" style="max-width: 80px;">
                                            <div class="h-2 rounded-full @if($perencanaan->persentase_realisasi >= 90) bg-red-500 @elseif($perencanaan->persentase_realisasi >= 70) bg-amber-500 @else bg-green-500 @endif" style="width: {{ min($perencanaan->persentase_realisasi, 100) }}%"></div>
                                        </div>
                                        <span class="text-sm text-gray-600">{{ number_format($perencanaan->persentase_realisasi, 1) }}%</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('perencanaan-penerimaan.show', $perencanaan) }}" class="p-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg" title="Lihat">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        <a href="{{ route('perencanaan-penerimaan.edit', $perencanaan) }}" class="p-2 text-gray-600 hover:text-amber-600 hover:bg-amber-50 rounded-lg" title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                        <p class="text-gray-500">Belum ada perencanaan penerimaan</p>
                                        <a href="{{ route('perencanaan-penerimaan.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                            Buat Perencanaan Baru
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if(isset($perencanaanPenerimaans) && $perencanaanPenerimaans->hasPages())
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    {{ $perencanaanPenerimaans->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
