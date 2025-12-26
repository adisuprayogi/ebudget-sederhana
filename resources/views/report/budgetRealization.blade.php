<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-secondary-900">Laporan Realisasi Anggaran</h1>
                <p class="text-secondary-600 mt-1">Perbandingan pagu vs realisasi</p>
            </div>
            <div class="flex items-center space-x-3">
                @if(auth()->user()->hasPermission('report.export'))
                    <button onclick="exportReport('excel')" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Export Excel
                    </button>
                @endif
                <a href="{{ route('reports.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-secondary-300 text-secondary-700 rounded-xl hover:bg-secondary-50 transition-all duration-200">
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <!-- Filters -->
        <div class="bg-white rounded-2xl shadow-soft p-6 mb-8">
            <form method="GET" action="{{ route('reports.budget-realization') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-secondary-700 mb-2">Tahun Anggaran</label>
                    <select name="tahun" class="w-full px-4 py-2 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        @foreach($filterOptions['years'] ?? [] as $year)
                            <option value="{{ $year }}" {{ ($filters['tahun'] ?? date('Y')) == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
                @if($permissions['view_all'] ?? false)
                    <div>
                        <label class="block text-sm font-medium text-secondary-700 mb-2">Divisi</label>
                        <select name="divisi_id" class="w-full px-4 py-2 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            <option value="">Semua Divisi</option>
                            @foreach($filterOptions['divisis'] ?? [] as $divisi)
                                <option value="{{ $divisi->id }}" {{ ($filters['divisi_id'] ?? '') == $divisi->id ? 'selected' : '' }}>{{ $divisi->nama_divisi }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <div class="flex items-end">
                    <button type="submit" class="w-full px-6 py-2 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-all duration-200">
                        Terapkan Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Overall Summary -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-gradient-to-br from-primary-500 to-primary-600 rounded-2xl shadow-soft p-6 text-white">
                <div class="text-sm text-primary-100 mb-1">Total Pagu Anggaran</div>
                <div class="text-2xl font-bold">{{ formatRupiah($budgetRealization['total_pagu'] ?? 0) }}</div>
            </div>
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl shadow-soft p-6 text-white">
                <div class="text-sm text-green-100 mb-1">Total Realisasi</div>
                <div class="text-2xl font-bold">{{ formatRupiah($budgetRealization['total_realisasi'] ?? 0) }}</div>
            </div>
            <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-2xl shadow-soft p-6 text-white">
                <div class="text-sm text-amber-100 mb-1">Sisa Pagu</div>
                <div class="text-2xl font-bold">{{ formatRupiah($budgetRealization['sisa_pagu'] ?? 0) }}</div>
            </div>
        </div>

        <!-- Realization Rate -->
        <div class="bg-white rounded-2xl shadow-soft p-6 mb-8">
            <h3 class="text-lg font-semibold text-secondary-900 mb-4">Tingkat Realisasi</h3>
            <div class="flex items-center">
                <div class="flex-1">
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-secondary-600">{{ number_format($budgetRealization['persentase_realisasi'] ?? 0, 1) }}</span>
                        <span class="font-semibold text-secondary-900">{{ formatRupiah($budgetRealization['total_realisasi'] ?? 0) }} dari {{ formatRupiah($budgetRealization['total_pagu'] ?? 0) }}</span>
                    </div>
                    <div class="w-full bg-secondary-200 rounded-full h-4">
                        <div class="bg-gradient-to-r from-primary-500 to-primary-600 h-4 rounded-full transition-all duration-500" style="width: {{ min($budgetRealization['persentase_realisasi'] ?? 0, 100) }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Division Comparison -->
        @if($permissions['view_all'] ?? false)
            <div class="bg-white rounded-2xl shadow-soft p-6">
                <h3 class="text-lg font-semibold text-secondary-900 mb-4">Realisasi per Divisi</h3>
                <div class="space-y-4">
                    @foreach($divisionComparison ?? [] as $div)
                        <div class="border-b border-secondary-100 pb-4 last:border-0">
                            <div class="flex justify-between items-center mb-2">
                                <span class="font-medium text-secondary-900">{{ $div['nama_divisi'] }}</span>
                                <span class="text-sm text-secondary-600">{{ number_format($div['persentase'] ?? 0, 1) }}%</span>
                            </div>
                            <div class="grid grid-cols-3 gap-4 text-sm">
                                <div>
                                    <span class="text-secondary-500">Pagu:</span>
                                    <span class="font-semibold text-secondary-900">{{ formatRupiah($div['pagu'] ?? 0) }}</span>
                                </div>
                                <div>
                                    <span class="text-secondary-500">Realisasi:</span>
                                    <span class="font-semibold text-green-600">{{ formatRupiah($div['realisasi'] ?? 0) }}</span>
                                </div>
                                <div>
                                    <span class="text-secondary-500">Sisa:</span>
                                    <span class="font-semibold text-amber-600">{{ formatRupiah($div['sisa'] ?? 0) }}</span>
                                </div>
                            </div>
                            <div class="mt-2 w-full bg-secondary-200 rounded-full h-2">
                                <div class="bg-primary-600 h-2 rounded-full" style="width: {{ min($div['persentase'] ?? 0, 100) }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <script>
        function exportReport(format) {
            const params = new URLSearchParams({
                type: 'budget_realization',
                format: format,
                ...@js($filters ?? [])
            });
            window.location.href = '{{ route('reports.export') }}?' + params.toString();
        }
    </script>
</x-app-layout>
