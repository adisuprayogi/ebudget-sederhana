<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard - Staff Divisi {{ $data['divisi']->nama_divisi }}</h2>
    </x-slot>

        <!-- Welcome Section -->
        <div class="bg-white rounded-lg shadow mb-8">
            <div class="px-6 py-8">
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Selamat datang, {{ auth()->user()->full_name }}!</h3>
                <p class="text-gray-600">Berikut adalah ringkasan pengajuan dana Anda di divisi {{ $data['divisi']->nama_divisi }}.</p>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-100 rounded-lg p-3">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Pengajuan</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $data['pengajuanCount'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-green-50 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-100 rounded-lg p-3">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-green-800">Disetujui</p>
                        <p class="text-2xl font-bold text-green-900">{{ $data['pengajuanApproved'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-yellow-50 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-yellow-100 rounded-lg p-3">
                        <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-yellow-800">Menunggu Approval</p>
                        <p class="text-2xl font-bold text-yellow-900">{{ $data['pengajuanPending'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-blue-50 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-100 rounded-lg p-3">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-blue-800">Sisa Pagu Divisi</p>
                        <p class="text-2xl font-bold text-blue-900">
                            {{ number_format($data['sisaPaguDivisi'], 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mb-8">
            <a href="{{ route('pengajuan-dana.create') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Buat Pengajuan Dana Baru
            </a>
        </div>

        <!-- My Pengajuan -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">Pengajuan Saya</h3>
                <a href="{{ route('pengajuan-dana.create') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
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
                                Judul Pengajuan
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
                        @foreach($data['myPengajuan'] as $pengajuan)
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
                                    {{ number_format($pengajuan->total_pengajuan, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($pengajuan->status == 'menunggu_approval') bg-yellow-100 text-yellow-800
                                        @elseif($pengajuan->status == 'disetujui') bg-green-100 text-green-800
                                        @elseif($pengajuan->status == 'ditolak') bg-red-100 text-red-800
                                        @elseif($pengajuan->status == 'revisi') bg-blue-100 text-blue-800
                                        @elseif($pengajuan->status == 'dicairkan') bg-purple-100 text-purple-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $pengajuan->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="#" class="text-blue-600 hover:text-blue-900 mr-3">Detail</a>
                                    @if($pengajuan->status == 'revisi')
                                        <a href="#" class="text-yellow-600 hover:text-yellow-900">Revisi</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @if($data['myPengajuan']->count() == 0)
                    <div class="text-center py-8 text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p>Belum ada pengajuan dana yang dibuat.</p>
                        <a href="{{ route('pengajuan-dana.create') }}" class="mt-2 text-blue-600 hover:text-blue-800 font-medium">
                            Buat pengajuan pertama Anda →
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Help Section -->
        <div class="mt-8 bg-blue-50 border-l-4 border-blue-400 p-4">
            <div class="flex">
                <div class="ml-3">
                    <p class="text-sm text-blue-700">
                        <strong>Butuh bantuan?</strong> Hubungi kepala divisi Anda atau bagian keuangan untuk informasi lebih lanjut mengenai proses pengajuan dana.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>