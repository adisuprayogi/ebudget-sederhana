<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('pencairan-dana.index') }}" class="p-2 text-secondary-600 hover:text-secondary-900 hover:bg-secondary-100 rounded-lg transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div class="flex-1">
                <div class="flex items-center space-x-3">
                    <h1 class="text-2xl font-bold text-secondary-900">{{ $pencairan->nomor_pencairan }}</h1>
                    @if($pencairan->status === 'pending')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                            Menunggu
                        </span>
                    @elseif($pencairan->status === 'processing')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                            Diproses
                        </span>
                    @elseif($pencairan->status === 'completed')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                            Selesai
                        </span>
                    @elseif($pencairan->status === 'failed')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                            Gagal
                        </span>
                    @elseif($pencairan->status === 'cancelled')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                            Batal
                        </span>
                    @endif
                </div>
                <p class="text-secondary-600 mt-1">Pencairan dana untuk {{ $pencairan->pengajuanDana->judul_pengajuan ?? '-' }}</p>
            </div>
            @if(in_array($pencairan->status, ['pending']) && auth()->user()->hasRole('staff_keuangan'))
                <a href="{{ route('pencairan-dana.edit', $pencairan) }}" class="inline-flex items-center px-4 py-2 bg-amber-500 text-white rounded-xl hover:bg-amber-600 transition-all duration-200 shadow-soft hover:shadow-medium">
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
        @if($pencairan->status === 'pending')
            <div class="mb-6 bg-amber-50 border border-amber-200 rounded-xl p-4 flex items-start">
                <svg class="w-5 h-5 text-amber-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="text-amber-700 text-sm">
                    Pencairan ini masih <strong>menunggu diproses</strong>. Silakan lengkapi data rekening dan proses pencairan.
                </div>
            </div>
        @elseif($pencairan->status === 'processing')
            <div class="mb-6 bg-blue-50 border border-blue-200 rounded-xl p-4 flex items-start">
                <svg class="w-5 h-5 text-blue-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="text-blue-700 text-sm">
                    Pencairan <strong>sedang diproses</strong>. Menunggu konfirmasi dari pihak bank.
                </div>
            </div>
        @elseif($pencairan->status === 'completed')
            <div class="mb-6 bg-green-50 border border-green-200 rounded-xl p-4 flex items-start">
                <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="text-green-700 text-sm">
                    Pencairan <strong>berhasil diselesaikan</strong> pada {{ \Carbon\Carbon::parse($pencairan->tanggal_selesai)->format('d/m/Y') }}.
                </div>
            </div>
        @elseif($pencairan->status === 'failed')
            <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4 flex items-start">
                <svg class="w-5 h-5 text-red-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="text-red-700 text-sm">
                    Pencairan <strong>gagal</strong>. Alasan: {{ $pencairan->alasan_gagal ?? 'Tidak ada informasi' }}
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Informasi Pengajuan -->
                <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                    <div class="px-6 py-4 border-b border-secondary-200">
                        <h2 class="text-lg font-semibold text-secondary-900 flex items-center">
                            <span class="w-8 h-8 bg-primary-100 text-primary-600 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </span>
                            Informasi Pengajuan
                        </h2>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <dt class="text-sm text-secondary-500">Nomor Pengajuan</dt>
                                <dd class="mt-1 font-mono text-sm font-semibold text-primary-600">{{ $pencairan->pengajuanDana->nomor_pengajuan ?? '-' }}</dd>
                            </div>
                            <div class="md:col-span-2">
                                <dt class="text-sm text-secondary-500">Judul Pengajuan</dt>
                                <dd class="mt-1 text-sm text-secondary-900">{{ $pencairan->pengajuanDana->judul_pengajuan ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-secondary-500">Divisi</dt>
                                <dd class="mt-1 text-sm text-secondary-900">{{ $pencairan->pengajuanDana->divisi->nama_divisi ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-secondary-500">Tanggal Pengajuan</dt>
                                <dd class="mt-1 text-sm text-secondary-900">{{ \Carbon\Carbon::parse($pencairan->pengajuanDana->tanggal_pengajuan)->format('d/m/Y') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-secondary-500">Total Pengajuan</dt>
                                <dd class="mt-1 text-sm font-semibold text-secondary-900">{{ formatRupiah($pencairan->pengajuanDana->total_pengajuan ?? 0) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-secondary-500">Metode Pengajuan</dt>
                                <dd class="mt-1 text-sm text-secondary-900">{{ ucfirst($pencairan->pengajuanDana->metode_pencairan ?? '-') }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Detail Pencairan -->
                <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                    <div class="px-6 py-4 border-b border-secondary-200">
                        <h2 class="text-lg font-semibold text-secondary-900 flex items-center">
                            <span class="w-8 h-8 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </span>
                            Detail Pencairan
                        </h2>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm text-secondary-500">Nomor Pencairan</dt>
                                <dd class="mt-1 font-mono text-sm font-semibold text-primary-600">{{ $pencairan->nomor_pencairan }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-secondary-500">Tanggal Pencairan</dt>
                                <dd class="mt-1 text-sm text-secondary-900">{{ \Carbon\Carbon::parse($pencairan->tanggal_pencairan)->format('d/m/Y') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-secondary-500">Jumlah Pencairan</dt>
                                <dd class="mt-1 text-lg font-bold text-primary-600">{{ formatRupiah($pencairan->jumlah_pencairan) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-secondary-500">Metode Pencairan</dt>
                                <dd class="mt-1">
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium
                                        @if($pencairan->metode_pencairan === 'transfer') bg-blue-100 text-blue-700
                                        @elseif($pencairan->metode_pencairan === 'cash') bg-green-100 text-green-700
                                        @else bg-purple-100 text-purple-700 @endif">
                                        {{ ucfirst($pencairan->metode_pencairan) }}
                                    </span>
                                </dd>
                            </div>
                            @if($pencairan->tanggal_selesai)
                            <div>
                                <dt class="text-sm text-secondary-500">Tanggal Selesai</dt>
                                <dd class="mt-1 text-sm text-secondary-900">{{ \Carbon\Carbon::parse($pencairan->tanggal_selesai)->format('d/m/Y') }}</dd>
                            </div>
                            @endif
                            @if($pencairan->verified_by)
                            <div>
                                <dt class="text-sm text-secondary-500">Diverifikasi Oleh</dt>
                                <dd class="mt-1 text-sm text-secondary-900">{{ $pencairan->verifiedBy->name ?? '-' }}</dd>
                            </div>
                            @endif
                        </dl>
                        @if($pencairan->catatan)
                        <div class="mt-4">
                            <dt class="text-sm text-secondary-500">Catatan</dt>
                            <dd class="mt-1 text-sm text-secondary-700">{{ $pencairan->catatan }}</dd>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Informasi Rekening (untuk transfer) -->
                @if($pencairan->metode_pencairan === 'transfer' && ($pencairan->nama_bank || $pencairan->nomor_rekening))
                <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                    <div class="px-6 py-4 border-b border-secondary-200">
                        <h2 class="text-lg font-semibold text-secondary-900 flex items-center">
                            <span class="w-8 h-8 bg-green-100 text-green-600 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                            </span>
                            Informasi Rekening Tujuan
                        </h2>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm text-secondary-500">Nama Bank</dt>
                                <dd class="mt-1 text-sm text-secondary-900">{{ $pencairan->nama_bank ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-secondary-500">Nomor Rekening</dt>
                                <dd class="mt-1 font-mono text-sm font-semibold text-secondary-900">{{ $pencairan->nomor_rekening ?? '-' }}</dd>
                            </div>
                            <div class="md:col-span-2">
                                <dt class="text-sm text-secondary-500">Atas Nama</dt>
                                <dd class="mt-1 text-sm text-secondary-900">{{ $pencairan->atas_nama ?? '-' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
                @endif

                <!-- Bukti Pencairan -->
                @if($pencairan->bukti_pencairan)
                <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                    <div class="px-6 py-4 border-b border-secondary-200">
                        <h2 class="text-lg font-semibold text-secondary-900 flex items-center">
                            <span class="w-8 h-8 bg-amber-100 text-amber-600 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                </svg>
                            </span>
                            Bukti Pencairan
                        </h2>
                    </div>
                    <div class="p-6">
                        <a href="{{ Storage::url($pencairan->bukti_pencairan) }}" target="_blank" class="flex items-center p-4 border border-secondary-200 rounded-xl hover:bg-secondary-50 hover:border-primary-300 transition-colors">
                            <svg class="w-10 h-10 text-secondary-400 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-secondary-900">Lihat Bukti Pencairan</p>
                                <p class="text-xs text-secondary-500">Klik untuk membuka di tab baru</p>
                            </div>
                            <svg class="w-5 h-5 text-secondary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                        </a>
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
                            <p class="text-sm text-secondary-500">Jumlah Pencairan</p>
                            <p class="text-2xl font-bold text-primary-600">{{ formatRupiah($pencairan->jumlah_pencairan) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-secondary-500">Status</p>
                            <p class="text-sm font-medium text-secondary-900">{{ ucfirst(str_replace('_', ' ', $pencairan->status)) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-secondary-500">Metode</p>
                            <p class="text-sm font-medium text-secondary-900">{{ ucfirst($pencairan->metode_pencairan) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Actions Card -->
                @if(auth()->user()->hasRole('staff_keuangan'))
                <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                    <div class="px-6 py-4 border-b border-secondary-200">
                        <h3 class="text-lg font-semibold text-secondary-900">Aksi</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        @if($pencairan->status === 'pending' || $pencairan->status === 'processing')
                        <form method="POST" action="{{ route('pencairan-dana.process', $pencairan) }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center justify-center px-4 py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-all duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Proses Pencairan
                            </button>
                        </form>
                        @endif

                        @if($pencairan->status === 'processing' && !$pencairan->verified_at)
                        <form method="POST" action="{{ route('pencairan-dana.verify', $pencairan) }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center justify-center px-4 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-all duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                Verifikasi Pencairan
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
                @endif

                <!-- Created By -->
                <div class="bg-white rounded-2xl shadow-soft p-6">
                    <p class="text-sm text-secondary-500">Dibuat oleh</p>
                    <div class="flex items-center mt-2">
                        <div class="w-10 h-10 bg-gradient-to-br from-primary-400 to-primary-600 rounded-full flex items-center justify-center">
                            <span class="text-white font-semibold">{{ strtoupper(substr($pencairan->createdBy->name ?? 'A', 0, 1)) }}</span>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-secondary-900">{{ $pencairan->createdBy->name ?? '-' }}</p>
                            <p class="text-xs text-secondary-500">{{ \Carbon\Carbon::parse($pencairan->created_at)->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
