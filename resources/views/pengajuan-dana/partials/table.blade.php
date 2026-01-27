<!-- Mobile Card View -->
<div class="md:hidden space-y-3">
    @forelse($pengajuans ?? [] as $pengajuan)
        @php
            $jenisLabels = [
                'kegiatan' => 'Kegiatan',
                'pengadaan' => 'Pengadaan',
                'pembayaran' => 'Pembayaran',
                'honorarium' => 'Honorarium',
                'sewa' => 'Sewa',
                'konsumsi' => 'Konsumsi',
                'konsumi' => 'Konsumi',
                'reimbursement' => 'Reimbursement',
                'lainnya' => 'Lainnya',
            ];
            $jenisColors = [
                'kegiatan' => 'bg-blue-100 text-blue-700',
                'pengadaan' => 'bg-green-100 text-green-700',
                'pembayaran' => 'bg-yellow-100 text-yellow-700',
                'honorarium' => 'bg-purple-100 text-purple-700',
                'sewa' => 'bg-orange-100 text-orange-700',
                'konsumsi' => 'bg-pink-100 text-pink-700',
                'konsumi' => 'bg-pink-100 text-pink-700',
                'reimbursement' => 'bg-teal-100 text-teal-700',
                'lainnya' => 'bg-gray-100 text-gray-700',
            ];
            $jenis = $pengajuan->jenis_pengajuan;
            $jenisLabel = $jenisLabels[$jenis] ?? ucfirst($jenis);
            $jenisColorClass = $jenisColors[$jenis] ?? 'bg-gray-100 text-gray-700';
        @endphp
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
            <!-- Header -->
            <div class="px-3 py-3 bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-100">
                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0 flex-1">
                        <span class="font-mono text-xs font-bold text-blue-600 block truncate">{{ $pengajuan->nomor_pengajuan }}</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ $jenisColorClass }} mt-1">
                            {{ $jenisLabel }}
                        </span>
                    </div>
                    @if($pengajuan->status === 'draft')
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 flex-shrink-0">
                            Draft
                        </span>
                    @elseif($pengajuan->status === 'menunggu_approval')
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-700 flex-shrink-0">
                            Menunggu
                        </span>
                    @elseif($pengajuan->status === 'disetujui' || $pengajuan->status === 'approved')
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700 flex-shrink-0">
                            Disetujui
                        </span>
                    @elseif($pengajuan->status === 'menunggu_pencairan')
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-700 flex-shrink-0">
                            Pencairan
                        </span>
                    @elseif($pengajuan->status === 'cair' || $pengajuan->status === 'dicairkan')
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-purple-100 text-purple-700 flex-shrink-0">
                            Cair
                        </span>
                    @elseif($pengajuan->status === 'menunggu_lpj')
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-700 flex-shrink-0">
                            LPJ
                        </span>
                    @elseif($pengajuan->status === 'lpj_submitted')
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-cyan-100 text-cyan-700 flex-shrink-0">
                            LPJ Sent
                        </span>
                    @elseif($pengajuan->status === 'lpj_ditolak')
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700 flex-shrink-0">
                            LPJ Tolak
                        </span>
                    @elseif($pengajuan->status === 'lpj_disetujui')
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-teal-100 text-teal-700 flex-shrink-0">
                            LPJ OK
                        </span>
                    @elseif($pengajuan->status === 'menunggu_refund')
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-orange-100 text-orange-700 flex-shrink-0">
                            Refund
                        </span>
                    @elseif($pengajuan->status === 'refund_ditolak')
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700 flex-shrink-0">
                            Ref Tolak
                        </span>
                    @elseif($pengajuan->status === 'selesai')
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700 flex-shrink-0">
                            Selesai
                        </span>
                    @elseif($pengajuan->status === 'ditolak' || $pengajuan->status === 'rejected')
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700 flex-shrink-0">
                            Ditolak
                        </span>
                    @elseif($pengajuan->status === 'cancelled')
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-700 flex-shrink-0">
                            Batal
                        </span>
                    @else
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 flex-shrink-0">
                            {{ ucfirst(str_replace('_', ' ', $pengajuan->status)) }}
                        </span>
                    @endif
                </div>
            </div>

            <!-- Body -->
            <div class="p-3 space-y-3">
                <div>
                    <p class="text-sm font-semibold text-slate-900 line-clamp-2">{{ $pengajuan->judul_pengajuan }}</p>
                    @if($pengajuan->programKerja)
                        <p class="text-xs text-slate-500 mt-0.5 truncate">{{ $pengajuan->programKerja->nama_program }}</p>
                    @endif
                </div>

                <div class="grid grid-cols-2 gap-2 text-xs">
                    <div>
                        <span class="text-slate-500">Divisi:</span>
                        <span class="ml-1 font-medium text-slate-700 truncate">{{ $pengajuan->divisi->nama_divisi ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-500">Tanggal:</span>
                        <span class="ml-1 font-medium text-slate-700">{{ \Carbon\Carbon::parse($pengajuan->tanggal_pengajuan)->format('d/m/Y') }}</span>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-2 border-t border-slate-100 gap-2">
                    <span class="text-base md:text-lg font-bold text-slate-900 truncate">{{ formatRupiah($pengajuan->total_pengajuan) }}</span>
                    <div class="flex items-center gap-1.5">
                        <a href="{{ route('pengajuan-dana.show', $pengajuan) }}" class="inline-flex items-center gap-1.5 px-3 py-2 text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors text-xs font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <span class="hidden sm:inline">Lihat</span>
                        </a>
                        @if(in_array($pengajuan->status, ['draft']) && auth()->user()->can('update', $pengajuan))
                            <a href="{{ route('pengajuan-dana.edit', $pengajuan) }}" class="inline-flex items-center gap-1.5 px-3 py-2 text-amber-600 bg-amber-50 rounded-lg hover:bg-amber-100 transition-colors text-xs font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                <span class="hidden sm:inline">Edit</span>
                            </a>
                        @endif
                        @if(in_array($pengajuan->status, ['draft', 'menunggu_approval']) && auth()->id() == $pengajuan->created_by)
                            <form method="POST" action="{{ route('pengajuan-dana.cancel', $pengajuan) }}" onsubmit="return confirm('Batalkan pengajuan?')" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-2 text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors text-xs font-medium">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    <span class="hidden sm:inline">Batal</span>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-xl border border-slate-100 p-8 text-center">
            <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <p class="text-slate-500">Belum ada pengajuan</p>
            @if(auth()->user()->hasAnyRole(['kepala_divisi', 'staff_divisi']))
                <a href="{{ route('pengajuan-dana.create') }}" class="mt-3 inline-flex items-center px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700 transition-colors">
                    Buat Pengajuan
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
                <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Total</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Status</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-secondary-600 uppercase tracking-wider">Tanggal</th>
                <th class="px-6 py-4 text-right text-xs font-semibold text-secondary-600 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-secondary-100">
            @forelse($pengajuans ?? [] as $pengajuan)
                @php
                    $jenisLabels = [
                        'kegiatan' => 'Kegiatan',
                        'pengadaan' => 'Pengadaan',
                        'pembayaran' => 'Pembayaran',
                        'honorarium' => 'Honorarium',
                        'sewa' => 'Sewa',
                        'konsumsi' => 'Konsumsi',
                        'konsumi' => 'Konsumi',
                        'reimbursement' => 'Reimbursement',
                        'lainnya' => 'Lainnya',
                    ];
                    $jenisColors = [
                        'kegiatan' => 'bg-blue-100 text-blue-700',
                        'pengadaan' => 'bg-green-100 text-green-700',
                        'pembayaran' => 'bg-yellow-100 text-yellow-700',
                        'honorarium' => 'bg-purple-100 text-purple-700',
                        'sewa' => 'bg-orange-100 text-orange-700',
                        'konsumsi' => 'bg-pink-100 text-pink-700',
                        'konsumi' => 'bg-pink-100 text-pink-700',
                        'reimbursement' => 'bg-teal-100 text-teal-700',
                        'lainnya' => 'bg-gray-100 text-gray-700',
                    ];
                    $jenis = $pengajuan->jenis_pengajuan;
                    $jenisLabel = $jenisLabels[$jenis] ?? ucfirst($jenis);
                    $jenisColorClass = $jenisColors[$jenis] ?? 'bg-gray-100 text-gray-700';
                @endphp
                <tr class="hover:bg-secondary-50 transition-colors duration-150">
                    <td class="px-6 py-4">
                        <div class="flex flex-col">
                            <span class="font-mono text-sm font-semibold text-primary-600">{{ $pengajuan->nomor_pengajuan }}</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $jenisColorClass }} w-fit mt-1">
                                {{ $jenisLabel }}
                            </span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-secondary-900">{{ $pengajuan->judul_pengajuan }}</div>
                        @if($pengajuan->programKerja)
                            <div class="text-sm text-secondary-500">{{ $pengajuan->programKerja->nama_program }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm text-secondary-700">{{ $pengajuan->divisi->nama_divisi ?? '-' }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm text-secondary-900">{{ formatRupiah($pengajuan->total_pengajuan) }}</span>
                    </td>
                    <td class="px-6 py-4">
                        @if($pengajuan->status === 'draft')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                                Draft
                            </span>
                        @elseif($pengajuan->status === 'menunggu_approval')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                Menunggu Approval
                            </span>
                        @elseif($pengajuan->status === 'menunggu_pencairan')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                Menunggu Pencairan
                            </span>
                        @elseif($pengajuan->status === 'cair')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-700">
                                Dana Dicairkan
                            </span>
                        @elseif($pengajuan->status === 'menunggu_lpj')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-700">
                                Menunggu LPJ
                            </span>
                        @elseif($pengajuan->status === 'lpj_submitted')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-cyan-100 text-cyan-700">
                                LPJ Disubmit
                            </span>
                        @elseif($pengajuan->status === 'lpj_ditolak')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                LPJ Ditolak
                            </span>
                        @elseif($pengajuan->status === 'lpj_disetujui')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-teal-100 text-teal-700">
                                LPJ Disetujui
                            </span>
                        @elseif($pengajuan->status === 'menunggu_refund')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-700">
                                Menunggu Refund
                            </span>
                        @elseif($pengajuan->status === 'refund_ditolak')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                Refund Ditolak
                            </span>
                        @elseif($pengajuan->status === 'selesai')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                Selesai
                            </span>
                        @elseif($pengajuan->status === 'disetujui' || $pengajuan->status === 'approved')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                Disetujui
                            </span>
                        @elseif($pengajuan->status === 'ditolak' || $pengajuan->status === 'rejected')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                Ditolak
                            </span>
                        @elseif($pengajuan->status === 'cancelled')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                                Dibatalkan
                            </span>
                        @elseif($pengajuan->status === 'dicairkan')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                Dicairkan
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-secondary-100 text-secondary-700">
                                {{ ucfirst(str_replace('_', ' ', $pengajuan->status)) }}
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-secondary-600">
                        {{ \Carbon\Carbon::parse($pengajuan->tanggal_pengajuan)->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end space-x-2">
                            <a href="{{ route('pengajuan-dana.show', $pengajuan) }}" class="p-2 text-secondary-600 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors" title="Lihat">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>
                            @if(in_array($pengajuan->status, ['draft']) && auth()->user()->can('update', $pengajuan))
                                <a href="{{ route('pengajuan-dana.edit', $pengajuan) }}" class="p-2 text-secondary-600 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                            @endif
                            {{-- Batalkan button for draft and menunggu_approval status - only creator --}}
                            @if(in_array($pengajuan->status, ['draft', 'menunggu_approval']) && auth()->id() == $pengajuan->created_by)
                                <form method="POST" action="{{ route('pengajuan-dana.cancel', $pengajuan) }}" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pengajuan ini?')" class="inline">
                                    @csrf
                                    <button type="submit" class="p-2 text-secondary-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Batalkan">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </form>
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
                            <p class="text-secondary-500">Belum ada pengajuan dana</p>
                            @if(auth()->user()->hasAnyRole(['kepala_divisi', 'staff_divisi']))
                                <a href="{{ route('pengajuan-dana.select-jenis') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-all duration-200">
                                    Buat Pengajuan Baru
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
@if(isset($pengajuans) && $pengajuans->hasPages())
    <div class="bg-secondary-50 px-4 md:px-6 py-4 border-t border-secondary-200">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <p class="text-xs md:text-sm text-secondary-600 text-center md:text-left">
                <span class="hidden md:inline">Menampilkan {{ $pengajuans->firstItem() }} sampai {{ $pengajuans->lastItem() }} dari {{ $pengajuans->total() }} pengajuan</span>
                <span class="md:hidden">{{ $pengajuans->total() }} pengajuan</span>
            </p>
            {{ $pengajuans->appends(request()->except('page'))->links() }}
        </div>
    </div>
@endif
