<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black gradient-text">Dashboard Staff Divisi</h1>
                <p class="text-secondary-600 mt-1">Kelola pengajuan dana dan LPJ untuk divisi {{ $data['divisi']->nama_divisi }}</p>
            </div>
            <div class="flex items-center space-x-4">
                <div class="hidden sm:flex items-center space-x-2 bg-white/60 backdrop-blur-sm px-4 py-2 rounded-xl border border-secondary-200/50">
                    <svg class="w-4 h-4 text-secondary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="text-sm font-semibold text-secondary-900">{{ now()->locale('id')->isoFormat('DD MMMM YYYY') }}</span>
                </div>
            </div>
        </div>
    </x-slot>

    @if($data['activePeriode'])
    <!-- Periode Info Banner -->
    <div class="bg-gradient-to-r from-primary-500 to-primary-600 rounded-2xl shadow-soft p-6 mb-8 text-white">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-sm text-primary-100 mb-1">Periode Anggaran Aktif</div>
                <div class="text-2xl font-bold">{{ $data['activePeriode']->nama_periode }}</div>
                <div class="text-primary-100 mt-1">
                    {{ $data['activePeriode']->tanggal_mulai_perencanaan_anggaran->translatedFormat('d F Y') }} - {{ $data['activePeriode']->tanggal_selesai_perencanaan_anggaran->translatedFormat('d F Y') }}
                </div>
            </div>
            <div class="text-right">
                <div class="text-sm text-primary-100 mb-1">Divisi</div>
                <div class="text-2xl font-bold">{{ $data['divisi']->nama_divisi }}</div>
            </div>
        </div>
    </div>
    @endif

    <!-- Welcome Section -->
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-soft p-8 mb-8 text-white">
        <div class="flex items-center space-x-4">
            <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold">Selamat datang, {{ auth()->user()->full_name }}!</h2>
                <p class="text-blue-100 mt-1">Berikut adalah ringkasan pengajuan dana Anda di divisi {{ $data['divisi']->nama_divisi }}</p>
            </div>
        </div>
    </div>

    <!-- Pagu Overview -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 lg:gap-6 mb-8">
        <div class="bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl p-6 text-white shadow-soft">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total Pagu Divisi</p>
                    <p class="text-2xl font-bold mt-1">{{ formatRupiah($data['totalPagu']) }}</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-amber-400 to-amber-600 rounded-2xl p-6 text-white shadow-soft">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-amber-100 text-sm font-medium">Terpakai</p>
                    <p class="text-2xl font-bold mt-1">{{ formatRupiah($data['terpakai']) }}</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl p-6 text-white shadow-soft">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Sisa Pagu</p>
                    <p class="text-2xl font-bold mt-1">{{ formatRupiah($data['sisaPagu']) }}</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Pengajuan Statistics -->
    <div class="grid grid-cols-1 sm:grid-cols-5 gap-4 lg:gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-soft p-6 border-l-4 border-blue-500">
            <p class="text-secondary-500 text-sm font-medium">Total Pengajuan</p>
            <p class="text-3xl font-bold text-blue-600 mt-1">{{ $data['pengajuanCount'] }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-soft p-6 border-l-4 border-gray-500">
            <p class="text-secondary-500 text-sm font-medium">Draft</p>
            <p class="text-3xl font-bold text-gray-600 mt-1">{{ $data['pengajuanDraft'] }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-soft p-6 border-l-4 border-amber-500">
            <p class="text-secondary-500 text-sm font-medium">Menunggu</p>
            <p class="text-3xl font-bold text-amber-600 mt-1">{{ $data['pengajuanMenunggu'] }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-soft p-6 border-l-4 border-blue-500">
            <p class="text-secondary-500 text-sm font-medium">Disetujui</p>
            <p class="text-3xl font-bold text-blue-600 mt-1">{{ $data['pengajuanDisetujui'] }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-soft p-6 border-l-4 border-red-500">
            <p class="text-secondary-500 text-sm font-medium">Ditolak</p>
            <p class="text-3xl font-bold text-red-600 mt-1">{{ $data['pengajuanDitolak'] }}</p>
        </div>
    </div>

    <!-- Notifications -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 lg:gap-6 mb-8">
        <!-- Pencairan Need LPJ -->
        <div class="bg-white rounded-2xl shadow-soft p-6 border-l-4 border-orange-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-secondary-500 text-sm font-medium">Perlu LPJ</p>
                    <p class="text-2xl font-bold text-orange-600 mt-1">{{ $data['pencairanNeedLpj']->count() }}</p>
                    <p class="text-xs text-gray-500 mt-1">Pencairan yang belum LPJ</p>
                </div>
                <div class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- LPJ Menunggu Verifikasi -->
        <div class="bg-white rounded-2xl shadow-soft p-6 border-l-4 border-amber-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-secondary-500 text-sm font-medium">LPJ Verifikasi</p>
                    <p class="text-2xl font-bold text-amber-600 mt-1">{{ $data['lpjMenungguVerifikasi'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">LPJ menunggu verifikasi</p>
                </div>
                <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- LPJ Revisi -->
        <div class="bg-white rounded-2xl shadow-soft p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-secondary-500 text-sm font-medium">LPJ Revisi</p>
                    <p class="text-2xl font-bold text-blue-600 mt-1">{{ $data['lpjRevisi'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">LPJ perlu revisi</p>
                </div>
                <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Pencairan Need LPJ Alert -->
    @if($data['pencairanNeedLpj']->count() > 0)
    <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-2xl shadow-soft mb-8 overflow-hidden">
        <div class="px-6 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-1.333-1.964-.833-2.694 0L3.34 16c-.77.833.192-2.694 1.732-2.5L12.998 3c.77-.833 1.964-.833 2.694 0l6.938 6.938c.77.833 1.964.833 2.694 0l1.732-2.5c.77-.833.192-2.694-1.732-2.5z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-white">Perlu Laporan Pertanggungjawaban</h3>
                    <p class="text-orange-100 text-sm">{{ $data['pencairanNeedLpj']->count() }} pencairan belum memiliki LPJ</p>
                </div>
            </div>
            <a href="{{ route('lpj.create') }}" class="bg-white text-orange-600 px-4 py-2 rounded-xl font-semibold hover:bg-orange-50 transition-colors">
                Buat LPJ →
            </a>
        </div>
        <div class="bg-white p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($data['pencairanNeedLpj']->take(4) as $pencairan)
                    <a href="{{ route('lpj.create', ['pencairan_dana_id' => $pencairan->id]) }}" class="flex justify-between items-center p-4 bg-orange-50 rounded-xl hover:bg-orange-100 transition-colors">
                        <div>
                            <p class="font-semibold text-gray-900">{{ $pencairan->nomor_pencairan }}</p>
                            <p class="text-sm text-gray-500">{{ $pencairan->pengajuanDana->judul_pengajuan }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">{{ $pencairan->tanggal_pencairan->format('d/m/Y') }}</p>
                            <p class="text-sm text-gray-500">{{ formatRupiah($pencairan->jumlah_pencairan) }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Quick Action -->
    <div class="mb-8">
        <a href="{{ route('pengajuan-dana.create') }}" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-bold rounded-2xl hover:from-blue-600 hover:to-blue-700 transition-all shadow-soft hover:shadow-medium">
            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Buat Pengajuan Dana Baru
        </a>
    </div>

    <!-- My Pengajuan -->
    <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-bold text-secondary-900">Pengajuan Saya</h3>
            <a href="{{ route('pengajuan-dana.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Lihat Semua →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Nomor</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Judul</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($data['myPengajuan'] as $pengajuan)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-700">{{ $pengajuan->tanggal_pengajuan ? $pengajuan->tanggal_pengajuan->format('d/m/Y') : '-' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-medium text-gray-900">{{ $pengajuan->nomor_pengajuan ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-medium text-gray-900">{{ $pengajuan->judul_pengajuan }}</p>
                                @if($pengajuan->subProgram)
                                    <p class="text-xs text-gray-500">{{ $pengajuan->subProgram->nama_sub_program }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="font-bold text-gray-900">{{ formatRupiah($pengajuan->total_pengajuan) }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold
                                    @if($pengajuan->status == 'draft') bg-gray-100 text-gray-800
                                    @elseif($pengajuan->status == 'menunggu_approval') bg-amber-100 text-amber-800
                                    @elseif($pengajuan->status == 'disetujui') bg-green-100 text-green-800
                                    @elseif($pengajuan->status == 'ditolak') bg-red-100 text-red-800
                                    @elseif($pengajuan->status == 'revisi') bg-blue-100 text-blue-800
                                    @elseif($pengajuan->status == 'dicairkan') bg-purple-100 text-purple-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $pengajuan->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('pengajuan-dana.show', $pengajuan) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Detail</a>
                                    @if(in_array($pengajuan->status, ['draft', 'revisi']))
                                        <a href="{{ route('pengajuan-dana.edit', $pengajuan) }}" class="text-amber-600 hover:text-amber-800 text-sm font-medium">Edit</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if($data['myPengajuan']->count() == 0)
                <div class="text-center py-12 text-gray-500">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 3h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <p class="text-lg font-medium">Belum ada pengajuan dana</p>
                    <p class="text-sm mt-1">Mulai dengan membuat pengajuan dana pertama Anda</p>
                    <a href="{{ route('pengajuan-dana.create') }}" class="mt-4 inline-flex items-center px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                        Buat Pengajuan →
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Help Section -->
    <div class="mt-8 bg-gradient-to-r from-blue-50 to-blue-50 rounded-2xl p-6 border border-blue-200">
        <div class="flex items-start">
            <div class="flex-shrink-0 bg-blue-100 rounded-xl p-3">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="ml-4">
                <h4 class="text-lg font-bold text-gray-900 mb-2">Butuh bantuan?</h4>
                <p class="text-gray-600">Hubungi kepala divisi Anda atau bagian keuangan untuk informasi lebih lanjut mengenai proses pengajuan dana dan pembuatan LPJ.</p>
            </div>
        </div>
    </div>
</x-app-layout>
