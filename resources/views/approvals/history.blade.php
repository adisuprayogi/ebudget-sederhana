<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('approvals.index') }}" class="p-2 text-secondary-600 hover:text-secondary-900 hover:bg-secondary-100 rounded-lg transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-secondary-900">Riwayat Approval</h1>
                <p class="text-secondary-600 mt-1">Daftar persetujuan yang sudah diproses</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <!-- Filters -->
        <div class="bg-white rounded-2xl shadow-soft p-6 mb-6">
            <form method="GET" action="{{ route('approvals.history') }}" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-secondary-700 mb-2">Status</label>
                    <select name="status" class="w-full px-4 py-2 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <option value="">Semua Status</option>
                        <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-secondary-700 mb-2">Level</label>
                    <select name="level" class="w-full px-4 py-2 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <option value="">Semua Level</option>
                        @foreach($filterOptions['levels'] ?? [] as $level)
                            <option value="{{ $level }}" {{ request('level') == $level ? 'selected' : '' }}>Level {{ $level }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-secondary-700 mb-2">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}" class="w-full px-4 py-2 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-secondary-700 mb-2">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}" class="w-full px-4 py-2 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
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

        <!-- Approval History List -->
        <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-secondary-50 border-b border-secondary-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Nomor</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Judul</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Divisi</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Level</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Tanggal Proses</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-secondary-100">
                        @forelse($approvals ?? [] as $approval)
                            <tr class="hover:bg-secondary-50 transition-colors duration-150">
                                <td class="px-6 py-4">
                                    <span class="font-mono text-sm font-semibold text-primary-600">{{ $approval->pengajuanDana->nomor_pengajuan }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-secondary-900">{{ $approval->pengajuanDana->judul_pengajuan }}</div>
                                    <div class="text-sm text-secondary-500">{{ formatRupiah($approval->pengajuanDana->total_pengajuan) }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-secondary-700">{{ $approval->pengajuanDana->divisi->nama_divisi ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                        Level {{ $approval->level }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($approval->status === 'disetujui')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                            Disetujui
                                        </span>
                                    @elseif($approval->status === 'ditolak')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                            Ditolak
                                        </span>
                                    @endif
                                    @if($approval->notes)
                                    <p class="text-xs text-secondary-500 mt-1">{{ $approval->notes }}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-secondary-600">
                                    @if($approval->approved_at)
                                        {{ \Carbon\Carbon::parse($approval->approved_at)->format('d/m/Y H:i') }}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-16 h-16 text-secondary-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <p class="text-secondary-500">Belum ada riwayat approval</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if(isset($approvals) && $approvals->hasPages())
                <div class="bg-secondary-50 px-6 py-4 border-t border-secondary-200">
                    {{ $approvals->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
