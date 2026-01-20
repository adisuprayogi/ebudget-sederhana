<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('perencanaan-penerimaan.index') }}" class="w-10 h-10 rounded-xl bg-blue-50 hover:bg-blue-100 flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-xl font-semibold text-gray-900">Detail Perencanaan Penerimaan</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Informasi lengkap perencanaan dana</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('perencanaan-penerimaan.edit', $perencanaan) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-6xl space-y-6">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium">Total Estimasi</p>
                        <p class="text-lg font-bold text-gray-900">{{ formatRupiah($perencanaan->jumlah_estimasi) }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium">Terealisasi</p>
                        <p class="text-lg font-bold text-gray-900">{{ formatRupiah($perencanaan->realisasi) }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium">Persentase</p>
                        <p class="text-lg font-bold text-gray-900">{{ number_format($perencanaan->persentase_realisasi, 1) }}%</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-500 px-8 py-8">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-blue-100/20 text-blue-100 text-sm font-medium">
                                {{ $perencanaan->kode_rekening ?? '-' }}
                            </span>
                            @if($perencanaan->divisi)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/20 text-white text-sm font-medium">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    {{ $perencanaan->divisi->nama_divisi }}
                                </span>
                            @endif
                        </div>
                        <h2 class="text-2xl font-bold text-white leading-tight">{{ $perencanaan->uraian }}</h2>
                        <p class="text-blue-100 mt-2">{{ $perencanaan->sumberDana->nama_sumber ?? '-' }}  {{ $perencanaan->periodeAnggaran->nama_periode ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="p-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Left Column - Main Info -->
                    <div class="lg:col-span-2 space-y-8">
                        <!-- Perkiraan Per Bulan -->
                        <div>
                            <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Perkiraan Per Bulan</h3>
                            @php
                                $perkiraanBulanan = $perencanaan->perkiraan_bulanan ?? [];
                                $bulanList = $perencanaan->bulan_list ?? [];
                            @endphp
                            @if(empty($bulanList))
                                <div class="text-center py-8">
                                    <p class="text-gray-500 text-sm">Tidak ada data bulan tersedia</p>
                                </div>
                            @else
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                                    @foreach($bulanList as $key => $label)
                                        @php $nilai = $perkiraanBulanan[$key] ?? 0; @endphp
                                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                            <p class="text-xs text-gray-500 font-medium mb-1">{{ $label }}</p>
                                            <p class="text-base font-bold text-gray-900">{{ formatRupiah($nilai) }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <!-- Progress Realisasi -->
                        <div>
                            <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Progress Realisasi</h3>
                            <div class="bg-gray-50 rounded-xl p-6">
                                <div class="flex justify-between items-end mb-3">
                                    <div>
                                        <p class="text-sm text-gray-600 mb-1">Total Terealisasi</p>
                                        <p class="text-2xl font-bold text-gray-900">{{ formatRupiah($perencanaan->realisasi) }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-3xl font-bold text-blue-600">{{ number_format($perencanaan->persentase_realisasi, 1) }}%</p>
                                    </div>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                                    <div class="h-2 rounded-full bg-gradient-to-r from-blue-500 to-blue-600 transition-all duration-500" style="width: {{ min($perencanaan->persentase_realisasi, 100) }}%"></div>
                                </div>
                                <div class="flex justify-between mt-3 text-sm">
                                    <span class="text-gray-500">Estimasi: {{ formatRupiah($perencanaan->jumlah_estimasi) }}</span>
                                    <span class="text-gray-500">Sisa: {{ formatRupiah($perencanaan->sisa_estimasi) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Pencatatan Penerimaan -->
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Pencatatan Penerimaan</h3>
                                <a href="{{ route('pencatatan-penerimaan.create', ['perencanaan_penerimaan_id' => $perencanaan->id]) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Tambah
                                </a>
                            </div>
                            @if($perencanaan->pencatatanPenerimaans && $perencanaan->pencatatanPenerimaans->count() > 0)
                                <div class="border border-gray-200 rounded-xl overflow-hidden">
                                    <table class="w-full">
                                        <thead class="bg-gray-50 border-b border-gray-200">
                                            <tr>
                                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Uraian</th>
                                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Jumlah</th>
                                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Bukti</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200">
                                            @foreach($perencanaan->pencatatanPenerimaans as $pencatatan)
                                                <tr class="hover:bg-gray-50 transition-colors">
                                                    <td class="px-4 py-3">
                                                        <span class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($pencatatan->tanggal_penerimaan)->format('d/m/Y') }}</span>
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        <span class="text-sm text-gray-700">{{ Str::limit($pencatatan->uraian, 40) }}</span>
                                                    </td>
                                                    <td class="px-4 py-3 text-right">
                                                        <span class="text-sm font-bold text-emerald-600">{{ formatRupiah($pencatatan->jumlah_diterima) }}</span>
                                                    </td>
                                                    <td class="px-4 py-3 text-center">
                                                        @if($pencatatan->bukti_penerimaan)
                                                            <a href="{{ asset('storage/' . $pencatatan->bukti_penerimaan) }}" target="_blank" class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-100 text-blue-700 hover:bg-blue-200 rounded-lg text-xs font-medium transition-colors">
                                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 112.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                                                </svg>
                                                                Lihat
                                                            </a>
                                                        @else
                                                            <span class="text-gray-400 text-xs">-</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-8 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                                    <svg class="w-10 h-10 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="text-gray-500 text-sm">Belum ada pencatatan penerimaan</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Right Column - Details -->
                    <div class="space-y-8">
                        <!-- Informasi Perencanaan -->
                        <div>
                            <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Informasi Perencanaan</h3>
                            <div class="space-y-4">
                                <div class="flex items-start gap-3">
                                    <div class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4.5 h-4.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-0.5">Kode Rekening</p>
                                        <p class="text-sm font-semibold text-gray-900">{{ $perencanaan->kode_rekening ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4.5 h-4.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-0.5">Periode Anggaran</p>
                                        <p class="text-sm font-semibold text-gray-900">{{ $perencanaan->periodeAnggaran->nama_periode ?? '-' }}</p>
                                        <p class="text-xs text-gray-500">{{ $perencanaan->periodeAnggaran->tahun_anggaran ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4.5 h-4.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-0.5">Divisi</p>
                                        <p class="text-sm font-semibold text-gray-900">{{ $perencanaan->divisi->nama_divisi ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4.5 h-4.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-0.5">Sumber Dana</p>
                                        <p class="text-sm font-semibold text-gray-900">{{ $perencanaan->sumberDana->nama_sumber ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4.5 h-4.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-0.5">Total Bulanan</p>
                                        <p class="text-sm font-semibold text-gray-900">{{ formatRupiah($perencanaan->total_bulanan) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Catatan -->
                        @if($perencanaan->catatan)
                            <div>
                                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Catatan</h3>
                                <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                    <p class="text-sm text-gray-700 leading-relaxed">{{ $perencanaan->catatan }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- Informasi Sistem -->
                        <div>
                            <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Informasi Sistem</h3>
                            <div class="space-y-4">
                                <div class="flex items-start gap-3">
                                    <div class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4.5 h-4.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-0.5">Dibuat Oleh</p>
                                        <p class="text-sm font-semibold text-gray-900">{{ $perencanaan->createdBy->name ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4.5 h-4.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-0.5">Tanggal Dibuat</p>
                                        <p class="text-sm font-semibold text-gray-900">{{ \Carbon\Carbon::parse($perencanaan->created_at)->format('d F Y, H:i') }}</p>
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
