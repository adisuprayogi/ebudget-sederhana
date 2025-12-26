<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard - Staff Keuangan</h2>
    </x-slot>

        <!-- Today's Summary -->
        <div class="bg-white rounded-lg shadow mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Ringkasan Hari Ini</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="text-sm font-medium text-gray-500">Pencairan Disetujui</div>
                        <div class="mt-2 text-3xl font-bold text-green-600">{{ $data['pencairanApproved'] }}</div>
                    </div>
                    <div class="text-center">
                        <div class="text-sm font-medium text-gray-500">Total Pencairan</div>
                        <div class="mt-2 text-3xl font-bold text-blue-600">
                            {{ number_format($data['totalPencairanHariIni'], 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="text-sm font-medium text-gray-500">Rata-rata Pencairan</div>
                        <div class="mt-2 text-3xl font-bold text-purple-600">
                            {{ $data['pencairanApproved'] > 0 ? number_format($data['totalPencairanHariIni'] / $data['pencairanApproved'], 0, ',', '.') : 0 }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <a href="{{ route('pencatatan-penerimaan.index') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-100 rounded-lg p-3">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">Pencatatan Penerimaan</h3>
                        <p class="mt-1 text-sm text-gray-500">Catat penerimaan dana masuk</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('pencairan-dana.index') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-100 rounded-lg p-3">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">Pencairan Dana</h3>
                        <p class="mt-1 text-sm text-gray-500">Proses pencairan dana</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Pending Pencairan -->
        @if($data['pencairanPending']->count() > 0)
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg shadow mb-8">
            <div class="px-6 py-4 border-b border-yellow-200 flex justify-between items-center">
                <h3 class="text-lg font-medium text-yellow-800">
                    <svg class="inline-block w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Pencairan Menunggu Persetujuan ({{ $data['pencairanPending']->count() }})
                </h3>
                <a href="{{ route('pencairan-dana.index') }}" class="text-yellow-700 hover:text-yellow-900 text-sm font-medium">
                    Proses Sekarang →
                </a>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    @foreach($data['pencairanPending']->take(5) as $pencairan)
                        <div class="flex justify-between items-center p-3 bg-white rounded-lg">
                            <div>
                                <p class="font-medium text-gray-900">{{ $pencairan->nomor_pencairan }}</p>
                                <p class="text-sm text-gray-500">{{ $pencairan->pengajuanDana->divisi->nama_divisi }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-gray-900">{{ number_format($pencairan->total_pencairan, 0, ',', '.') }}</p>
                                <p class="text-sm text-gray-500">{{ $pencairan->tanggal_pencairan->format('d/m/Y') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                @if($data['pencairanPending']->count() > 5)
                    <div class="mt-4 text-center">
                        <a href="{{ route('pencairan-dana.index') }}" class="text-yellow-700 hover:text-yellow-900 text-sm font-medium">
                            Lihat {{ $data['pencairanPending']->count() - 5 }} pencairan lagi →
                        </a>
                    </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Pengajuan Need Processing -->
        @if($data['pengajuanNeedProcessing']->count() > 0)
        <div class="bg-blue-50 border border-blue-200 rounded-lg shadow mb-8">
            <div class="px-6 py-4 border-b border-blue-200">
                <h3 class="text-lg font-medium text-blue-800">
                    <svg class="inline-block w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                    Pengajuan Siap Dicairkan ({{ $data['pengajuanNeedProcessing']->count() }})
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    @foreach($data['pengajuanNeedProcessing']->take(5) as $pengajuan)
                        <div class="flex justify-between items-center p-3 bg-white rounded-lg">
                            <div>
                                <p class="font-medium text-gray-900">{{ $pengajuan->nomor_pengajuan ?? '-' }}</p>
                                <p class="text-sm text-gray-500">{{ $pengajuan->judul_pengajuan }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-gray-900">{{ number_format($pengajuan->total_pengajuan, 0, ',', '.') }}</p>
                                <p class="text-sm text-gray-500">{{ $pengajuan->divisi->nama_divisi }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                @if($data['pengajuanNeedProcessing']->count() > 5)
                    <div class="mt-4 text-center">
                        <a href="{{ route('pencairan-dana.index') }}" class="text-blue-700 hover:text-blue-900 text-sm font-medium">
                            Lihat {{ $data['pengajuanNeedProcessing']->count() - 5 }} pengajuan lagi →
                        </a>
                    </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Empty State -->
        @if($data['pencairanPending']->count() == 0 && $data['pengajuanNeedProcessing']->count() == 0)
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada pencairan pending</h3>
            <p class="text-gray-500">Semua pencairan telah diproses hari ini.</p>
        </div>
        @endif
    </div>
</x-app-layout>