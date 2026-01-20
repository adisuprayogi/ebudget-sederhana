<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ request()->headers->get('referer') ?: route('refund.index') }}" class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Detail Refund</h1>
                <p class="text-sm text-gray-500 mt-0.5">{{ $refund->nomor_refund }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <!-- Status Banner -->
        @if($refund->status === 'draft')
            <div class="mb-6 bg-slate-50 border border-slate-200 rounded-xl p-4 flex items-start gap-3">
                <div class="w-10 h-10 bg-slate-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 011.707.293l5.414 5.414a1 1 0 011.707.293V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-slate-800">Draft</p>
                    <p class="text-sm text-slate-600">Refund ini masih dalam draft. Silakan edit atau submit untuk approval.</p>
                </div>
            </div>
        @elseif($refund->status === 'menunggu_approval')
            <div class="mb-6 bg-amber-50 border border-amber-200 rounded-xl p-4 flex items-start gap-3">
                <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-amber-800">Menunggu Approval</p>
                    <p class="text-sm text-amber-600">Refund sedang dalam proses verifikasi oleh staff keuangan.</p>
                </div>
            </div>
        @elseif($refund->status === 'approved')
            <div class="mb-6 bg-blue-50 border border-blue-200 rounded-xl p-4 flex items-start gap-3">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-blue-800">Disetujui</p>
                    <p class="text-sm text-blue-600">Refund telah disetujui. Menunggu diproses oleh staff keuangan.</p>
                </div>
            </div>
        @elseif($refund->status === 'processed')
            <div class="mb-6 bg-green-50 border border-green-200 rounded-xl p-4 flex items-start gap-3">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-green-800">Selesai Diproses</p>
                    <p class="text-sm text-green-600">Refund telah selesai diproses dan dana telah dikembalikan.</p>
                </div>
            </div>
        @elseif($refund->status === 'rejected')
            <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4 flex items-start gap-3">
                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-red-800">Ditolak</p>
                    <p class="text-sm text-red-600">Refund ditolak. @if($refund->catatan_approval) Alasan: {{ $refund->catatan_approval }} @endif</p>
                </div>
            </div>
        @endif

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <!-- Status -->
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center
                        @if($refund->status === 'draft') bg-slate-50 text-slate-600
                        @elseif($refund->status === 'menunggu_approval') bg-amber-50 text-amber-600
                        @elseif($refund->status === 'approved') bg-blue-50 text-blue-600
                        @elseif($refund->status === 'processed') bg-green-50 text-green-600
                        @elseif($refund->status === 'rejected') bg-red-50 text-red-600
                        @endif">
                        @if($refund->status === 'draft')
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 011.707.293l5.414 5.414a1 1 0 011.707.293V19a2 2 0 01-2 2z" />
                            </svg>
                        @elseif($refund->status === 'menunggu_approval')
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        @elseif($refund->status === 'approved' || $refund->status === 'processed')
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        @elseif($refund->status === 'rejected')
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        @endif
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Status</p>
                        <p class="text-lg font-bold text-gray-900 mt-1">
                            @if($refund->status === 'draft') Draft
                            @elseif($refund->status === 'menunggu_approval') Menunggu Approval
                            @elseif($refund->status === 'approved') Disetujui
                            @elseif($refund->status === 'processed') Selesai
                            @elseif($refund->status === 'rejected') Ditolak
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Jumlah Refund -->
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Jumlah Refund</p>
                        <p class="text-lg font-bold text-blue-600 mt-1">{{ formatRupiah($refund->jumlah_refund) }}</p>
                    </div>
                </div>
            </div>

            <!-- Jenis Refund -->
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-violet-50 text-violet-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6 6" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Jenis Refund</p>
                        <p class="text-lg font-bold text-violet-600 mt-1">{{ ucfirst(str_replace('_', ' ', $refund->jenis_refund)) }}</p>
                    </div>
                </div>
            </div>

            <!-- Tanggal -->
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-gray-50 text-gray-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Tanggal</p>
                        <p class="text-sm font-semibold text-gray-900 mt-1">{{ \Carbon\Carbon::parse($refund->tanggal_refund)->format('d M Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Informasi Refund -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                            Informasi Refund
                        </h2>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <dt class="text-xs text-gray-500 uppercase tracking-wider">Nomor Refund</dt>
                                <dd class="mt-1 font-mono text-sm font-semibold text-blue-600">{{ $refund->nomor_refund }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-500 uppercase tracking-wider">Jenis Refund</dt>
                                <dd class="mt-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-medium bg-violet-100 text-violet-700">
                                        {{ ucfirst(str_replace('_', ' ', $refund->jenis_refund)) }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-500 uppercase tracking-wider">Tanggal Refund</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($refund->tanggal_refund)->format('d F Y') }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-500 uppercase tracking-wider">Jumlah Refund</dt>
                                <dd class="mt-1 text-xl font-bold text-blue-600">{{ formatRupiah($refund->jumlah_refund) }}</dd>
                            </div>
                        </dl>

                        @if($refund->alasan_refund)
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <dt class="text-xs text-gray-500 uppercase tracking-wider">Alasan Refund</dt>
                            <dd class="mt-1 text-sm text-gray-700">{{ $refund->alasan_refund }}</dd>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Referensi -->
                @if($refund->pencairanDana || $refund->pengajuanDana || $refund->lpj)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4-4a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l4-4a4 4 0 001.064 1.065l-4-4a4 4 0 00-5.656 0z" />
                            </svg>
                            Referensi
                        </h2>
                    </div>
                    <div class="p-6">
                        @if($refund->lpj)
                            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-blue-900">Laporan Pertanggung Jawaban</p>
                                        <a href="{{ route('lpj.show', $refund->lpj) }}" target="_blank" class="text-blue-600 hover:underline text-sm font-medium">{{ $refund->lpj->nomor_lpj }}</a>
                                        <p class="text-xs text-blue-600 mt-0.5">{{ $refund->lpj->judul_lpj }}</p>
                                        @if($refund->lpj->sisa_dana > 0)
                                            <p class="text-xs text-green-600 font-medium mt-1">Sisa Dana: {{ formatRupiah($refund->lpj->sisa_dana) }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @elseif($refund->pencairanDana)
                            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0zM9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h2m2 4v6a2 2 0 002 2h3m-6 4h6" />
                                    </svg>
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-blue-900">Pencairan Dana</p>
                                        <a href="{{ route('pencairan-dana.show', $refund->pencairanDana) }}" target="_blank" class="text-blue-600 hover:underline text-sm font-medium">{{ $refund->pencairanDana->nomor_pencairan }}</a>
                                        <p class="text-xs text-blue-600 mt-0.5">{{ $refund->pencairanDana->pengajuanDana->judul_pengajuan }}</p>
                                    </div>
                                </div>
                            </div>
                        @elseif($refund->pengajuanDana)
                            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-blue-900">Pengajuan Dana</p>
                                        <a href="{{ route('pengajuan-dana.show', $refund->pengajuanDana) }}" target="_blank" class="text-blue-600 hover:underline text-sm font-medium">{{ $refund->pengajuanDana->nomor_pengajuan }}</a>
                                        <p class="text-xs text-blue-600 mt-0.5">{{ $refund->pengajuanDana->judul_pengajuan }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Bukti Transfer -->
                @if($refund->bukti_transfer)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 002.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415-6.585a6 6 0 00-8.486 8.486L20.5 13" />
                            </svg>
                            Bukti Transfer
                        </h2>
                    </div>
                    <div class="p-6">
                        <a href="{{ asset('storage/' . $refund->bukti_transfer) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-xl transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            <span class="text-sm font-medium">Lihat Bukti Transfer</span>
                        </a>
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Action Buttons -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-lg font-semibold text-gray-900">Aksi</h3>
                    </div>
                    <div class="p-4 space-y-2">
                        <!-- Print Button -->
                        <button onclick="window.print()" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            <span class="text-sm font-medium">Cetak</span>
                        </button>

                        @if(in_array($refund->status, ['draft', 'rejected']))
                            <a href="{{ route('refund.edit', $refund) }}" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                <span class="text-sm font-medium">Edit Refund</span>
                            </a>
                        @endif

                        @if($refund->status === 'draft')
                            <form method="POST" action="{{ route('refund.submit', $refund) }}" class="w-full">
                                @csrf
                                <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                    </svg>
                                    <span class="text-sm font-medium">Submit Approval</span>
                                </button>
                            </form>
                        @endif

                        @if($refund->status === 'menunggu_approval' && auth()->user()->hasAnyRole(['staff_keuangan', 'direktur_keuangan', 'direktur_utama']))
                            <div class="flex gap-2">
                                <button type="button" onclick="showApproveModal()" class="flex-1 flex items-center justify-center gap-1 px-3 py-2 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-xs font-medium">Setujui</span>
                                </button>
                                <button type="button" onclick="showRejectModal()" class="flex-1 flex items-center justify-center gap-1 px-3 py-2 border border-red-300 text-red-600 rounded-xl hover:bg-red-50 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    <span class="text-xs font-medium">Tolak</span>
                                </button>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Rekening Info -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-lg font-semibold text-gray-900">Informasi Rekening</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        @if($refund->rekeningPerusahaan)
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Rekening Tujuan</p>
                                <div class="bg-blue-50 border border-blue-100 rounded-lg p-3">
                                    <p class="text-sm font-semibold text-blue-900">{{ $refund->rekeningPerusahaan->bank->nama_bank }}</p>
                                    <p class="font-mono text-sm text-blue-700 mt-1">{{ $refund->rekeningPerusahaan->nomor_rekening_formatted }}</p>
                                    <p class="text-xs text-gray-600 mt-1">a.n {{ $refund->rekeningPerusahaan->atas_nama }}</p>
                                </div>
                            </div>
                        @endif

                        @if($refund->rekening_pengirim)
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Rekening Pengirim</p>
                                <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                                    <p class="text-sm text-gray-900 font-mono">{{ $refund->rekening_pengirim }}</p>
                                </div>
                            </div>
                        @endif

                        @if($refund->nama_pengirim)
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Nama Pengirim</p>
                                <p class="text-sm text-gray-900">{{ $refund->nama_pengirim }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Catatan Approval -->
                @if($refund->catatan_approval)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-lg font-semibold text-gray-900">Catatan Approval</h3>
                        </div>
                        <div class="p-6">
                            <p class="text-sm text-gray-700">{{ $refund->catatan_approval }}</p>
                            <div class="mt-3 flex items-center gap-2 text-xs text-gray-500">
                                <span>Oleh:</span>
                                <span class="font-medium text-gray-900">{{ $refund->approvedBy->name ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Audit Info -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    <p class="text-xs text-gray-500 uppercase tracking-wider mb-3">Audit Info</p>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Dibuat oleh:</span>
                            <span class="font-medium text-gray-900">{{ $refund->createdBy->name ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tanggal dibuat:</span>
                            <span class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($refund->created_at)->format('d M Y, H:i') }}</span>
                        </div>
                        @if($refund->approved_at)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tanggal approval:</span>
                            <span class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($refund->approved_at)->format('d M Y, H:i') }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Approve Modal -->
    <div id="approveModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-lg max-w-md w-full mx-4 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-green-50">
                <h3 class="text-lg font-semibold text-green-900">Setujui Refund</h3>
            </div>
            <form method="POST" action="{{ route('refund.approve', $refund) }}" class="p-6">
                @csrf
                <input type="hidden" name="status" value="approved">
                <div class="mb-4">
                    <p class="text-sm text-gray-600">Apakah Anda yakin ingin menyetujui refund ini?</p>
                    <div class="mt-2 bg-blue-50 border border-blue-200 rounded-lg p-3">
                        <p class="text-xs text-gray-600">Nomor: <span class="font-semibold text-gray-900">{{ $refund->nomor_refund }}</span></p>
                        <p class="text-xs text-gray-600">Jumlah: <span class="font-semibold text-gray-900">{{ formatRupiah($refund->jumlah_refund) }}</span></p>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                    <textarea name="catatan_approval" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" placeholder="Tambahkan catatan approval..."></textarea>
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="closeApproveModal()" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-sm">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm">
                        Ya, Setujui
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-lg max-w-md w-full mx-4 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-red-50">
                <h3 class="text-lg font-semibold text-red-900">Tolak Refund</h3>
            </div>
            <form method="POST" action="{{ route('refund.approve', $refund) }}" class="p-6">
                @csrf
                <input type="hidden" name="status" value="rejected">
                <div class="mb-4">
                    <p class="text-sm text-gray-600">Apakah Anda yakin ingin menolak refund ini?</p>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Penolakan <span class="text-red-500">*</span></label>
                    <textarea name="catatan_approval" rows="3" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" placeholder="Jelaskan alasan penolakan..."></textarea>
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="closeRejectModal()" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-sm">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm">
                        Ya, Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showApproveModal() {
            document.getElementById('approveModal').classList.remove('hidden');
            document.getElementById('approveModal').classList.add('flex');
        }

        function closeApproveModal() {
            document.getElementById('approveModal').classList.add('hidden');
            document.getElementById('approveModal').classList.remove('flex');
        }

        function showRejectModal() {
            document.getElementById('rejectModal').classList.remove('hidden');
            document.getElementById('rejectModal').classList.add('flex');
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
            document.getElementById('rejectModal').classList.remove('flex');
        }

        // Close modals on outside click
        document.getElementById('approveModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeApproveModal();
            }
        });

        document.getElementById('rejectModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeRejectModal();
            }
        });
    </script>
</x-app-layout>
