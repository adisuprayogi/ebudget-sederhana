<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('pencairan-dana.index') }}" class="p-2 text-secondary-600 hover:text-secondary-900 hover:bg-secondary-100 rounded-lg transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-secondary-900">Pilih Pengajuan Dana</h1>
                <p class="text-secondary-600 mt-1">Pilih pengajuan dana yang akan dicairkan</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <!-- Info -->
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-blue-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="text-blue-700 text-sm">
                    <p class="font-medium">Pilih pengajuan dana yang sudah disetujui dan belum dicairkan untuk membuat pencairan dana baru.</p>
                </div>
            </div>
        </div>

        @if($pengajuans->isEmpty())
            <!-- Empty State -->
            <div class="bg-white rounded-2xl shadow-soft p-12 text-center">
                <svg class="w-20 h-20 text-secondary-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="text-lg font-semibold text-secondary-900 mb-2">Tidak Ada Pengajuan Tersedia</h3>
                <p class="text-secondary-500 mb-4">Tidak ada pengajuan dana yang sudah disetujui dan belum dicairkan.</p>
                <a href="{{ route('pengajuan-dana.index') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-all duration-200">
                    Lihat Daftar Pengajuan
                </a>
            </div>
        @else
            <!-- Pengajuan List -->
            <div class="space-y-4">
                @foreach($pengajuans as $pengajuan)
                <div class="bg-white rounded-2xl shadow-soft overflow-hidden hover:shadow-medium transition-shadow">
                    <div class="p-6">
                        <!-- Header -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-2">
                                    <span class="font-mono text-sm font-semibold text-primary-600">{{ $pengajuan->nomor_pengajuan }}</span>
                                    @php
                                        $jenisLabels = [
                                            'kegiatan' => 'Kegiatan',
                                            'pengadaan' => 'Pengadaan',
                                            'pembayaran' => 'Pembayaran',
                                            'honorarium' => 'Honorarium',
                                            'sewa' => 'Sewa',
                                            'konsumsi' => 'Konsumsi',
                                            'konsumi' => 'Konsumi',
                                            'reimbursement' => 'Reimbursement',
                                            'lainnya' => 'Lainnya',
                                        ];
                                        $jenisColors = [
                                            'kegiatan' => 'bg-blue-100 text-blue-700',
                                            'pengadaan' => 'bg-green-100 text-green-700',
                                            'pembayaran' => 'bg-yellow-100 text-yellow-700',
                                            'honorarium' => 'bg-purple-100 text-purple-700',
                                            'sewa' => 'bg-orange-100 text-orange-700',
                                            'konsumsi' => 'bg-pink-100 text-pink-700',
                                            'konsumi' => 'bg-pink-100 text-pink-700',
                                            'reimbursement' => 'bg-teal-100 text-teal-700',
                                            'lainnya' => 'bg-gray-100 text-gray-700',
                                        ];
                                        $statusLabels = [
                                            'menunggu_pencairan' => 'Menunggu Pencairan',
                                            'cair' => 'Dana Sudah Dicairkan',
                                        ];
                                        $statusColors = [
                                            'menunggu_pencairan' => 'bg-green-100 text-green-700',
                                            'cair' => 'bg-blue-100 text-blue-700',
                                        ];
                                        $jenis = $pengajuan->jenis_pengajuan;
                                        $label = $jenisLabels[$jenis] ?? ucfirst($jenis);
                                        $colorClass = $jenisColors[$jenis] ?? 'bg-gray-100 text-gray-700';
                                        $statusLabel = $statusLabels[$pengajuan->status] ?? ucfirst(str_replace('_', ' ', $pengajuan->status));
                                        $statusColorClass = $statusColors[$pengajuan->status] ?? 'bg-gray-100 text-gray-700';
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $colorClass }}">
                                        {{ $label }}
                                    </span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $statusColorClass }}">
                                        {{ $statusLabel }}
                                    </span>
                                </div>
                                <h3 class="text-lg font-semibold text-secondary-900">{{ $pengajuan->judul_pengajuan }}</h3>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl font-bold text-primary-600">{{ formatRupiah($pengajuan->total_pengajuan) }}</p>
                            </div>
                        </div>

                        <!-- Details -->
                        <dl class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                            <div>
                                <dt class="text-xs text-secondary-500 uppercase tracking-wider">Divisi</dt>
                                <dd class="text-sm font-medium text-secondary-900 mt-1">{{ $pengajuan->divisi->nama_divisi ?? '-' }}</dd>
                            </div>
                            @if($pengajuan->programKerja)
                            <div>
                                <dt class="text-xs text-secondary-500 uppercase tracking-wider">Program Kerja</dt>
                                <dd class="text-sm font-medium text-secondary-900 mt-1">{{ $pengajuan->programKerja->nama_program }}</dd>
                            </div>
                            @endif
                            <div>
                                <dt class="text-xs text-secondary-500 uppercase tracking-wider">Diajukan Oleh</dt>
                                <dd class="text-sm font-medium text-secondary-900 mt-1">{{ $pengajuan->createdBy->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-secondary-500 uppercase tracking-wider">Tanggal Pengajuan</dt>
                                <dd class="text-sm font-medium text-secondary-900 mt-1">{{ \Carbon\Carbon::parse($pengajuan->tanggal_pengajuan)->format('d/m/Y H:i') }}</dd>
                            </div>
                        </dl>

                        <!-- Riwayat Approval -->
                        @if($pengajuan->approvals && $pengajuan->approvals->isNotEmpty())
                        <div class="border-t border-secondary-100 pt-4 mb-4">
                            <p class="text-xs text-secondary-500 uppercase tracking-wider mb-2">Riwayat Approval</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($pengajuan->approvals as $approval)
                                <div class="inline-flex items-center px-3 py-2 bg-secondary-50 rounded-lg">
                                    @if($approval->status === 'approved')
                                        <svg class="w-4 h-4 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    @endif
                                    <div class="text-xs">
                                        <p class="font-medium text-secondary-900">{{ $approval->approver->name }}</p>
                                        <p class="text-secondary-500">{{ ucfirst(str_replace('_', ' ', $approval->level)) }}</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @else
                        <div class="border-t border-secondary-100 pt-4 mb-4">
                            <p class="text-xs text-secondary-500 uppercase tracking-wider mb-2">Riwayat Approval</p>
                            <p class="text-xs text-gray-500 italic">Data riwayat approval tidak tersedia</p>
                        </div>
                        @endif

                        <!-- Action -->
                        <div class="flex items-center justify-between pt-4 border-t border-secondary-100">
                            <a href="{{ route('pengajuan-dana.show', $pengajuan) }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium">
                                Lihat Detail Pengajuan →
                            </a>
                            <a href="{{ route('pencairan-dana.create', ['pengajuan_id' => $pengajuan->id]) }}" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-all duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Buat Pencairan
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
