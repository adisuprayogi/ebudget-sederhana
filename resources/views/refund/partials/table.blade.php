<div class="overflow-x-auto">
    <table class="w-full">
        <thead class="bg-secondary-50 border-b border-secondary-200">
            <tr>
                <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Tanggal</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Jenis</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Alasan</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Referensi</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Jumlah Refund</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Status</th>
                <th class="px-6 py-4 text-right text-xs font-semibold text-secondary-600 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-secondary-100">
            @forelse($refunds ?? [] as $refund)
                <tr class="hover:bg-secondary-50 transition-colors duration-150">
                    <td class="px-6 py-4 text-sm text-secondary-600">
                        {{ \Carbon\Carbon::parse($refund->tanggal_refund)->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4">
                        @if($refund->pencairan_dana_id)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                Pencairan
                            </span>
                        @elseif($refund->pengajuan_dana_id)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-700">
                                Pengajuan
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                                -
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-medium text-secondary-900">{{ $refund->alasan_refund }}</div>
                        @if($refund->keterangan_tambahan)
                            <div class="text-sm text-secondary-500 truncate max-w-xs">{{ $refund->keterangan_tambahan }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="font-mono text-sm font-semibold text-primary-600">{{ $refund->nomor_refund }}</span>
                        @if($refund->pencairanDana)
                            <div class="text-xs text-secondary-500 mt-1">Ref: {{ $refund->pencairanDana->nomor_pencairan ?? '-' }}</div>
                        @elseif($refund->pengajuanDana)
                            <div class="text-xs text-secondary-500 mt-1">Ref: {{ $refund->pengajuanDana->nomor_pengajuan ?? '-' }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="font-semibold text-secondary-900">{{ formatRupiah($refund->jumlah_refund) }}</span>
                    </td>
                    <td class="px-6 py-4">
                        @if($refund->status === 'draft')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                                Draft
                            </span>
                        @elseif($refund->status === 'menunggu_approval')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                Menunggu Approval
                            </span>
                        @elseif($refund->status === 'approved')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                Disetujui
                            </span>
                        @elseif($refund->status === 'processed')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-teal-100 text-teal-700">
                                Selesai
                            </span>
                        @elseif($refund->status === 'rejected')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                Ditolak
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end space-x-2">
                            <a href="{{ route('refund.show', $refund) }}" class="p-2 text-secondary-600 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors" title="Lihat">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>
                            @if(in_array($refund->status, ['draft', 'rejected']) && auth()->user()->can('update', $refund))
                                <a href="{{ route('refund.edit', $refund) }}" class="p-2 text-secondary-600 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Edit">
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                            </svg>
                            <p class="text-secondary-500">Belum ada refund</p>
                            @if(auth()->user()->hasAnyRole(['direktur_keuangan', 'direktur_utama', 'kepala_divisi', 'staff_keuangan']))
                                <a href="{{ route('refund.select-lpj') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-all duration-200">
                                    Buat Refund Baru
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
@if(isset($refunds) && $refunds->hasPages())
    <div class="bg-secondary-50 px-6 py-4 border-t border-secondary-200 flex items-center justify-between">
        <p class="text-sm text-secondary-600">
            Menampilkan {{ $refunds->firstItem() }} sampai {{ $refunds->lastItem() }} dari {{ $refunds->total() }} Refund
        </p>
        {{ $refunds->appends(request()->except('page'))->links() }}
    </div>
@endif
