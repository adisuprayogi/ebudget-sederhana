<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">Laporan Realisasi Anggaran</h1>
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
        <form method="GET" action="{{ route('reports.budget-realization') }}" class="flex flex-wrap items-center gap-3">
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
            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                Filter
            </button>
        </form>
    </div>

    <!-- Overall Summary -->
    <div class="grid grid-cols-3 gap-3 mb-4">
        <div class="bg-white rounded-xl border border-blue-100 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-900">{{ formatRupiah($budgetRealization['total_pagu'] ?? 0) }}</p>
                    <p class="text-xs text-gray-500">Total Pagu</p>
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
                    <p class="text-sm font-semibold text-gray-900">{{ formatRupiah($budgetRealization['total_realisasi'] ?? 0) }}</p>
                    <p class="text-xs text-gray-500">Total Realisasi</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-blue-100 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-amber-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-900">{{ formatRupiah($budgetRealization['sisa_pagu'] ?? 0) }}</p>
                    <p class="text-xs text-gray-500">Sisa Pagu</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Realization Rate -->
    <div class="bg-white rounded-xl border border-blue-100 p-4 mb-4">
        <h3 class="text-sm font-semibold text-gray-900 mb-3">Tingkat Realisasi</h3>
        <div class="flex items-center gap-3">
            <div class="flex-1">
                <div class="flex justify-between text-xs mb-2">
                    <span class="text-gray-600">{{ number_format($budgetRealization['persentase_realisasi'] ?? 0, 1) }}%</span>
                    <span class="font-semibold text-gray-900">{{ formatRupiah($budgetRealization['total_realisasi'] ?? 0) }} dari {{ formatRupiah($budgetRealization['total_pagu'] ?? 0) }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-blue-600 h-3 rounded-full transition-all" style="width: {{ min($budgetRealization['persentase_realisasi'] ?? 0, 100) }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Division Comparison -->
    @if($permissions['view_all'] ?? false)
        <div class="bg-white rounded-xl border border-blue-100 p-4">
            <h3 class="text-sm font-semibold text-gray-900 mb-3">Realisasi per Divisi</h3>
            <div class="space-y-3">
                @foreach($budgetRealization ?? [] as $div)
                    @if(is_array($div) && isset($div['divisi']))
                    <div class="border-b border-blue-50 pb-3 last:border-0">
                        <div class="flex justify-between items-center mb-2">
                            <span class="font-medium text-gray-900 text-sm">{{ $div['divisi'] }}</span>
                            <span class="text-xs text-gray-600">{{ number_format($div['persentase_realisasi'] ?? 0, 1) }}%</span>
                        </div>
                        <div class="grid grid-cols-3 gap-3 text-xs">
                            <div>
                                <span class="text-gray-500">Pagu:</span>
                                <span class="font-semibold text-gray-900">{{ formatRupiah($div['pagu'] ?? 0) }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500">Realisasi:</span>
                                <span class="font-semibold text-emerald-600">{{ formatRupiah($div['total_digunakan'] ?? 0) }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500">Sisa:</span>
                                <span class="font-semibold text-amber-600">{{ formatRupiah($div['sisa_pagu'] ?? 0) }}</span>
                            </div>
                        </div>
                        <div class="mt-2 w-full bg-gray-200 rounded-full h-1.5">
                            <div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ min($div['persentase_realisasi'] ?? 0, 100) }}%"></div>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
    @endif

    <script>
        function exportReport(format) {
            const params = new URLSearchParams({
                format: format,
                ...@js($filters ?? [])
            });
            window.location.href = '{{ route('reports.export', ['budget_realization']) }}?' + params.toString();
        }
    </script>
</x-app-layout>
