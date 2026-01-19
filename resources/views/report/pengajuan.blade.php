<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">Laporan Pengajuan Dana</h1>
                <p class="text-sm text-gray-500 mt-0.5">{{ $selectedPeriode ? $selectedPeriode->nama_periode : 'Semua Periode' }}</p>
            </div>
            <div class="flex items-center gap-2">
                @if(auth()->user()->hasPermission('report.export'))
                    <button onclick="exportReport('excel')" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Export Excel
                    </button>
                @endif
                <a href="{{ route('reports.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm font-medium rounded-lg transition-colors">
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <!-- Filter -->
    <div class="bg-white rounded-xl border border-blue-100 p-3 mb-4">
        <form method="GET" action="{{ route('reports.pengajuan') }}" class="flex flex-wrap items-center gap-3">
            <div class="min-w-[160px]">
                <select name="periode_anggaran_id" class="w-full px-3 py-2 text-sm border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all bg-white">
                    <option value="">Semua Periode</option>
                    @foreach($availablePeriodes ?? [] as $periode)
                        @php
                            $isSelected = ($selectedPeriode && $selectedPeriode->id == $periode->id);
                            $isActive = ($activePeriode && $activePeriode->id == $periode->id);
                        @endphp
                        <option value="{{ $periode->id }}" {{ $isSelected ? 'selected' : '' }}>
                            {{ $periode->nama_periode }}
                            @if($isActive) (Aktif) @endif
                        </option>
                    @endforeach
                </select>
            </div>
            @if($permissions['view_all'] ?? false)
                <div class="min-w-[140px]">
                    <select name="divisi_id" class="w-full px-3 py-2 text-sm border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all bg-white">
                        <option value="">Semua Divisi</option>
                        @foreach($filterOptions['divisis'] ?? [] as $divisi)
                            <option value="{{ $divisi->id }}" {{ ($filters['divisi_id'] ?? '') == $divisi->id ? 'selected' : '' }}>{{ $divisi->nama_divisi }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
            <div class="min-w-[140px]">
                <input type="date" name="tanggal_mulai" value="{{ $filters['tanggal_mulai'] ?? '' }}" placeholder="Tanggal Mulai" class="w-full px-3 py-2 text-sm border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
            </div>
            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                Filter
            </button>
        </form>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-4 gap-3 mb-4">
        <div class="bg-white rounded-xl border border-blue-100 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xl font-semibold text-gray-900">{{ $statistics['total_pengajuan'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500">Total Pengajuan</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-blue-100 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-violet-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-900">{{ formatRupiah($statistics['total_nominal_pengajuan'] ?? 0) }}</p>
                    <p class="text-xs text-gray-500">Total Nominal</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-blue-100 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-emerald-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xl font-semibold text-gray-900">{{ $statistics['pengajuan_approved'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500">Disetujui</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-blue-100 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-amber-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xl font-semibold text-gray-900">{{ $statistics['pengajuan_pending'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500">Pending</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
        <!-- Monthly Trend -->
        <div class="bg-white rounded-xl border border-blue-100 p-4">
            <h3 class="text-sm font-semibold text-gray-900 mb-3">Tren Bulanan</h3>
            <div class="h-48 flex items-end justify-around gap-1">
                @php($maxTotal = collect($monthlyTrend)->max('pengajuan_total') ?: 1)
                @foreach($monthlyTrend ?? [] as $month)
                    <div class="flex flex-col items-center flex-1">
                        <div class="w-full bg-blue-500 rounded-t" style="height: {{ ($month['pengajuan_total'] / $maxTotal) * 160 }}px; min-height: 4px;"></div>
                        <div class="text-xs text-gray-500 mt-1">{{ substr($month['month_name'], 0, 3) }}</div>
                        <div class="text-xs font-semibold text-gray-700">{{ formatRupiah($month['pengajuan_total']) }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Jenis Analysis -->
        <div class="bg-white rounded-xl border border-blue-100 p-4">
            <h3 class="text-sm font-semibold text-gray-900 mb-3">Analisis Jenis Pengajuan</h3>
            <div class="space-y-2">
                @foreach($jenisAnalysis ?? [] as $jenisName => $jenisData)
                    <div class="flex items-center gap-2">
                        <div class="flex-1">
                            <div class="flex justify-between text-xs mb-0.5">
                                <span class="text-gray-700">{{ ucfirst(str_replace('_', ' ', $jenisName)) }}</span>
                                <span class="font-semibold text-gray-900">{{ formatRupiah($jenisData['total_pengajuan']) }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-1.5">
                                @php($totalAll = collect($jenisAnalysis)->sum('total_pengajuan'))
                                <div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ ($jenisData['total_pengajuan'] / ($totalAll ?? 1)) * 100 }}%"></div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Division Comparison -->
    @if($permissions['view_all'] ?? false)
        <div class="bg-white rounded-xl border border-blue-100 p-4 mb-4">
            <h3 class="text-sm font-semibold text-gray-900 mb-3">Perbandingan Divisi</h3>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-blue-50 border-b border-blue-100">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-semibold text-blue-700 uppercase">Divisi</th>
                            <th class="px-4 py-2 text-right text-xs font-semibold text-blue-700 uppercase">Jumlah</th>
                            <th class="px-4 py-2 text-right text-xs font-semibold text-blue-700 uppercase">Total Nominal</th>
                            <th class="px-4 py-2 text-right text-xs font-semibold text-blue-700 uppercase">Rata-rata</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-blue-50">
                        @foreach($divisionComparison ?? [] as $div)
                            <tr class="hover:bg-blue-50/50 transition-colors">
                                <td class="px-4 py-2 text-sm text-gray-900">{{ $div['divisi'] }}</td>
                                <td class="px-4 py-2 text-sm text-right text-gray-900">{{ $div['pengajuan_count'] }}</td>
                                <td class="px-4 py-2 text-sm text-right font-semibold text-blue-600">{{ formatRupiah($div['total_pengajuan']) }}</td>
                                <td class="px-4 py-2 text-sm text-right text-gray-700">{{ formatRupiah($div['avg_pengajuan'] ?? 0) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- High Value Transactions -->
    <div class="bg-white rounded-xl border border-blue-100 p-4">
        <h3 class="text-sm font-semibold text-gray-900 mb-3">Transaksi Bernilai Besar (>{{ formatRupiah($filters['threshold'] ?? 100000000) }})</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-blue-50 border-b border-blue-100">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-blue-700 uppercase">Nomor</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-blue-700 uppercase">Uraian</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-blue-700 uppercase">Divisi</th>
                        <th class="px-4 py-2 text-right text-xs font-semibold text-blue-700 uppercase">Total</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-blue-700 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-blue-50">
                    @foreach($highValueTransactions ?? [] as $trx)
                        <tr class="hover:bg-blue-50/50 transition-colors">
                            <td class="px-4 py-2 text-sm font-mono text-blue-600">{{ $trx->nomor_pengajuan }}</td>
                            <td class="px-4 py-2 text-sm text-gray-900">{{ $trx->uraian }}</td>
                            <td class="px-4 py-2 text-sm text-gray-700">{{ $trx->divisi->nama_divisi ?? '-' }}</td>
                            <td class="px-4 py-2 text-sm text-right font-semibold text-gray-900">{{ formatRupiah($trx->total_pengajuan) }}</td>
                            <td class="px-4 py-2">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                    @if($trx->status === 'approved') bg-emerald-100 text-emerald-700
                                    @elseif($trx->status === 'proposed') bg-amber-100 text-amber-700
                                    @else bg-gray-100 text-gray-700 @endif">
                                    {{ $trx->status }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function exportReport(format) {
            const params = new URLSearchParams({
                format: format,
                ...@js($filters ?? [])
            });
            window.location.href = '{{ route('reports.export', ['pengajuan']) }}?' + params.toString();
        }
    </script>
</x-app-layout>
