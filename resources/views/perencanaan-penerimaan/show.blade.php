<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Detail Perencanaan Penerimaan</h1>
                <p class="text-gray-600 mt-1">Informasi lengkap perencanaan penerimaan dana</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('perencanaan-penerimaan.edit', $perencanaan) }}" class="inline-flex items-center px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </a>
                <a href="{{ route('perencanaan-penerimaan.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-6xl mx-auto py-8">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-sm text-gray-500 mb-1">Total Estimasi</div>
                <div class="text-2xl font-bold text-blue-600">{{ formatRupiah($perencanaan->jumlah_estimasi) }}</div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-sm text-gray-500 mb-1">Terealisasi</div>
                <div class="text-2xl font-bold text-green-600">{{ formatRupiah($perencanaan->realisasi) }}</div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-sm text-gray-500 mb-1">Persentase Realisasi</div>
                <div class="text-2xl font-bold text-amber-600">{{ number_format($perencanaan->persentase_realisasi, 1) }}%</div>
            </div>
        </div>

        <!-- Detail Perencanaan -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-6">Informasi Perencanaan</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-sm text-gray-500">Kode Rekening</label>
                    <div class="mt-1 font-mono font-semibold text-blue-600">{{ $perencanaan->kode_rekening ?? '-' }}</div>
                </div>
                <div>
                    <label class="text-sm text-gray-500">Periode Anggaran</label>
                    <div class="mt-1 font-medium text-gray-900">{{ $perencanaan->periodeAnggaran->nama_periode ?? '-' }} ({{ $perencanaan->periodeAnggaran->tahun_anggaran ?? '-' }})</div>
                </div>
                <div>
                    <label class="text-sm text-gray-500">Divisi</label>
                    <div class="mt-1 font-medium text-gray-900">{{ $perencanaan->divisi->nama_divisi ?? '-' }}</div>
                </div>
                <div>
                    <label class="text-sm text-gray-500">Sumber Dana</label>
                    <div class="mt-1">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-700">
                            {{ $perencanaan->sumberDana->nama_sumber ?? '-' }}
                        </span>
                    </div>
                </div>
                <div class="md:col-span-2">
                    <label class="text-sm text-gray-500">Uraian</label>
                    <div class="mt-1 text-gray-900">{{ $perencanaan->uraian }}</div>
                </div>
                <div>
                    <label class="text-sm text-gray-500">Jumlah Estimasi</label>
                    <div class="mt-1 text-2xl font-bold text-blue-600">{{ formatRupiah($perencanaan->jumlah_estimasi) }}</div>
                </div>
                <div>
                    <label class="text-sm text-gray-500">Total Per Bulan</label>
                    <div class="mt-1 text-2xl font-bold text-green-600">{{ formatRupiah($perencanaan->total_bulanan) }}</div>
                </div>
            </div>
        </div>

        <!-- Per Bulan -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-2">Perkiraan Per Bulan</h2>
            <p class="text-sm text-gray-500 mb-6">Estimasi penerimaan per bulan sesuai periode anggaran</p>

            @php
                $colors = [
                    'from-green-50 to-green-100 text-green-700',
                    'from-blue-50 to-blue-100 text-blue-700',
                    'from-amber-50 to-amber-100 text-amber-700',
                    'from-purple-50 to-purple-100 text-purple-700'
                ];
                $bulanList = $perencanaan->bulan_list;
                $perkiraanBulanan = $perencanaan->perkiraan_bulanan ?? [];
            @endphp

            @if(empty($bulanList))
                <div class="text-center py-8 text-gray-500">
                    <p>Tidak ada data bulan tersedia</p>
                </div>
            @else
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @php $index = 0; @endphp
                    @foreach($bulanList as $key => $label)
                        @php
                            $colorClass = $colors[$index % count($colors)];
                            $nilai = $perkiraanBulanan[$key] ?? 0;
                            $index++;
                        @endphp
                        <div class="bg-gradient-to-br {{ $colorClass }} rounded-lg p-4">
                            <div class="text-sm font-medium mb-1">{{ $label }}</div>
                            <div class="text-lg font-bold">{{ formatRupiah($nilai) }}</div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6 flex justify-between items-center bg-gray-50 rounded-lg px-6 py-4">
                    <span class="text-sm font-medium text-gray-700">Total Per Bulan</span>
                    <span class="text-xl font-bold text-gray-900">{{ formatRupiah($perencanaan->total_bulanan) }}</span>
                </div>
            @endif
        </div>

        <!-- Realization Summary -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-6">Ringkasan Realisasi</h2>

            <div class="space-y-4">
                <div class="flex justify-between items-center py-3 border-b border-gray-100">
                    <span class="text-gray-600">Total Estimasi</span>
                    <span class="font-semibold text-gray-900">{{ formatRupiah($perencanaan->jumlah_estimasi) }}</span>
                </div>
                <div class="flex justify-between items-center py-3 border-b border-gray-100">
                    <span class="text-gray-600">Total Terealisasi</span>
                    <span class="font-semibold text-green-600">{{ formatRupiah($perencanaan->realisasi) }}</span>
                </div>
                <div class="flex justify-between items-center py-3 border-b border-gray-100">
                    <span class="text-gray-600">Sisa</span>
                    <span class="font-semibold text-amber-600">{{ formatRupiah($perencanaan->sisa_estimasi) }}</span>
                </div>
                <div class="flex justify-between items-center py-3">
                    <span class="text-gray-600">Persentase Realisasi</span>
                    <span class="font-bold text-blue-600">{{ number_format($perencanaan->persentase_realisasi, 1) }}%</span>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="mt-6">
                <div class="flex justify-between text-sm text-gray-600 mb-2">
                    <span>Progress Realisasi</span>
                    <span>{{ number_format($perencanaan->persentase_realisasi, 1) }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="h-3 rounded-full transition-all duration-300 @if($perencanaan->persentase_realisasi >= 90) bg-red-500 @elseif($perencanaan->persentase_realisasi >= 70) bg-amber-500 @else bg-green-500 @endif" style="width: {{ min($perencanaan->persentase_realisasi, 100) }}%"></div>
                </div>
            </div>
        </div>

        <!-- Catatan -->
        @if($perencanaan->catatan)
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Catatan</h2>
                <p class="text-gray-700">{{ $perencanaan->catatan }}</p>
            </div>
        @endif

        <!-- Pencatatan Penerimaan List -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-gray-900">Pencatatan Penerimaan</h2>
                <a href="{{ route('pencatatan-penerimaan.create', ['perencanaan_penerimaan_id' => $perencanaan->id]) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Pencatatan
                </a>
            </div>

            @if($perencanaan->pencatatanPenerimaans && $perencanaan->pencatatanPenerimaans->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Tanggal</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Uraian</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Jumlah</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Bukti</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Oleh</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($perencanaan->pencatatanPenerimaans as $pencatatan)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm">{{ \Carbon\Carbon::parse($pencatatan->tanggal_penerimaan)->format('d/m/Y') }}</td>
                                    <td class="px-4 py-3">
                                        <div class="font-medium text-gray-900">{{ $pencatatan->uraian }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-right font-semibold text-gray-900">{{ formatRupiah($pencatatan->jumlah_diterima) }}</td>
                                    <td class="px-4 py-3 text-center">
                                        @if($pencatatan->bukti_penerimaan)
                                            <a href="{{ asset('storage/' . $pencatatan->bukti_penerimaan) }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                                <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 112.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                                </svg>
                                                Lihat Bukti
                                            </a>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $pencatatan->createdBy->name ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.514a2 2 0 011.714 1l.506 3.026a2 2 0 01-.714 2.212l-1.506 3.026a2 2 0 01-1.714 1H7a2 2 0 01-2-2V9a2 2 0 012-2h5.514z" />
                    </svg>
                    <p>Belum ada pencatatan penerimaan</p>
                </div>
            @endif

            <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-blue-700">Total Penerimaan:</span>
                    <span class="text-sm font-bold text-blue-900">{{ formatRupiah($perencanaan->realisasi) }}</span>
                </div>
            </div>
        </div>

        <!-- Audit Info -->
        <div class="mt-6 flex items-center justify-between text-sm text-gray-500">
            <div>Dibuat oleh: {{ $perencanaan->createdBy->name ?? '-' }}</div>
            <div>{{ \Carbon\Carbon::parse($perencanaan->created_at)->format('d F Y, H:i') }}</div>
        </div>
    </div>
</x-app-layout>
