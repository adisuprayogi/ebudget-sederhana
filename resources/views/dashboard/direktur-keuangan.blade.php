<x-app-layout>
    <x-slot name="header">
        <x-dashboard.header :userName="Auth::user()->name" />
    </x-slot>

    <div class="space-y-4">
        @if($data['activePeriode'])
        <!-- Periode Info Banner -->
        <div class="bg-gradient-to-r from-blue-600 to-cyan-500 rounded-xl p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-blue-100 mb-0.5">Periode Anggaran Aktif</p>
                    <p class="text-lg font-bold text-white">{{ $data['activePeriode']->nama_periode }}</p>
                    <p class="text-xs text-blue-100 mt-0.5">
                        {{ $data['activePeriode']->tanggal_mulai_perencanaan_anggaran->translatedFormat('d F Y') }} - {{ $data['activePeriode']->tanggal_selesai_perencanaan_anggaran->translatedFormat('d F Y') }}
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-cyan-100 mb-0.5">Total Pagu Periode</p>
                    <p class="text-lg font-bold text-white">{{ formatRupiah($data['totalPagu']) }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Quick Stats -->
        <div class="grid grid-cols-4 gap-3">
            <!-- Total Pagu -->
            <div class="bg-white rounded-xl border border-blue-100 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">{{ formatRupiah($data['totalPagu']) }}</p>
                        <p class="text-xs text-gray-500">Total Pagu</p>
                    </div>
                </div>
            </div>

            <!-- Terpakai -->
            <div class="bg-white rounded-xl border border-blue-100 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-amber-50 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">{{ formatRupiah($data['terpakai']) }}</p>
                        <p class="text-xs text-gray-500">Terpakai ({{ $data['totalPagu'] > 0 ? round(($data['terpakai'] / $data['totalPagu']) * 100, 1) : 0 }}%)</p>
                    </div>
                </div>
            </div>

            <!-- Sisa Pagu -->
            <div class="bg-white rounded-xl border border-blue-100 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-emerald-50 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">{{ formatRupiah($data['sisaPagu']) }}</p>
                        <p class="text-xs text-gray-500">Sisa Pagu</p>
                    </div>
                </div>
            </div>

            <!-- Today's Disbursement -->
            <div class="bg-white rounded-xl border border-blue-100 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-cyan-50 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">{{ formatRupiah($data['totalPencairanHariIni']) }}</p>
                        <p class="text-xs text-gray-500">Pencairan Hari Ini</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- LPJ & Refund Statistics -->
        <div class="grid grid-cols-4 gap-3">
            <!-- LPJ Pending -->
            <a href="{{ route('lpj.index', ['status' => 'menunggu_verifikasi']) }}" class="bg-white rounded-xl border border-blue-100 p-4 hover:border-blue-200 transition-all">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-amber-50 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xl font-semibold text-gray-900">{{ $data['lpjPending'] }}</p>
                        <p class="text-xs text-gray-500">LPJ Pending</p>
                    </div>
                </div>
            </a>

            <!-- LPJ Revisi -->
            <div class="bg-white rounded-xl border border-blue-100 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-violet-50 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xl font-semibold text-gray-900">{{ $data['lpjRevisi'] }}</p>
                        <p class="text-xs text-gray-500">LPJ Revisi</p>
                    </div>
                </div>
            </div>

            <!-- Refund Pending -->
            <a href="{{ route('refund.index', ['status' => 'menunggu_approval']) }}" class="bg-white rounded-xl border border-blue-100 p-4 hover:border-red-200 transition-all">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-red-50 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xl font-semibold text-gray-900">{{ $data['refundPending'] }}</p>
                        <p class="text-xs text-gray-500">Refund Pending</p>
                    </div>
                </div>
            </a>

            <!-- Pencairan Pending -->
            <a href="{{ route('pencairan-dana.index') }}" class="bg-white rounded-xl border border-blue-100 p-4 hover:border-blue-200 transition-all">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xl font-semibold text-gray-900">{{ $data['pencairanPending'] }}</p>
                        <p class="text-xs text-gray-500">Pencairan Pending</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Verification Lists -->
        <div class="grid grid-cols-3 gap-3">
            <!-- Pencairan Need Verification -->
            <div class="bg-white rounded-xl border border-blue-100">
                <div class="px-4 py-3 border-b border-blue-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <span class="text-sm font-semibold text-gray-900">Pencairan</span>
                    </div>
                    <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-blue-100 text-blue-700">{{ $data['pencairanNeedVerification']->count() }}</span>
                </div>
                <div class="p-3 max-h-64 overflow-y-auto">
                    @if($data['pencairanNeedVerification']->count() > 0)
                        <div class="space-y-2">
                            @foreach($data['pencairanNeedVerification'] as $pencairan)
                                <a href="{{ route('pencairan-dana.show', $pencairan) }}" class="block p-3 bg-blue-50 rounded-xl hover:bg-blue-100 transition-colors">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="font-semibold text-gray-900 text-xs">{{ $pencairan->nomor_pencairan }}</p>
                                            <p class="text-xs text-gray-500">{{ $pencairan->pengajuanDana->divisi->nama_divisi ?? '-' }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-semibold text-blue-600 text-xs">{{ formatRupiah($pencairan->jumlah_pencairan) }}</p>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-xs text-gray-500">No pending pencairan</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- LPJ Need Verification -->
            <div class="bg-white rounded-xl border border-blue-100">
                <div class="px-4 py-3 border-b border-blue-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <span class="text-sm font-semibold text-gray-900">LPJ</span>
                    </div>
                    <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-amber-100 text-amber-700">{{ $data['lpjNeedVerification']->count() }}</span>
                </div>
                <div class="p-3 max-h-64 overflow-y-auto">
                    @if($data['lpjNeedVerification']->count() > 0)
                        <div class="space-y-2">
                            @foreach($data['lpjNeedVerification'] as $lpj)
                                <a href="{{ route('lpj.show', $lpj) }}" class="block p-3 bg-amber-50 rounded-xl hover:bg-amber-100 transition-colors">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="font-semibold text-gray-900 text-xs">{{ $lpj->nomor_lpj }}</p>
                                            <p class="text-xs text-gray-500">{{ $lpj->pencairanDana->pengajuanDana->divisi->nama_divisi ?? '-' }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-semibold text-amber-600 text-xs">{{ formatRupiah($lpj->total_pengeluaran) }}</p>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-xs text-gray-500">No LPJ pending</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Refund Need Verification -->
            <div class="bg-white rounded-xl border border-blue-100">
                <div class="px-4 py-3 border-b border-blue-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                        </div>
                        <span class="text-sm font-semibold text-gray-900">Refund</span>
                    </div>
                    <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-red-100 text-red-700">{{ $data['refundNeedVerification']->count() }}</span>
                </div>
                <div class="p-3 max-h-64 overflow-y-auto">
                    @if($data['refundNeedVerification']->count() > 0)
                        <div class="space-y-2">
                            @foreach($data['refundNeedVerification'] as $refund)
                                <a href="{{ route('refund.show', $refund) }}" class="block p-3 bg-red-50 rounded-xl hover:bg-red-100 transition-colors">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="font-semibold text-gray-900 text-xs">{{ $refund->nomor_refund }}</p>
                                            <p class="text-xs text-gray-500">{{ $refund->lpj->pencairanDana->pengajuanDana->divisi->nama_divisi ?? '-' }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-semibold text-red-600 text-xs">{{ formatRupiah($refund->jumlah_refund) }}</p>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-xs text-gray-500">No refund pending</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Pengajuan -->
        <div class="bg-white rounded-xl border border-blue-100 overflow-hidden">
            <div class="px-4 py-3 border-b border-blue-100 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-900">Recent Pengajuan</h3>
                <a href="{{ route('pengajuan-dana.index') }}" class="text-blue-600 hover:text-blue-700 text-xs font-medium">View All →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-blue-50 border-b border-blue-100">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-semibold text-blue-700 uppercase">Nomor</th>
                            <th class="px-4 py-2 text-left text-xs font-semibold text-blue-700 uppercase">Judul</th>
                            <th class="px-4 py-2 text-left text-xs font-semibold text-blue-700 uppercase">Divisi</th>
                            <th class="px-4 py-2 text-right text-xs font-semibold text-blue-700 uppercase">Total</th>
                            <th class="px-4 py-2 text-left text-xs font-semibold text-blue-700 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-blue-50">
                        @foreach($data['recentPengajuan'] as $pengajuan)
                            <tr class="hover:bg-blue-50/50 transition-colors">
                                <td class="px-4 py-3"><span class="font-medium text-gray-900 text-sm">{{ $pengajuan->nomor_pengajuan ?? '-' }}</span></td>
                                <td class="px-4 py-3"><p class="font-medium text-gray-900 text-sm">{{ $pengajuan->judul_pengajuan }}</p></td>
                                <td class="px-4 py-3"><span class="px-2 py-0.5 text-xs font-medium rounded-full bg-blue-100 text-blue-700">{{ $pengajuan->divisi->nama_divisi ?? '-' }}</span></td>
                                <td class="px-4 py-3 text-right"><span class="font-semibold text-gray-900 text-sm">{{ formatRupiah($pengajuan->total_pengajuan) }}</span></td>
                                <td class="px-4 py-3">
                                    @php
                                        $statusColors = ['menunggu_approval' => 'bg-amber-100 text-amber-700', 'disetujui' => 'bg-emerald-100 text-emerald-700', 'ditolak' => 'bg-red-100 text-red-700'];
                                        $statusColor = $statusColors[$pengajuan->status] ?? 'bg-gray-100 text-gray-600';
                                    @endphp
                                    <span class="px-2 py-0.5 text-xs font-medium rounded-full {{ $statusColor }}">{{ ucfirst(str_replace('_', ' ', $pengajuan->status)) }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
