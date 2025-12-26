<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Pencatatan Penerimaan</h1>
                <p class="text-gray-600 mt-1">Kelola pencatatan penerimaan dana yang terealisasi</p>
            </div>
            <a href="{{ route('pencatatan-penerimaan.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Catat Penerimaan Baru
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm text-gray-500 mb-1">Total Pencatatan</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $pencatatanPenerimaans->total() ?? 0 }}</div>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm text-gray-500 mb-1">Total Diterima</div>
                        <div class="text-2xl font-bold text-green-600">{{ formatRupiah($summary['total_diterima'] ?? 0) }}</div>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <form method="GET" action="{{ route('pencatatan-penerimaan.index') }}" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[180px]">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Periode Anggaran</label>
                    <select name="periode_anggaran_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Semua Periode</option>
                        @foreach($filterOptions['periodeAnggarans'] ?? [] as $periode)
                            <option value="{{ $periode->id }}" {{ request('periode_anggaran_id') == $periode->id ? 'selected' : '' }}>
                                {{ $periode->nama_periode }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1 min-w-[180px]">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sumber Dana</label>
                    <select name="sumber_dana_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Semua Sumber Dana</option>
                        @foreach($filterOptions['sumberDanas'] ?? [] as $sumber)
                            <option value="{{ $sumber->id }}" {{ request('sumber_dana_id') == $sumber->id ? 'selected' : '' }}>
                                {{ $sumber->nama_sumber }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1 min-w-[180px]">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Referensi Perencanaan</label>
                    <select name="perencanaan_penerimaan_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Semua Perencanaan</option>
                        @foreach($filterOptions['perencanaanPenerimaans'] ?? [] as $perencanaan)
                            <option value="{{ $perencanaan->id }}" {{ request('perencanaan_penerimaan_id') == $perencanaan->id ? 'selected' : '' }}>
                                {{ Str::limit($perencanaan->uraian, 40) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cari</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Uraian..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 3.293A1 1 0 013 2.586V4z" />
                        </svg>
                        Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Pencatatan List -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Tanggal</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Uraian</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Sumber Dana</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase">Jumlah</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($pencatatanPenerimaans ?? [] as $pencatatan)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($pencatatan->tanggal_penerimaan)->format('d/m/Y') }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900">{{ $pencatatan->uraian }}</div>
                                    @if($pencatatan->perencanaanPenerimaan)
                                        <div class="text-xs text-gray-500">Ref: {{ Str::limit($pencatatan->perencanaanPenerimaan->uraian, 50) }}</div>
                                    @endif
                                    @if($pencatatan->bukti_penerimaan)
                                        <a href="{{ asset('storage/' . $pencatatan->bukti_penerimaan) }}" target="_blank" class="text-xs text-blue-600 hover:underline">Lihat Bukti</a>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                        {{ $pencatatan->sumberDana->nama_sumber ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="font-semibold text-gray-900">{{ formatRupiah($pencatatan->jumlah_diterima) }}</div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('pencatatan-penerimaan.show', $pencatatan) }}" class="p-2 text-gray-600 hover:text-blue-600 rounded-lg hover:bg-blue-50" title="Lihat">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        <a href="{{ route('pencatatan-penerimaan.edit', $pencatatan) }}" class="p-2 text-gray-600 hover:text-amber-600 rounded-lg hover:bg-amber-50" title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <form method="POST" action="{{ route('pencatatan-penerimaan.destroy', $pencatatan) }}" onsubmit="return confirm('Yakin ingin menghapus pencatatan penerimaan ini?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-gray-600 hover:text-red-600 rounded-lg hover:bg-red-50" title="Hapus">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                        </svg>
                                        <p class="text-gray-500">Belum ada pencatatan penerimaan</p>
                                        <a href="{{ route('pencatatan-penerimaan.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                            Catat Penerimaan Baru
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if(isset($pencatatanPenerimaans) && $pencatatanPenerimaans->hasPages())
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    {{ $pencatatanPenerimaans->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
