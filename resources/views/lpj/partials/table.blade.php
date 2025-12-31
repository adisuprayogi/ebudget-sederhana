<div class="overflow-x-auto">
    <table class="w-full">
        <thead class="bg-secondary-50 border-b border-secondary-200">
            <tr>
                <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Nomor</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Judul</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Divisi</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Periode</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Status</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Tanggal</th>
                <th class="px-6 py-4 text-right text-xs font-semibold text-secondary-600 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-secondary-100">
            @forelse($lpjs ?? [] as $lpj)
                <tr class="hover:bg-secondary-50 transition-colors duration-150">
                    <td class="px-6 py-4">
                        <span class="font-mono text-sm font-semibold text-primary-600">{{ $lpj->nomor_lpj }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-medium text-secondary-900">{{ $lpj->judul_lpj }}</div>
                        @if($lpj->pencairanDana && $lpj->pencairanDana->pengajuanDana)
                            <div class="text-sm text-secondary-500">{{ $lpj->pencairanDana->pengajuanDana->judul_pengajuan ?? '-' }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($lpj->pencairanDana && $lpj->pencairanDana->pengajuanDana && $lpj->pencairanDana->pengajuanDana->divisi)
                            <span class="text-sm text-secondary-700">{{ $lpj->pencairanDana->pengajuanDana->divisi->nama_divisi }}</span>
                        @else
                            <span class="text-sm text-secondary-700">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($lpj->pencairanDana && $lpj->pencairanDana->pengajuanDana && $lpj->pencairanDana->pengajuanDana->periodeAnggaran)
                            <span class="text-sm text-secondary-700">{{ $lpj->pencairanDana->pengajuanDana->periodeAnggaran->nama_periode ?? '-' }}</span>
                        @else
                            <span class="text-sm text-secondary-700">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($lpj->status === 'draft')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                                Draft
                            </span>
                        @elseif($lpj->status === 'menunggu_verifikasi')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                Menunggu Verifikasi
                            </span>
                        @elseif($lpj->status === 'approved')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                Disetujui
                            </span>
                        @elseif($lpj->status === 'rejected')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                Ditolak
                            </span>
                        @elseif($lpj->status === 'revisi')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-700">
                                Perlu Revisi
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-secondary-600">
                        {{ \Carbon\Carbon::parse($lpj->tanggal_lpj)->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end space-x-2">
                            <a href="{{ route('lpj.show', $lpj) }}" class="p-2 text-secondary-600 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors" title="Lihat">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>
                            @if(in_array($lpj->status, ['draft', 'rejected', 'revisi']) && auth()->user()->can('update', $lpj))
                                <a href="{{ route('lpj.edit', $lpj) }}" class="p-2 text-secondary-600 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Edit">
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
                    <td colspan="7" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <svg class="w-16 h-16 text-secondary-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="text-secondary-500">Belum ada LPJ</p>
                            @if(auth()->user()->hasAnyRole(['kepala_divisi', 'staff_divisi']))
                                <a href="{{ route('lpj.select-pengajuan') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-all duration-200">
                                    Buat LPJ Baru
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
@if(isset($lpjs) && $lpjs->hasPages())
    <div class="bg-secondary-50 px-6 py-4 border-t border-secondary-200 flex items-center justify-between">
        <p class="text-sm text-secondary-600">
            Menampilkan {{ $lpjs->firstItem() }} sampai {{ $lpjs->lastItem() }} dari {{ $lpjs->total() }} LPJ
        </p>
        {{ $lpjs->appends(request()->except('page'))->links() }}
    </div>
@endif
