<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard - Kepala Divisi {{ $data['divisi']->nama_divisi }}</h2>
    </x-slot>

        <!-- Pagu Overview -->
        <div class="bg-white rounded-lg shadow mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Pagu Anggaran Divisi</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="text-sm font-medium text-gray-500">Total Pagu</div>
                        <div class="mt-2 text-3xl font-bold text-blue-600">
                            {{ number_format($data['totalPagu'], 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="text-sm font-medium text-gray-500">Terpakai</div>
                        <div class="mt-2 text-3xl font-bold text-red-600">
                            {{ number_format($data['terpakai'], 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="text-sm font-medium text-gray-500">Sisa Pagu</div>
                        <div class="mt-2 text-3xl font-bold text-green-600">
                            {{ number_format($data['sisaPagu'], 0, ',', '.') }}
                        </div>
                    </div>
                </div>
                <div class="mt-6">
                    <div class="flex justify-between text-sm mb-2">
                        <span>Penggunaan Pagu</span>
                        <span>{{ round(($data['terpakai'] / $data['totalPagu']) * 100, 1) }}%</span>
                    </div>
                    <div class="bg-gray-200 rounded-full h-4">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-4 rounded-full" style="width: {{ round(($data['terpakai'] / $data['totalPagu']) * 100, 1) }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <a href="{{ route('program-kerja.index') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-100 rounded-lg p-3">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">Program Kerja</h3>
                        <p class="mt-1 text-sm text-gray-500">Kelola program kerja divisi</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('pengajuan-dana.index') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-100 rounded-lg p-3">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">Pengajuan Dana</h3>
                        <p class="mt-1 text-sm text-gray-500">Buat dan review pengajuan</p>
                    </div>
                </div>
            </a>

            @if($data['pengajuanMenunggu'] > 0)
            <a href="#" class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 hover:bg-yellow-100 transition-colors">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-yellow-100 rounded-lg p-3">
                        <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-yellow-800">Pengajuan Menunggu</h3>
                        <p class="mt-1 text-2xl font-bold text-yellow-900">{{ $data['pengajuanMenunggu'] }}</p>
                    </div>
                </div>
            </a>
            @endif
        </div>

        <!-- Recent Pengajuan -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">Pengajuan Divisi Terbaru</h3>
                <a href="{{ route('pengajuan-dana.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    Lihat Semua →
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nomor
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Judul
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Pengaju
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Total
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($data['pengajuanDivisi'] as $pengajuan)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $pengajuan->tanggal_pengajuan ? $pengajuan->tanggal_pengajuan->format('d/m/Y') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $pengajuan->nomor_pengajuan ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $pengajuan->judul_pengajuan }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $pengajuan->user->full_name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ number_format($pengajuan->total_pengajuan, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($pengajuan->status == 'menunggu_approval') bg-yellow-100 text-yellow-800
                                        @elseif($pengajuan->status == 'disetujui') bg-green-100 text-green-800
                                        @elseif($pengajuan->status == 'ditolak') bg-red-100 text-red-800
                                        @elseif($pengajuan->status == 'revisi') bg-blue-100 text-blue-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $pengajuan->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="#" class="text-blue-600 hover:text-blue-900">Detail</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @if($data['pengajuanDivisi']->count() == 0)
                    <div class="text-center py-8 text-gray-500">
                        Belum ada pengajuan dana untuk divisi ini.
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>