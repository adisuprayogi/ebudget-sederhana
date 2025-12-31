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
                    @if($pencairan->status === 'menunggu')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                            Menunggu Konfirmasi
                        </span>
                    @elseif($pencairan->status === 'menunggu_lpj')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-700">
                            Menunggu LPJ
                        </span>
                    @elseif($pencairan->status === 'selesai')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                            Selesai
                        </span>
                    @elseif($pencairan->status === 'revisi')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                            Perlu Revisi
                        </span>
                    @elseif($pencairan->status === 'cancelled')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                            Batal
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                            {{ ucfirst(str_replace('_', ' ', $pencairan->status)) }}
                        </span>
                    @endif
                </div>
                <p class="text-secondary-600 mt-1">Pencairan dana untuk {{ $pencairan->pengajuanDana->judul_pengajuan ?? '-' }}</p>
            </div>
            @if($permissions['edit'])
                <a href="{{ route('pencairan-dana.edit', $pencairan) }}" class="inline-flex items-center px-4 py-2 bg-amber-500 text-white rounded-xl hover:bg-amber-600 transition-all duration-200 shadow-soft hover:shadow-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </a>
            @endif
            @if($permissions['retry'])
                <a href="{{ route('pencairan-dana.retry', $pencairan) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-all duration-200 shadow-soft hover:shadow-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Buat Ulang
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-8">
        <!-- Alert Messages -->
        @if($pencairan->status === 'menunggu')
            <div class="mb-6 bg-amber-50 border border-amber-200 rounded-xl p-4 flex items-start">
                <svg class="w-5 h-5 text-amber-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="text-amber-700 text-sm">
                    Pencairan ini <strong>menunggu konfirmasi</strong> dari pengaju. Silakan konfirmasi apakah Anda sudah menerima dana.
                </div>
            </div>
        @elseif($pencairan->status === 'menunggu_lpj')
            <div class="mb-6 bg-indigo-50 border border-indigo-200 rounded-xl p-4 flex items-start">
                <svg class="w-5 h-5 text-indigo-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <div class="text-indigo-700 text-sm">
                    Pencairan ini <strong>telah diverifikasi</strong> dan menunggu Laporan Pertanggung Jawaban (LPJ).
                    @if($pencairan->verified_at) - Diverifikasi pada {{ \Carbon\Carbon::parse($pencairan->verified_at)->format('d/m/Y H:i') }}@endif
                </div>
            </div>
        @elseif($pencairan->status === 'selesai')
            <div class="mb-6 bg-green-50 border border-green-200 rounded-xl p-4 flex items-start">
                <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="text-green-700 text-sm">
                    Pencairan <strong>berhasil diselesaikan</strong>.
                    @if($pencairan->verified_at) pada {{ \Carbon\Carbon::parse($pencairan->verified_at)->format('d/m/Y H:i') }}@endif.
                </div>
            </div>
        @elseif($pencairan->status === 'revisi')
            <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4 flex items-start">
                <svg class="w-5 h-5 text-red-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="text-red-700 text-sm">
                    Pencairan ini <strong>ditolak oleh pengaju</strong> dan perlu dicairkan kembali.
                    @if($pencairan->verification_notes) - {{ $pencairan->verification_notes }}@endif
                </div>
            </div>
        @elseif($pencairan->status === 'cancelled')
            <div class="mb-6 bg-slate-50 border border-slate-200 rounded-xl p-4 flex items-start">
                <svg class="w-5 h-5 text-slate-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                </svg>
                <div class="text-slate-700 text-sm">
                    Pencairan ini <strong>dibatalkan</strong>.
                    @if($pencairan->cancellation_reason) - {{ $pencairan->cancellation_reason }}@endif
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
                            @if($pencairan->pengajuanDana->programKerja)
                            <div class="md:col-span-2">
                                <dt class="text-sm text-secondary-500">Program Kerja</dt>
                                <dd class="mt-1 text-sm text-secondary-900">{{ $pencairan->pengajuanDana->programKerja->nama_program ?? '-' }}</dd>
                            </div>
                            @endif
                            @if($pencairan->pengajuanDana->subProgram)
                            <div class="md:col-span-2">
                                <dt class="text-sm text-secondary-500">Sub Program</dt>
                                <dd class="mt-1 text-sm text-secondary-900">{{ $pencairan->pengajuanDana->subProgram->nama_sub_program ?? '-' }}</dd>
                            </div>
                            @endif
                            <div>
                                <dt class="text-sm text-secondary-500">Total Pengajuan</dt>
                                <dd class="mt-1 text-lg font-bold text-primary-600">{{ formatRupiah($pencairan->pengajuanDana->total_pengajuan ?? 0) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-secondary-500">Diajukan Oleh</dt>
                                <dd class="mt-1 text-sm text-secondary-900">{{ $pencairan->pengajuanDana->createdBy->name ?? '-' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Rincian Pengajuan (Non-Honorarium) -->
                @if($pengajuan->jenis_pengajuan !== 'honorarium' && $pengajuan->details && $pengajuan->details->isNotEmpty())
                <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                    <div class="px-6 py-4 border-b border-secondary-200">
                        <h2 class="text-lg font-semibold text-secondary-900 flex items-center">
                            <span class="w-8 h-8 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </span>
                            Rincian Pengajuan
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-secondary-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left font-semibold text-secondary-700">Uraian</th>
                                        <th class="px-4 py-3 text-right font-semibold text-secondary-700">Volume</th>
                                        <th class="px-4 py-3 text-right font-semibold text-secondary-700">Satuan</th>
                                        <th class="px-4 py-3 text-right font-semibold text-secondary-700">Harga Satuan</th>
                                        <th class="px-4 py-3 text-right font-semibold text-secondary-700">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-secondary-200">
                                    @foreach($pengajuan->details as $detail)
                                    <tr>
                                        <td class="px-4 py-3 text-secondary-900">{{ $detail->uraian }}</td>
                                        <td class="px-4 py-3 text-right text-secondary-600">{{ $detail->volume }} {{ $detail->satuan }}</td>
                                        <td class="px-4 py-3 text-right text-secondary-600">{{ $detail->satuan }}</td>
                                        <td class="px-4 py-3 text-right text-secondary-600">{{ formatRupiah($detail->harga_satuan) }}</td>
                                        <td class="px-4 py-3 text-right font-semibold text-secondary-900">{{ formatRupiah($detail->subtotal) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-secondary-50">
                                    <tr>
                                        <td colspan="4" class="px-4 py-3 text-right font-bold text-secondary-700">Total</td>
                                        <td class="px-4 py-3 text-right font-bold text-primary-600">{{ formatRupiah($pengajuan->details->sum('subtotal')) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Daftar Penerima Honorarium -->
                @if($pengajuan->jenis_pengajuan === 'honorarium' && $pencairan->detailPencairans && $pencairan->detailPencairans->isNotEmpty())
                <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                    <div class="px-6 py-4 border-b border-secondary-200">
                        <h2 class="text-lg font-semibold text-secondary-900 flex items-center">
                            <span class="w-8 h-8 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </span>
                            Daftar Penerima Honorarium
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-secondary-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left font-semibold text-secondary-700">Nama Penerima</th>
                                        <th class="px-4 py-3 text-left font-semibold text-secondary-700">Jabatan</th>
                                        <th class="px-4 py-3 text-right font-semibold text-secondary-700">Jumlah Honor</th>
                                        <th class="px-4 py-3 text-left font-semibold text-secondary-700">Nomor Rekening</th>
                                        <th class="px-4 py-3 text-center font-semibold text-secondary-700">Lampiran</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-secondary-200">
                                    @foreach($pencairan->detailPencairans as $detail)
                                    @php
                                        $honorarium = $detail->honorariumDetail;
                                    @endphp
                                    <tr>
                                        <td class="px-4 py-3 font-medium text-secondary-900">{{ $honorarium->penerima_nama ?? '-' }}</td>
                                        <td class="px-4 py-3 text-secondary-600">{{ $honorarium->jabatan ?? '-' }}</td>
                                        <td class="px-4 py-3 text-right font-semibold text-primary-600">{{ formatRupiah($honorarium->jumlah_honor ?? 0) }}</td>
                                        <td class="px-4 py-3 text-secondary-600 font-mono text-xs">{{ $honorarium->nomor_rekening ?? '-' }}</td>
                                        <td class="px-4 py-3 text-center">
                                            @php
                                                $lampiran = $pencairan->honorariumLampirans->firstWhere('honorarium_detail_id', $honorarium->id ?? null);
                                            @endphp
                                            @if($lampiran)
                                                <a href="{{ Storage::url($lampiran->path_file) }}" target="_blank" class="inline-flex items-center text-primary-600 hover:text-primary-800">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                                    </svg>
                                                    Lihat
                                                </a>
                                            @else
                                                <span class="text-secondary-400">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-secondary-50">
                                    <tr>
                                        <td colspan="2" class="px-4 py-3 text-right font-bold text-secondary-700">Total</td>
                                        <td class="px-4 py-3 text-right font-bold text-primary-600">{{ formatRupiah($pencairan->detailPencairans->sum('subtotal')) }}</td>
                                        <td colspan="2"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                @endif

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
                                    @if($pencairan->metode_pencairan === 'transfer')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                            Transfer Bank
                                        </span>
                                    @elseif($pencairan->metode_pencairan === 'cash')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                            Tunai
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-700">
                                            Reimburse
                                        </span>
                                    @endif
                                </dd>
                            </div>
                            @if($pencairan->processed_at)
                            <div>
                                <dt class="text-sm text-secondary-500">Diproses Pada</dt>
                                <dd class="mt-1 text-sm text-secondary-900">{{ \Carbon\Carbon::parse($pencairan->processed_at)->format('d/m/Y H:i') }}</dd>
                            </div>
                            @endif
                            @if($pencairan->processedBy)
                            <div>
                                <dt class="text-sm text-secondary-500">Diproses Oleh</dt>
                                <dd class="mt-1 text-sm text-secondary-900">{{ $pencairan->processedBy->name ?? '-' }}</dd>
                            </div>
                            @endif
                        </dl>
                        @if($pencairan->catatan)
                        <div class="mt-4 pt-4 border-t border-secondary-200">
                            <dt class="text-sm text-secondary-500">Catatan</dt>
                            <dd class="mt-1 text-sm text-secondary-700">{{ $pencairan->catatan }}</dd>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Informasi Rekening (untuk transfer non-honorarium atau rekening sumber untuk honorarium) -->
                @if($pencairan->metode_pencairan === 'transfer')
                <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                    <div class="px-6 py-4 border-b border-secondary-200">
                        <h2 class="text-lg font-semibold text-secondary-900 flex items-center">
                            <span class="w-8 h-8 bg-green-100 text-green-600 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                            </span>
                            Informasi Rekening
                        </h2>
                    </div>
                    <div class="p-6">
                        <!-- Rekening Sumber -->
                        <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                            <h3 class="text-sm font-semibold text-blue-800 mb-3 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" />
                                </svg>
                                Rekening Sumber (Perusahaan)
                            </h3>
                            @if($pencairan->rekeningPerusahaan)
                            <dl class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <dt class="text-xs text-blue-600">Bank</dt>
                                    <dd class="text-sm font-medium text-secondary-900">{{ $pencairan->rekeningPerusahaan->bank->nama_bank ?? '-' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs text-blue-600">Nomor Rekening</dt>
                                    <dd class="text-sm font-medium font-secondary-900">{{ $pencairan->rekeningPerusahaan->nomor_rekening_formatted ?? $pencairan->nomor_rekening_sumber ?? '-' }}</dd>
                                </div>
                                <div class="md:col-span-2">
                                    <dt class="text-xs text-blue-600">Atas Nama</dt>
                                    <dd class="text-sm font-medium text-secondary-900">{{ $pencairan->rekeningPerusahaan->atas_nama ?? '-' }}</dd>
                                </div>
                            </dl>
                            @else
                            <p class="text-sm text-secondary-600">{{ $pencairan->nama_bank_sumber ?? '-' }} - {{ $pencairan->nomor_rekening_sumber ?? '-' }}</p>
                            @endif
                        </div>

                        <!-- Rekening Tujuan (hanya untuk non-honorarium) -->
                        @if($pengajuan->jenis_pengajuan !== 'honorarium')
                        <div class="p-4 bg-green-50 border border-green-200 rounded-xl">
                            <h3 class="text-sm font-semibold text-green-800 mb-3 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6" />
                                </svg>
                                Rekening Tujuan
                            </h3>
                            <dl class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <dt class="text-xs text-green-600">Bank</dt>
                                    <dd class="text-sm font-medium text-secondary-900">{{ $pencairan->nama_bank ?? '-' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs text-green-600">Nomor Rekening</dt>
                                    <dd class="text-sm font-medium font-secondary-900">{{ $pencairan->nomor_rekening ?? '-' }}</dd>
                                </div>
                                <div class="md:col-span-2">
                                    <dt class="text-xs text-green-600">Atas Nama</dt>
                                    <dd class="text-sm font-medium text-secondary-900">{{ $pencairan->atas_nama ?? '-' }}</dd>
                                </div>
                            </dl>
                        </div>
                        @else
                        <div class="p-4 bg-green-50 border border-green-200 rounded-xl">
                            <p class="text-sm text-green-700">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Informasi rekening tujuan untuk masing-masing penerima honorarium dapat dilihat pada tabel Daftar Penerima Honorarium di atas.
                            </p>
                        </div>
                        @endif
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

                <!-- Lampiran -->
                @if($pencairan->lampirans && $pencairan->lampirans->isNotEmpty())
                <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                    <div class="px-6 py-4 border-b border-secondary-200">
                        <h2 class="text-lg font-semibold text-secondary-900 flex items-center">
                            <span class="w-8 h-8 bg-orange-100 text-orange-600 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                </svg>
                            </span>
                            Lampiran
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-2">
                            @foreach($pencairan->lampirans as $lampiran)
                            <a href="{{ $lampiran->file_url }}" target="_blank" class="flex items-center justify-between p-3 border border-secondary-200 rounded-lg hover:bg-secondary-50 hover:border-primary-300 transition-colors">
                                <div class="flex items-center space-x-3">
                                    <span class="text-2xl">📎</span>
                                    <div>
                                        <p class="text-sm font-medium text-secondary-900">{{ $lampiran->nama_file }}</p>
                                        <p class="text-xs text-secondary-500">{{ $lampiran->formatted_size }}</p>
                                    </div>
                                </div>
                                <svg class="w-5 h-5 text-secondary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                            </a>
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
                            <p class="text-sm text-secondary-500">Jumlah Pencairan</p>
                            <p class="text-2xl font-bold text-primary-600">{{ formatRupiah($pencairan->jumlah_pencairan) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-secondary-500">Status</p>
                            <p class="text-sm font-medium">
                                @if($pencairan->status === 'pending')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700">
                                        Menunggu
                                    </span>
                                @elseif($pencairan->status === 'processed')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                        Diproses
                                    </span>
                                @elseif($pencairan->status === 'completed')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                        Selesai
                                    </span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-secondary-500">Metode</p>
                            <p class="text-sm font-medium text-secondary-900">
                                @if($pencairan->metode_pencairan === 'transfer')
                                    Transfer Bank
                                @elseif($pencairan->metode_pencairan === 'cash')
                                    Tunai
                                @else
                                    Reimburse
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Actions Card - For Pengaju to Verify -->
                @php
                    \Log::info('View Permissions Debug', [
                        'verify' => $permissions['verify'] ?? 'NOT_SET',
                        'edit' => $permissions['edit'] ?? 'NOT_SET',
                        'delete' => $permissions['delete'] ?? 'NOT_SET',
                        'all_permissions' => $permissions,
                    ]);
                @endphp
                @if($permissions['verify'])
                <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                    <div class="px-6 py-4 border-b border-secondary-200">
                        <h3 class="text-lg font-semibold text-secondary-900">Konfirmasi Penerimaan Dana</h3>
                        <p class="text-sm text-secondary-500 mt-1">Silakan konfirmasi apakah Anda sudah menerima dana</p>
                    </div>
                    <div class="p-6 space-y-3">
                        <form method="POST" action="{{ route('pencairan-dana.verify', $pencairan) }}">
                            @csrf
                            <input type="hidden" name="confirmed" value="1">
                            <div class="mb-3">
                                <label class="block text-sm font-medium text-secondary-700 mb-1">Catatan (opsional)</label>
                                <textarea name="notes" rows="2" class="w-full rounded-xl border-secondary-200 focus:border-primary-500 focus:ring-primary-500" placeholder="Tambahkan catatan..."></textarea>
                            </div>
                            <button type="submit" class="w-full flex items-center justify-center px-4 py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-all duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Saya Sudah Menerima Dana
                            </button>
                        </form>

                        <form method="POST" action="{{ route('pencairan-dana.verify', $pencairan) }}" onsubmit="return confirm('Apakah Anda yakin ingin menolak pencairan ini?')">
                            @csrf
                            <input type="hidden" name="confirmed" value="0">
                            <div class="mb-3">
                                <label class="block text-sm font-medium text-secondary-700 mb-1">Alasan Penolakan <span class="text-red-500">*</span></label>
                                <textarea name="notes" rows="2" required class="w-full rounded-xl border-secondary-200 focus:border-red-500 focus:ring-red-500" placeholder="Jelaskan alasan penolakan..."></textarea>
                            </div>
                            <button type="submit" class="w-full flex items-center justify-center px-4 py-3 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-all duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Saya Belum Menerima Dana
                            </button>
                        </form>
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

                <!-- Print Button -->
                <button onclick="window.print()" class="w-full flex items-center justify-center px-4 py-3 bg-white border border-secondary-200 text-secondary-700 rounded-xl hover:bg-secondary-50 transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Cetak
                </button>
            </div>
        </div>
    </div>
</x-app-layout>
