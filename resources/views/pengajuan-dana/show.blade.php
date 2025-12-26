<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('pengajuan-dana.index') }}" class="p-2 text-secondary-600 hover:text-secondary-900 hover:bg-secondary-100 rounded-lg transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div class="flex-1">
                <div class="flex items-center space-x-3">
                    <h1 class="text-2xl font-bold text-secondary-900">{{ $pengajuan->nomor_pengajuan }}</h1>
                    @if($pengajuan->status === 'draft')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                            Draft
                        </span>
                    @elseif($pengajuan->status === 'menunggu_approval')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                            Menunggu Approval
                        </span>
                    @elseif($pengajuan->status === 'approved')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                            Disetujui
                        </span>
                    @elseif($pengajuan->status === 'rejected')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                            Ditolak
                        </span>
                    @elseif($pengajuan->status === 'dicairkan')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                            Dicairkan
                        </span>
                    @endif
                </div>
                <p class="text-secondary-600 mt-1">{{ $pengajuan->judul_pengajuan }}</p>
            </div>
            @if(in_array($pengajuan->status, ['draft', 'rejected']) && auth()->user()->can('update', $pengajuan))
                <a href="{{ route('pengajuan-dana.edit', $pengajuan) }}" class="inline-flex items-center px-4 py-2 bg-amber-500 text-white rounded-xl hover:bg-amber-600 transition-all duration-200 shadow-soft hover:shadow-medium">
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
        @if($pengajuan->status === 'draft')
            <div class="mb-6 bg-amber-50 border border-amber-200 rounded-xl p-4 flex items-start">
                <svg class="w-5 h-5 text-amber-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="text-amber-700 text-sm">
                    Pengajuan ini masih dalam status <strong>draft</strong>. Silakan lengkapi data dan submit untuk meminta approval.
                </div>
            </div>
        @elseif($pengajuan->status === 'rejected')
            <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4 flex items-start">
                <svg class="w-5 h-5 text-red-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="text-red-700 text-sm">
                    Pengajuan ini <strong>ditolak</strong>. Alasan: {{ $pengajuan->catatan_penolakan ?? 'Tidak ada' }}
                </div>
            </div>
        @elseif($pengajuan->status === 'approved')
            <div class="mb-6 bg-green-50 border border-green-200 rounded-xl p-4 flex items-start">
                <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="text-green-700 text-sm">
                    Pengajuan ini telah <strong>disetujui</strong>. Menunggu proses pencairan dana.
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Informasi Dasar -->
                <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                    <div class="px-6 py-4 border-b border-secondary-200">
                        <h2 class="text-lg font-semibold text-secondary-900 flex items-center">
                            <span class="w-8 h-8 bg-primary-100 text-primary-600 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </span>
                            Informasi Dasar
                        </h2>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm text-secondary-500">Nomor Pengajuan</dt>
                                <dd class="mt-1 font-mono text-sm font-semibold text-primary-600">{{ $pengajuan->nomor_pengajuan }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-secondary-500">Tanggal Pengajuan</dt>
                                <dd class="mt-1 text-sm text-secondary-900">{{ \Carbon\Carbon::parse($pengajuan->tanggal_pengajuan)->format('d/m/Y') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-secondary-500">Divisi</dt>
                                <dd class="mt-1 text-sm text-secondary-900">{{ $pengajuan->divisi->nama_divisi ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-secondary-500">Periode Anggaran</dt>
                                <dd class="mt-1 text-sm text-secondary-900">{{ $pengajuan->periodeAnggaran->nama_periode ?? '-' }}</dd>
                            </div>
                            @if($pengajuan->program_kerja)
                            <div class="md:col-span-2">
                                <dt class="text-sm text-secondary-500">Program Kerja</dt>
                                <dd class="mt-1 text-sm text-secondary-900">{{ $pengajuan->programKerja->nama_program }}</dd>
                            </div>
                            @endif
                            @if($pengajuan->kegiatan)
                            <div class="md:col-span-2">
                                <dt class="text-sm text-secondary-500">Kegiatan</dt>
                                <dd class="mt-1 text-sm text-secondary-900">{{ $pengajuan->kegiatan->nama_kegiatan }}</dd>
                            </div>
                            @endif
                        </dl>
                        @if($pengajuan->deskripsi)
                        <div class="mt-4">
                            <dt class="text-sm text-secondary-500">Deskripsi</dt>
                            <dd class="mt-1 text-sm text-secondary-700 whitespace-pre-line">{{ $pengajuan->deskripsi }}</dd>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Detail Pengajuan -->
                <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                    <div class="px-6 py-4 border-b border-secondary-200">
                        <h2 class="text-lg font-semibold text-secondary-900 flex items-center">
                            <span class="w-8 h-8 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </span>
                            Rincian Pengajuan
                        </h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-secondary-50 border-b border-secondary-200">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-secondary-600 uppercase">Uraian</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-secondary-600 uppercase">Volume</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-secondary-600 uppercase">Satuan</th>
                                    <th class="px-6 py-3 text-right text-xs font-semibold text-secondary-600 uppercase">Harga</th>
                                    <th class="px-6 py-3 text-right text-xs font-semibold text-secondary-600 uppercase">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-secondary-100">
                                @foreach($pengajuan->detailPengajuan as $detail)
                                <tr>
                                    <td class="px-6 py-4 text-sm text-secondary-900">{{ $detail->uraian }}</td>
                                    <td class="px-6 py-4 text-sm text-secondary-700">{{ $detail->volume }}</td>
                                    <td class="px-6 py-4 text-sm text-secondary-700">{{ $detail->satuan }}</td>
                                    <td class="px-6 py-4 text-sm text-secondary-700 text-right">{{ formatRupiah($detail->harga_satuan) }}</td>
                                    <td class="px-6 py-4 text-sm font-semibold text-secondary-900 text-right">{{ formatRupiah($detail->jumlah) }}</td>
                                </tr>
                                @endforeach
                                <tr class="bg-secondary-50">
                                    <td colspan="4" class="px-6 py-4 text-right text-sm font-semibold text-secondary-900">Total Pengajuan</td>
                                    <td class="px-6 py-4 text-right text-lg font-bold text-primary-600">{{ formatRupiah($pengajuan->total_pengajuan) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Penerima Manfaat -->
                @if($pengajuan->penerimaManfaat && $pengajuan->penerimaManfaat->count() > 0)
                <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                    <div class="px-6 py-4 border-b border-secondary-200">
                        <h2 class="text-lg font-semibold text-secondary-900 flex items-center">
                            <span class="w-8 h-8 bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </span>
                            Penerima Manfaat
                        </h2>
                    </div>
                    <div class="p-6">
                        <ul class="space-y-2">
                            @foreach($pengajuan->penerimaManfaat as $penerima)
                            <li class="flex items-center text-sm text-secondary-700">
                                <svg class="w-4 h-4 text-primary-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                {{ $penerima->nama_penerima }} @if($penerima->jenis_penerima) <span class="text-secondary-500">({{ $penerima->jenis_penerima }})</span> @endif
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif

                <!-- Lampiran -->
                @if($pengajuan->lampiranPengajuan && $pengajuan->lampiranPengajuan->count() > 0)
                <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                    <div class="px-6 py-4 border-b border-secondary-200">
                        <h2 class="text-lg font-semibold text-secondary-900 flex items-center">
                            <span class="w-8 h-8 bg-amber-100 text-amber-600 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                </svg>
                            </span>
                            Lampiran Dokumen
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($pengajuan->lampiranPengajuan as $lampiran)
                            <a href="{{ Storage::url($lampiran->path_dokumen) }}" target="_blank" class="flex items-center p-4 border border-secondary-200 rounded-xl hover:bg-secondary-50 hover:border-primary-300 transition-colors">
                                <svg class="w-8 h-8 text-secondary-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-secondary-900 truncate">{{ $lampiran->nama_dokumen }}</p>
                                    <p class="text-xs text-secondary-500">{{ $lampiran->jenis_dokumen }}</p>
                                </div>
                                <svg class="w-5 h-5 text-secondary-400 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Riwayat Approval -->
                @if($pengajuan->riwayatApproval && $pengajuan->riwayatApproval->count() > 0)
                <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                    <div class="px-6 py-4 border-b border-secondary-200">
                        <h2 class="text-lg font-semibold text-secondary-900 flex items-center">
                            <span class="w-8 h-8 bg-green-100 text-green-600 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </span>
                            Riwayat Approval
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($pengajuan->riwayatApproval as $riwayat)
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    @if($riwayat->status === 'approved')
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
                            <p class="text-sm text-secondary-500">Total Pengajuan</p>
                            <p class="text-2xl font-bold text-primary-600">{{ formatRupiah($pengajuan->total_pengajuan) }}</p>
                        </div>
                        @if($pengajuan->tanggal_dibutuhkan)
                        <div>
                            <p class="text-sm text-secondary-500">Tanggal Dibutuhkan</p>
                            <p class="text-sm font-medium text-secondary-900">{{ \Carbon\Carbon::parse($pengajuan->tanggal_dibutuhkan)->format('d/m/Y') }}</p>
                        </div>
                        @endif
                        @if($pengajuan->metode_pencairan)
                        <div>
                            <p class="text-sm text-secondary-500">Metode Pencairan</p>
                            <p class="text-sm font-medium text-secondary-900">{{ ucfirst(str_replace('_', ' ', $pengajuan->metode_pencairan)) }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Actions Card -->
                <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                    <div class="px-6 py-4 border-b border-secondary-200">
                        <h3 class="text-lg font-semibold text-secondary-900">Aksi</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        @if($pengajuan->status === 'draft' && auth()->user()->can('submit', $pengajuan))
                        <form method="POST" action="{{ route('pengajuan-dana.submit', $pengajuan) }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center justify-center px-4 py-3 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-all duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                </svg>
                                Submit Pengajuan
                            </button>
                        </form>
                        @endif

                        @if(auth()->user()->hasRole('direktur_utama') && $pengajuan->status === 'menunggu_approval')
                        <button onclick="document.getElementById('approveModal').classList.remove('hidden')" class="w-full flex items-center justify-center px-4 py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-all duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Setujui
                        </button>
                        <button onclick="document.getElementById('rejectModal').classList.remove('hidden')" class="w-full flex items-center justify-center px-4 py-3 border border-red-300 text-red-600 rounded-xl hover:bg-red-50 transition-all duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Tolak
                        </button>
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
                            <span class="text-white font-semibold">{{ strtoupper(substr($pengajuan->createdBy->name ?? 'A', 0, 1)) }}</span>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-secondary-900">{{ $pengajuan->createdBy->name ?? '-' }}</p>
                            <p class="text-xs text-secondary-500">{{ \Carbon\Carbon::parse($pengajuan->created_at)->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Approve Modal -->
    @if(auth()->user()->hasRole('direktur_utama') && $pengajuan->status === 'menunggu_approval')
    <div id="approveModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <form method="POST" action="{{ route('pengajuan-dana.approve', $pengajuan) }}" class="bg-white rounded-2xl shadow-xl max-w-md w-full mx-4">
            @csrf
            <div class="p-6">
                <h3 class="text-lg font-semibold text-secondary-900 mb-4">Setujui Pengajuan Dana</h3>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-secondary-700 mb-2">Catatan (Opsional)</label>
                    <textarea name="catatan" rows="3" class="w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="Tambahkan catatan..."></textarea>
                </div>
                <div class="flex space-x-3">
                    <button type="button" onclick="document.getElementById('approveModal').classList.add('hidden')" class="flex-1 px-4 py-3 border border-secondary-200 text-secondary-700 rounded-xl hover:bg-secondary-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 px-4 py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-colors">
                        Ya, Setujui
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <form method="POST" action="{{ route('pengajuan-dana.reject', $pengajuan) }}" class="bg-white rounded-2xl shadow-xl max-w-md w-full mx-4">
            @csrf
            <div class="p-6">
                <h3 class="text-lg font-semibold text-secondary-900 mb-4">Tolak Pengajuan Dana</h3>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-secondary-700 mb-2">Alasan Penolakan <span class="text-red-500">*</span></label>
                    <textarea name="catatan_penolakan" rows="3" required class="w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="Jelaskan alasan penolakan..."></textarea>
                </div>
                <div class="flex space-x-3">
                    <button type="button" onclick="document.getElementById('rejectModal').classList.add('hidden')" class="flex-1 px-4 py-3 border border-secondary-200 text-secondary-700 rounded-xl hover:bg-secondary-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 px-4 py-3 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors">
                        Ya, Tolak
                    </button>
                </div>
            </div>
        </form>
    </div>
    @endif
</x-app-layout>
