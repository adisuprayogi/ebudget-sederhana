<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('lpj.index') }}" class="p-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-xl font-semibold text-gray-900">Buat LPJ</h1>
                <p class="text-xs text-gray-500 mt-0.5">Pilih pengajuan dana untuk membuat Laporan Pertanggung Jawaban</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto">
        <!-- Filters -->
        <div class="mb-4 bg-white rounded-xl border border-blue-100 p-4">
            <form method="GET" action="{{ route('lpj.select-pengajuan') }}" class="grid grid-cols-4 gap-3">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Cari</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                        class="w-full px-3 py-2 border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                        placeholder="Nomor atau judul">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Divisi</label>
                    <select name="divisi_id" id="divisi_id"
                        class="w-full px-3 py-2 border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <option value="">Semua Divisi</option>
                        @foreach($divisis ?? \App\Models\Divisi::orderBy('nama_divisi')->get() as $divisi)
                            <option value="{{ $divisi->id }}" {{ request('divisi_id') == $divisi->id ? 'selected' : '' }}>
                                {{ $divisi->nama_divisi }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Periode</label>
                    <select name="periode_anggaran_id" id="periode_anggaran_id"
                        class="w-full px-3 py-2 border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <option value="">Semua Periode</option>
                        @foreach($periodeAnggaran ?? \App\Models\PeriodeAnggaran::orderBy('tahun_anggaran', 'desc')->get() as $periode)
                            <option value="{{ $periode->id }}" {{ request('periode_anggaran_id') == $periode->id ? 'selected' : '' }}>
                                {{ $periode->nama_periode }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="submit" class="w-full px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V5.414a1 1 0 01-.293.707L3.293 1.707A1 1 0 013 1v2.586z" />
                        </svg>
                        Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Info -->
        <div class="mb-4 bg-blue-50 border border-blue-100 rounded-lg p-3 flex items-center gap-3">
            <svg class="w-5 h-5 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="text-sm text-blue-700">Daftar pengajuan dengan status <strong>Menunggu LPJ</strong></p>
        </div>

        <!-- Pengajuan List -->
        @if($pengajuans->count() > 0)
            <div class="bg-white rounded-xl border border-blue-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-xs">
                        <thead class="bg-blue-50">
                            <tr>
                                <th class="px-3 py-2 text-left font-semibold text-blue-700">No. Pengajuan</th>
                                <th class="px-3 py-2 text-left font-semibold text-blue-700">Judul Pengajuan</th>
                                <th class="px-3 py-2 text-left font-semibold text-blue-700">Divisi</th>
                                <th class="px-3 py-2 text-left font-semibold text-blue-700">Program Kerja</th>
                                <th class="px-3 py-2 text-right font-semibold text-blue-700">Total</th>
                                <th class="px-3 py-2 text-center font-semibold text-blue-700">Pencairan</th>
                                <th class="px-3 py-2 text-center font-semibold text-blue-700">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-blue-50">
                            @foreach($pengajuans as $pengajuan)
                            @php
                                $pencairan = $pengajuan->activePencairan;
                            @endphp
                            <tr class="hover:bg-blue-50/50">
                                <td class="px-3 py-2">
                                    <span class="font-mono text-blue-600 font-semibold text-xs">{{ $pengajuan->nomor_pengajuan }}</span>
                                </td>
                                <td class="px-3 py-2">
                                    <div class="font-medium text-gray-900 text-sm">{{ $pengajuan->judul_pengajuan }}</div>
                                    @if($pengajuan->subProgram)
                                        <div class="text-xs text-gray-500">{{ $pengajuan->subProgram->nama_sub_program }}</div>
                                    @endif
                                </td>
                                <td class="px-3 py-2">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                        {{ $pengajuan->divisi->nama_divisi ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-3 py-2">
                                    @if($pengajuan->programKerja)
                                        <div class="text-gray-600 text-xs">{{ $pengajuan->programKerja->nama_program }}</div>
                                        @if($pengajuan->programKerja->periodeAnggaran)
                                            <div class="text-xs text-gray-400">{{ $pengajuan->programKerja->periodeAnggaran->nama_periode }}</div>
                                        @endif
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-3 py-2 text-right font-semibold text-gray-900 text-sm">
                                    {{ formatRupiah($pengajuan->total_pengajuan) }}
                                </td>
                                <td class="px-3 py-2 text-center">
                                    @if($pencairan)
                                        <div class="text-xs text-gray-600">
                                            <div class="font-mono">{{ $pencairan->nomor_pencairan }}</div>
                                            <div class="text-blue-600 font-semibold">{{ formatRupiah($pencairan->jumlah_pencairan) }}</div>
                                            <div class="text-xs">{{ $pencairan->tanggal_pencairan->format('d/m/Y') }}</div>
                                        </div>
                                    @else
                                        <span class="text-red-500">Tidak ada pencairan</span>
                                    @endif
                                </td>
                                <td class="px-3 py-2 text-center">
                                    @if($pencairan)
                                        <a href="{{ route('lpj.create', ['pengajuan_dana_id' => $pengajuan->id]) }}"
                                           class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-xs">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            Buat LPJ
                                        </a>
                                    @else
                                        <span class="text-xs text-red-500">Pencairan tidak tersedia</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-4 py-3 border-t border-blue-100 bg-gray-50">
                    {{ $pengajuans->appends(['search' => request('search'), 'divisi_id' => request('divisi_id'), 'periode_anggaran_id' => request('periode_anggaran_id')])->links() }}
                </div>
            </div>
        @else
            <div class="bg-white rounded-xl border border-blue-100 p-12 text-center">
                <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="text-sm font-semibold text-gray-900 mb-2">Tidak Ada Pengajuan Menunggu LPJ</h3>
                <p class="text-xs text-gray-500">Tidak ada pengajuan dana yang menunggu untuk dibuatkan LPJ saat ini.</p>
            </div>
        @endif
    </div>
</x-app-layout>
