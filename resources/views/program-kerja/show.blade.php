<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <nav class="flex text-sm text-secondary-500 mb-2">
                    <a href="{{ route('program-kerja.index') }}" class="hover:text-primary-600">Program Kerja</a>
                    <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <a href="{{ route('program-kerja.divisi-show', $divisi) }}" class="hover:text-primary-600">{{ $divisi->nama_divisi }}</a>
                    <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <span class="text-secondary-900">{{ $programKerja->nama_program }}</span>
                </nav>
                <h1 class="text-2xl font-bold text-secondary-900">{{ $programKerja->nama_program }}</h1>
                <p class="text-secondary-600 mt-1">{{ $programKerja->kode_program }} | {{ $divisi->nama_divisi }}</p>
            </div>
            @if(auth()->user()->hasAnyRole(['superadmin', 'direktur_utama']))
                <a href="{{ route('program-kerja.edit', [$divisi, $programKerja]) }}" class="inline-flex items-center px-4 py-2 bg-white border border-secondary-300 text-secondary-700 rounded-xl hover:bg-secondary-50 transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Program
                </a>
            @endif
        </div>
    </x-slot>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-soft p-6">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm text-secondary-500 mb-1">Total Pagu</div>
                    <div class="text-xl font-bold text-primary-600">{{ formatRupiah($statistics['total_pagu']) }}</div>
                </div>
                <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-soft p-6">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm text-secondary-500 mb-1">Total Sub Program</div>
                    <div class="text-2xl font-bold text-indigo-600">{{ $statistics['jumlah_sub_program'] }}</div>
                </div>
                <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-soft p-6">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm text-secondary-500 mb-1">Total Realisasi</div>
                    <div class="text-xl font-bold text-green-600">{{ formatRupiah($statistics['total_detail_anggaran']) }}</div>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-soft p-6">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm text-secondary-500 mb-1">Sisa Pagu</div>
                    <div class="text-xl font-bold {{ $statistics['sisa_pagu'] < 0 ? 'text-red-600' : 'text-amber-600' }}">{{ formatRupiah($statistics['sisa_pagu']) }}</div>
                </div>
                <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="bg-white rounded-2xl shadow-soft p-6 mb-8">
        <div class="flex items-center justify-between mb-2">
            <span class="text-sm font-medium text-secondary-700">Pagu Terpakai</span>
            <span class="text-sm font-semibold {{ $statistics['persentase_terpakai'] > 90 ? 'text-red-600' : ($statistics['persentase_terpakai'] > 70 ? 'text-amber-600' : 'text-green-600') }}">
                {{ $statistics['persentase_terpakai'] }}%
            </span>
        </div>
        <div class="w-full bg-secondary-200 rounded-full h-3">
            <div class="h-3 rounded-full {{ $statistics['persentase_terpakai'] > 90 ? 'bg-red-500' : ($statistics['persentase_terpakai'] > 70 ? 'bg-amber-500' : 'bg-green-500') }}" style="width: {{ min($statistics['persentase_terpakai'], 100) }}%"></div>
        </div>
        <div class="flex justify-between mt-2 text-xs text-secondary-500">
            <span>{{ formatRupiah($statistics['total_detail_anggaran']) }} terpakai</span>
            <span>{{ formatRupiah($statistics['sisa_pagu']) }} tersisa</span>
        </div>
    </div>

    <!-- Program Info -->
    <div class="bg-white rounded-2xl shadow-soft p-6 mb-8">
        <h3 class="text-lg font-semibold text-secondary-900 mb-4">Informasi Program</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <div class="text-sm text-secondary-500 mb-1">Kode Program</div>
                <div class="font-medium text-secondary-900">{{ $programKerja->kode_program }}</div>
            </div>
            <div>
                <div class="text-sm text-secondary-500 mb-1">Nama Program</div>
                <div class="font-medium text-secondary-900">{{ $programKerja->nama_program }}</div>
            </div>
            <div>
                <div class="text-sm text-secondary-500 mb-1">Divisi</div>
                <div class="font-medium text-secondary-900">{{ $divisi->nama_divisi }}</div>
            </div>
            <div>
                <div class="text-sm text-secondary-500 mb-1">Periode Anggaran</div>
                <div class="font-medium text-secondary-900">{{ $programKerja->periodeAnggaran->nama_periode }}</div>
            </div>
            <div>
                <div class="text-sm text-secondary-500 mb-1">Target Output</div>
                <div class="font-medium text-secondary-900">{{ $programKerja->target_output ?? '-' }}</div>
            </div>
            <div>
                <div class="text-sm text-secondary-500 mb-1">Status</div>
                <div>
                    @if($programKerja->status === 'active')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">Aktif</span>
                    @elseif($programKerja->status === 'inactive')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">Non-Aktif</span>
                    @elseif($programKerja->status === 'suspended')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">Ditangguhkan</span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-secondary-100 text-secondary-700">{{ $programKerja->status }}</span>
                    @endif
                </div>
            </div>
            <div class="md:col-span-2">
                <div class="text-sm text-secondary-500 mb-1">Deskripsi</div>
                <div class="font-medium text-secondary-900">{{ $programKerja->deskripsi ?? '-' }}</div>
            </div>
        </div>
    </div>

    <!-- Sub Program Section -->
    <div class="bg-white rounded-2xl shadow-soft p-6 mb-8">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-secondary-900">Sub Program</h3>
            @if(auth()->user()->hasAnyRole(['superadmin', 'direktur_utama', 'kepala_divisi']))
                <button onclick="toggleAddSubProgramForm()" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-all duration-200 shadow-soft hover:shadow-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Sub Program
                </button>
            @endif
        </div>

        @if(auth()->user()->hasAnyRole(['superadmin', 'direktur_utama', 'kepala_divisi']))
            <!-- Add Sub Program Form (Inline) -->
            <form id="addSubProgramForm" method="POST" action="{{ route('program-kerja.sub-programs.store', [$divisi, $programKerja]) }}" class="hidden mb-6 bg-secondary-50 rounded-xl p-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-secondary-700 mb-1">Kode Sub Program</label>
                        <input type="text" name="kode_sub_program" required class="w-full px-3 py-2 border border-secondary-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent text-sm" placeholder="Contoh: 1.1">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-secondary-700 mb-1">Nama Sub Program</label>
                        <input type="text" name="nama_sub_program" required class="w-full px-3 py-2 border border-secondary-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent text-sm" placeholder="Contoh: Pengadaan ATK">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-secondary-700 mb-1">Pagu Anggaran</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-secondary-500 text-sm">Rp</span>
                            <input type="text" name="pagu_anggaran" required class="nominal-input w-full pl-10 pr-3 py-2 border border-secondary-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent text-sm" placeholder="0">
                        </div>
                    </div>
                    <div class="md:col-span-2 flex items-end">
                        <div class="flex gap-2 w-full">
                            <button type="submit" class="flex-1 px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors text-sm">Simpan</button>
                            <button type="button" onclick="toggleAddSubProgramForm()" class="px-4 py-2 border border-secondary-300 text-secondary-700 rounded-lg hover:bg-secondary-50 transition-colors text-sm">Batal</button>
                        </div>
                    </div>
                </div>
            </form>
        @endif

        <!-- Sub Program Cards -->
        <div class="space-y-16">
            @forelse($programKerja->subPrograms as $subProgram)
                <div class="border border-secondary-200 rounded-xl overflow-hidden">
                    <!-- Sub Program Header -->
                    <div class="bg-secondary-50 px-6 py-4 flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div>
                                <span class="font-mono text-sm font-semibold text-primary-600">{{ $subProgram->kode_sub_program }}</span>
                            </div>
                            <div>
                                <h4 class="font-semibold text-secondary-900">{{ $subProgram->nama_sub_program }}</h4>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="text-right">
                                <div class="text-sm text-secondary-500">Pagu</div>
                                <div class="font-semibold text-secondary-900">{{ formatRupiah($subProgram->pagu_anggaran) }}</div>
                            </div>
                            @if(auth()->user()->hasAnyRole(['superadmin', 'direktur_utama', 'kepala_divisi']))
                                <button onclick="openSubProgramModal({{ $subProgram->id }}, '{{ $subProgram->nama_sub_program }}', {{ $subProgram->pagu_anggaran }})" class="p-2 text-secondary-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <form method="POST" action="{{ route('program-kerja.sub-programs.destroy', [$divisi, $programKerja, $subProgram]) }}" onsubmit="return confirm('Yakin ingin menghapus sub program ini?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-secondary-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            @endif
                            <button onclick="openDetailAnggaranModal({{ $subProgram->id }}, '{{ $subProgram->nama_sub_program }}')" class="inline-flex items-center px-3 py-1.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors text-sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Detail
                            </button>
                        </div>
                    </div>

                    <!-- Detail Anggaran List -->
                    <div class="bg-white">
                        <div class="px-6 py-3 bg-indigo-50 border-t border-indigo-100">
                            <h5 class="text-sm font-semibold text-indigo-900 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                                Detail Anggaran
                            </h5>
                        </div>
                        @if($subProgram->detailAnggarans && $subProgram->detailAnggarans->count() > 0)
                            <div class="divide-y divide-secondary-100">
                                @foreach($subProgram->detailAnggarans as $detail)
                                    <div class="p-6 hover:bg-secondary-50 transition-colors">
                                        <!-- Detail Header -->
                                        <div class="flex items-start justify-between mb-3">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-3">
                                                    <span class="font-semibold text-secondary-900">{{ $detail->nama_detail }}</span>
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-emerald-100 text-emerald-700">
                                                        {{ $detail->frekuensi }}
                                                    </span>
                                                    <span class="text-xs text-secondary-500">{{ $detail->jumlah_periode }} periode</span>
                                                </div>
                                                @if($detail->deskripsi)
                                                    <p class="text-sm text-secondary-500 mt-1">{{ $detail->deskripsi }}</p>
                                                @endif
                                                <div class="text-sm text-secondary-600 mt-2">
                                                    <span class="text-secondary-500">{{ $detail->jumlah_periode }} x {{ formatRupiah($detail->nominal_per_periode) }}</span>
                                                    <span class="mx-2">•</span>
                                                    <span class="font-semibold text-primary-600">{{ formatRupiah($detail->total_nominal) }}</span>
                                                </div>
                                            </div>
                                            @if(auth()->user()->hasAnyRole(['superadmin', 'direktur_utama', 'kepala_divisi']))
                                                <div class="flex items-center gap-1">
                                                    <button onclick="openDetailAnggaranEditModal({{ $detail->id }}, '{{ $detail->nama_detail }}', '{{ $detail->frekuensi }}', {{ $detail->jumlah_periode }}, {{ $detail->nominal_per_periode }}, {{ $subProgram->id }})" class="p-1.5 text-secondary-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Edit">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </button>
                                                    <form method="POST" action="{{ route('program-kerja.sub-programs.detail-anggaran.destroy', [$divisi, $programKerja, $subProgram, $detail]) }}" onsubmit="return confirm('Yakin ingin menghapus detail ini?')" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="p-1.5 text-secondary-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Estimasi Pengeluaran -->
                                        @if($detail->estimasiPengeluarans && $detail->estimasiPengeluarans->count() > 0)
                                            <div class="ml-4 mt-3 pl-4 border-l-2 border-secondary-200">
                                                <div class="text-xs text-secondary-500 mb-2 flex items-center gap-1">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                    Estimasi Pengeluaran
                                                </div>
                                                <div class="space-y-1">
                                                    @foreach($detail->estimasiPengeluarans as $estimasi)
                                                        <div class="flex items-center justify-between px-2 py-1 text-xs rounded
                                                            @if($estimasi->status === 'selesai') bg-green-50
                                                            @elseif($estimasi->status === 'pending') bg-amber-50
                                                            @else bg-slate-50 @endif">
                                                            <div class="flex items-center gap-2">
                                                                <span class="text-secondary-600">{{ $estimasi->tanggal_rencana_realisasi->format('M Y') }}</span>
                                                                <span class="font-medium text-secondary-800">{{ formatRupiah($estimasi->nominal_rencana) }}</span>
                                                                @if($estimasi->nominal_realisasi)
                                                                    <span class="text-green-600">→ {{ formatRupiah($estimasi->nominal_realisasi) }}</span>
                                                                @endif
                                                                <span class="px-1.5 py-0.5 rounded-full text-xs
                                                                    @if($estimasi->status === 'selesai') bg-green-200 text-green-800
                                                                    @elseif($estimasi->status === 'pending') bg-amber-200 text-amber-800
                                                                    @else bg-slate-200 text-slate-800 @endif">
                                                                    {{ $estimasi->status }}
                                                                </span>
                                                            </div>
                                                            @if(auth()->user()->hasAnyRole(['superadmin', 'direktur_utama', 'kepala_divisi']))
                                                                <div class="flex items-center gap-1">
                                                                    <button onclick="openEstimasiModal({{ $estimasi->id }}, '{{ $estimasi->tanggal_rencana_realisasi->format('Y-m-d') }}', {{ $estimasi->nominal_rencana }}, '{{ addslashes($estimasi->catatan ?? '') }}', {{ $detail->id }}, {{ $subProgram->id }})" class="text-primary-600 hover:text-primary-800 text-xs font-medium">Edit</button>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @else
                                            <div class="ml-4 mt-3 pl-4 border-l-2 border-dashed border-secondary-200 text-xs text-secondary-400 italic">
                                                Belum ada estimasi pengeluaran
                                            </div>
                                        @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="px-6 py-8 text-center text-secondary-400">
                            <svg class="w-12 h-12 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="text-sm">Belum ada detail anggaran</p>
                            <p class="text-xs mt-1">Klik tombol "Detail" di atas untuk menambah</p>
                        </div>
                    @endif
                </div>
            @empty
                <div class="text-center py-12 text-secondary-400">
                    <svg class="w-16 h-16 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                    <p class="text-lg font-medium mb-2">Belum ada sub program</p>
                    <p class="text-sm">Tambahkan sub program untuk mulai mengelola anggaran</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Audit Info -->
    <div class="flex items-center justify-between text-sm text-secondary-500">
        <div>Dibuat oleh: {{ $programKerja->createdBy->name ?? '-' }}</div>
        <div>{{ \Carbon\Carbon::parse($programKerja->created_at)->format('d F Y, H:i') }}</div>
    </div>
</div>

<!-- Modal Tambah Detail Anggaran -->
<div id="detailAnggaranModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-secondary-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-secondary-900">Tambah Detail Anggaran</h3>
                <button onclick="closeDetailAnggaranModal()" class="text-secondary-400 hover:text-secondary-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <p class="text-sm text-secondary-500 mt-1">Sub Program: <span id="modalSubProgramName" class="font-medium text-secondary-700"></span></p>
        </div>
        <form method="POST" id="detailAnggaranModalForm" class="p-6">
            @csrf
            <input type="hidden" name="sub_program_id" id="modalSubProgramId">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-secondary-700 mb-1">Nama Detail</label>
                    <input type="text" name="nama_detail" required class="w-full px-3 py-2 border border-secondary-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent text-sm" placeholder="Contoh: Sewa Biznet">
                </div>
                <div>
                    <label class="block text-sm font-medium text-secondary-700 mb-1">Frekuensi</label>
                    <select name="frekuensi" required class="w-full px-3 py-2 border border-secondary-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent text-sm">
                        <option value="sekali">Sekali</option>
                        <option value="bulanan" selected>Bulanan</option>
                        <option value="triwulan">Triwulan</option>
                        <option value="semesteran">Semesteran</option>
                        <option value="tahunan">Tahunan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-secondary-700 mb-1">Jumlah Periode</label>
                    <input type="number" name="jumlah_periode" id="modalJumlahPeriode" required min="1" value="1" class="w-full px-3 py-2 border border-secondary-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent text-sm">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-secondary-700 mb-1">Nominal Per Periode</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-secondary-500 text-sm">Rp</span>
                        <input type="text" name="nominal_per_periode" id="modalNominalPerPeriode" required class="nominal-input w-full pl-10 pr-3 py-2 border border-secondary-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent text-sm" placeholder="0">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-secondary-700 mb-1">Satuan</label>
                    <input type="text" name="satuan" class="w-full px-3 py-2 border border-secondary-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent text-sm" placeholder="Contoh: bulan, unit">
                </div>
                <div>
                    <label class="block text-sm font-medium text-secondary-700 mb-1">Tanggal Mulai Custom</label>
                    <input type="date" name="tanggal_mulai_custom" class="w-full px-3 py-2 border border-secondary-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent text-sm">
                    <p class="text-xs text-secondary-500 mt-1">Opsional, kosongkan untuk gunakan default periode anggaran</p>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-secondary-700 mb-1">Deskripsi</label>
                    <textarea name="deskripsi" rows="2" class="w-full px-3 py-2 border border-secondary-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent text-sm" placeholder="Deskripsi singkat..."></textarea>
                </div>
                <div class="md:col-span-2 flex items-center justify-between">
                    <span class="text-sm text-primary-600" id="modalTotalPreview">Total: Rp 0</span>
                    <div class="flex gap-2">
                        <button type="button" onclick="closeDetailAnggaranModal()" class="px-4 py-2 border border-secondary-300 text-secondary-700 rounded-lg hover:bg-secondary-50 transition-colors text-sm">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors text-sm">Simpan</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Estimasi Pengeluaran -->
<div id="estimasiModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-xl max-w-lg w-full mx-4">
        <div class="p-6 border-b border-secondary-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-secondary-900">Edit Estimasi Pengeluaran</h3>
                <button onclick="closeEstimasiModal()" class="text-secondary-400 hover:text-secondary-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
        <form method="POST" id="estimasiModalForm" class="p-6">
            @csrf
            @method('PUT')
            <input type="hidden" name="estimasi_id" id="modalEstimasiId">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-secondary-700 mb-1">Tanggal Rencana</label>
                    <input type="date" name="tanggal_rencana_realisasi" id="modalTanggalRencana" required class="w-full px-3 py-2 border border-secondary-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-secondary-700 mb-1">Nominal Rencana</label>
                    <input type="text" name="nominal_rencana" id="modalNominalRencana" required class="nominal-input w-full px-3 py-2 border border-secondary-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent text-sm" placeholder="0">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-secondary-700 mb-1">Catatan</label>
                    <textarea name="catatan" id="modalCatatan" rows="2" class="w-full px-3 py-2 border border-secondary-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent text-sm" placeholder="Catatan..."></textarea>
                </div>
                <div class="md:col-span-2 flex justify-end gap-2 pt-2">
                    <button type="button" onclick="closeEstimasiModal()" class="px-4 py-2 border border-secondary-300 text-secondary-700 rounded-lg hover:bg-secondary-50 transition-colors text-sm">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors text-sm">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Sub Program -->
<div id="subProgramModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-xl max-w-lg w-full mx-4">
        <div class="p-6 border-b border-secondary-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-secondary-900">Edit Sub Program</h3>
                <button onclick="closeSubProgramModal()" class="text-secondary-400 hover:text-secondary-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
        <form method="POST" id="subProgramModalForm" class="p-6">
            @csrf
            @method('PUT')
            <input type="hidden" name="sub_program_id" id="modalSubProgramId">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-secondary-700 mb-1">Nama Sub Program</label>
                    <input type="text" name="nama_sub_program" id="modalSubProgramNama" required class="w-full px-3 py-2 border border-secondary-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent text-sm">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-secondary-700 mb-1">Pagu Anggaran</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-secondary-500 text-sm">Rp</span>
                        <input type="text" name="pagu_anggaran" id="modalSubProgramPagu" required class="nominal-input w-full pl-10 pr-3 py-2 border border-secondary-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent text-sm">
                    </div>
                </div>
                <div class="md:col-span-2 flex justify-end gap-2 pt-2">
                    <button type="button" onclick="closeSubProgramModal()" class="px-4 py-2 border border-secondary-300 text-secondary-700 rounded-lg hover:bg-secondary-50 transition-colors text-sm">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors text-sm">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Detail Anggaran -->
<div id="detailAnggaranEditModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-xl max-w-lg w-full mx-4">
        <div class="p-6 border-b border-secondary-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-secondary-900">Edit Detail Anggaran</h3>
                <button onclick="closeDetailAnggaranEditModal()" class="text-secondary-400 hover:text-secondary-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
        <form method="POST" id="detailAnggaranEditModalForm" class="p-6">
            @csrf
            @method('PUT')
            <input type="hidden" name="detail_id" id="modalDetailAnggaranId">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-secondary-700 mb-1">Nama Detail</label>
                    <input type="text" name="nama_detail" id="modalDetailNama" required class="w-full px-3 py-2 border border-secondary-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-secondary-700 mb-1">Frekuensi</label>
                    <select name="frekuensi" id="modalDetailFrekuensi" required class="w-full px-3 py-2 border border-secondary-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent text-sm">
                        <option value="sekali">Sekali</option>
                        <option value="bulanan">Bulanan</option>
                        <option value="triwulan">Triwulan</option>
                        <option value="semesteran">Semesteran</option>
                        <option value="tahunan">Tahunan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-secondary-700 mb-1">Jumlah Periode</label>
                    <input type="number" name="jumlah_periode" id="modalDetailJumlahPeriode" required min="1" class="w-full px-3 py-2 border border-secondary-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent text-sm">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-secondary-700 mb-1">Nominal Per Periode</label>
                    <input type="text" name="nominal_per_periode" id="modalDetailNominalPerPeriode" required class="nominal-input w-full px-3 py-2 border border-secondary-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent text-sm">
                </div>
                <div class="md:col-span-2 flex justify-end gap-2 pt-2">
                    <button type="button" onclick="closeDetailAnggaranEditModal()" class="px-4 py-2 border border-secondary-300 text-secondary-700 rounded-lg hover:bg-secondary-50 transition-colors text-sm">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors text-sm">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    const subPrograms = @js($programKerja->subPrograms ?? []);
    const divisiId = @js($divisi->id);
    const programKerjaId = @js($programKerja->id);

    // Toggle add sub program form
    function toggleAddSubProgramForm() {
        const form = document.getElementById('addSubProgramForm');
        if (form) {
            form.classList.toggle('hidden');
        }
    }

    // Open modal for editing sub program
    function openSubProgramModal(subProgramId, nama, pagu) {
        const modal = document.getElementById('subProgramModal');
        const form = document.getElementById('subProgramModalForm');

        // Set form values
        document.getElementById('modalSubProgramId').value = subProgramId;
        document.getElementById('modalSubProgramNama').value = nama;
        document.getElementById('modalSubProgramPagu').value = formatNominal(pagu.toString());

        // Set form action
        form.action = `/program-kerja/${divisiId}/${programKerjaId}/sub-programs/${subProgramId}`;

        // Show modal
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    // Close sub program modal
    function closeSubProgramModal() {
        const modal = document.getElementById('subProgramModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // Open modal for editing detail anggaran
    function openDetailAnggaranEditModal(detailId, nama, frekuensi, jumlahPeriode, nominalPerPeriode, subProgramId) {
        const modal = document.getElementById('detailAnggaranEditModal');
        const form = document.getElementById('detailAnggaranEditModalForm');

        // Set form values
        document.getElementById('modalDetailAnggaranId').value = detailId;
        document.getElementById('modalDetailNama').value = nama;
        document.getElementById('modalDetailFrekuensi').value = frekuensi;
        document.getElementById('modalDetailJumlahPeriode').value = jumlahPeriode;
        document.getElementById('modalDetailNominalPerPeriode').value = formatNominal(nominalPerPeriode.toString());

        // Set form action
        form.action = `/program-kerja/${divisiId}/${programKerjaId}/sub-programs/${subProgramId}/detail-anggaran/${detailId}`;

        // Show modal
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    // Close detail anggaran edit modal
    function closeDetailAnggaranEditModal() {
        const modal = document.getElementById('detailAnggaranEditModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // Open modal for adding detail anggaran
    function openDetailAnggaranModal(subProgramId, subProgramName) {
        const modal = document.getElementById('detailAnggaranModal');
        const form = document.getElementById('detailAnggaranModalForm');
        const subProgramIdInput = document.getElementById('modalSubProgramId');
        const subProgramNameSpan = document.getElementById('modalSubProgramName');

        subProgramIdInput.value = subProgramId;
        subProgramNameSpan.textContent = subProgramName;

        // Set form action
        form.action = `/program-kerja/${divisiId}/${programKerjaId}/sub-programs/${subProgramId}/detail-anggaran`;

        // Reset form
        form.reset();
        document.getElementById('modalTotalPreview').textContent = 'Total: Rp 0';

        // Show modal
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    // Close modal
    function closeDetailAnggaranModal() {
        const modal = document.getElementById('detailAnggaranModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // Calculate total for modal
    function calculateModalTotal() {
        const jumlahPeriode = document.getElementById('modalJumlahPeriode')?.value || 0;
        const nominalPerPeriode = unformatNominal(document.getElementById('modalNominalPerPeriode')?.value || '0');
        const total = parseFloat(jumlahPeriode) * parseFloat(nominalPerPeriode);
        const totalPreview = document.getElementById('modalTotalPreview');
        if (totalPreview) {
            totalPreview.textContent = 'Total: Rp ' + formatNominal(total.toString());
        }
    }

    // Open modal for editing estimasi pengeluaran
    function openEstimasiModal(estimasiId, tanggalRencana, nominalRencana, catatan, detailId, subProgramId) {
        const modal = document.getElementById('estimasiModal');
        const form = document.getElementById('estimasiModalForm');

        // Set form values
        document.getElementById('modalEstimasiId').value = estimasiId;
        document.getElementById('modalTanggalRencana').value = tanggalRencana;
        document.getElementById('modalNominalRencana').value = formatNominal(nominalRencana.toString());
        document.getElementById('modalCatatan').value = catatan;

        // Set form action
        form.action = `/program-kerja/${divisiId}/${programKerjaId}/sub-programs/${subProgramId}/detail-anggaran/${detailId}/estimasi-pengeluaran/${estimasiId}`;

        // Show modal
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    // Close estimasi modal
    function closeEstimasiModal() {
        const modal = document.getElementById('estimasiModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // Close modal on escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeDetailAnggaranModal();
            closeEstimasiModal();
            closeSubProgramModal();
            closeDetailAnggaranEditModal();
        }
    });

    // Close modal on backdrop click
    document.getElementById('detailAnggaranModal')?.addEventListener('click', function(event) {
        if (event.target === this) {
            closeDetailAnggaranModal();
        }
    });

    document.getElementById('estimasiModal')?.addEventListener('click', function(event) {
        if (event.target === this) {
            closeEstimasiModal();
        }
    });

    document.getElementById('subProgramModal')?.addEventListener('click', function(event) {
        if (event.target === this) {
            closeSubProgramModal();
        }
    });

    document.getElementById('detailAnggaranEditModal')?.addEventListener('click', function(event) {
        if (event.target === this) {
            closeDetailAnggaranEditModal();
        }
    });

    // Setup modal input listeners
    document.addEventListener('DOMContentLoaded', function() {
        const modalJumlahPeriode = document.getElementById('modalJumlahPeriode');
        if (modalJumlahPeriode) {
            modalJumlahPeriode.addEventListener('input', calculateModalTotal);
        }

        // Setup format nominal for all input with class 'nominal-input'
        setupNominalFormatter();
    });

    // Format number with thousand separator (Indonesian format)
    function formatNominal(value) {
        // Remove existing format first
        const cleanValue = value.replace(/\./g, '');
        if (!cleanValue) return '';
        // Add thousand separator
        return cleanValue.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    // Unformat for form submission
    function unformatNominal(value) {
        return value.replace(/\./g, '');
    }

    // Setup nominal formatter for all inputs with class 'nominal-input'
    function setupNominalFormatter() {
        const nominalInputs = document.querySelectorAll('.nominal-input');

        nominalInputs.forEach(input => {
            // Format initial value
            const initialValue = input.getAttribute('value');
            if (initialValue) {
                input.value = formatNominal(initialValue);
            }

            // Format on input
            input.addEventListener('input', function(e) {
                let value = e.target.value.replace(/[^\d]/g, ''); // Only allow digits
                e.target.value = formatNominal(value);
                // Trigger calculateModalTotal if this is the nominal_per_periode input
                if (input.id === 'modalNominalPerPeriode') {
                    calculateModalTotal();
                }
            });

            // Unformat before form submission
            input.closest('form')?.addEventListener('submit', function(e) {
                input.value = unformatNominal(input.value) || 0;
            });
        });
    }
</script>
</x-app-layout>
