<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('lpj.index') }}" class="p-2 text-secondary-600 hover:text-secondary-900 hover:bg-secondary-100 rounded-lg transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div class="flex-1">
                <div class="flex items-center space-x-3">
                    <h1 class="text-2xl font-bold text-secondary-900">{{ $lpj->nomor_lpj }}</h1>
                    @if($lpj->status === 'draft')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                            Draft
                        </span>
                    @elseif($lpj->status === 'submitted')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                            Menunggu Verifikasi
                        </span>
                    @elseif($lpj->status === 'verified')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                            Terverifikasi
                        </span>
                    @elseif($lpj->status === 'approved')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                            Disetujui
                        </span>
                    @elseif($lpj->status === 'rejected')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                            Ditolak
                        </span>
                    @endif
                </div>
                <p class="text-secondary-600 mt-1">{{ $lpj->judul_lpj }}</p>
            </div>
            @if(in_array($lpj->status, ['draft', 'rejected']) && auth()->user()->can('update', $lpj))
                <a href="{{ route('lpj.edit', $lpj) }}" class="inline-flex items-center px-4 py-2 bg-amber-500 text-white rounded-xl hover:bg-amber-600 transition-all duration-200 shadow-soft hover:shadow-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-8">
        <!-- Alert Messages -->
        @if($lpj->status === 'draft')
            <div class="mb-6 bg-slate-50 border border-slate-200 rounded-xl p-4 flex items-start">
                <svg class="w-5 h-5 text-slate-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="text-slate-700 text-sm">
                    LPJ ini masih dalam status <strong>draft</strong>. Silakan lengkapi data dan submit untuk meminta verifikasi.
                </div>
            </div>
        @elseif($lpj->status === 'submitted')
            <div class="mb-6 bg-amber-50 border border-amber-200 rounded-xl p-4 flex items-start">
                <svg class="w-5 h-5 text-amber-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="text-amber-700 text-sm">
                    LPJ ini <strong>menunggu verifikasi</strong> dari staff keuangan.
                </div>
            </div>
        @elseif($lpj->status === 'verified')
            <div class="mb-6 bg-blue-50 border border-blue-200 rounded-xl p-4 flex items-start">
                <svg class="w-5 h-5 text-blue-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="text-blue-700 text-sm">
                    LPJ ini telah <strong>terverifikasi</strong> dan menunggu persetujuan direktur utama.
                </div>
            </div>
        @elseif($lpj->status === 'approved')
            <div class="mb-6 bg-green-50 border border-green-200 rounded-xl p-4 flex items-start">
                <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="text-green-700 text-sm">
                    LPJ ini telah <strong>disetujui</strong>. Sisa dana akan dikembalikan ke kas divisi.
                </div>
            </div>
        @elseif($lpj->status === 'rejected')
            <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4 flex items-start">
                <svg class="w-5 h-5 text-red-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="text-red-700 text-sm">
                    LPJ ini <strong>ditolak</strong>. Alasan: {{ $lpj->catatan_penolakan ?? 'Tidak ada' }}
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Informasi Pencairan -->
                <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                    <div class="px-6 py-4 border-b border-secondary-200">
                        <h2 class="text-lg font-semibold text-secondary-900 flex items-center">
                            <span class="w-8 h-8 bg-primary-100 text-primary-600 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </span>
                            Informasi Pencairan
                        </h2>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm text-secondary-500">Nomor Pencairan</dt>
                                <dd class="mt-1 font-mono text-sm font-semibold text-primary-600">{{ $lpj->pencairanDana->nomor_pencairan }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-secondary-500">Judul Pengajuan</dt>
                                <dd class="mt-1 text-sm text-secondary-900">{{ $lpj->pencairanDana->pengajuanDana->judul_pengajuan ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-secondary-500">Jumlah Pencairan</dt>
                                <dd class="mt-1 text-sm font-semibold text-secondary-900">{{ formatRupiah($lpj->pencairanDana->jumlah_pencairan) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-secondary-500">Tanggal Pencairan</dt>
                                <dd class="mt-1 text-sm text-secondary-900">{{ \Carbon\Carbon::parse($lpj->pencairanDana->tanggal_pencairan)->format('d/m/Y') }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Informasi LPJ -->
                <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                    <div class="px-6 py-4 border-b border-secondary-200">
                        <h2 class="text-lg font-semibold text-secondary-900 flex items-center">
                            <span class="w-8 h-8 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </span>
                            Informasi LPJ
                        </h2>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm text-secondary-500">Nomor LPJ</dt>
                                <dd class="mt-1 font-mono text-sm font-semibold text-primary-600">{{ $lpj->nomor_lpj }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-secondary-500">Tanggal LPJ</dt>
                                <dd class="mt-1 text-sm text-secondary-900">{{ \Carbon\Carbon::parse($lpj->tanggal_lpj)->format('d/m/Y') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-secondary-500">Periode Anggaran</dt>
                                <dd class="mt-1 text-sm text-secondary-900">{{ $lpj->periodeAnggaran->nama_periode ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-secondary-500">Jenis Penggunaan</dt>
                                <dd class="mt-1">
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium
                                        @if($lpj->jenis_penggunaan === 'penuh') bg-green-100 text-green-700
                                        @else bg-amber-100 text-amber-700 @endif">
                                        {{ ucfirst($lpj->jenis_penggunaan) }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm text-secondary-500">Tanggal Mulai Pelaksanaan</dt>
                                <dd class="mt-1 text-sm text-secondary-900">{{ \Carbon\Carbon::parse($lpj->tanggal_mulai_pelaksanaan)->format('d/m/Y') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-secondary-500">Tanggal Selesai Pelaksanaan</dt>
                                <dd class="mt-1 text-sm text-secondary-900">{{ \Carbon\Carbon::parse($lpj->tanggal_selesai_pelaksanaan)->format('d/m/Y') }}</dd>
                            </div>
                        </dl>
                        @if($lpj->deskripsi)
                        <div class="mt-4">
                            <dt class="text-sm text-secondary-500">Deskripsi Pelaksanaan</dt>
                            <dd class="mt-1 text-sm text-secondary-700 whitespace-pre-line">{{ $lpj->deskripsi }}</dd>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Rincian Penggunaan Dana -->
                <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                    <div class="px-6 py-4 border-b border-secondary-200">
                        <h2 class="text-lg font-semibold text-secondary-900 flex items-center">
                            <span class="w-8 h-8 bg-green-100 text-green-600 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </span>
                            Rincian Penggunaan Dana
                        </h2>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="p-4 bg-secondary-50 rounded-xl">
                                <dt class="text-sm text-secondary-500">Jumlah Pencairan</dt>
                                <dd class="mt-2 text-lg font-bold text-secondary-900">{{ formatRupiah($lpj->pencairanDana->jumlah_pencairan) }}</dd>
                            </div>
                            <div class="p-4 bg-primary-50 rounded-xl">
                                <dt class="text-sm text-primary-600">Jumlah Digunakan</dt>
                                <dd class="mt-2 text-lg font-bold text-primary-600">{{ formatRupiah($lpj->jumlah_digunakan) }}</dd>
                            </div>
                            <div class="p-4 @if($lpj->sisa_dana > 0) bg-green-50 @else bg-red-50 @endif rounded-xl">
                                <dt class="text-sm @if($lpj->sisa_dana > 0) text-green-600 @else text-red-600 @endif">Sisa Dana</dt>
                                <dd class="mt-2 text-lg font-bold @if($lpj->sisa_dana > 0) text-green-600 @else text-red-600 @endif">{{ formatRupiah($lpj->sisa_dana) }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Lampiran -->
                @if($lpj->lampiranLpj && $lpj->lampiranLpj->count() > 0)
                <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                    <div class="px-6 py-4 border-b border-secondary-200">
                        <h2 class="text-lg font-semibold text-secondary-900 flex items-center">
                            <span class="w-8 h-8 bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                </svg>
                            </span>
                            Lampiran Dokumen
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($lpj->lampiranLpj as $lampiran)
                            <a href="{{ Storage::url($lampiran->path_dokumen) }}" target="_blank" class="flex items-center p-4 border border-secondary-200 rounded-xl hover:bg-secondary-50 hover:border-primary-300 transition-colors">
                                <svg class="w-8 h-8 text-secondary-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-secondary-900 truncate">{{ $lampiran->nama_dokumen }}</p>
                                    <p class="text-xs text-secondary-500">{{ $lampiran->jenis_dokumen }}</p>
                                </div>
                                <svg class="w-5 h-5 text-secondary-400 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Riwayat Approval -->
                @if($lpj->riwayatApproval && $lpj->riwayatApproval->count() > 0)
                <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                    <div class="px-6 py-4 border-b border-secondary-200">
                        <h2 class="text-lg font-semibold text-secondary-900 flex items-center">
                            <span class="w-8 h-8 bg-amber-100 text-amber-600 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </span>
                            Riwayat Verifikasi
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($lpj->riwayatApproval as $riwayat)
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    @if($riwayat->status === 'approved' || $riwayat->status === 'verified')
                                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                    @elseif($riwayat->status === 'rejected')
                                        <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </div>
                                    @else
                                        <div class="w-8 h-8 bg-amber-100 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4 flex-1">
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-medium text-secondary-900">{{ $riwayat->user->name ?? 'System' }}</p>
                                        <p class="text-xs text-secondary-500">{{ \Carbon\Carbon::parse($riwayat->created_at)->format('d/m/Y H:i') }}</p>
                                    </div>
                                    <p class="text-xs text-secondary-500">{{ $riwayat->level_approval }}</p>
                                    @if($riwayat->catatan)
                                    <p class="text-sm text-secondary-700 mt-1">{{ $riwayat->catatan }}</p>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Summary Card -->
                <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                    <div class="px-6 py-4 border-b border-secondary-200">
                        <h3 class="text-lg font-semibold text-secondary-900">Ringkasan</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <p class="text-sm text-secondary-500">Jumlah Digunakan</p>
                            <p class="text-2xl font-bold text-primary-600">{{ formatRupiah($lpj->jumlah_digunakan) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-secondary-500">Sisa Dana</p>
                            <p class="text-xl font-bold @if($lpj->sisa_dana > 0) text-green-600 @else text-red-600 @endif">{{ formatRupiah($lpj->sisa_dana) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-secondary-500">Status</p>
                            <p class="text-sm font-medium text-secondary-900">{{ ucfirst(str_replace('_', ' ', $lpj->status)) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Actions Card -->
                <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                    <div class="px-6 py-4 border-b border-secondary-200">
                        <h3 class="text-lg font-semibold text-secondary-900">Aksi</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        @if($lpj->status === 'draft' && auth()->user()->can('submit', $lpj))
                        <form method="POST" action="{{ route('lpj.submit', $lpj) }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center justify-center px-4 py-3 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-all duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                </svg>
                                Submit LPJ
                            </button>
                        </form>
                        @endif

                        @if(auth()->user()->hasRole('staff_keuangan') && $lpj->status === 'submitted')
                        <form method="POST" action="{{ route('lpj.verify', $lpj) }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center justify-center px-4 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-all duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                Verifikasi LPJ
                            </button>
                        </form>
                        @endif

                        @if(auth()->user()->hasRole('direktur_utama') && $lpj->status === 'verified')
                        <form method="POST" action="{{ route('lpj.approve', $lpj) }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center justify-center px-4 py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-all duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Setujui LPJ
                            </button>
                        </form>
                        @endif

                        <button onclick="window.print()" class="w-full flex items-center justify-center px-4 py-3 border border-secondary-200 text-secondary-700 rounded-xl hover:bg-secondary-50 transition-all duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            Cetak
                        </button>
                    </div>
                </div>

                <!-- Created By -->
                <div class="bg-white rounded-2xl shadow-soft p-6">
                    <p class="text-sm text-secondary-500">Dibuat oleh</p>
                    <div class="flex items-center mt-2">
                        <div class="w-10 h-10 bg-gradient-to-br from-primary-400 to-primary-600 rounded-full flex items-center justify-center">
                            <span class="text-white font-semibold">{{ strtoupper(substr($lpj->createdBy->name ?? 'A', 0, 1)) }}</span>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-secondary-900">{{ $lpj->createdBy->name ?? '-' }}</p>
                            <p class="text-xs text-secondary-500">{{ \Carbon\Carbon::parse($lpj->created_at)->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
