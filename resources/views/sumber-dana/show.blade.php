<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Detail Sumber Dana</h1>
                <p class="text-gray-600 mt-1">Informasi lengkap sumber dana</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('sumber-dana.edit', $sumberDana) }}" class="inline-flex items-center px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </a>
                <a href="{{ route('sumber-dana.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-6xl mx-auto py-8">
        <!-- Detail Sumber Dana -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="flex items-start justify-between mb-6">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Informasi Sumber Dana</h2>
                    <p class="text-sm text-gray-500">Data master sumber dana</p>
                </div>
                @if($sumberDana->is_active)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-700">
                        Aktif
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-700">
                        Tidak Aktif
                    </span>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-sm text-gray-500">Kode Sumber</label>
                    <div class="mt-1 font-mono font-semibold text-blue-600">{{ $sumberDana->kode_sumber }}</div>
                </div>
                <div>
                    <label class="text-sm text-gray-500">Nama Sumber Dana</label>
                    <div class="mt-1 font-medium text-gray-900">{{ $sumberDana->nama_sumber }}</div>
                </div>
                <div class="md:col-span-2">
                    <label class="text-sm text-gray-500">Deskripsi</label>
                    <div class="mt-1 text-gray-900">{{ $sumberDana->deskripsi ?? '-' }}</div>
                </div>
                <div>
                    <label class="text-sm text-gray-500">Dibuat Oleh</label>
                    <div class="mt-1 text-gray-900">{{ $sumberDana->createdBy->name ?? '-' }}</div>
                </div>
                <div>
                    <label class="text-sm text-gray-500">Tanggal Dibuat</label>
                    <div class="mt-1 text-gray-900">{{ \Carbon\Carbon::parse($sumberDana->created_at)->format('d F Y, H:i') }}</div>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-sm text-gray-500 mb-1">Perencanaan Penerimaan</div>
                <div class="text-2xl font-bold text-blue-600">{{ $sumberDana->perencanaanPenerimaans->count() }}</div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-sm text-gray-500 mb-1">Pencatatan Penerimaan</div>
                <div class="text-2xl font-bold text-green-600">{{ $sumberDana->pencatatanPenerimaans->count() }}</div>
            </div>
        </div>

        <!-- Perencanaan Penerimaan List -->
        @if($sumberDana->perencanaanPenerimaans && $sumberDana->perencanaanPenerimaans->count() > 0)
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-6">Perencanaan Penerimaan Terkait ({{ $sumberDana->perencanaanPenerimaans->count() }})</h2>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Uraian</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Estimasi</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Realisasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($sumberDana->perencanaanPenerimaans->take(5) as $perencanaan)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3">
                                        <a href="{{ route('perencanaan-penerimaan.show', $perencanaan) }}" class="font-medium text-gray-900 hover:text-blue-600">
                                            {{ $perencanaan->uraian }}
                                        </a>
                                        <div class="text-sm text-gray-500">{{ $perencanaan->periodeAnggaran->nama_periode ?? '-' }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-right font-semibold text-gray-900">{{ formatRupiah($perencanaan->jumlah_estimasi) }}</td>
                                    <td class="px-4 py-3 text-right text-green-600 font-medium">{{ formatRupiah($perencanaan->realisasi) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($sumberDana->perencanaanPenerimaans->count() > 5)
                    <div class="mt-4 text-center">
                        <a href="{{ route('perencanaan-penerimaan.index', ['sumber_dana' => $sumberDana->id]) }}" class="text-blue-600 hover:text-blue-700">
                            Lihat semua perencanaan penerimaan →
                        </a>
                    </div>
                @endif
            </div>
        @endif

        <!-- Audit Info -->
        <div class="mt-6 flex items-center justify-between text-sm text-gray-500">
            <div>Dibuat oleh: {{ $sumberDana->createdBy->name ?? '-' }}</div>
            <div>{{ \Carbon\Carbon::parse($sumberDana->created_at)->format('d F Y, H:i') }}</div>
        </div>
    </div>
</x-app-layout>
