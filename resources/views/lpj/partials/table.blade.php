<!-- Mobile Card View -->
<div class="md:hidden space-y-3">
    @forelse($lpjs ?? [] as $lpj)
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
            <!-- Header -->
            <div class="px-3 py-3 bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-100">
                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0 flex-1">
                        <span class="font-mono text-xs font-bold text-blue-600 block truncate">{{ $lpj->nomor_lpj }}</span>
                    </div>
                    @if($lpj->status === 'draft')
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 flex-shrink-0">
                            Draft
                        </span>
                    @elseif($lpj->status === 'menunggu_verifikasi')
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-700 flex-shrink-0">
                            Verifikasi
                        </span>
                    @elseif($lpj->status === 'approved')
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700 flex-shrink-0">
                            Disetujui
                        </span>
                    @elseif($lpj->status === 'rejected')
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700 flex-shrink-0">
                            Ditolak
                        </span>
                    @elseif($lpj->status === 'revisi')
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-orange-100 text-orange-700 flex-shrink-0">
                            Revisi
                        </span>
                    @endif
                </div>
            </div>

            <!-- Body -->
            <div class="p-3 space-y-3">
                <div>
                    <p class="text-sm font-semibold text-slate-900 line-clamp-2">{{ $lpj->judul_lpj }}</p>
                    @if($lpj->pencairanDana && $lpj->pencairanDana->pengajuanDana)
                        <p class="text-xs text-slate-500 mt-0.5 truncate">{{ $lpj->pencairanDana->pengajuanDana->judul_pengajuan ?? '-' }}</p>
                    @endif
                </div>

                <div class="grid grid-cols-2 gap-2 text-xs">
                    <div>
                        <span class="text-slate-500">Divisi:</span>
                        <span class="ml-1 font-medium text-slate-700 truncate">
                            @if($lpj->pencairanDana && $lpj->pencairanDana->pengajuanDana && $lpj->pencairanDana->pengajuanDana->divisi)
                                {{ $lpj->pencairanDana->pengajuanDana->divisi->nama_divisi }}
                            @else
                                -
                            @endif
                        </span>
                    </div>
                    <div>
                        <span class="text-slate-500">Tanggal:</span>
                        <span class="ml-1 font-medium text-slate-700">{{ \Carbon\Carbon::parse($lpj->tanggal_lpj)->format('d/m/Y') }}</span>
                    </div>
                </div>

                @if($lpj->pencairanDana && $lpj->pencairanDana->pengajuanDana && $lpj->pencairanDana->pengajuanDana->periodeAnggaran)
                    <div class="text-xs">
                        <span class="text-slate-500">Periode:</span>
                        <span class="ml-1 font-medium text-slate-700 truncate block">{{ $lpj->pencairanDana->pengajuanDana->periodeAnggaran->nama_periode ?? '-' }}</span>
                        @if($lpj->status === 'approved' && $lpj->sisa_dana > 0)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-700 mt-1">
                                Sisa: {{ formatRupiah($lpj->sisa_dana) }}
                            </span>
                        @endif
                    </div>
                @endif

                <div class="flex items-center justify-end pt-2 border-t border-slate-100 gap-1.5">
                    <a href="{{ route('lpj.show', $lpj) }}" class="inline-flex items-center gap-1.5 px-3 py-2 text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors text-xs font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <span class="hidden sm:inline">Lihat</span>
                    </a>
                    @if(in_array($lpj->status, ['draft', 'rejected', 'revisi']) && auth()->user()->can('update', $lpj))
                        <a href="{{ route('lpj.edit', $lpj) }}" class="inline-flex items-center gap-1.5 px-3 py-2 text-amber-600 bg-amber-50 rounded-lg hover:bg-amber-100 transition-colors text-xs font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            <span class="hidden sm:inline">Edit</span>
                        </a>
                    @endif
                    @if($lpj->status === 'approved' && $lpj->sisa_dana > 0 && !$lpj->refunds()->where('status', '!=', 'rejected')->exists())
                        <a href="{{ route('refund.create') }}?lpj_id={{ $lpj->id }}" class="inline-flex items-center gap-1.5 px-3 py-2 text-violet-600 bg-violet-50 rounded-lg hover:bg-violet-100 transition-colors text-xs font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6 6" />
                            </svg>
                            <span class="hidden sm:inline">Refund</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-xl border border-slate-100 p-8 text-center">
            <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <p class="text-slate-500">Belum ada LPJ</p>
            @if(auth()->user()->hasAnyRole(['kepala_divisi', 'staff_divisi']))
                <a href="{{ route('lpj.select-pengajuan') }}" class="mt-3 inline-flex items-center px-4 py-2 bg-amber-600 text-white text-sm font-medium rounded-lg hover:bg-amber-700 transition-colors">
                    Buat LPJ
                </a>
            @endif
        </div>
    @endforelse
</div>

<!-- Desktop Table View -->
<div class="hidden md:block overflow-x-auto">
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
                        @if($lpj->status === 'approved' && $lpj->sisa_dana > 0)
                            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-700">
                                Sisa: {{ formatRupiah($lpj->sisa_dana) }}
                            </span>
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
                            @if($lpj->status === 'approved' && $lpj->sisa_dana > 0 && !$lpj->refunds()->where('status', '!=', 'rejected')->exists())
                                <a href="{{ route('refund.create') }}?lpj_id={{ $lpj->id }}" class="p-2 text-secondary-600 hover:text-violet-600 hover:bg-violet-50 rounded-lg transition-colors" title="Buat Refund">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6 6" />
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
    <div class="bg-secondary-50 px-4 md:px-6 py-4 border-t border-secondary-200">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <p class="text-xs md:text-sm text-secondary-600 text-center md:text-left">
                <span class="hidden md:inline">Menampilkan {{ $lpjs->firstItem() }} sampai {{ $lpjs->lastItem() }} dari {{ $lpjs->total() }} LPJ</span>
                <span class="md:hidden">{{ $lpjs->total() }} LPJ</span>
            </p>
            {{ $lpjs->appends(request()->except('page'))->links() }}
        </div>
    </div>
@endif
