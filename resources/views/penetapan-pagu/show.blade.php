<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Detail Penetapan Pagu</h1>
                <p class="text-gray-600 mt-1">Informasi lengkap alokasi pagu anggaran</p>
            </div>
            <div class="flex items-center space-x-3">
                @if(auth()->user()->hasAnyRole(['direktur_keuangan', 'direktur_utama']))
                    <a href="{{ route('penetapan-pagu.edit', $penetapanPagu) }}" class="inline-flex items-center px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit
                    </a>
                @endif
                <a href="{{ route('penetapan-pagu.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto py-8">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-sm text-gray-500 mb-1">Total Pagu</div>
                <div class="text-2xl font-bold text-blue-600">{{ formatRupiah($statistics['total_pagu'] ?? 0) }}</div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-sm text-gray-500 mb-1">Terpakai</div>
                <div class="text-2xl font-bold text-amber-600">{{ formatRupiah($statistics['total_terpakai'] ?? 0) }}</div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-sm text-gray-500 mb-1">Sisa Pagu</div>
                <div class="text-2xl font-bold text-green-600">{{ formatRupiah($statistics['sisa_pagu'] ?? 0) }}</div>
            </div>
        </div>

        <!-- Detail Pagu -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-6">Informasi Pagu</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-sm text-gray-500">Periode Anggaran</label>
                    <div class="mt-1 font-medium text-gray-900">
                        {{ $penetapanPagu->periodeAnggaran->nama_periode ?? '-' }} ({{ $penetapanPagu->periodeAnggaran->tahun_anggaran ?? '-' }})
                    </div>
                </div>
                <div>
                    <label class="text-sm text-gray-500">Divisi</label>
                    <div class="mt-1 font-medium text-gray-900">{{ $penetapanPagu->divisi->nama_divisi ?? '-' }}</div>
                </div>
                <div class="md:col-span-2">
                    <label class="text-sm text-gray-500">Jumlah Pagu</label>
                    <div class="mt-1 text-2xl font-bold text-blue-600">{{ formatRupiah($penetapanPagu->jumlah_pagu) }}</div>
                </div>
            </div>
        </div>

        <!-- Usage Statistics -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-6">Statistik Penggunaan</h2>

            <div class="space-y-4">
                <div class="flex justify-between items-center py-3 border-b border-gray-100">
                    <span class="text-gray-600">Total Pagu</span>
                    <span class="font-semibold text-gray-900">{{ formatRupiah($statistics['total_pagu'] ?? 0) }}</span>
                </div>
                <div class="flex justify-between items-center py-3 border-b border-gray-100">
                    <span class="text-gray-600">Total Terpakai</span>
                    <span class="font-semibold text-amber-600">{{ formatRupiah($statistics['total_terpakai'] ?? 0) }}</span>
                </div>
                <div class="flex justify-between items-center py-3">
                    <span class="text-gray-600">Sisa Pagu</span>
                    <span class="font-bold text-green-600">{{ formatRupiah($statistics['sisa_pagu'] ?? 0) }}</span>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="mt-6">
                <div class="flex justify-between text-sm text-gray-600 mb-2">
                    <span>Penggunaan Pagu</span>
                    <span>{{ number_format($statistics['persentase_terpakai'] ?? 0, 1) }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="h-3 rounded-full transition-all duration-300 @if(($statistics['persentase_terpakai'] ?? 0) >= 90) bg-red-500 @elseif(($statistics['persentase_terpakai'] ?? 0) >= 70) bg-amber-500 @else bg-green-500 @endif" style="width: {{ min($statistics['persentase_terpakai'] ?? 0, 100) }}%"></div>
                </div>
            </div>

            @if(($statistics['persentase_terpakai'] ?? 0) >= 90)
                <div class="mt-4 flex items-start p-4 bg-amber-50 border-l-4 border-amber-400 rounded-r">
                    <svg class="h-5 w-5 text-amber-400 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <p class="text-sm text-amber-700">Pagu hampir habis terpakai. Pertimbangkan untuk menambah pagu jika diperlukan.</p>
                </div>
            @endif
        </div>

        <!-- Catatan -->
        @if($penetapanPagu->catatan)
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Catatan</h2>
                <p class="text-gray-700">{{ $penetapanPagu->catatan }}</p>
            </div>
        @endif

        <!-- Audit Info -->
        <div class="flex items-center justify-between text-sm text-gray-500">
            <div>Dibuat oleh: {{ $penetapanPagu->createdBy->name ?? '-' }}</div>
            <div>{{ \Carbon\Carbon::parse($penetapanPagu->created_at)->format('d F Y, H:i') }}</div>
        </div>
    </div>
</x-app-layout>
