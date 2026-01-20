<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('pencatatan-penerimaan.index') }}" class="w-10 h-10 rounded-xl bg-blue-50 hover:bg-blue-100 flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-xl font-semibold text-gray-900">Detail Pencatatan Penerimaan</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Informasi lengkap penerimaan dana</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('pencatatan-penerimaan.edit', $pencatatan) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-5xl">
        <!-- Main Card -->
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-500 px-8 py-10">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium mb-2">{{ \Carbon\Carbon::parse($pencatatan->tanggal_penerimaan)->format('l, d F Y') }}</p>
                        <h2 class="text-3xl font-bold text-white">{{ formatRupiah($pencatatan->jumlah_diterima) }}</h2>
                        <p class="text-blue-100 mt-1">{{ $pencatatan->sumberDana->nama_sumber ?? '-' }}</p>
                    </div>
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="p-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Left Column -->
                    <div class="space-y-8">
                        <!-- Informasi Penerimaan -->
                        <div>
                            <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Informasi Penerimaan</h3>
                            <div class="space-y-4">
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-0.5">Tanggal Penerimaan</p>
                                        <p class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($pencatatan->tanggal_penerimaan)->format('d F Y') }}</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-0.5">Sumber Dana</p>
                                        <p class="text-sm font-medium text-gray-900">{{ $pencatatan->sumberDana->nama_sumber ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-0.5">Uraian</p>
                                        <p class="text-sm text-gray-700 leading-relaxed">{{ $pencatatan->uraian }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Bukti Penerimaan -->
                        @if($pencatatan->bukti_penerimaan)
                            <div>
                                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Bukti Penerimaan</h3>
                                <a href="{{ asset('storage/' . $pencatatan->bukti_penerimaan) }}" target="_blank" class="flex items-center gap-4 p-4 rounded-xl border border-gray-200 hover:border-blue-300 hover:bg-blue-50/50 transition-all group">
                                    <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center flex-shrink-0 group-hover:bg-blue-200 transition-colors">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">{{ basename($pencatatan->bukti_penerimaan) }}</p>
                                        <p class="text-xs text-gray-500">Klik untuk melihat file</p>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-8">
                        <!-- Periode & Referensi -->
                        <div>
                            <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Periode & Referensi</h3>
                            <div class="space-y-4">
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-0.5">Periode Anggaran</p>
                                        <p class="text-sm font-medium text-gray-900">{{ $pencatatan->periodeAnggaran->nama_periode ?? '-' }}</p>
                                        <p class="text-xs text-gray-500">{{ $pencatatan->periodeAnggaran->tahun_anggaran ?? '-' }}</p>
                                    </div>
                                </div>
                                @if($pencatatan->perencanaanPenerimaan)
                                    <div class="flex items-start gap-4">
                                        <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500 mb-0.5">Referensi Perencanaan</p>
                                            <a href="{{ route('perencanaan-penerimaan.show', $pencatatan->perencanaanPenerimaan) }}" class="text-sm font-medium text-blue-600 hover:text-blue-700 transition-colors">
                                                {{ Str::limit($pencatatan->perencanaanPenerimaan->uraian, 60) }}
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Timestamp -->
                        <div>
                            <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Informasi Sistem</h3>
                            <div class="space-y-4">
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-0.5">Dibuat Oleh</p>
                                        <p class="text-sm font-medium text-gray-900">{{ $pencatatan->createdBy->name ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-0.5">Tanggal Dibuat</p>
                                        <p class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($pencatatan->created_at)->format('d F Y, H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
