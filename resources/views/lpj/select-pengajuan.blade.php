<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('lpj.index') }}" class="p-2 text-secondary-600 hover:text-secondary-900 hover:bg-secondary-100 rounded-lg transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-secondary-900">Buat LPJ Baru</h1>
                <p class="text-secondary-600 mt-1">Pilih pengajuan dana yang akan dibuatkan Laporan Pertanggung Jawaban</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <!-- Filters -->
        <div class="mb-6 bg-white rounded-2xl shadow-soft p-6">
            <form method="GET" action="{{ route('lpj.select-pengajuan') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <x-input-label for="search" value="Cari Pengajuan" />
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                        class="mt-1 block w-full px-4 py-2 border border-secondary-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                        placeholder="Nomor atau judul pengajuan">
                </div>

                <div>
                    <x-input-label for="divisi_id" value="Divisi" />
                    <select name="divisi_id" id="divisi_id"
                        class="mt-1 block w-full px-4 py-2 border border-secondary-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <option value="">Semua Divisi</option>
                        @foreach($divisis ?? \App\Models\Divisi::orderBy('nama_divisi')->get() as $divisi)
                            <option value="{{ $divisi->id }}" {{ request('divisi_id') == $divisi->id ? 'selected' : '' }}>
                                {{ $divisi->nama_divisi }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <x-input-label for="periode_anggaran_id" value="Periode Anggaran" />
                    <select name="periode_anggaran_id" id="periode_anggaran_id"
                        class="mt-1 block w-full px-4 py-2 border border-secondary-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <option value="">Semua Periode</option>
                        @foreach($periodeAnggaran ?? \App\Models\PeriodeAnggaran::orderBy('tahun_anggaran', 'desc')->get() as $periode)
                            <option value="{{ $periode->id }}" {{ request('periode_anggaran_id') == $periode->id ? 'selected' : '' }}>
                                {{ $periode->nama_periode }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="submit" class="w-full px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V5.414a1 1 0 01-.293.707L3.293 1.707A1 1 0 013 1v2.586z" />
                        </svg>
                        Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Info -->
        <div class="mb-6 bg-blue-50 border border-blue-200 rounded-xl p-4 flex items-start">
            <svg class="w-5 h-5 text-blue-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div class="text-blue-700 text-sm">
                Daftar pengajuan dana dengan status <strong>Menunggu LPJ</strong>. Pilih pengajuan untuk membuat Laporan Pertanggung Jawaban.
            </div>
        </div>

        <!-- Pengajuan List -->
        @if($pengajuans->count() > 0)
            <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-secondary-50">
                            <tr>
                                <th class="px-6 py-4 text-left font-semibold text-secondary-700">Nomor Pengajuan</th>
                                <th class="px-6 py-4 text-left font-semibold text-secondary-700">Judul Pengajuan</th>
                                <th class="px-6 py-4 text-left font-semibold text-secondary-700">Divisi</th>
                                <th class="px-6 py-4 text-left font-semibold text-secondary-700">Program Kerja</th>
                                <th class="px-6 py-4 text-right font-semibold text-secondary-700">Total Pengajuan</th>
                                <th class="px-6 py-4 text-center font-semibold text-secondary-700">Pencairan</th>
                                <th class="px-6 py-4 text-center font-semibold text-secondary-700">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-secondary-200">
                            @foreach($pengajuans as $pengajuan)
                            @php
                                $pencairan = $pengajuan->activePencairan;
                            @endphp
                            <tr class="hover:bg-secondary-50">
                                <td class="px-6 py-4">
                                    <span class="font-mono text-primary-600 font-semibold">{{ $pengajuan->nomor_pengajuan }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-secondary-900">{{ $pengajuan->judul_pengajuan }}</div>
                                    @if($pengajuan->subProgram)
                                        <div class="text-xs text-secondary-500 mt-1">{{ $pengajuan->subProgram->nama_sub_program }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-700">
                                        {{ $pengajuan->divisi->nama_divisi ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($pengajuan->programKerja)
                                        <div class="text-secondary-600">{{ $pengajuan->programKerja->nama_program }}</div>
                                        @if($pengajuan->programKerja->periodeAnggaran)
                                            <div class="text-xs text-secondary-500">{{ $pengajuan->programKerja->periodeAnggaran->nama_periode }}</div>
                                        @endif
                                    @else
                                        <span class="text-secondary-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right font-semibold text-secondary-900">
                                    {{ formatRupiah($pengajuan->total_pengajuan) }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($pencairan)
                                        <div class="text-xs text-secondary-600">
                                            <div class="font-mono">{{ $pencairan->nomor_pencairan }}</div>
                                            <div class="text-primary-600 font-semibold">{{ formatRupiah($pencairan->jumlah_pencairan) }}</div>
                                            <div class="text-xs">{{ $pencairan->tanggal_pencairan->format('d/m/Y') }}</div>
                                        </div>
                                    @else
                                        <span class="text-red-500">Tidak ada pencairan aktif</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($pencairan)
                                        <a href="{{ route('lpj.create', ['pengajuan_dana_id' => $pengajuan->id]) }}"
                                           class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors text-sm font-medium">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                <div class="px-6 py-4 border-t border-secondary-200">
                    {{ $pengajuans->appends(['search' => request('search'), 'divisi_id' => request('divisi_id'), 'periode_anggaran_id' => request('periode_anggaran_id')])->links() }}
                </div>
            </div>
        @else
            <div class="bg-white rounded-2xl shadow-soft p-12 text-center">
                <svg class="w-16 h-16 mx-auto text-secondary-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="text-lg font-semibold text-secondary-900 mb-2">Tidak Ada Pengajuan Menunggu LPJ</h3>
                <p class="text-secondary-500">Tidak ada pengajuan dana yang menunggu untuk dibuatkan LPJ saat ini.</p>
            </div>
        @endif
    </div>
</x-app-layout>
