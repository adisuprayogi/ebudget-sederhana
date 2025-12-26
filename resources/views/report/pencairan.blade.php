<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-secondary-900">Laporan Pencairan Dana</h1>
                <p class="text-secondary-600 mt-1">Status pencairan dan jadwal pembayaran</p>
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
            <form method="GET" action="{{ route('reports.pencairan') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-secondary-700 mb-2">Tahun</label>
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
                <div class="text-sm text-secondary-500 mb-1">Total Pencairan</div>
                <div class="text-2xl font-bold text-secondary-900">{{ $pencairanStats['total'] ?? 0 }}</div>
            </div>
            <div class="bg-white rounded-2xl shadow-soft p-6">
                <div class="text-sm text-secondary-500 mb-1">Total Nominal</div>
                <div class="text-2xl font-bold text-green-600">{{ formatRupiah($pencairanStats['total_nominal'] ?? 0) }}</div>
            </div>
            <div class="bg-white rounded-2xl shadow-soft p-6">
                <div class="text-sm text-secondary-500 mb-1">Sudah Dibayar</div>
                <div class="text-2xl font-bold text-green-600">{{ $pencairanStats['sudah_dibayar'] ?? 0 }}</div>
            </div>
            <div class="bg-white rounded-2xl shadow-soft p-6">
                <div class="text-sm text-secondary-500 mb-1">Menunggu Pembayaran</div>
                <div class="text-2xl font-bold text-amber-600">{{ $pencairanStats['menunggu'] ?? 0 }}</div>
            </div>
        </div>

        <!-- Upcoming Pencairans -->
        <div class="bg-white rounded-2xl shadow-soft p-6 mb-8">
            <h3 class="text-lg font-semibold text-secondary-900 mb-4">Jadwal Pencairan Mendatang</h3>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-secondary-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-secondary-600 uppercase">Nomor</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-secondary-600 uppercase">Uraian</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-secondary-600 uppercase">Divisi</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-secondary-600 uppercase">Jumlah</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-secondary-600 uppercase">Jadwal Bayar</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-secondary-600 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-secondary-100">
                        @forelse($upcomingPencairans ?? [] as $pencairan)
                            <tr>
                                <td class="px-4 py-3 text-sm font-mono text-primary-600">{{ $pencairan->nomor_pencairan }}</td>
                                <td class="px-4 py-3 text-sm text-secondary-900">{{ $pencairan->pengajuanDana->uraian ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-secondary-700">{{ $pencairan->pengajuanDana->divisi->nama_divisi ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-right font-semibold text-secondary-900">{{ formatRupiah($pencairan->jumlah_pencairan) }}</td>
                                <td class="px-4 py-3 text-sm text-secondary-700">{{ \Carbon\Carbon::parse($pencairan->jadwal_bayar)->format('d/m/Y') }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold
                                        @if($pencairan->status === 'paid') bg-green-100 text-green-700
                                        @elseif($pencairan->status === 'pending') bg-amber-100 text-amber-700
                                        @else bg-slate-100 text-slate-700 @endif">
                                        {{ $pencairan->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-secondary-500">Tidak ada pencairan mendatang</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Monthly Trend -->
        <div class="bg-white rounded-2xl shadow-soft p-6">
            <h3 class="text-lg font-semibold text-secondary-900 mb-4">Tren Pencairan Bulanan</h3>
            <div class="h-64 flex items-end justify-around space-x-2">
                @foreach($monthlyTrend ?? [] as $month)
                    <div class="flex flex-col items-center flex-1">
                        <div class="w-full bg-green-500 rounded-t" style="height: {{ ($month['total'] / ($monthlyTrend->max('total') ?? 1)) * 200 }}px; min-height: 4px;"></div>
                        <div class="text-xs text-secondary-500 mt-2">{{ substr($month['bulan'], 0, 3) }}</div>
                        <div class="text-xs font-semibold text-secondary-700">{{ formatRupiah($month['total']) }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script>
        function exportReport(format) {
            const params = new URLSearchParams({
                type: 'pencairan',
                format: format,
                ...@js($filters ?? [])
            });
            window.location.href = '{{ route('reports.export') }}?' + params.toString();
        }
    </script>
</x-app-layout>
