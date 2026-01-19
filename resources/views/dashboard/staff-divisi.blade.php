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
                    <p class="text-xs text-cyan-100 mb-0.5">Divisi</p>
                    <p class="text-lg font-bold text-white">{{ $data['divisi']->nama_divisi }}</p>
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

            <!-- My Pengajuan -->
            <div class="bg-white rounded-xl border border-blue-100 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-cyan-50 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xl font-semibold text-gray-900">{{ $data['pengajuanCount'] }}</p>
                        <p class="text-xs text-gray-500">My Pengajuan</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pengajuan Statistics -->
        <div class="grid grid-cols-5 gap-3">
            <!-- Total -->
            <div class="bg-white rounded-xl border border-blue-100 p-3">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-lg font-semibold text-gray-900">{{ $data['pengajuanCount'] }}</p>
                        <p class="text-xs text-gray-500">Total</p>
                    </div>
                </div>
            </div>

            <!-- Draft -->
            <div class="bg-white rounded-xl border border-blue-100 p-3">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-lg font-semibold text-gray-900">{{ $data['pengajuanDraft'] }}</p>
                        <p class="text-xs text-gray-500">Draft</p>
                    </div>
                </div>
            </div>

            <!-- Menunggu -->
            <div class="bg-white rounded-xl border border-blue-100 p-3">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-amber-50 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-lg font-semibold text-gray-900">{{ $data['pengajuanMenunggu'] }}</p>
                        <p class="text-xs text-gray-500">Menunggu</p>
                    </div>
                </div>
            </div>

            <!-- Disetujui -->
            <div class="bg-white rounded-xl border border-blue-100 p-3">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-emerald-50 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-lg font-semibold text-gray-900">{{ $data['pengajuanDisetujui'] }}</p>
                        <p class="text-xs text-gray-500">Disetujui</p>
                    </div>
                </div>
            </div>

            <!-- Ditolak -->
            <div class="bg-white rounded-xl border border-blue-100 p-3">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-red-50 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-lg font-semibold text-gray-900">{{ $data['pengajuanDitolak'] }}</p>
                        <p class="text-xs text-gray-500">Ditolak</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notifications -->
        <div class="grid grid-cols-3 gap-3">
            <!-- Need LPJ -->
            <a href="{{ route('lpj.create') }}" class="bg-white rounded-xl border border-blue-100 p-4 hover:border-orange-200 transition-all">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-orange-50 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xl font-semibold text-gray-900">{{ $data['pencairanNeedLpj']->count() }}</p>
                        <p class="text-xs text-gray-500">Need LPJ</p>
                    </div>
                </div>
            </a>

            <!-- LPJ Verifikasi -->
            <div class="bg-white rounded-xl border border-blue-100 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-amber-50 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xl font-semibold text-gray-900">{{ $data['lpjMenungguVerifikasi'] }}</p>
                        <p class="text-xs text-gray-500">LPJ Verifikasi</p>
                    </div>
                </div>
            </div>

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
        </div>

        <!-- LPJ Need Refund Alert -->
        @if($data['lpjNeedRefund'] > 0)
            <div class="bg-gradient-to-r from-violet-600 to-violet-700 rounded-xl p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-white">Refunds Needed</p>
                            <p class="text-xs text-violet-100">{{ $data['lpjNeedRefund'] }} LPJ has remaining balance</p>
                        </div>
                    </div>
                    <a href="{{ route('refund.create') }}" class="px-4 py-2 bg-white text-violet-600 text-sm font-medium rounded-lg hover:bg-violet-50 transition-colors">Create Refund →</a>
                </div>
            </div>
        @endif

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl border border-blue-100">
            <div class="px-4 py-3 border-b border-blue-100">
                <span class="text-sm font-semibold text-gray-900">Quick Actions</span>
            </div>
            <div class="p-4">
                <div class="grid grid-cols-3 gap-3">
                    <a href="{{ route('pengajuan-dana.create') }}" class="flex flex-col items-center p-3 bg-emerald-50 rounded-xl hover:bg-emerald-100 transition-colors">
                        <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center mb-2">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-gray-700">Create Pengajuan</span>
                    </a>

                    <a href="{{ route('lpj.index') }}" class="flex flex-col items-center p-3 bg-blue-50 rounded-xl hover:bg-blue-100 transition-colors">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mb-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-gray-700">My LPJ</span>
                    </a>

                    <a href="{{ route('pengajuan-dana.index') }}" class="flex flex-col items-center p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                        <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center mb-2">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-gray-700">My Pengajuan</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- My Pengajuan -->
        <div class="bg-white rounded-xl border border-blue-100 overflow-hidden">
            <div class="px-4 py-3 border-b border-blue-100 flex items-center justify-between">
                <span class="text-sm font-semibold text-gray-900">My Pengajuan</span>
                <a href="{{ route('pengajuan-dana.index') }}" class="text-blue-600 hover:text-blue-700 text-xs font-medium">View All →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-blue-50 border-b border-blue-100">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-semibold text-blue-700 uppercase">Tanggal</th>
                            <th class="px-4 py-2 text-left text-xs font-semibold text-blue-700 uppercase">Nomor</th>
                            <th class="px-4 py-2 text-left text-xs font-semibold text-blue-700 uppercase">Judul</th>
                            <th class="px-4 py-2 text-right text-xs font-semibold text-blue-700 uppercase">Total</th>
                            <th class="px-4 py-2 text-left text-xs font-semibold text-blue-700 uppercase">Status</th>
                            <th class="px-4 py-2 text-left text-xs font-semibold text-blue-700 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-blue-50">
                        @foreach($data['myPengajuan'] as $pengajuan)
                            <tr class="hover:bg-blue-50/50 transition-colors">
                                <td class="px-4 py-3"><span class="text-sm text-gray-700">{{ $pengajuan->tanggal_pengajuan ? $pengajuan->tanggal_pengajuan->format('d/m/Y') : '-' }}</span></td>
                                <td class="px-4 py-3"><span class="font-medium text-gray-900 text-sm">{{ $pengajuan->nomor_pengajuan ?? '-' }}</span></td>
                                <td class="px-4 py-3">
                                    <p class="font-medium text-gray-900 text-sm">{{ $pengajuan->judul_pengajuan }}</p>
                                    @if($pengajuan->subProgram)<p class="text-xs text-gray-500">{{ $pengajuan->subProgram->nama_sub_program }}</p>@endif
                                </td>
                                <td class="px-4 py-3 text-right"><span class="font-semibold text-gray-900 text-sm">{{ formatRupiah($pengajuan->total_pengajuan) }}</span></td>
                                <td class="px-4 py-3">
                                    @php
                                        $statusColors = ['draft' => 'bg-gray-100 text-gray-700', 'menunggu_approval' => 'bg-amber-100 text-amber-700', 'disetujui' => 'bg-emerald-100 text-emerald-700', 'ditolak' => 'bg-red-100 text-red-700', 'revisi' => 'bg-blue-100 text-blue-700', 'dicairkan' => 'bg-violet-100 text-violet-700'];
                                        $statusColor = $statusColors[$pengajuan->status] ?? 'bg-gray-100 text-gray-600';
                                    @endphp
                                    <span class="px-2 py-0.5 text-xs font-medium rounded-full {{ $statusColor }}">{{ ucfirst(str_replace('_', ' ', $pengajuan->status)) }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <a href="{{ route('pengajuan-dana.show', $pengajuan) }}" class="text-blue-600 hover:text-blue-700 text-xs font-medium">Detail</a>
                                        @if(in_array($pengajuan->status, ['draft', 'revisi']))<a href="{{ route('pengajuan-dana.edit', $pengajuan) }}" class="text-amber-600 hover:text-amber-700 text-xs font-medium">Edit</a>@endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @if($data['myPengajuan']->count() == 0)
                    <div class="text-center py-12">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="text-gray-500 font-medium text-sm">Belum ada pengajuan dana</p>
                        <p class="text-gray-400 text-xs mt-1">Mulai dengan membuat pengajuan dana pertama Anda</p>
                        <a href="{{ route('pengajuan-dana.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition-colors text-xs">Buat Pengajuan →</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
