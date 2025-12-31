<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black gradient-text">Dashboard Kepala Divisi</h1>
                <p class="text-secondary-600 mt-1">Kelola anggaran dan pengajuan dana divisi {{ $data['divisi']->nama_divisi }}</p>
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

    <!-- Pagu Overview -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 lg:gap-6 mb-8">
        <div class="bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl p-6 text-white shadow-soft">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total Pagu</p>
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

        <div class="bg-gradient-to-br from-green-400 to-green-600 rounded-2xl p-6 text-white shadow-soft">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Sisa Pagu</p>
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

    <!-- Pagu Usage Progress -->
    <div class="bg-white rounded-2xl shadow-soft p-6 mb-8">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-secondary-900">Penggunaan Pagu Anggaran</h3>
            <span class="text-2xl font-bold {{ ($data['terpakai'] / $data['totalPagu']) * 100 > 90 ? 'text-red-600' : (($data['terpakai'] / $data['totalPagu']) * 100 > 70 ? 'text-amber-600' : 'text-green-600') }}">
                {{ round(($data['terpakai'] / $data['totalPagu']) * 100, 1) }}%
            </span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-4 mb-2">
            <div class="h-4 rounded-full bg-gradient-to-r {{ ($data['terpakai'] / $data['totalPagu']) * 100 > 90 ? 'from-red-500 to-red-600' : (($data['terpakai'] / $data['totalPagu']) * 100 > 70 ? 'from-amber-500 to-amber-600' : 'from-green-500 to-green-600') }}" style="width: {{ min(($data['terpakai'] / $data['totalPagu']) * 100, 100) }}%"></div>
        </div>
        <div class="flex justify-between text-sm text-gray-600">
            <span>{{ formatRupiah($data['terpakai']) }} terpakai</span>
            <span>{{ formatRupiah($data['sisaPagu']) }} tersedia</span>
        </div>
    </div>

    <!-- Pengajuan Statistics -->
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 lg:gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-soft p-6 border-l-4 border-amber-500">
            <p class="text-secondary-500 text-sm font-medium">Menunggu Approval</p>
            <p class="text-3xl font-bold text-amber-600 mt-1">{{ $data['pengajuanMenunggu'] }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-soft p-6 border-l-4 border-green-500">
            <p class="text-secondary-500 text-sm font-medium">Disetujui</p>
            <p class="text-3xl font-bold text-green-600 mt-1">{{ $data['pengajuanDisetujui'] }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-soft p-6 border-l-4 border-red-500">
            <p class="text-secondary-500 text-sm font-medium">Ditolak</p>
            <p class="text-3xl font-bold text-red-600 mt-1">{{ $data['pengajuanDitolak'] }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-soft p-6 border-l-4 border-blue-500">
            <p class="text-secondary-500 text-sm font-medium">Total Pengajuan</p>
            <p class="text-3xl font-bold text-blue-600 mt-1">{{ $data['pengajuanTotal'] }}</p>
        </div>
    </div>

    <!-- Notifications -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 lg:gap-6 mb-8">
        <!-- Pencairan Menunggu -->
        <div class="bg-white rounded-2xl shadow-soft p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-secondary-500 text-sm font-medium">Pencairan Menunggu</p>
                    <p class="text-2xl font-bold text-purple-600 mt-1">{{ $data['pencairanMenunggu'] }}</p>
                </div>
                <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- LPJ Belum Dibuat -->
        <div class="bg-white rounded-2xl shadow-soft p-6 border-l-4 border-orange-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-secondary-500 text-sm font-medium">LPJ Belum Dibuat</p>
                    <p class="text-2xl font-bold text-orange-600 mt-1">{{ $data['lpjBelumDibuat'] }}</p>
                </div>
                <div class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- LPJ Need Refund -->
        <div class="bg-white rounded-2xl shadow-soft p-6 border-l-4 border-teal-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-secondary-500 text-sm font-medium">Perlu Refund</p>
                    <p class="text-2xl font-bold text-teal-600 mt-1">{{ $data['lpjNeedRefund'] }}</p>
                </div>
                <div class="w-10 h-10 bg-teal-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <a href="{{ route('program-kerja.divisi-show', $data['divisi']) }}" class="flex items-center p-6 bg-white rounded-2xl shadow-soft hover:shadow-medium transition-all group">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-4 group-hover:scale-110 transition-transform">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
            </div>
            <div class="ml-5">
                <h3 class="text-lg font-bold text-gray-900 group-hover:text-blue-600">Program Kerja</h3>
                <p class="text-sm text-gray-500 mt-1">Kelola program kerja divisi</p>
            </div>
            <svg class="w-5 h-5 text-gray-400 ml-auto group-hover:text-blue-600 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>

        <a href="{{ route('pengajuan-dana.create') }}" class="flex items-center p-6 bg-white rounded-2xl shadow-soft hover:shadow-medium transition-all group">
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-4 group-hover:scale-110 transition-transform">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
            </div>
            <div class="ml-5">
                <h3 class="text-lg font-bold text-gray-900 group-hover:text-green-600">Buat Pengajuan</h3>
                <p class="text-sm text-gray-500 mt-1">Ajukan dana baru</p>
            </div>
            <svg class="w-5 h-5 text-gray-400 ml-auto group-hover:text-green-600 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>

        <a href="{{ route('pengajuan-dana.index') }}" class="flex items-center p-6 bg-white rounded-2xl shadow-soft hover:shadow-medium transition-all group">
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-4 group-hover:scale-110 transition-transform">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <div class="ml-5">
                <h3 class="text-lg font-bold text-gray-900 group-hover:text-purple-600">Lihat Semua Pengajuan</h3>
                <p class="text-sm text-gray-500 mt-1">Daftar pengajuan divisi</p>
            </div>
            <svg class="w-5 h-5 text-gray-400 ml-auto group-hover:text-purple-600 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>
    </div>

    <!-- Recent Pengajuan -->
    <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-bold text-secondary-900">Pengajuan Divisi Terbaru</h3>
            <a href="{{ route('pengajuan-dana.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Lihat Semua →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Nomor</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Judul</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Pengaju</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($data['pengajuanDivisi'] as $pengajuan)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-700">{{ $pengajuan->tanggal_pengajuan ? $pengajuan->tanggal_pengajuan->format('d/m/Y') : '-' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-medium text-gray-900">{{ $pengajuan->nomor_pengajuan ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-medium text-gray-900">{{ $pengajuan->judul_pengajuan }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-700">{{ $pengajuan->user->full_name ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="font-bold text-gray-900">{{ formatRupiah($pengajuan->total_pengajuan) }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold
                                    @if($pengajuan->status == 'menunggu_approval') bg-amber-100 text-amber-800
                                    @elseif($pengajuan->status == 'disetujui') bg-green-100 text-green-800
                                    @elseif($pengajuan->status == 'ditolak') bg-red-100 text-red-800
                                    @elseif($pengajuan->status == 'revisi') bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $pengajuan->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('pengajuan-dana.show', $pengajuan) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Detail</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if($data['pengajuanDivisi']->count() == 0)
                <div class="text-center py-8 text-gray-500">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 3h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                    </svg>
                    <p>Belum ada pengajuan dana untuk divisi ini.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- My Pengajuan -->
    <div class="mt-8 bg-white rounded-2xl shadow-soft overflow-hidden">
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
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="font-bold text-gray-900">{{ formatRupiah($pengajuan->total_pengajuan) }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold
                                    @if($pengajuan->status == 'menunggu_approval') bg-amber-100 text-amber-800
                                    @elseif($pengajuan->status == 'disetujui') bg-green-100 text-green-800
                                    @elseif($pengajuan->status == 'ditolak') bg-red-100 text-red-800
                                    @elseif($pengajuan->status == 'revisi') bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $pengajuan->status)) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if($data['myPengajuan']->count() == 0)
                <div class="text-center py-8 text-gray-500">
                    <p>Belum ada pengajuan dana yang dibuat.</p>
                    <a href="{{ route('pengajuan-dana.create') }}" class="mt-2 text-blue-600 hover:text-blue-800 font-medium">
                        Buat pengajuan pertama Anda →
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
