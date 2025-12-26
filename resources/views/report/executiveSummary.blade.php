<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-secondary-900">Eksekutif Summary</h1>
                <p class="text-secondary-600 mt-1">Ringkasan eksekutif untuk manajemen puncak</p>
            </div>
            <div class="flex items-center space-x-3">
                @if(auth()->user()->hasPermission('report.export'))
                    <button onclick="window.print()" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Cetak
                    </button>
                @endif
                <a href="{{ route('reports.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-secondary-300 text-secondary-700 rounded-xl hover:bg-secondary-50 transition-all duration-200">
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <!-- Year Selector -->
        <div class="bg-white rounded-2xl shadow-soft p-6 mb-8">
            <form method="GET" action="{{ route('reports.executive-summary') }}" class="flex items-center gap-4">
                <label class="text-sm font-medium text-secondary-700">Tahun Anggaran:</label>
                <select name="tahun" class="px-4 py-2 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    @foreach($filterOptions['years'] ?? [] as $year)
                        <option value="{{ $year }}" {{ ($filters['tahun'] ?? date('Y')) == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
                <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-all duration-200">
                    Tampilkan
                </button>
            </form>
        </div>

        <!-- Executive Summary Dashboard -->
        <div class="space-y-6">
            <!-- Key Metrics -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg p-6 text-white">
                    <div class="text-sm text-blue-100 mb-1">Total Pagu Anggaran</div>
                    <div class="text-2xl font-bold">{{ formatRupiah($executiveSummary['total_pagu'] ?? 0) }}</div>
                </div>
                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl shadow-lg p-6 text-white">
                    <div class="text-sm text-green-100 mb-1">Total Pencairan</div>
                    <div class="text-2xl font-bold">{{ formatRupiah($executiveSummary['total_pencairan'] ?? 0) }}</div>
                </div>
                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl shadow-lg p-6 text-white">
                    <div class="text-sm text-purple-100 mb-1">Tingkat Penyerapan</div>
                    <div class="text-2xl font-bold">{{ number_format($executiveSummary['tingkat_penyerapan'] ?? 0, 1) }}%</div>
                </div>
                <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-2xl shadow-lg p-6 text-white">
                    <div class="text-sm text-amber-100 mb-1">Sisa Kas</div>
                    <div class="text-2xl font-bold">{{ formatRupiah($executiveSummary['sisa_kas'] ?? 0) }}</div>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Pengajuan Summary -->
                <div class="bg-white rounded-2xl shadow-soft p-6">
                    <h3 class="text-lg font-semibold text-secondary-900 mb-4">Ringkasan Pengajuan Dana</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-secondary-600">Total Pengajuan</span>
                            <span class="font-semibold text-secondary-900">{{ $executiveSummary['pengajuan']['total'] ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-secondary-600">Disetujui</span>
                            <span class="font-semibold text-green-600">{{ $executiveSummary['pengajuan']['approved'] ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-secondary-600">Ditolak</span>
                            <span class="font-semibold text-red-600">{{ $executiveSummary['pengajuan']['rejected'] ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-secondary-600">Pending</span>
                            <span class="font-semibold text-amber-600">{{ $executiveSummary['pengajuan']['pending'] ?? 0 }}</span>
                        </div>
                    </div>
                </div>

                <!-- LPJ Summary -->
                <div class="bg-white rounded-2xl shadow-soft p-6">
                    <h3 class="text-lg font-semibold text-secondary-900 mb-4">Ringkasan LPJ</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-secondary-600">Total LPJ Masuk</span>
                            <span class="font-semibold text-secondary-900">{{ $executiveSummary['lpj']['total'] ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-secondary-600">Terverifikasi</span>
                            <span class="font-semibold text-green-600">{{ $executiveSummary['lpj']['verified'] ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-secondary-600">Belum Diverifikasi</span>
                            <span class="font-semibold text-amber-600">{{ $executiveSummary['lpj']['pending'] ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-secondary-600">Tingkat Verifikasi</span>
                            <span class="font-semibold text-primary-600">{{ number_format($executiveSummary['lpj']['verification_rate'] ?? 0, 1) }}%</span>
                        </div>
                    </div>
                </div>

                <!-- Penerimaan Summary -->
                <div class="bg-white rounded-2xl shadow-soft p-6">
                    <h3 class="text-lg font-semibold text-secondary-900 mb-4">Ringkasan Penerimaan Dana</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-secondary-600">Total Perencanaan</span>
                            <span class="font-semibold text-secondary-900">{{ formatRupiah($executiveSummary['penerimaan']['total_perencanaan'] ?? 0) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-secondary-600">Terealisasi</span>
                            <span class="font-semibold text-green-600">{{ formatRupiah($executiveSummary['penerimaan']['terealisasi'] ?? 0) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-secondary-600">Tingkat Realisasi</span>
                            <span class="font-semibold text-primary-600">{{ number_format($executiveSummary['penerimaan']['realisasi_rate'] ?? 0, 1) }}%</span>
                        </div>
                    </div>
                </div>

                <!-- Refund Summary -->
                <div class="bg-white rounded-2xl shadow-soft p-6">
                    <h3 class="text-lg font-semibold text-secondary-900 mb-4">Ringkasan Refund</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-secondary-600">Total Refund</span>
                            <span class="font-semibold text-secondary-900">{{ $executiveSummary['refund']['total'] ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-secondary-600">Total Nominal</span>
                            <span class="font-semibold text-red-600">{{ formatRupiah($executiveSummary['refund']['total_nominal'] ?? 0) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-secondary-600">Diproses</span>
                            <span class="font-semibold text-green-600">{{ $executiveSummary['refund']['processed'] ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Divisions -->
            <div class="bg-white rounded-2xl shadow-soft p-6">
                <h3 class="text-lg font-semibold text-secondary-900 mb-4">Top Divisi - Penyerapan Anggaran Tertinggi</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach(array_slice($executiveSummary['top_divisions'] ?? [], 0, 3) as $div)
                        <div class="bg-secondary-50 rounded-xl p-4">
                            <div class="font-semibold text-secondary-900">{{ $div['nama_divisi'] ?? '-' }}</div>
                            <div class="text-sm text-secondary-500 mt-1">
                                {{ formatRupiah($div['realisasi'] ?? 0) }} / {{ formatRupiah($div['pagu'] ?? 0) }}
                            </div>
                            <div class="text-lg font-bold text-primary-600 mt-2">{{ number_format($div['persentase'] ?? 0, 1) }}%</div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Alerts & Recommendations -->
            @if(isset($executiveSummary['alerts']) && count($executiveSummary['alerts']) > 0)
                <div class="bg-amber-50 border-l-4 border-amber-400 rounded-r-2xl p-6">
                    <h3 class="text-lg font-semibold text-amber-900 mb-4">Perhatian & Rekomendasi</h3>
                    <ul class="space-y-2">
                        @foreach($executiveSummary['alerts'] as $alert)
                            <li class="flex items-start text-amber-800">
                                <svg class="h-5 w-5 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                {{ $alert }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="mt-8 text-center text-sm text-secondary-500">
            <p>Laporan ini dibuat secara otomatis oleh sistem e-Budget pada {{ now()->format('d F Y, H:i') }}</p>
        </div>
    </div>
</x-app-layout>
