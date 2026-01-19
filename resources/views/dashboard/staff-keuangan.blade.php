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
                </div>
                <div class="text-right">
                    <p class="text-xs text-cyan-100 mb-0.5">Total Pagu Periode</p>
                    <p class="text-lg font-bold text-white">{{ formatRupiah($data['totalPagu']) }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Quick Stats -->
        <div class="grid grid-cols-3 gap-3">
            <!-- Processed Today -->
            <div class="bg-white rounded-xl border border-blue-100 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xl font-semibold text-gray-900">{{ $data['pencairanProcessedToday'] }}</p>
                        <p class="text-xs text-gray-500">Processed Today</p>
                    </div>
                </div>
            </div>

            <!-- Total Today -->
            <div class="bg-white rounded-xl border border-blue-100 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-emerald-50 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">{{ formatRupiah($data['totalPencairanHariIni']) }}</p>
                        <p class="text-xs text-gray-500">Total Today</p>
                    </div>
                </div>
            </div>

            <!-- Budget Used -->
            <div class="bg-white rounded-xl border border-blue-100 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-amber-50 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xl font-semibold text-gray-900">{{ $data['totalPagu'] > 0 ? round(($data['totalTerpakai'] / $data['totalPagu']) * 100, 1) : 0 }}%</p>
                        <p class="text-xs text-gray-500">Budget Used</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Verification Pending Counts -->
        <div class="grid grid-cols-3 gap-3">
            <!-- Pencairan Pending -->
            <a href="{{ route('pencairan-dana.index') }}" class="bg-white rounded-xl border border-blue-100 p-4 hover:border-blue-200 transition-all">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xl font-semibold text-gray-900">{{ $data['pencairanPending']->count() }}</p>
                        <p class="text-xs text-gray-500">Pencairan Pending</p>
                    </div>
                </div>
            </a>

            <!-- LPJ Pending -->
            <a href="{{ route('lpj.index', ['status' => 'menunggu_verifikasi']) }}" class="bg-white rounded-xl border border-blue-100 p-4 hover:border-amber-200 transition-all">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-amber-50 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xl font-semibold text-gray-900">{{ $data['lpjPending']->count() }}</p>
                        <p class="text-xs text-gray-500">LPJ Pending</p>
                    </div>
                </div>
            </a>

            <!-- Refund Pending -->
            <a href="{{ route('refund.index', ['status' => 'menunggu_approval']) }}" class="bg-white rounded-xl border border-blue-100 p-4 hover:border-red-200 transition-all">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-red-50 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xl font-semibold text-gray-900">{{ $data['refundPending']->count() }}</p>
                        <p class="text-xs text-gray-500">Refund Pending</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Pending Pencairan Alert -->
        @if($data['pencairanPending']->count() > 0)
            <div class="bg-gradient-to-r from-blue-600 to-cyan-500 rounded-xl p-4">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-white">Pencairan Menunggu Verifikasi</p>
                            <p class="text-xs text-blue-100">{{ $data['pencairanPending']->count() }} pencairan perlu diproses</p>
                        </div>
                    </div>
                    <a href="{{ route('pencairan-dana.index') }}" class="px-4 py-2 bg-white text-blue-600 text-sm font-medium rounded-lg hover:bg-blue-50 transition-colors">Process Now →</a>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    @foreach($data['pencairanPending']->take(4) as $pencairan)
                        <a href="{{ route('pencairan-dana.show', $pencairan) }}" class="flex justify-between items-center p-3 bg-white rounded-xl hover:bg-blue-50 transition-colors">
                            <div>
                                <p class="font-semibold text-gray-900 text-xs">{{ $pencairan->nomor_pencairan }}</p>
                                <p class="text-xs text-gray-500">{{ $pencairan->pengajuanDana->divisi->nama_divisi ?? '-' }} - {{ $pencairan->pengajuanDana->judul_pengajuan }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-blue-600 text-xs">{{ formatRupiah($pencairan->jumlah_pencairan) }}</p>
                                <p class="text-xs text-gray-500">{{ $pencairan->tanggal_pencairan->format('d/m/Y') }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Pengajuan Ready to Process -->
        @if($data['pengajuanNeedProcessing']->count() > 0)
            <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 rounded-xl p-4">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-white">Pengajuan Siap Dicairkan</p>
                            <p class="text-xs text-emerald-100">{{ $data['countPengajuanNeedProcessing'] }} pengajuan • {{ formatRupiah($data['totalPengajuanNeedProcessing']) }}</p>
                        </div>
                    </div>
                    <a href="{{ route('pencairan-dana.create') }}" class="px-4 py-2 bg-white text-emerald-600 font-semibold rounded-lg hover:bg-emerald-50 transition-colors text-sm">Create Pencairan →</a>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    @foreach($data['pengajuanNeedProcessing']->take(4) as $pengajuan)
                        <a href="{{ route('pencairan-dana.create', ['pengajuan_dana_id' => $pengajuan->id]) }}" class="flex justify-between items-center p-3 bg-white rounded-xl hover:bg-emerald-50 transition-colors border-2 border-transparent">
                            <div class="flex-1">
                                <p class="font-bold text-gray-900 text-sm">{{ $pengajuan->nomor_pengajuan ?? '-' }}</p>
                                <p class="text-xs text-gray-600">{{ $pengajuan->divisi->nama_divisi ?? '-' }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">{{ $pengajuan->judul_pengajuan }}</p>
                            </div>
                            <div class="text-right ml-3">
                                <p class="text-lg font-black text-emerald-600">{{ formatRupiah($pengajuan->total_pengajuan) }}</p>
                                <p class="text-xs text-emerald-600 font-medium">{{ \Carbon\Carbon::parse($pengajuan->updated_at)->diffForHumans() }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @else
            <div class="bg-white rounded-xl border border-blue-100 p-8 text-center border-2 border-dashed">
                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="text-sm font-bold text-gray-700 mb-1">Tidak Ada Pengajuan Siap Dicairkan</p>
                <p class="text-xs text-gray-500">All approved pengajuan have been processed</p>
            </div>
        @endif

        <!-- LPJ & Refund Verification -->
        <div class="grid grid-cols-2 gap-4">
            <!-- LPJ Pending -->
            @if($data['lpjPending']->count() > 0)
                <div class="bg-white rounded-xl border border-blue-100">
                    <div class="px-4 py-3 border-b border-blue-100 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <span class="text-sm font-semibold text-gray-900">LPJ Menunggu Verifikasi</span>
                        </div>
                        <a href="{{ route('lpj.index', ['status' => 'menunggu_verifikasi']) }}" class="px-3 py-1 bg-amber-100 text-amber-700 text-xs font-medium rounded-lg hover:bg-amber-200 transition-colors">View All</a>
                    </div>
                    <div class="p-3 max-h-64 overflow-y-auto">
                        <div class="space-y-2">
                            @foreach($data['lpjPending']->take(5) as $lpj)
                                <a href="{{ route('lpj.show', $lpj) }}" class="block p-3 bg-amber-50 rounded-xl hover:bg-amber-100 transition-colors">
                                    <div class="flex justify-between items-start">
                                        <div><p class="font-semibold text-gray-900 text-xs">{{ $lpj->nomor_lpj }}</p><p class="text-xs text-gray-500">{{ $lpj->pencairanDana->pengajuanDana->divisi->nama_divisi ?? '-' }}</p></div>
                                        <div class="text-right"><p class="font-bold text-amber-600 text-xs">{{ formatRupiah($lpj->total_pengeluaran) }}</p></div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Refund Pending -->
            @if($data['refundPending']->count() > 0)
                <div class="bg-white rounded-xl border border-blue-100">
                    <div class="px-4 py-3 border-b border-blue-100 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                            </div>
                            <span class="text-sm font-semibold text-gray-900">Refund Menunggu Approval</span>
                        </div>
                        <a href="{{ route('refund.index', ['status' => 'menunggu_approval']) }}" class="px-3 py-1 bg-red-100 text-red-700 text-xs font-medium rounded-lg hover:bg-red-200 transition-colors">View All</a>
                    </div>
                    <div class="p-3 max-h-64 overflow-y-auto">
                        <div class="space-y-2">
                            @foreach($data['refundPending']->take(5) as $refund)
                                <a href="{{ route('refund.show', $refund) }}" class="block p-3 bg-red-50 rounded-xl hover:bg-red-100 transition-colors">
                                    <div class="flex justify-between items-start">
                                        <div><p class="font-semibold text-gray-900 text-xs">{{ $refund->nomor_refund }}</p><p class="text-xs text-gray-500">{{ $refund->lpj->pencairanDana->pengajuanDana->divisi->nama_divisi ?? '-' }}</p></div>
                                        <div class="text-right"><p class="font-bold text-red-600 text-xs">{{ formatRupiah($refund->jumlah_refund) }}</p></div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Empty State -->
        @if($data['pencairanPending']->count() == 0 && $data['pengajuanNeedProcessing']->count() == 0 && $data['lpjPending']->count() == 0 && $data['refundPending']->count() == 0)
            <div class="bg-white rounded-xl border border-blue-100 p-12 text-center">
                <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">All Tasks Complete!</h3>
                <p class="text-gray-500 text-sm">No pending pencairan, LPJ, or refund at this time.</p>
            </div>
        @endif
    </div>
</x-app-layout>
