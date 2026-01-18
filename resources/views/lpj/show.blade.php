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
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                            Draft
                        </span>
                    @elseif($lpj->status === 'menunggu_verifikasi')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-700">
                            Menunggu Verifikasi
                        </span>
                    @elseif($lpj->status === 'approved')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                            Disetujui
                        </span>
                    @elseif($lpj->status === 'rejected')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                            Ditolak
                        </span>
                    @elseif($lpj->status === 'revisi')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-700">
                            Perlu Revisi
                        </span>
                    @endif
                </div>
                <p class="text-secondary-600 mt-1">{{ $lpj->uraian_kegiatan }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <!-- Alert Messages -->
        @if($lpj->status === 'menunggu_verifikasi')
            <div class="mb-6 bg-orange-50 border border-orange-200 rounded-xl p-4 flex items-start">
                <svg class="w-5 h-5 text-orange-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="text-orange-700 text-sm">
                    LPJ ini <strong>menunggu verifikasi</strong> dari staff keuangan atau direktur keuangan.
                </div>
            </div>
        @elseif($lpj->status === 'approved')
            <div class="mb-6 bg-blue-50 border border-blue-200 rounded-xl p-4 flex items-start">
                <svg class="w-5 h-5 text-blue-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="text-blue-700 text-sm">
                    LPJ ini telah <strong>disetujui</strong>.
                </div>
            </div>
        @elseif($lpj->status === 'rejected')
            <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4 flex items-start">
                <svg class="w-5 h-5 text-red-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="text-red-700 text-sm">
                    LPJ ini <strong>ditolak</strong>. @if($lpj->rejection_reason) Alasan: {{ $lpj->rejection_reason }} @endif
                </div>
            </div>
        @elseif($lpj->status === 'revisi')
            <div class="mb-6 bg-orange-50 border border-orange-200 rounded-xl p-4 flex items-start">
                <svg class="w-5 h-5 text-orange-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="text-orange-700 text-sm">
                    LPJ ini <strong>perlu revisi</strong>. @if($lpj->rejection_reason) Catatan: {{ $lpj->rejection_reason }} @endif
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
                                <dd class="mt-1 text-sm text-secondary-900">{{ \Carbon\Carbon::parse($lpj->tanggal_lpj)->format('d/m/Y H:i') }}</dd>
                            </div>
                            @if($lpj->uraian_kegiatan)
                            <div class="md:col-span-2">
                                <dt class="text-sm text-secondary-500">Uraian Kegiatan</dt>
                                <dd class="mt-1 text-sm text-secondary-700">{{ $lpj->uraian_kegiatan }}</dd>
                            </div>
                            @endif
                            @if($lpj->catatan)
                            <div class="md:col-span-2">
                                <dt class="text-sm text-secondary-500">Catatan</dt>
                                <dd class="mt-1 text-sm text-secondary-700">{{ $lpj->catatan }}</dd>
                            </div>
                            @endif
                        </dl>
                    </div>
                </div>

                <!-- Rincian Realisasi -->
                <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                    <div class="px-6 py-4 border-b border-secondary-200">
                        <h2 class="text-lg font-semibold text-secondary-900 flex items-center">
                            <span class="w-8 h-8 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </span>
                            Rincian Realisasi
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-secondary-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left font-semibold text-secondary-700">Uraian</th>
                                        <th class="px-4 py-3 text-center font-semibold text-secondary-700">Tgl Realisasi</th>
                                        <th class="px-4 py-3 text-center font-semibold text-secondary-700">Volume</th>
                                        <th class="px-4 py-3 text-center font-semibold text-secondary-700">Harga</th>
                                        <th class="px-4 py-3 text-right font-semibold text-secondary-700">Subtotal</th>
                                        <th class="px-4 py-3 text-center font-semibold text-secondary-700">Lampiran</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-secondary-200">
                                    @foreach($lpj->detailLpjs as $detail)
                                    <tr>
                                        <td class="px-4 py-3">
                                            <p class="font-medium text-secondary-900">{{ $detail->uraian }}</p>
                                            @if($detail->keterangan)
                                                <p class="text-xs text-secondary-500 mt-1">{{ $detail->keterangan }}</p>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            {{ $detail->tanggal_realisasi ? \Carbon\Carbon::parse($detail->tanggal_realisasi)->format('d/m/Y') : '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            {{ $detail->volume_realisasi }} {{ $detail->satuan ?? '' }}
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            {{ formatRupiah($detail->harga_satuan) }}
                                        </td>
                                        <td class="px-4 py-3 text-right font-semibold">
                                            {{ formatRupiah($detail->subtotal_realisasi) }}
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            @if($detail->file_lampiran)
                                                <a href="{{ Storage::url($detail->file_lampiran) }}" target="_blank" class="text-primary-600 hover:text-primary-800">
                                                    <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
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
                                        <td colspan="4" class="px-4 py-3 text-right font-bold text-secondary-900">Total Realisasi</td>
                                        <td class="px-4 py-3 text-right font-bold text-primary-600">{{ formatRupiah($lpj->detailLpjs->sum('subtotal_realisasi')) }}</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Summary -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-white rounded-2xl shadow-soft p-4">
                        <p class="text-sm text-secondary-500">Jumlah Pencairan</p>
                        <p class="mt-2 text-lg font-bold text-secondary-900">{{ formatRupiah($lpj->pencairanDana->jumlah_pencairan) }}</p>
                    </div>
                    <div class="bg-white rounded-2xl shadow-soft p-4">
                        <p class="text-sm text-secondary-500">Total Digunakan</p>
                        <p class="mt-2 text-lg font-bold text-primary-600">{{ formatRupiah($lpj->total_digunakan) }}</p>
                    </div>
                    <div class="bg-white rounded-2xl shadow-soft p-4 @if($lpj->sisa_dana > 0) border-green-200 @else border-red-200 @endif">
                        <p class="text-sm @if($lpj->sisa_dana > 0) text-green-600 @else text-red-600 @endif">Sisa Dana</p>
                        <p class="mt-2 text-lg font-bold @if($lpj->sisa_dana > 0) text-green-600 @else text-red-600 @endif">{{ formatRupiah($lpj->sisa_dana) }}</p>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Actions Card -->
                <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                    <div class="px-6 py-4 border-b border-secondary-200">
                        <h3 class="text-lg font-semibold text-secondary-900">Aksi</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <!-- Revisi LPJ Button for pengaju when status is revisi -->
                        @if(auth()->id() === $lpj->created_by && $lpj->status === 'revisi')
                        <a href="{{ route('lpj.edit', $lpj) }}" class="w-full flex items-center justify-center px-4 py-3 bg-orange-600 text-white rounded-xl hover:bg-orange-700 transition-all duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Revisi LPJ
                        </a>
                        @endif

                        <!-- Verify Buttons for staff_keuangan or direktur_keuangan -->
                        @if(auth()->user()->hasAnyRole(['staff_keuangan', 'direktur_keuangan']) && $lpj->status === 'menunggu_verifikasi')
                        <div class="space-y-2">
                            <p class="text-sm font-medium text-secondary-700 mb-2">Verifikasi LPJ:</p>

                            <!-- Setujui Button -->
                            <button type="button" onclick="showApproveForm()" class="w-full flex items-center justify-center px-4 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-all duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Setujui
                            </button>

                            <!-- Tolak/Revisi Button -->
                            <button type="button" onclick="showRejectForm()" class="w-full flex items-center justify-center px-4 py-3 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-all duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Tolak / Minta Revisi
                            </button>
                        </div>

                        <!-- Approve Form -->
                        <form id="approveForm" method="POST" action="{{ route('lpj.verify', $lpj) }}" class="hidden space-y-3 mt-3 p-4 bg-blue-50 rounded-xl border border-blue-200">
                            @csrf
                            <input type="hidden" name="status" value="approved">
                            <div>
                                <label class="block text-sm font-medium text-blue-700 mb-1">Catatan (opsional)</label>
                                <textarea name="catatan_verifikasi" rows="2" class="w-full px-3 py-2 border border-blue-200 rounded-lg text-sm" placeholder="Catatan verifikasi..."></textarea>
                            </div>
                            <div class="flex space-x-2">
                                <button type="button" onclick="hideApproveForm()" class="flex-1 px-3 py-2 border border-blue-200 text-blue-700 rounded-lg hover:bg-blue-50 text-sm">
                                    Batal
                                </button>
                                <button type="submit" class="flex-1 flex items-center justify-center px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Ya, Setujui
                                </button>
                            </div>
                        </form>

                        <!-- Reject Form -->
                        <form id="rejectForm" method="POST" action="{{ route('lpj.verify', $lpj) }}" class="hidden space-y-3 mt-3 p-4 bg-red-50 rounded-xl border border-red-200">
                            @csrf
                            <input type="hidden" name="status" value="rejected">
                            <div>
                                <label class="block text-sm font-medium text-red-700 mb-1">Alasan Penolakan *</label>
                                <textarea name="catatan_verifikasi" rows="3" required class="w-full px-3 py-2 border border-red-200 rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Jelaskan alasan penolakan agar pengaju dapat merevisi..."></textarea>
                            </div>
                            <div class="flex space-x-2">
                                <button type="button" onclick="hideRejectForm()" class="flex-1 px-3 py-2 border border-red-200 text-red-700 rounded-lg hover:bg-red-50 text-sm">
                                    Batal
                                </button>
                                <button type="submit" class="flex-1 flex items-center justify-center px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Ya, Tolak
                                </button>
                            </div>
                        </form>

                        <script>
                        function showApproveForm() {
                            document.getElementById('approveForm').classList.remove('hidden');
                            document.getElementById('rejectForm').classList.add('hidden');
                        }
                        function hideApproveForm() {
                            document.getElementById('approveForm').classList.add('hidden');
                        }
                        function showRejectForm() {
                            document.getElementById('rejectForm').classList.remove('hidden');
                            document.getElementById('approveForm').classList.add('hidden');
                        }
                        function hideRejectForm() {
                            document.getElementById('rejectForm').classList.add('hidden');
                        }
                        </script>
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
                    @if($lpj->verifiedBy)
                    <div class="flex items-center mt-4 pt-4 border-t border-secondary-100">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center">
                            <span class="text-white font-semibold">{{ strtoupper(substr($lpj->verifiedBy->name ?? 'A', 0, 1)) }}</span>
                        </div>
                        <div class="ml-3">
                            <p class="text-xs text-secondary-500">Diverifikasi oleh</p>
                            <p class="text-sm font-medium text-secondary-900">{{ $lpj->verifiedBy->name ?? '-' }}</p>
                            <p class="text-xs text-secondary-500">{{ \Carbon\Carbon::parse($lpj->verified_at)->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
