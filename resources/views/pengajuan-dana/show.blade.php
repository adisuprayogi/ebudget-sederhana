<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('pengajuan-dana.index') }}" class="p-2 text-secondary-600 hover:text-secondary-900 hover:bg-secondary-100 rounded-lg transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div class="flex-1">
                <div class="flex items-center space-x-3">
                    <h1 class="text-2xl font-bold text-secondary-900">{{ $pengajuan->nomor_pengajuan }}</h1>
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
                            Dana Sudah Dicairkan
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
                    @elseif($pengajuan->status === 'dicairkan')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                            Dicairkan
                        </span>
                    @elseif($pengajuan->status === 'cancelled')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                            Dibatalkan
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                            {{ ucfirst(str_replace('_', ' ', $pengajuan->status)) }}
                        </span>
                    @endif
                </div>
                <p class="text-secondary-600 mt-1">{{ $pengajuan->judul_pengajuan }}</p>
            </div>
            @if(in_array($pengajuan->status, ['draft', 'ditolak', 'rejected']) && auth()->user()->can('update', $pengajuan))
                <a href="{{ route('pengajuan-dana.edit', $pengajuan) }}" class="inline-flex items-center px-4 py-2 bg-amber-500 text-white rounded-xl hover:bg-amber-600 transition-all duration-200 shadow-soft hover:shadow-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </a>
            @endif

            {{-- Batalkan button for draft and menunggu_approval status --}}
            @if(in_array($pengajuan->status, ['draft', 'menunggu_approval']) && auth()->id() == $pengajuan->created_by)
                <form method="POST" action="{{ route('pengajuan-dana.cancel', $pengajuan) }}" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pengajuan ini?')" class="inline">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-500 text-white rounded-xl hover:bg-red-600 transition-all duration-200 shadow-soft hover:shadow-medium">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Batalkan
                    </button>
                </form>
            @endif
        </div>
    </x-slot>

    <div class="py-8">
        <!-- Alert Messages -->
        @if($pengajuan->status === 'draft')
            <div class="mb-6 bg-amber-50 border border-amber-200 rounded-xl p-4 flex items-start">
                <svg class="w-5 h-5 text-amber-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="text-amber-700 text-sm">
                    Pengajuan ini masih dalam status <strong>draft</strong>. Silakan lengkapi data dan submit untuk meminta approval.
                </div>
            </div>
        @elseif($pengajuan->status === 'rejected' || $pengajuan->status === 'ditolak')
            <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4 flex items-start">
                <svg class="w-5 h-5 text-red-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="text-red-700 text-sm">
                    Pengajuan ini <strong>ditolak</strong>. Alasan: {{ $pengajuan->approvals->where('status', 'ditolak')->first()?->notes ?? 'Tidak ada' }}
                </div>
            </div>
        @elseif($pengajuan->status === 'cancelled')
            <div class="mb-6 bg-gray-50 border border-gray-200 rounded-xl p-4 flex items-start">
                <svg class="w-5 h-5 text-gray-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                </svg>
                <div class="text-gray-700 text-sm">
                    Pengajuan ini telah <strong>dibatalkan</strong>.
                </div>
            </div>
        @elseif($pengajuan->status === 'approved' || $pengajuan->status === 'disetujui')
            <div class="mb-6 bg-green-50 border border-green-200 rounded-xl p-4 flex items-start">
                <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="text-green-700 text-sm">
                    Pengajuan ini telah <strong>disetujui</strong>. Menunggu proses pencairan dana.
                </div>
            </div>
        @elseif($pengajuan->status === 'menunggu_pencairan')
            <div class="mb-6 bg-blue-50 border border-blue-200 rounded-xl p-4 flex items-start">
                <svg class="w-5 h-5 text-blue-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="text-blue-700 text-sm">
                    Pengajuan ini telah <strong>disetujui</strong>. Menunggu staff keuangan untuk membuat pencairan dana.
                </div>
            </div>
        @elseif($pengajuan->status === 'cair')
            <div class="mb-6 bg-purple-50 border border-purple-200 rounded-xl p-4 flex items-start">
                <svg class="w-5 h-5 text-purple-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="text-purple-700 text-sm">
                    Dana <strong>sudah dicairkan</strong>. Silakan verifikasi apakah Anda sudah menerima dana tersebut.
                </div>
            </div>
        @elseif($pengajuan->status === 'selesai')
            <div class="mb-6 bg-green-50 border border-green-200 rounded-xl p-4 flex items-start">
                <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="text-green-700 text-sm">
                    Pengajuan ini telah <strong>selesai</strong>. Dana telah diterima dan dikonfirmasi.
                </div>
            </div>
        @elseif($pengajuan->status === 'menunggu_lpj')
            <div class="mb-6 bg-indigo-50 border border-indigo-200 rounded-xl p-4 flex items-start">
                <svg class="w-5 h-5 text-indigo-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <div class="text-indigo-700 text-sm">
                    Dana telah <strong>dicairkan</strong>. Silakan submit <strong>Laporan Pertanggungjawaban (LPJ)</strong> untuk melanjutkan proses ini.
                </div>
            </div>
        @elseif($pengajuan->status === 'lpj_submitted')
            <div class="mb-6 bg-cyan-50 border border-cyan-200 rounded-xl p-4 flex items-start">
                <svg class="w-5 h-5 text-cyan-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="text-cyan-700 text-sm">
                    <strong>LPJ telah disubmit</strong>. Menunggu approval dari staff keuangan.
                </div>
            </div>
        @elseif($pengajuan->status === 'lpj_ditolak')
            <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4 flex items-start">
                <svg class="w-5 h-5 text-red-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="text-red-700 text-sm">
                    <strong>LPJ ditolak</strong>. Silakan perbaiki dan submit kembali.
                </div>
            </div>
        @elseif($pengajuan->status === 'lpj_disetujui')
            <div class="mb-6 bg-teal-50 border border-teal-200 rounded-xl p-4 flex items-start">
                <svg class="w-5 h-5 text-teal-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="text-teal-700 text-sm">
                    <strong>LPJ disetujui</strong>. Pengajuan sedang dalam proses finalisasi.
                </div>
            </div>
        @elseif($pengajuan->status === 'menunggu_refund')
            <div class="mb-6 bg-orange-50 border border-orange-200 rounded-xl p-4 flex items-start">
                <svg class="w-5 h-5 text-orange-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="text-orange-700 text-sm">
                    Menunggu proses <strong>refund</strong> dari staff keuangan.
                </div>
            </div>
        @elseif($pengajuan->status === 'refund_ditolak')
            <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4 flex items-start">
                <svg class="w-5 h-5 text-red-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="text-red-700 text-sm">
                    <strong>Refund ditolak</strong>. Silakan hubungi staff keuangan untuk informasi lebih lanjut.
                </div>
            </div>
        @endif

        {{-- Pencairan Card - shown when status has pencairan data --}}
        @php
            \Log::info('Pengajuan Show - Pencairan Check', [
                'pengajuan_id' => $pengajuan->id,
                'pengajuan_status' => $pengajuan->status,
                'has_pencairan' => $pengajuan->activePencairan !== null,
                'pencairan_id' => $pengajuan->activePencairan->id ?? null,
                'pencairan_status' => $pengajuan->activePencairan->status ?? null,
                'is_created_by' => auth()->id() === $pengajuan->created_by,
                'auth_id' => auth()->id(),
                'created_by' => $pengajuan->created_by,
            ]);
        @endphp
        @if(in_array($pengajuan->status, ['cair', 'selesai', 'menunggu_lpj', 'lpj_submitted', 'lpj_disetujui', 'menunggu_refund']) && $pengajuan->activePencairan)
            @php $pencairan = $pengajuan->activePencairan; @endphp
            @if($pengajuan->status === 'cair')
                <div class="mb-6 bg-purple-50 border-purple-200 border rounded-xl p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <svg class="w-10 h-10 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <div>
                                <div class="flex items-center space-x-2">
                                    <h3 class="text-base font-semibold text-purple-900">
                                        {{ $pencairan->nomor_pencairan }}
                                    </h3>
                                    <span class="text-sm text-purple-700">
                                        • {{ formatRupiah($pencairan->jumlah_pencairan) }}
                                    </span>
                                </div>
                                <p class="text-sm text-purple-600">
                                    Menunggu konfirmasi penerimaan dana
                                </p>
                            </div>
                        </div>
                        @if(auth()->id() === $pengajuan->created_by)
                        <a href="{{ route('pencairan-dana.show', $pencairan) }}" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-xl hover:bg-purple-700 transition-all duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Verifikasi Penerimaan
                        </a>
                        @else
                        <a href="{{ route('pencairan-dana.show', $pencairan) }}" class="inline-flex items-center px-4 py-2 bg-white border border-secondary-300 text-secondary-700 rounded-xl hover:bg-secondary-50 transition-all duration-200">
                            Lihat Detail
                        </a>
                        @endif
                    </div>
                </div>
            @else
                <div class="mb-6 bg-green-50 border border-green-200 border rounded-xl p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <div>
                                <div class="flex items-center space-x-2">
                                    <h3 class="text-base font-semibold text-green-900">
                                        {{ $pencairan->nomor_pencairan }}
                                    </h3>
                                    <span class="text-sm text-green-700">
                                        • {{ formatRupiah($pencairan->jumlah_pencairan) }}
                                    </span>
                                </div>
                                <p class="text-sm text-green-600">
                                    Dana sudah dicairkan
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('pencairan-dana.show', $pencairan) }}" class="inline-flex items-center px-4 py-2 bg-white border border-secondary-300 text-secondary-700 rounded-xl hover:bg-secondary-50 transition-all duration-200">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            @endif
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Informasi Dasar -->
                <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                    <div class="px-6 py-4 border-b border-secondary-200">
                        <h2 class="text-lg font-semibold text-secondary-900 flex items-center">
                            <span class="w-8 h-8 bg-primary-100 text-primary-600 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </span>
                            Informasi Dasar
                        </h2>
                    </div>
                    <div class="p-6">
                        <!-- Header with Nomor, Tanggal, Jenis -->
                        <div class="flex flex-wrap items-center gap-4 mb-4 pb-4 border-b border-secondary-100">
                            <div>
                                <span class="text-xs text-secondary-500 uppercase tracking-wider">Nomor</span>
                                <p class="font-mono text-sm font-semibold text-primary-600">{{ $pengajuan->nomor_pengajuan }}</p>
                            </div>
                            <div class="w-px h-8 bg-secondary-200"></div>
                            <div>
                                <span class="text-xs text-secondary-500 uppercase tracking-wider">Tanggal</span>
                                <p class="text-sm text-secondary-900">{{ \Carbon\Carbon::parse($pengajuan->tanggal_pengajuan)->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="w-px h-8 bg-secondary-200"></div>
                            <div>
                                <span class="text-xs text-secondary-500 uppercase tracking-wider">Jenis</span>
                                @php
                                    $jenisLabels = [
                                        'kegiatan' => 'Kegiatan',
                                        'pengadaan' => 'Pengadaan',
                                        'pembayaran' => 'Pembayaran',
                                        'honorarium' => 'Honorarium',
                                        'sewa' => 'Sewa',
                                        'konsumsi' => 'Konsumsi',
                                        'konsumi' => 'Konsumsi',
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
                                    $label = $jenisLabels[$jenis] ?? ucfirst($jenis);
                                    $colorClass = $jenisColors[$jenis] ?? 'bg-gray-100 text-gray-700';
                                @endphp
                                <p class="mt-1">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold {{ $colorClass }}">
                                        {{ $label }}
                                    </span>
                                </p>
                            </div>
                        </div>

                        <!-- Judul -->
                        <div class="mb-4">
                            <h3 class="text-base font-semibold text-secondary-900">{{ $pengajuan->judul_pengajuan }}</h3>
                        </div>

                        <!-- Details Grid -->
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-secondary-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                <div>
                                    <dt class="text-xs text-secondary-500 uppercase tracking-wider">Divisi</dt>
                                    <dd class="text-sm text-secondary-900 font-medium">{{ $pengajuan->divisi->nama_divisi ?? '-' }}</dd>
                                </div>
                            </div>
                            @if($pengajuan->programKerja && $pengajuan->programKerja->periodeAnggaran)
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-secondary-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <div>
                                    <dt class="text-xs text-secondary-500 uppercase tracking-wider">Periode Anggaran</dt>
                                    <dd class="text-sm text-secondary-900 font-medium">{{ $pengajuan->programKerja->periodeAnggaran->nama_periode }}</dd>
                                </div>
                            </div>
                            @endif
                            @if($pengajuan->programKerja)
                            <div class="flex items-start md:col-span-2">
                                <svg class="w-5 h-5 text-secondary-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                                <div class="flex-1">
                                    <dt class="text-xs text-secondary-500 uppercase tracking-wider">Program Kerja</dt>
                                    <dd class="text-sm text-secondary-900 font-medium">{{ $pengajuan->programKerja->nama_program }}</dd>
                                </div>
                            </div>
                            @endif
                            @if($pengajuan->subProgram)
                            <div class="flex items-start md:col-span-2">
                                <svg class="w-5 h-5 text-secondary-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                </svg>
                                <div class="flex-1">
                                    <dt class="text-xs text-secondary-500 uppercase tracking-wider">Sub Program</dt>
                                    <dd class="text-sm text-secondary-900 font-medium">{{ $pengajuan->subProgram->nama_sub_program }}</dd>
                                </div>
                            </div>
                            @endif
                            @if($pengajuan->jenis_pengajuan === 'honorarium' && $pengajuan->detailAnggaran)
                            <div class="flex items-start md:col-span-2">
                                <svg class="w-5 h-5 text-secondary-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M15 14h.01M15 17h.01M9 20h6a2 2 0 002-2V5a2 2 0 00-2-2H9a2 2 0 00-2 2v12a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                </svg>
                                <div class="flex-1">
                                    <dt class="text-xs text-secondary-500 uppercase tracking-wider">Detail Anggaran</dt>
                                    <dd class="text-sm text-secondary-900 font-medium">{{ $pengajuan->detailAnggaran->nama_detail }}</dd>
                                </div>
                            </div>
                            @endif
                        </dl>
                        @if($pengajuan->deskripsi)
                        <div class="mt-4 pt-4 border-t border-secondary-100">
                            <dt class="text-xs text-secondary-500 uppercase tracking-wider mb-2">Deskripsi</dt>
                            <dd class="text-sm text-secondary-700 whitespace-pre-line">{{ $pengajuan->deskripsi }}</dd>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Detail Pengajuan -->
                @if($pengajuan->jenis_pengajuan === 'honorarium')
                    <!-- Honorarium Details -->
                    <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                        <div class="px-6 py-4 border-b border-secondary-200">
                            <h2 class="text-lg font-semibold text-secondary-900 flex items-center">
                                <span class="w-8 h-8 bg-amber-100 text-amber-600 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </span>
                                Daftar Penerima Honorarium
                            </h2>
                        </div>
                        <div class="divide-y divide-secondary-100">
                            @forelse($pengajuan->honorariumDetails as $index => $honorarium)
                            <div class="p-6">
                                <div class="flex justify-between items-start mb-4">
                                    <h4 class="font-semibold text-secondary-900">Penerima {{ $index + 1 }}</h4>
                                    <span class="px-3 py-1 text-xs font-medium rounded-full {{ $honorarium->penerima_manfaat_type === 'karyawan' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                                        {{ $honorarium->penerima_manfaat_type === 'karyawan' ? 'Karyawan' : 'Non-Karyawan' }}
                                    </span>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <span class="text-sm text-secondary-500">Nama Penerima</span>
                                        <p class="text-sm font-medium text-secondary-900">{{ $honorarium->penerima_nama }}</p>
                                    </div>
                                    <div>
                                        <span class="text-sm text-secondary-500">Jumlah Honor</span>
                                        <p class="text-lg font-bold text-primary-600">{{ formatRupiah($honorarium->jumlah_honor) }}</p>
                                    </div>
                                    <div class="md:col-span-2">
                                        <span class="text-sm text-secondary-500">Nomor Rekening</span>
                                        <p class="text-sm font-medium text-secondary-900">{{ $honorarium->nomor_rekening ?? '-' }}</p>
                                    </div>
                                    @if($honorarium->deskripsi)
                                    <div class="md:col-span-2">
                                        <span class="text-sm text-secondary-500">Deskripsi</span>
                                        <p class="text-sm text-secondary-700">{{ $honorarium->deskripsi }}</p>
                                    </div>
                                    @endif
                                    @if($honorarium->lampiran)
                                    <div class="md:col-span-2">
                                        <span class="text-sm text-secondary-500">Lampiran</span>
                                        <p class="text-sm">
                                            <a href="{{ asset('storage/' . $honorarium->lampiran) }}" target="_blank" class="inline-flex items-center text-primary-600 hover:text-primary-800">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                {{ $honorarium->lampiran_filename }}
                                            </a>
                                        </p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @empty
                            <div class="p-6 text-center text-secondary-500">
                                Tidak ada data penerima honorarium
                            </div>
                            @endforelse

                            <!-- Total -->
                            <div class="bg-secondary-50 px-6 py-4 flex justify-end items-center">
                                <span class="text-sm font-semibold text-secondary-900 mr-4">Total Pengajuan:</span>
                                <span class="text-xl font-bold text-primary-600">{{ formatRupiah($pengajuan->total_pengajuan) }}</span>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Regular Detail Pengajuan -->
                    <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                        <div class="px-6 py-4 border-b border-secondary-200">
                            <h2 class="text-lg font-semibold text-secondary-900 flex items-center">
                                <span class="w-8 h-8 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </span>
                                Rincian Pengajuan
                            </h2>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-secondary-50 border-b border-secondary-200">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-secondary-600 uppercase">Uraian</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-secondary-600 uppercase">Volume</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-secondary-600 uppercase">Satuan</th>
                                        <th class="px-6 py-3 text-right text-xs font-semibold text-secondary-600 uppercase">Harga</th>
                                        <th class="px-6 py-3 text-right text-xs font-semibold text-secondary-600 uppercase">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-secondary-100">
                                    @foreach($pengajuan->details as $detail)
                                    <tr>
                                        <td class="px-6 py-4 text-sm text-secondary-900">{{ $detail->uraian }}</td>
                                        <td class="px-6 py-4 text-sm text-secondary-700">{{ $detail->volume }}</td>
                                        <td class="px-6 py-4 text-sm text-secondary-700">{{ $detail->satuan }}</td>
                                        <td class="px-6 py-4 text-sm text-secondary-700 text-right">{{ formatRupiah($detail->harga_satuan) }}</td>
                                        <td class="px-6 py-4 text-sm font-semibold text-secondary-900 text-right">{{ formatRupiah($detail->jumlah) }}</td>
                                    </tr>
                                    @endforeach
                                    <tr class="bg-secondary-50">
                                        <td colspan="4" class="px-6 py-4 text-right text-sm font-semibold text-secondary-900">Total Pengajuan</td>
                                        <td class="px-6 py-4 text-right text-lg font-bold text-primary-600">{{ formatRupiah($pengajuan->total_pengajuan) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <!-- Penerima Manfaat -->
                @if($pengajuan->penerimaManfaat && $pengajuan->penerimaManfaat->count() > 0)
                <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                    <div class="px-6 py-4 border-b border-secondary-200">
                        <h2 class="text-lg font-semibold text-secondary-900 flex items-center">
                            <span class="w-8 h-8 bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </span>
                            Penerima Manfaat
                        </h2>
                    </div>
                    <div class="p-6">
                        <ul class="space-y-2">
                            @foreach($pengajuan->penerimaManfaat as $penerima)
                            <li class="flex items-center text-sm text-secondary-700">
                                <svg class="w-4 h-4 text-primary-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                {{ $penerima->nama_penerima }} @if($penerima->jenis_penerima) <span class="text-secondary-500">({{ $penerima->jenis_penerima }})</span> @endif
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif

                <!-- Lampiran -->
                @if($pengajuan->attachments && $pengajuan->attachments->count() > 0)
                <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                    <div class="px-6 py-4 border-b border-secondary-200">
                        <h2 class="text-lg font-semibold text-secondary-900 flex items-center">
                            <span class="w-8 h-8 bg-amber-100 text-amber-600 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                </svg>
                            </span>
                            Lampiran Dokumen
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($pengajuan->attachments as $attachment)
                            <a href="{{ asset('storage/' . $attachment->path) }}" target="_blank" class="flex items-center p-4 border border-secondary-200 rounded-xl hover:bg-secondary-50 hover:border-primary-300 transition-colors">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                    @if(str_contains($attachment->mime_type, 'image'))
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    @elseif(str_contains($attachment->mime_type, 'pdf'))
                                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 text-secondary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-secondary-900 truncate">{{ $attachment->filename }}</p>
                                    <p class="text-xs text-secondary-500">{{ formatBytes($attachment->size) }}</p>
                                </div>
                                <svg class="w-5 h-5 text-secondary-400 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>
                @else
                <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                    <div class="px-6 py-4 border-b border-secondary-200">
                        <h2 class="text-lg font-semibold text-secondary-900 flex items-center">
                            <span class="w-8 h-8 bg-amber-100 text-amber-600 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                </svg>
                            </span>
                            Lampiran Dokumen
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-secondary-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                            </svg>
                            <p class="text-sm text-secondary-500">Tidak ada lampiran dokumen</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Riwayat Approval -->
                @if($pengajuan->approvals && $pengajuan->approvals->count() > 0)
                <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                    <div class="px-6 py-4 border-b border-secondary-200">
                        <h2 class="text-lg font-semibold text-secondary-900 flex items-center">
                            <span class="w-8 h-8 bg-green-100 text-green-600 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </span>
                            Riwayat Approval
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            @foreach($pengajuan->approvals as $riwayat)
                            <div class="flex items-start p-4 border border-secondary-200 rounded-xl hover:bg-secondary-50 transition-colors">
                                <div class="flex-shrink-0">
                                    @if($riwayat->status === 'disetujui')
                                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                    @elseif($riwayat->status === 'ditolak')
                                        <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </div>
                                    @elseif($riwayat->status === 'pending')
                                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                    @else
                                        <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4 flex-1">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-semibold text-secondary-900">{{ $riwayat->approver->name ?? 'System' }}</p>
                                            <p class="text-xs text-secondary-500 mt-0.5">{{ ucfirst(str_replace('_', ' ', $riwayat->level)) }}</p>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            @if($riwayat->status === 'disetujui')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                                    Disetujui
                                                </span>
                                            @elseif($riwayat->status === 'ditolak')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                                    Ditolak
                                                </span>
                                            @elseif($riwayat->status === 'pending')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                                    Menunggu Persetujuan
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                                                    Menunggu
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between mt-2">
                                        <p class="text-xs text-secondary-500">
                                            @if($riwayat->approved_at)
                                                Diproses: {{ \Carbon\Carbon::parse($riwayat->approved_at)->format('d/m/Y H:i:s') }}
                                            @else
                                                Dibuat: {{ \Carbon\Carbon::parse($riwayat->created_at)->format('d/m/Y H:i:s') }}
                                            @endif
                                        </p>
                                        @if($riwayat->status === 'pending')
                                            <span class="text-xs text-blue-600 font-medium">Menunggu persetujuan</span>
                                        @elseif($riwayat->status === 'waiting')
                                            <span class="text-xs text-gray-500 font-medium">Menunggu approval sebelumnya</span>
                                        @elseif($riwayat->status === 'disetujui')
                                            <span class="text-xs text-green-600 font-medium">✓ Disetujui</span>
                                        @elseif($riwayat->status === 'ditolak')
                                            <span class="text-xs text-red-600 font-medium">✗ Ditolak</span>
                                        @endif
                                    </div>
                                    @if($riwayat->catatan)
                                        <div class="mt-2 p-2 bg-secondary-50 rounded-lg">
                                            <p class="text-xs text-secondary-600"><span class="font-medium">Catatan:</span> {{ $riwayat->catatan }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Summary Card -->
                <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                    <div class="px-6 py-4 border-b border-secondary-200">
                        <h3 class="text-lg font-semibold text-secondary-900">Ringkasan</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <p class="text-sm text-secondary-500">Total Pengajuan</p>
                            <p class="text-2xl font-bold text-primary-600">{{ formatRupiah($pengajuan->total_pengajuan) }}</p>
                        </div>
                        @if($pengajuan->tanggal_dibutuhkan)
                        <div>
                            <p class="text-sm text-secondary-500">Tanggal Dibutuhkan</p>
                            <p class="text-sm font-medium text-secondary-900">{{ \Carbon\Carbon::parse($pengajuan->tanggal_dibutuhkan)->format('d/m/Y') }}</p>
                        </div>
                        @endif
                        @if($pengajuan->metode_pencairan)
                        <div>
                            <p class="text-sm text-secondary-500">Metode Pencairan</p>
                            <p class="text-sm font-medium text-secondary-900">{{ ucfirst(str_replace('_', ' ', $pengajuan->metode_pencairan)) }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Actions Card -->
                <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                    <div class="px-6 py-4 border-b border-secondary-200">
                        <h3 class="text-lg font-semibold text-secondary-900">Aksi</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        @if($pengajuan->status === 'draft' && $pengajuan->created_by === auth()->user()->id)
                        <form method="POST" action="{{ route('pengajuan-dana.submit', $pengajuan) }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center justify-center px-4 py-3 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-all duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                </svg>
                                Submit Pengajuan
                            </button>
                        </form>
                        @endif

                        @if(auth()->user()->hasRole('direktur_utama') && $pengajuan->status === 'menunggu_approval')
                        <button onclick="document.getElementById('approveModal').classList.remove('hidden')" class="w-full flex items-center justify-center px-4 py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-all duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Setujui
                        </button>
                        <button onclick="document.getElementById('rejectModal').classList.remove('hidden')" class="w-full flex items-center justify-center px-4 py-3 border border-red-300 text-red-600 rounded-xl hover:bg-red-50 transition-all duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Tolak
                        </button>
                        @endif

                        <button onclick="window.print()" class="w-full flex items-center justify-center px-4 py-3 border border-secondary-200 text-secondary-700 rounded-xl hover:bg-secondary-50 transition-all duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            Cetak
                        </button>
                    </div>
                </div>

                <!-- Created By -->
                <div class="bg-white rounded-2xl shadow-soft p-6">
                    <p class="text-sm text-secondary-500">Dibuat oleh</p>
                    <div class="flex items-center mt-2">
                        <div class="w-10 h-10 bg-gradient-to-br from-primary-400 to-primary-600 rounded-full flex items-center justify-center">
                            <span class="text-white font-semibold">{{ strtoupper(substr($pengajuan->createdBy->name ?? 'A', 0, 1)) }}</span>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-secondary-900">{{ $pengajuan->createdBy->name ?? '-' }}</p>
                            <p class="text-xs text-secondary-500">{{ \Carbon\Carbon::parse($pengajuan->created_at)->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Approve Modal -->
    @if(auth()->user()->hasRole('direktur_utama') && $pengajuan->status === 'menunggu_approval')
    <div id="approveModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <form method="POST" action="{{ route('pengajuan-dana.approve', $pengajuan) }}" class="bg-white rounded-2xl shadow-xl max-w-md w-full mx-4">
            @csrf
            <div class="p-6">
                <h3 class="text-lg font-semibold text-secondary-900 mb-4">Setujui Pengajuan Dana</h3>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-secondary-700 mb-2">Catatan (Opsional)</label>
                    <textarea name="catatan" rows="3" class="w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="Tambahkan catatan..."></textarea>
                </div>
                <div class="flex space-x-3">
                    <button type="button" onclick="document.getElementById('approveModal').classList.add('hidden')" class="flex-1 px-4 py-3 border border-secondary-200 text-secondary-700 rounded-xl hover:bg-secondary-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 px-4 py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-colors">
                        Ya, Setujui
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <form method="POST" action="{{ route('pengajuan-dana.reject', $pengajuan) }}" class="bg-white rounded-2xl shadow-xl max-w-md w-full mx-4">
            @csrf
            <div class="p-6">
                <h3 class="text-lg font-semibold text-secondary-900 mb-4">Tolak Pengajuan Dana</h3>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-secondary-700 mb-2">Alasan Penolakan <span class="text-red-500">*</span></label>
                    <textarea name="catatan_penolakan" rows="3" required class="w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="Jelaskan alasan penolakan..."></textarea>
                </div>
                <div class="flex space-x-3">
                    <button type="button" onclick="document.getElementById('rejectModal').classList.add('hidden')" class="flex-1 px-4 py-3 border border-secondary-200 text-secondary-700 rounded-xl hover:bg-secondary-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 px-4 py-3 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors">
                        Ya, Tolak
                    </button>
                </div>
            </div>
        </form>
    </div>
    @endif
</x-app-layout>
