<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Detail Pencatatan Penerimaan</h1>
                <p class="text-gray-600 mt-1">Informasi lengkap pencatatan penerimaan dana</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('pencatatan-penerimaan.edit', $pencatatan) }}" class="inline-flex items-center px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </a>
                <form method="POST" action="{{ route('pencatatan-penerimaan.destroy', $pencatatan) }}" onsubmit="return confirm('Yakin ingin menghapus pencatatan penerimaan ini?');" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Hapus
                    </button>
                </form>
                @if($pencatatan->perencanaanPenerimaan)
                    <a href="{{ route('perencanaan-penerimaan.show', $pencatatan->perencanaanPenerimaan) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali ke Perencanaan
                    </a>
                @else
                    <a href="{{ route('pencatatan-penerimaan.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="max-w-6xl mx-auto py-8">
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-sm text-gray-500 mb-1">Tanggal Penerimaan</div>
                <div class="text-xl font-bold text-blue-600">{{ \Carbon\Carbon::parse($pencatatan->tanggal_penerimaan)->format('d F Y') }}</div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-sm text-gray-500 mb-1">Jumlah Diterima</div>
                <div class="text-xl font-bold text-green-600">{{ formatRupiah($pencatatan->jumlah_diterima) }}</div>
            </div>
        </div>

        <!-- Detail Pencatatan -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-6">Informasi Penerimaan</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-sm text-gray-500">Tanggal Penerimaan</label>
                    <div class="mt-1 font-medium text-gray-900">{{ \Carbon\Carbon::parse($pencatatan->tanggal_penerimaan)->format('d F Y') }}</div>
                </div>
                <div>
                    <label class="text-sm text-gray-500">Periode Anggaran</label>
                    <div class="mt-1 font-medium text-gray-900">{{ $pencatatan->periodeAnggaran->nama_periode ?? '-' }} ({{ $pencatatan->periodeAnggaran->tahun_anggaran ?? '-' }})</div>
                </div>
                <div>
                    <label class="text-sm text-gray-500">Sumber Dana</label>
                    <div class="mt-1 font-medium text-gray-900">{{ $pencatatan->sumberDana->nama_sumber ?? '-' }}</div>
                </div>
                @if($pencatatan->perencanaanPenerimaan)
                    <div>
                        <label class="text-sm text-gray-500">Referensi Perencanaan</label>
                        <div class="mt-1">
                            <a href="{{ route('perencanaan-penerimaan.show', $pencatatan->perencanaanPenerimaan) }}" class="text-blue-600 hover:underline">
                                {{ Str::limit($pencatatan->perencanaanPenerimaan->uraian, 80) }}
                            </a>
                        </div>
                    </div>
                @endif
                <div class="md:col-span-2">
                    <label class="text-sm text-gray-500">Uraian</label>
                    <div class="mt-1 text-gray-900">{{ $pencatatan->uraian }}</div>
                </div>
                <div class="md:col-span-2">
                    <label class="text-sm text-gray-500">Jumlah Diterima</label>
                    <div class="mt-1 text-2xl font-bold text-green-600">{{ formatRupiah($pencatatan->jumlah_diterima) }}</div>
                </div>
            </div>
        </div>

        <!-- Bukti Penerimaan -->
        @if($pencatatan->bukti_penerimaan)
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-6">Bukti Penerimaan</h2>
                <div class="flex items-center justify-between bg-gray-50 rounded-lg px-6 py-4">
                    <div class="flex items-center">
                        <svg class="w-8 h-8 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        <div>
                            <div class="font-medium text-gray-900">Bukti Penerimaan</div>
                            <div class="text-sm text-gray-500">{{ basename($pencatatan->bukti_penerimaan) }}</div>
                        </div>
                    </div>
                    <a href="{{ asset('storage/' . $pencatatan->bukti_penerimaan) }}" target="_blank" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
                        Lihat File
                    </a>
                </div>
            </div>
        @else
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-6">Bukti Penerimaan</h2>
                <div class="text-center py-8 text-gray-500">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.514a2 2 0 011.714 1l.506 3.026a2 2 0 01-.714 2.212l-1.506 3.026a2 2 0 01-1.714 1H7a2 2 0 01-2-2V9a2 2 0 012-2h5.514z" />
                    </svg>
                    <p>Tidak ada bukti penerimaan yang diunggah</p>
                </div>
            </div>
        @endif

        <!-- Audit Info -->
        <div class="mt-6 flex items-center justify-between text-sm text-gray-500">
            <div>Dibuat oleh: {{ $pencatatan->createdBy->name ?? '-' }}</div>
            <div>{{ \Carbon\Carbon::parse($pencatatan->created_at)->format('d F Y, H:i') }}</div>
        </div>
    </div>
</x-app-layout>
