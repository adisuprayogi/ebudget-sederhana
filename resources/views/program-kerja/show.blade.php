<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="min-w-0">
                <nav class="flex text-xs text-gray-500 mb-2 overflow-x-auto">
                    <a href="{{ route('program-kerja.index') }}" class="hover:text-blue-600 whitespace-nowrap">Program Kerja</a>
                    <svg class="w-4 h-4 mx-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <a href="{{ route('program-kerja.divisi-show', $divisi) }}" class="hover:text-blue-600 whitespace-nowrap">{{ $divisi->nama_divisi }}</a>
                    <svg class="w-4 h-4 mx-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <span class="text-gray-900 truncate">{{ $programKerja->nama_program }}</span>
                </nav>
                <h1 class="text-xl font-semibold text-gray-900 truncate">{{ $programKerja->nama_program }}</h1>
                <p class="text-xs text-gray-500 mt-0.5 truncate">{{ $programKerja->kode_program }} | {{ $divisi->nama_divisi }}</p>
            </div>
            @if(auth()->user()->hasAnyRole(['superadmin', 'direktur_utama', 'kepala_divisi']))
                @php
                    $canEdit = true;
                    $periode = $programKerja->periodeAnggaran;
                    if($periode && $periode->fase !== 'perencanan') {
                        $canEdit = false;
                    }
                @endphp
                @if($canEdit)
                    <a href="{{ route('program-kerja.edit', [$divisi, $programKerja]) }}" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit
                    </a>
                @endif
            @endif
        </div>
    </x-slot>

    <div class="space-y-4">
        <!-- Quick Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <div class="bg-white rounded-xl border border-blue-100 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">{{ formatRupiah($statistics['total_pagu']) }}</p>
                        <p class="text-xs text-gray-500">Total Pagu</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-blue-100 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-violet-50 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xl font-semibold text-gray-900">{{ $statistics['jumlah_sub_program'] }}</p>
                        <p class="text-xs text-gray-500">Sub Program</p>
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
                        <p class="text-sm font-semibold text-gray-900">{{ formatRupiah($statistics['total_detail_anggaran']) }}</p>
                        <p class="text-xs text-gray-500">Realisasi</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-blue-100 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-amber-50 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold {{ $statistics['sisa_pagu'] < 0 ? 'text-red-600' : 'text-gray-900' }}">{{ formatRupiah($statistics['sisa_pagu']) }}</p>
                        <p class="text-xs text-gray-500">Sisa Pagu</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="bg-white rounded-xl border border-blue-100 p-4">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-medium text-gray-600">Pagu Terpakai</span>
                <span class="text-xs font-semibold {{ $statistics['persentase_terpakai'] > 90 ? 'text-red-600' : ($statistics['persentase_terpakai'] > 70 ? 'text-amber-600' : 'text-blue-600') }}">
                    {{ $statistics['persentase_terpakai'] }}%
                </span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="h-2 rounded-full {{ $statistics['persentase_terpakai'] > 90 ? 'bg-red-500' : ($statistics['persentase_terpakai'] > 70 ? 'bg-amber-500' : 'bg-blue-500') }}" style="width: {{ min($statistics['persentase_terpakai'], 100) }}%"></div>
            </div>
            <div class="flex justify-between mt-2 text-xs text-gray-500">
                <span>{{ formatRupiah($statistics['total_detail_anggaran']) }} terpakai</span>
                <span>{{ formatRupiah($statistics['sisa_pagu']) }} tersisa</span>
            </div>
        </div>

        <!-- Program Info -->
        <div class="bg-white rounded-xl border border-blue-100 p-3 md:p-4">
            <h3 class="text-sm font-semibold text-gray-900 mb-3">Informasi Program</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 md:gap-4">
                <div>
                    <p class="text-xs text-gray-500 mb-0.5">Kode Program</p>
                    <p class="text-sm font-medium text-gray-900 break-all">{{ $programKerja->kode_program }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 mb-0.5">Nama Program</p>
                    <p class="text-sm font-medium text-gray-900 break-words">{{ $programKerja->nama_program }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 mb-0.5">Divisi</p>
                    <p class="text-sm font-medium text-gray-900">{{ $divisi->nama_divisi }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 mb-0.5">Periode Anggaran</p>
                    <p class="text-sm font-medium text-gray-900 break-words">{{ $programKerja->periodeAnggaran->nama_periode }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 mb-0.5">Target Output</p>
                    <p class="text-sm font-medium text-gray-900 break-words">{{ $programKerja->target_output ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 mb-0.5">Status</p>
                    <div>
                        @if($programKerja->status === 'active')
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">Aktif</span>
                        @elseif($programKerja->status === 'inactive')
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Non-Aktif</span>
                        @elseif($programKerja->status === 'suspended')
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-700">Ditangguhkan</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">{{ $programKerja->status }}</span>
                        @endif
                    </div>
                </div>
                <div class="sm:col-span-2 lg:col-span-3">
                    <p class="text-xs text-gray-500 mb-0.5">Deskripsi</p>
                    <p class="text-sm font-medium text-gray-900 break-words">{{ $programKerja->deskripsi ?? '-' }}</p>
                </div>
            </div>
        </div>

        <!-- Sub Program Section -->
        <div class="bg-white rounded-xl border border-blue-100 p-3 md:p-4">
            <div class="flex items-center justify-between mb-4 gap-2">
                <h3 class="text-sm font-semibold text-gray-900">Sub Program</h3>
                @if(auth()->user()->hasAnyRole(['superadmin', 'direktur_utama', 'kepala_divisi']))
                    <button onclick="toggleAddSubProgramForm()" class="w-full sm:w-auto inline-flex items-center justify-center px-3 py-1.5 bg-blue-600 text-white text-xs font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah
                    </button>
                @endif
            </div>

            @if(auth()->user()->hasAnyRole(['superadmin', 'direktur_utama', 'kepala_divisi']))
                <!-- Add Sub Program Form -->
                <form id="addSubProgramForm" method="POST" action="{{ route('program-kerja.sub-programs.store', [$divisi, $programKerja]) }}" class="hidden mb-4 bg-blue-50 rounded-lg p-3 md:p-4">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Kode Sub Program</label>
                            <input type="text" name="kode_sub_program" required class="w-full px-3 py-2 border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" placeholder="Contoh: 1.1">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Nama Sub Program</label>
                            <input type="text" name="nama_sub_program" required class="w-full px-3 py-2 border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" placeholder="Contoh: Pengadaan ATK">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Pagu Anggaran</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-xs">Rp</span>
                                <input type="text" name="pagu_anggaran" required class="nominal-input w-full pl-10 pr-3 py-2 border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" placeholder="0">
                            </div>
                        </div>
                        <div class="sm:col-span-3 flex flex-col sm:flex-row justify-end gap-2">
                            <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">Simpan</button>
                            <button type="button" onclick="toggleAddSubProgramForm()" class="w-full sm:w-auto px-4 py-2 border border-blue-200 text-gray-700 rounded-lg hover:bg-blue-50 transition-colors text-sm">Batal</button>
                        </div>
                    </div>
                </form>
            @endif

            <!-- Sub Program Cards -->
            <div class="space-y-4">
                @forelse($programKerja->subPrograms as $subProgram)
                    <div class="border border-blue-100 rounded-lg overflow-hidden">
                        <!-- Sub Program Header -->
                        <div class="bg-blue-50 px-3 md:px-4 py-3">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                <div class="flex flex-wrap items-center gap-2 min-w-0">
                                    <span class="font-mono text-xs font-semibold text-blue-600 flex-shrink-0">{{ $subProgram->kode_sub_program }}</span>
                                    <span class="text-sm font-medium text-gray-900 truncate">{{ $subProgram->nama_sub_program }}</span>
                                    <span class="text-xs text-gray-500 flex-shrink-0">{{ formatRupiah($subProgram->pagu_anggaran) }}</span>
                                </div>
                                <div class="flex items-center gap-1 sm:gap-2 flex-wrap">
                                    @if(auth()->user()->hasAnyRole(['superadmin', 'direktur_utama', 'kepala_divisi']))
                                        <button onclick="openSubProgramModal({{ $subProgram->id }}, '{{ $subProgram->nama_sub_program }}', {{ $subProgram->pagu_anggaran }})" class="p-1.5 text-gray-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <form method="POST" action="{{ route('program-kerja.sub-programs.destroy', [$divisi, $programKerja, $subProgram]) }}" onsubmit="return confirm('Yakin ingin menghapus sub program ini?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                    <button onclick="openDetailAnggaranModal({{ $subProgram->id }}, '{{ $subProgram->nama_sub_program }}')" class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-xs">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Detail
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Detail Anggaran List -->
                        <div class="bg-white">
                            @if($subProgram->detailAnggarans && $subProgram->detailAnggarans->count() > 0)
                                <div class="divide-y divide-blue-50">
                                    @foreach($subProgram->detailAnggarans as $detail)
                                        <div class="p-3 md:p-4 hover:bg-blue-50/50 transition-colors">
                                            <div class="flex flex-col gap-2">
                                                <div class="flex items-start justify-between gap-2">
                                                    <div class="flex-1 min-w-0">
                                                        <div class="flex flex-wrap items-center gap-1.5">
                                                            <span class="font-medium text-gray-900 text-sm">{{ $detail->nama_detail }}</span>
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-700 flex-shrink-0">
                                                                {{ $detail->frekuensi }}
                                                            </span>
                                                            <span class="text-xs text-gray-500">{{ $detail->jumlah_periode }} periode</span>
                                                        </div>
                                                        @if($detail->deskripsi)
                                                            <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $detail->deskripsi }}</p>
                                                        @endif
                                                        <div class="text-xs text-gray-600 mt-2">
                                                            <span class="text-gray-500">{{ $detail->jumlah_periode }} x {{ formatRupiah($detail->nominal_per_periode) }}</span>
                                                            <span class="mx-2">•</span>
                                                            <span class="font-semibold text-blue-600">{{ formatRupiah($detail->total_nominal) }}</span>
                                                        </div>
                                                        <!-- Estimasi Pengeluaran -->
                                                        @if($detail->estimasiPengeluarans && $detail->estimasiPengeluarans->count() > 0)
                                                            <div class="mt-3 space-y-1">
                                                                @foreach($detail->estimasiPengeluarans as $estimasi)
                                                                    <div class="flex flex-wrap items-center justify-between gap-1 px-2 py-1.5 text-xs rounded
                                                                        @if($estimasi->status === 'selesai') bg-blue-50
                                                                        @elseif($estimasi->status === 'pending') bg-amber-50
                                                                        @else bg-gray-50 @endif">
                                                                        <div class="flex flex-wrap items-center gap-1.5 min-w-0">
                                                                            <span class="text-gray-600">{{ $estimasi->tanggal_rencana_realisasi->format('M Y') }}</span>
                                                                            <span class="font-medium text-gray-800">{{ formatRupiah($estimasi->nominal_rencana) }}</span>
                                                                            @if($estimasi->nominal_realisasi)
                                                                                <span class="text-blue-600">→ {{ formatRupiah($estimasi->nominal_realisasi) }}</span>
                                                                            @endif
                                                                            <span class="px-1.5 py-0.5 rounded-full text-xs flex-shrink-0
                                                                                @if($estimasi->status === 'selesai') bg-blue-200 text-blue-800
                                                                                @elseif($estimasi->status === 'pending') bg-amber-200 text-amber-800
                                                                                @else bg-gray-200 text-gray-800 @endif">
                                                                                {{ $estimasi->status }}
                                                                            </span>
                                                                        </div>
                                                                        @if(auth()->user()->hasAnyRole(['superadmin', 'direktur_utama', 'kepala_divisi']))
                                                                            <button onclick="openEstimasiModal({{ $estimasi->id }}, '{{ $estimasi->tanggal_rencana_realisasi->format('Y-m-d') }}', {{ $estimasi->nominal_rencana }}, '{{ addslashes($estimasi->catatan ?? '') }}', {{ $detail->id }}, {{ $subProgram->id }})" class="text-blue-600 hover:text-blue-800 text-xs font-medium flex-shrink-0">Edit</button>
                                                                        @endif
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    </div>
                                                    @if(auth()->user()->hasAnyRole(['superadmin', 'direktur_utama', 'kepala_divisi']))
                                                        <div class="flex items-center gap-1">
                                                            <button onclick="openDetailAnggaranEditModal({{ $detail->id }}, '{{ $detail->nama_detail }}', '{{ $detail->frekuensi }}', {{ $detail->jumlah_periode }}, {{ $detail->nominal_per_periode }}, {{ $subProgram->id }})" class="p-1.5 text-gray-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Edit">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                                </svg>
                                                            </button>
                                                            <form method="POST" action="{{ route('program-kerja.sub-programs.detail-anggaran.destroy', [$divisi, $programKerja, $subProgram, $detail]) }}" onsubmit="return confirm('Yakin ingin menghapus detail ini?')" class="inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                    </svg>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="px-4 py-6 text-center text-gray-400">
                                    <svg class="w-10 h-10 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="text-sm">Belum ada detail anggaran</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                        </svg>
                        <p class="text-sm font-medium">Belum ada sub program</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Audit Info -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between text-xs text-gray-500 px-2 gap-1">
            <span>Dibuat: {{ $programKerja->createdBy->name ?? '-' }}</span>
            <span>{{ \Carbon\Carbon::parse($programKerja->created_at)->format('d F Y, H:i') }}</span>
        </div>
    </div>
</div>

<!-- Modal Tambah Detail Anggaran -->
<div id="detailAnggaranModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-lg max-w-lg w-full mx-4">
        <div class="p-4 border-b border-blue-100">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-900">Tambah Detail Anggaran</h3>
                <button onclick="closeDetailAnggaranModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <p class="text-xs text-gray-500 mt-1">Sub Program: <span id="modalSubProgramName" class="font-medium text-gray-700"></span></p>
        </div>
        <form method="POST" id="detailAnggaranModalForm" class="p-4">
            @csrf
            <input type="hidden" name="sub_program_id" id="modalSubProgramId">
            <div class="space-y-3">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Nama Detail</label>
                    <input type="text" name="nama_detail" required class="w-full px-3 py-2 border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" placeholder="Contoh: Sewa Biznet">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Frekuensi</label>
                        <select name="frekuensi" required class="w-full px-3 py-2 border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                            <option value="sekali">Sekali</option>
                            <option value="bulanan" selected>Bulanan</option>
                            <option value="triwulan">Triwulan</option>
                            <option value="semesteran">Semesteran</option>
                            <option value="tahunan">Tahunan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Jumlah Periode</label>
                        <input type="number" name="jumlah_periode" id="modalJumlahPeriode" required min="1" value="1" class="w-full px-3 py-2 border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Nominal Per Periode</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm">Rp</span>
                        <input type="text" name="nominal_per_periode" id="modalNominalPerPeriode" required class="nominal-input w-full pl-10 pr-3 py-2 border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" placeholder="0">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Satuan</label>
                        <input type="text" name="satuan" class="w-full px-3 py-2 border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" placeholder="bulan, unit">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai_custom" class="w-full px-3 py-2 border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="deskripsi" rows="2" class="w-full px-3 py-2 border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" placeholder="Deskripsi singkat..."></textarea>
                </div>
                <div class="flex items-center justify-between pt-2">
                    <span class="text-sm text-blue-600 font-medium" id="modalTotalPreview">Total: Rp 0</span>
                    <div class="flex gap-2">
                        <button type="button" onclick="closeDetailAnggaranModal()" class="px-4 py-2 border border-blue-200 text-gray-700 rounded-lg hover:bg-blue-50 transition-colors text-sm">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">Simpan</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Estimasi Pengeluaran -->
<div id="estimasiModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-lg max-w-lg w-full mx-4">
        <div class="p-4 border-b border-blue-100">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-900">Edit Estimasi Pengeluaran</h3>
                <button onclick="closeEstimasiModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
        <form method="POST" id="estimasiModalForm" class="p-4">
            @csrf
            @method('PUT')
            <input type="hidden" name="estimasi_id" id="modalEstimasiId">
            <div class="space-y-3">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Tanggal Rencana</label>
                        <input type="date" name="tanggal_rencana_realisasi" id="modalTanggalRencana" required class="w-full px-3 py-2 border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Nominal Rencana</label>
                        <input type="text" name="nominal_rencana" id="modalNominalRencana" required class="nominal-input w-full px-3 py-2 border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" placeholder="0">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Catatan</label>
                    <textarea name="catatan" id="modalCatatan" rows="2" class="w-full px-3 py-2 border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" placeholder="Catatan..."></textarea>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="closeEstimasiModal()" class="px-4 py-2 border border-blue-200 text-gray-700 rounded-lg hover:bg-blue-50 transition-colors text-sm">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Sub Program -->
<div id="subProgramModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-lg max-w-lg w-full mx-4">
        <div class="p-4 border-b border-blue-100">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-900">Edit Sub Program</h3>
                <button onclick="closeSubProgramModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
        <form method="POST" id="subProgramModalForm" class="p-4">
            @csrf
            @method('PUT')
            <input type="hidden" name="sub_program_id" id="modalSubProgramId">
            <div class="space-y-3">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Nama Sub Program</label>
                    <input type="text" name="nama_sub_program" id="modalSubProgramNama" required class="w-full px-3 py-2 border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Pagu Anggaran</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm">Rp</span>
                        <input type="text" name="pagu_anggaran" id="modalSubProgramPagu" required class="nominal-input w-full pl-10 pr-3 py-2 border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                    </div>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="closeSubProgramModal()" class="px-4 py-2 border border-blue-200 text-gray-700 rounded-lg hover:bg-blue-50 transition-colors text-sm">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Detail Anggaran -->
<div id="detailAnggaranEditModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-lg max-w-lg w-full mx-4">
        <div class="p-4 border-b border-blue-100">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-900">Edit Detail Anggaran</h3>
                <button onclick="closeDetailAnggaranEditModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
        <form method="POST" id="detailAnggaranEditModalForm" class="p-4">
            @csrf
            @method('PUT')
            <input type="hidden" name="detail_id" id="modalDetailAnggaranId">
            <div class="space-y-3">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Nama Detail</label>
                    <input type="text" name="nama_detail" id="modalDetailNama" required class="w-full px-3 py-2 border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Frekuensi</label>
                        <select name="frekuensi" id="modalDetailFrekuensi" required class="w-full px-3 py-2 border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                            <option value="sekali">Sekali</option>
                            <option value="bulanan">Bulanan</option>
                            <option value="triwulan">Triwulan</option>
                            <option value="semesteran">Semesteran</option>
                            <option value="tahunan">Tahunan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Jumlah Periode</label>
                        <input type="number" name="jumlah_periode" id="modalDetailJumlahPeriode" required min="1" class="w-full px-3 py-2 border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Nominal Per Periode</label>
                    <input type="text" name="nominal_per_periode" id="modalDetailNominalPerPeriode" required class="nominal-input w-full px-3 py-2 border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="closeDetailAnggaranEditModal()" class="px-4 py-2 border border-blue-200 text-gray-700 rounded-lg hover:bg-blue-50 transition-colors text-sm">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    const subPrograms = @js($programKerja->subPrograms ?? []);
    const divisiId = @js($divisi->id);
    const programKerjaId = @js($programKerja->id);

    function toggleAddSubProgramForm() {
        const form = document.getElementById('addSubProgramForm');
        if (form) {
            form.classList.toggle('hidden');
        }
    }

    function openSubProgramModal(subProgramId, nama, pagu) {
        const modal = document.getElementById('subProgramModal');
        const form = document.getElementById('subProgramModalForm');
        document.getElementById('modalSubProgramId').value = subProgramId;
        document.getElementById('modalSubProgramNama').value = nama;
        document.getElementById('modalSubProgramPagu').value = formatNominal(pagu.toString());
        form.action = `/program-kerja/${divisiId}/${programKerjaId}/sub-programs/${subProgramId}`;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeSubProgramModal() {
        const modal = document.getElementById('subProgramModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function openDetailAnggaranEditModal(detailId, nama, frekuensi, jumlahPeriode, nominalPerPeriode, subProgramId) {
        const modal = document.getElementById('detailAnggaranEditModal');
        const form = document.getElementById('detailAnggaranEditModalForm');
        document.getElementById('modalDetailAnggaranId').value = detailId;
        document.getElementById('modalDetailNama').value = nama;
        document.getElementById('modalDetailFrekuensi').value = frekuensi;
        document.getElementById('modalDetailJumlahPeriode').value = jumlahPeriode;
        document.getElementById('modalDetailNominalPerPeriode').value = formatNominal(nominalPerPeriode.toString());
        form.action = `/program-kerja/${divisiId}/${programKerjaId}/sub-programs/${subProgramId}/detail-anggaran/${detailId}`;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeDetailAnggaranEditModal() {
        const modal = document.getElementById('detailAnggaranEditModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function openDetailAnggaranModal(subProgramId, subProgramName) {
        const modal = document.getElementById('detailAnggaranModal');
        const form = document.getElementById('detailAnggaranModalForm');
        const subProgramIdInput = document.getElementById('modalSubProgramId');
        const subProgramNameSpan = document.getElementById('modalSubProgramName');
        subProgramIdInput.value = subProgramId;
        subProgramNameSpan.textContent = subProgramName;
        form.action = `/program-kerja/${divisiId}/${programKerjaId}/sub-programs/${subProgramId}/detail-anggaran`;
        form.reset();
        document.getElementById('modalTotalPreview').textContent = 'Total: Rp 0';
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeDetailAnggaranModal() {
        const modal = document.getElementById('detailAnggaranModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function calculateModalTotal() {
        const jumlahPeriode = document.getElementById('modalJumlahPeriode')?.value || 0;
        const nominalPerPeriode = unformatNominal(document.getElementById('modalNominalPerPeriode')?.value || '0');
        const total = parseFloat(jumlahPeriode) * parseFloat(nominalPerPeriode);
        const totalPreview = document.getElementById('modalTotalPreview');
        if (totalPreview) {
            totalPreview.textContent = 'Total: Rp ' + formatNominal(total.toString());
        }
    }

    function openEstimasiModal(estimasiId, tanggalRencana, nominalRencana, catatan, detailId, subProgramId) {
        const modal = document.getElementById('estimasiModal');
        const form = document.getElementById('estimasiModalForm');
        document.getElementById('modalEstimasiId').value = estimasiId;
        document.getElementById('modalTanggalRencana').value = tanggalRencana;
        document.getElementById('modalNominalRencana').value = formatNominal(nominalRencana.toString());
        document.getElementById('modalCatatan').value = catatan;
        form.action = `/program-kerja/${divisiId}/${programKerjaId}/sub-programs/${subProgramId}/detail-anggaran/${detailId}/estimasi-pengeluaran/${estimasiId}`;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeEstimasiModal() {
        const modal = document.getElementById('estimasiModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeDetailAnggaranModal();
            closeEstimasiModal();
            closeSubProgramModal();
            closeDetailAnggaranEditModal();
        }
    });

    document.getElementById('detailAnggaranModal')?.addEventListener('click', function(event) {
        if (event.target === this) closeDetailAnggaranModal();
    });

    document.getElementById('estimasiModal')?.addEventListener('click', function(event) {
        if (event.target === this) closeEstimasiModal();
    });

    document.getElementById('subProgramModal')?.addEventListener('click', function(event) {
        if (event.target === this) closeSubProgramModal();
    });

    document.getElementById('detailAnggaranEditModal')?.addEventListener('click', function(event) {
        if (event.target === this) closeDetailAnggaranEditModal();
    });

    document.addEventListener('DOMContentLoaded', function() {
        const modalJumlahPeriode = document.getElementById('modalJumlahPeriode');
        if (modalJumlahPeriode) {
            modalJumlahPeriode.addEventListener('input', calculateModalTotal);
        }
        setupNominalFormatter();
    });

    function formatNominal(value) {
        const cleanValue = value.replace(/\./g, '');
        if (!cleanValue) return '';
        return cleanValue.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function unformatNominal(value) {
        return value.replace(/\./g, '');
    }

    function setupNominalFormatter() {
        const nominalInputs = document.querySelectorAll('.nominal-input');
        nominalInputs.forEach(input => {
            const initialValue = input.getAttribute('value');
            if (initialValue) {
                input.value = formatNominal(initialValue);
            }
            input.addEventListener('input', function(e) {
                let value = e.target.value.replace(/[^\d]/g, '');
                e.target.value = formatNominal(value);
                if (input.id === 'modalNominalPerPeriode') {
                    calculateModalTotal();
                }
            });
            input.closest('form')?.addEventListener('submit', function(e) {
                input.value = unformatNominal(input.value) || 0;
            });
        });
    }
</script>
</x-app-layout>
