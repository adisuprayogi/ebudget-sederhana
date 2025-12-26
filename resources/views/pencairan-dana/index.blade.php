<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-secondary-900">Pencairan Dana</h1>
                <p class="text-secondary-600 mt-1">Kelola pencairan dana untuk pengajuan yang disetujui</p>
            </div>
            @if(auth()->user()->hasRole('staff_keuangan'))
                <a href="{{ route('pencairan-dana.create') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-all duration-200 shadow-soft hover:shadow-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Buat Pencairan Baru
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-8">
        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-2xl shadow-soft p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm text-secondary-500 mb-1">Total Pencairan</div>
                        <div class="text-2xl font-bold text-secondary-900">{{ $statistics['total_count'] ?? 0 }}</div>
                    </div>
                    <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-soft p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm text-secondary-500 mb-1">Menunggu Proses</div>
                        <div class="text-2xl font-bold text-amber-600">{{ $statistics['pending_count'] ?? 0 }}</div>
                    </div>
                    <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-soft p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm text-secondary-500 mb-1">Berhasil Dicairkan</div>
                        <div class="text-2xl font-bold text-green-600">{{ $statistics['completed_count'] ?? 0 }}</div>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-soft p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm text-secondary-500 mb-1">Total Nilai</div>
                        <div class="text-2xl font-bold text-primary-600">{{ formatRupiah($statistics['total_amount'] ?? 0) }}</div>
                    </div>
                    <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-2xl shadow-soft p-6 mb-6">
            <form method="GET" action="{{ route('pencairan-dana.index') }}" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-secondary-700 mb-2">Status</label>
                    <select name="status" class="w-full px-4 py-2 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu Proses</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Sedang Diproses</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Gagal</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-secondary-700 mb-2">Metode Pencairan</label>
                    <select name="metode_pencairan" class="w-full px-4 py-2 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <option value="">Semua Metode</option>
                        <option value="transfer" {{ request('metode_pencairan') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                        <option value="cash" {{ request('metode_pencairan') == 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="reimburse" {{ request('metode_pencairan') == 'reimburse' ? 'selected' : '' }}>Reimburse</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-secondary-700 mb-2">Cari</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nomor pencairan..." class="w-full px-4 py-2 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
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

        <!-- Pencairan List -->
        <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-secondary-50 border-b border-secondary-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Nomor</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Pengajuan</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Divisi</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Jumlah</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Metode</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-secondary-600 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-secondary-100">
                        @forelse($pencairans ?? [] as $pencairan)
                            <tr class="hover:bg-secondary-50 transition-colors duration-150">
                                <td class="px-6 py-4">
                                    <span class="font-mono text-sm font-semibold text-primary-600">{{ $pencairan->nomor_pencairan }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-secondary-900">{{ $pencairan->pengajuanDana->judul_pengajuan ?? '-' }}</div>
                                    <div class="text-sm text-secondary-500 font-mono">{{ $pencairan->pengajuanDana->nomor_pengajuan ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-secondary-700">{{ $pencairan->pengajuanDana->divisi->nama_divisi ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-secondary-900">{{ formatRupiah($pencairan->jumlah_pencairan) }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium
                                        @if($pencairan->metode_pencairan === 'transfer') bg-blue-100 text-blue-700
                                        @elseif($pencairan->metode_pencairan === 'cash') bg-green-100 text-green-700
                                        @else bg-purple-100 text-purple-700 @endif">
                                        {{ ucfirst($pencairan->metode_pencairan) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($pencairan->status === 'pending')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                            Menunggu
                                        </span>
                                    @elseif($pencairan->status === 'processing')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                            Diproses
                                        </span>
                                    @elseif($pencairan->status === 'completed')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                            Selesai
                                        </span>
                                    @elseif($pencairan->status === 'failed')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                            Gagal
                                        </span>
                                    @elseif($pencairan->status === 'cancelled')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                                            Batal
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-secondary-600">
                                    {{ \Carbon\Carbon::parse($pencairan->tanggal_pencairan)->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('pencairan-dana.show', $pencairan) }}" class="p-2 text-secondary-600 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors" title="Lihat">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        @if(in_array($pencairan->status, ['pending']) && auth()->user()->hasRole('staff_keuangan'))
                                            <a href="{{ route('pencairan-dana.edit', $pencairan) }}" class="p-2 text-secondary-600 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Edit">
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
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-16 h-16 text-secondary-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        <p class="text-secondary-500">Belum ada pencairan dana</p>
                                        @if(auth()->user()->hasRole('staff_keuangan'))
                                            <a href="{{ route('pencairan-dana.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-all duration-200">
                                                Buat Pencairan Baru
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
            @if(isset($pencairans) && $pencairans->hasPages())
                <div class="bg-secondary-50 px-6 py-4 border-t border-secondary-200">
                    {{ $pencairans->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
