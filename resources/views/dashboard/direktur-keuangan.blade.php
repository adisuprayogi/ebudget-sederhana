<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard - Direktur Keuangan</h2>
    </x-slot>

    <!-- Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm font-medium text-gray-500">Total Pagu Anggaran</div>
            <div class="mt-2 text-3xl font-semibold text-gray-900">
                {{ number_format($data['totalPagu'], 0, ',', '.') }}
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm font-medium text-gray-500">Terpakai</div>
            <div class="mt-2 text-3xl font-semibold text-red-600">
                {{ number_format($data['terpakai'], 0, ',', '.') }}
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm font-medium text-gray-500">Sisa Pagu</div>
            <div class="mt-2 text-3xl font-semibold text-green-600">
                {{ number_format($data['sisaPagu'], 0, ',', '.') }}
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm font-medium text-gray-500">Persentase Terpakai</div>
            <div class="mt-2 text-3xl font-semibold text-blue-600">
                {{ $data['totalPagu'] > 0 ? round(($data['terpakai'] / $data['totalPagu']) * 100, 1) : 0 }}%
            </div>
        </div>
    </div>

    <!-- Status Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-yellow-800">
                        Pengajuan Menunggu Approval
                    </p>
                    <p class="text-lg font-semibold text-yellow-900">{{ $data['pengajuanMenunggu'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-green-50 border-l-4 border-green-400 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">
                        Pengajuan Disetujui
                    </p>
                    <p class="text-lg font-semibold text-green-900">{{ $data['pengajuanDisetujui'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C7.762 6.076 7.5 6.596 7.5 7.2v1.645a1 1 0 001.19.984l1.5-1.5a.662.662 0 01.014-1.035A4.535 4.535 0 0011 7.092V6zm-1 1.849v2.345a1 1 0 01-1.19.984l-1.5-1.5a.662.662 0 01.014-1.035A4.535 4.535 0 009 8.908V12.5a2 2 0 01-2 0v-1.849z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-blue-800">
                        Pencairan Pending
                    </p>
                    <p class="text-lg font-semibold text-blue-900">{{ $data['pencairanPending'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Pengajuan -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Pengajuan Terbaru</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nomor
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Judul
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Divisi
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Total
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($data['recentPengajuan'] as $pengajuan)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $pengajuan->nomor_pengajuan ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $pengajuan->judul_pengajuan }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $pengajuan->divisi->nama_divisi }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ number_format($pengajuan->total_pengajuan, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($pengajuan->status == 'menunggu_approval') bg-yellow-100 text-yellow-800
                                    @elseif($pengajuan->status == 'disetujui') bg-green-100 text-green-800
                                    @elseif($pengajuan->status == 'ditolak') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $pengajuan->status)) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Divisi Overview -->
    <div class="mt-8 bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Overview Divisi</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($data['divisis'] as $divisi)
                    @php
                        $percentage = $divisi->total_pagu > 0 ? round(($divisi->terpakai / $divisi->total_pagu) * 100, 0) : 0;
                    @endphp
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="text-sm font-medium text-gray-900">{{ $divisi->nama_divisi }}</div>
                        <div class="mt-1 text-xs text-gray-500">{{ $divisi->pengajuan_dana_count }} pengajuan bulan ini</div>
                        <div class="mt-2 flex justify-between text-xs">
                            <span>Pagu: {{ number_format($divisi->total_pagu, 0, ',', '.') }}</span>
                            <span class="text-gray-500">{{ $percentage }}%</span>
                        </div>
                        <div class="mt-1 bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
