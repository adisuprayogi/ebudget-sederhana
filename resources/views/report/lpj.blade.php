<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-secondary-900">Laporan Pertanggungjawaban</h1>
                <p class="text-secondary-600 mt-1">Status LPJ dan verifikasi pertanggungjawaban</p>
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
            <form method="GET" action="{{ route('reports.lpj') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-secondary-700 mb-2">Periode Anggaran</label>
                    <select name="periode_anggaran_id" class="w-full px-4 py-2 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <option value="">-- Pilih Periode Anggaran --</option>
                        @foreach($availablePeriodes ?? [] as $periode)
                            @php
                                $isSelected = ($selectedPeriode && $selectedPeriode->id == $periode->id);
                                $isActive = ($activePeriode && $activePeriode->id == $periode->id);
                            @endphp
                            <option value="{{ $periode->id }}" {{ $isSelected ? 'selected' : '' }}>
                                {{ $periode->nama_periode }}
                                @if($isActive) <span class="text-xs text-primary-600">(Aktif)</span> @endif
                            </option>
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
                <div>
                    <label class="block text-sm font-medium text-secondary-700 mb-2">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" value="{{ $filters['tanggal_mulai'] ?? '' }}" class="w-full px-4 py-2 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full px-6 py-2 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-all duration-200">
                        Terapkan Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Statistics Overview -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-2xl shadow-soft p-6">
                <div class="text-sm text-secondary-500 mb-1">Total LPJ</div>
                <div class="text-2xl font-bold text-secondary-900">{{ $lpjStats['total_lpj'] ?? 0 }}</div>
            </div>
            <div class="bg-white rounded-2xl shadow-soft p-6">
                <div class="text-sm text-secondary-500 mb-1">Total Digunakan</div>
                <div class="text-2xl font-bold text-green-600">{{ formatRupiah($lpjStats['total_digunakan'] ?? 0) }}</div>
            </div>
            <div class="bg-white rounded-2xl shadow-soft p-6">
                <div class="text-sm text-secondary-500 mb-1">Total Sisa</div>
                <div class="text-2xl font-bold text-amber-600">{{ formatRupiah($lpjStats['total_sisa'] ?? 0) }}</div>
            </div>
            <div class="bg-white rounded-2xl shadow-soft p-6">
                <div class="text-sm text-secondary-500 mb-1">Efisiensi</div>
                <div class="text-2xl font-bold text-primary-600">{{ number_format($lpjStats['efficiency_rate'] ?? 0, 1) }}%</div>
            </div>
        </div>

        <!-- Overdue LPJ Alert -->
        @if(count($overdueLpj ?? []) > 0)
            <div class="bg-red-50 border-l-4 border-red-400 rounded-r-2xl p-6 mb-8">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="ml-3 flex-1">
                        <h3 class="text-lg font-medium text-red-800">LPJ Jatuh Tempo</h3>
                        <p class="text-sm text-red-700 mt-1">{{ count($overdueLpj) }} LPJ belum disubmit dan sudah melewati tenggat waktu</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Monthly Trend -->
        <div class="bg-white rounded-2xl shadow-soft p-6">
            <h3 class="text-lg font-semibold text-secondary-900 mb-4">Tren Bulanan</h3>
            <div class="h-64 flex items-end justify-around space-x-2">
                @php($maxTotal = collect($monthlyTrend)->max('pengajuan_total') ?: 1)
                @foreach($monthlyTrend ?? [] as $month)
                    <div class="flex flex-col items-center flex-1">
                        <div class="w-full bg-amber-500 rounded-t" style="height: {{ ($month['pengajuan_total'] / $maxTotal) * 200 }}px; min-height: 4px;"></div>
                        <div class="text-xs text-secondary-500 mt-2">{{ substr($month['month_name'], 0, 3) }}</div>
                        <div class="text-xs font-semibold text-secondary-700">{{ formatRupiah($month['pengajuan_total']) }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script>
        function exportReport(format) {
            const params = new URLSearchParams({
                format: format,
                ...@js($filters ?? [])
            });
            window.location.href = '{{ route('reports.export', ['lpj']) }}?' + params.toString();
        }
    </script>
</x-app-layout>
