<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">Laporan Refund</h1>
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
        <form method="GET" action="{{ route('reports.refund') }}" class="flex flex-wrap items-center gap-3">
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
                <div class="w-10 h-10 bg-red-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                    </svg>
                </div>
                <div>
                    <p class="text-xl font-semibold text-gray-900">{{ $statistics['total_refund'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500">Total Refund</p>
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
                    <p class="text-sm font-semibold text-gray-900">{{ formatRupiah($statistics['total_nominal_refund'] ?? 0) }}</p>
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
                    <p class="text-xl font-semibold text-gray-900">{{ $statistics['refund_processed'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500">Diproses</p>
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
                    <p class="text-xl font-semibold text-gray-900">{{ $statistics['refund_pending'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500">Pending</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Refunds List -->
    <div class="bg-white rounded-xl border border-blue-100 p-4">
        <h3 class="text-sm font-semibold text-gray-900 mb-3">Daftar Refund</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-blue-50 border-b border-blue-100">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-blue-700 uppercase">Tanggal</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-blue-700 uppercase">Jenis</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-blue-700 uppercase">Alasan</th>
                        <th class="px-4 py-2 text-right text-xs font-semibold text-blue-700 uppercase">Jumlah</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-blue-700 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-blue-50">
                    @foreach($refunds ?? [] as $refund)
                        <tr class="hover:bg-blue-50/50 transition-colors">
                            <td class="px-4 py-2 text-sm text-gray-900">{{ \Carbon\Carbon::parse($refund->tanggal_refund)->format('d/m/Y') }}</td>
                            <td class="px-4 py-2">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-violet-100 text-violet-700">
                                    {{ ucfirst(str_replace('_', ' ', $refund->jenis_refund)) }}
                                </span>
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-700 max-w-xs truncate">{{ $refund->alasan_refund }}</td>
                            <td class="px-4 py-2 text-sm text-right font-semibold text-gray-900">{{ formatRupiah($refund->jumlah_refund) }}</td>
                            <td class="px-4 py-2">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                    @if($refund->status === 'processed') bg-emerald-100 text-emerald-700
                                    @elseif($refund->status === 'approved') bg-blue-100 text-blue-700
                                    @elseif($refund->status === 'menunggu_approval') bg-amber-100 text-amber-700
                                    @else bg-gray-100 text-gray-700 @endif">
                                    {{ ucfirst($refund->status) }}
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
            window.location.href = '{{ route('reports.export', ['refund']) }}?' + params.toString();
        }
    </script>
</x-app-layout>
