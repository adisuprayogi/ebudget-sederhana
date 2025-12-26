<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-secondary-900">Laporan Refund</h1>
                <p class="text-secondary-600 mt-1">Rekapitulasi pengembalian dana</p>
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
            <form method="GET" action="{{ route('reports.refund') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
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
                <div class="text-sm text-secondary-500 mb-1">Total Refund</div>
                <div class="text-2xl font-bold text-secondary-900">{{ $statistics['total_refund'] ?? 0 }}</div>
            </div>
            <div class="bg-white rounded-2xl shadow-soft p-6">
                <div class="text-sm text-secondary-500 mb-1">Total Nominal</div>
                <div class="text-2xl font-bold text-red-600">{{ formatRupiah($statistics['total_nominal_refund'] ?? 0) }}</div>
            </div>
            <div class="bg-white rounded-2xl shadow-soft p-6">
                <div class="text-sm text-secondary-500 mb-1">Diproses</div>
                <div class="text-2xl font-bold text-green-600">{{ $statistics['refund_processed'] ?? 0 }}</div>
            </div>
            <div class="bg-white rounded-2xl shadow-soft p-6">
                <div class="text-sm text-secondary-500 mb-1">Pending</div>
                <div class="text-2xl font-bold text-amber-600">{{ $statistics['refund_pending'] ?? 0 }}</div>
            </div>
        </div>

        <!-- Refunds List -->
        <div class="bg-white rounded-2xl shadow-soft p-6">
            <h3 class="text-lg font-semibold text-secondary-900 mb-4">Daftar Refund</h3>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-secondary-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-secondary-600 uppercase">Tanggal</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-secondary-600 uppercase">Jenis</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-secondary-600 uppercase">Alasan</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-secondary-600 uppercase">Jumlah</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-secondary-600 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-secondary-100">
                        @foreach($refunds ?? [] as $refund)
                            <tr>
                                <td class="px-4 py-3 text-sm text-secondary-900">{{ \Carbon\Carbon::parse($refund->tanggal_refund)->format('d/m/Y') }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-700">
                                        {{ ucfirst(str_replace('_', ' ', $refund->jenis_refund)) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-secondary-700 max-w-xs truncate">{{ $refund->alasan_refund }}</td>
                                <td class="px-4 py-3 text-sm text-right font-semibold text-secondary-900">{{ formatRupiah($refund->jumlah_refund) }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold
                                        @if($refund->status === 'processed') bg-green-100 text-green-700
                                        @elseif($refund->status === 'approved') bg-blue-100 text-blue-700
                                        @elseif($refund->status === 'menunggu_approval') bg-amber-100 text-amber-700
                                        @else bg-slate-100 text-slate-700 @endif">
                                        {{ ucfirst($refund->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function exportReport(format) {
            const params = new URLSearchParams({
                type: 'refund',
                format: format,
                ...@js($filters ?? [])
            });
            window.location.href = '{{ route('reports.export') }}?' + params.toString();
        }
    </script>
</x-app-layout>
