<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('approvals.index') }}" class="p-2 text-secondary-600 hover:text-secondary-900 hover:bg-secondary-100 rounded-lg transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-secondary-900">Review Approval</h1>
                <p class="text-secondary-600 mt-1">{{ $approval->pengajuanDana->nomor_pengajuan }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <!-- Alert -->
        <div class="mb-6 bg-amber-50 border border-amber-200 rounded-xl p-4 flex items-start">
            <svg class="w-5 h-5 text-amber-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div class="text-amber-700 text-sm">
                Anda sedang memproses approval <strong>Level {{ $approval->level }}</strong> untuk pengajuan dana ini.
            </div>
        </div>

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
                            <div>
                                <dt class="text-sm text-secondary-500">Nomor Pengajuan</dt>
                                <dd class="mt-1 font-mono text-sm font-semibold text-primary-600">{{ $approval->pengajuanDana->nomor_pengajuan }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-secondary-500">Tanggal Pengajuan</dt>
                                <dd class="mt-1 text-sm text-secondary-900">{{ \Carbon\Carbon::parse($approval->pengajuanDana->tanggal_pengajuan)->format('d/m/Y') }}</dd>
                            </div>
                            <div class="md:col-span-2">
                                <dt class="text-sm text-secondary-500">Judul Pengajuan</dt>
                                <dd class="mt-1 text-sm text-secondary-900 font-medium">{{ $approval->pengajuanDana->judul_pengajuan }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-secondary-500">Divisi</dt>
                                <dd class="mt-1 text-sm text-secondary-900">{{ $approval->pengajuanDana->divisi->nama_divisi ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-secondary-500">Diajukan Oleh</dt>
                                <dd class="mt-1 text-sm text-secondary-900">{{ $approval->pengajuanDana->createdBy->name ?? '-' }}</dd>
                            </div>
                            @if($approval->pengajuanDana->program_kerja)
                            <div class="md:col-span-2">
                                <dt class="text-sm text-secondary-500">Program Kerja</dt>
                                <dd class="mt-1 text-sm text-secondary-900">{{ $approval->pengajuanDana->programKerja->nama_program }}</dd>
                            </div>
                            @endif
                        </dl>
                        @if($approval->pengajuanDana->deskripsi)
                        <div class="mt-4">
                            <dt class="text-sm text-secondary-500">Deskripsi</dt>
                            <dd class="mt-1 text-sm text-secondary-700 whitespace-pre-line">{{ $approval->pengajuanDana->deskripsi }}</dd>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Rincian Pengajuan -->
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
                                @foreach($approval->pengajuanDana->detailPengajuan ?? [] as $detail)
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
                                    <td class="px-6 py-4 text-right text-lg font-bold text-primary-600">{{ formatRupiah($approval->pengajuanDana->total_pengajuan) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Riwayat Approval -->
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
                            @foreach($approval->pengajuanDana->approvals ?? [] as $appr)
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    @if($appr->status === 'approved' || $appr->status === 'disetujui')
                                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                    @elseif($appr->status === 'rejected' || $appr->status === 'ditolak')
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
                                        <p class="text-sm font-medium text-secondary-900">
                                            Level {{ $appr->level }} - {{ $appr->approver->name ?? 'System' }}
                                            @if($appr->id === $approval->id)
                                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-100 text-amber-800">Current</span>
                                            @endif
                                        </p>
                                        <p class="text-xs text-secondary-500">
                                            @if($appr->approved_at)
                                                {{ \Carbon\Carbon::parse($appr->approved_at)->format('d/m/Y H:i') }}
                                            @else
                                                {{ \Carbon\Carbon::parse($appr->created_at)->format('d/m/Y') }}
                                            @endif
                                        </p>
                                    </div>
                                    <p class="text-xs text-secondary-500">
                                        @if($appr->status === 'pending') Menunggu
                                        @elseif($appr->status === 'approved' || $appr->status === 'disetujui') Disetujui
                                        @elseif($appr->status === 'rejected' || $appr->status === 'ditolak') Ditolak
                                        @else {{ $appr->status }} @endif
                                    </p>
                                    @if($appr->notes)
                                    <p class="text-sm text-secondary-700 mt-1">Catatan: {{ $appr->notes }}</p>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
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
                            <p class="text-2xl font-bold text-primary-600">{{ formatRupiah($approval->pengajuanDana->total_pengajuan) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-secondary-500">Level Approval</p>
                            <p class="text-sm font-medium text-secondary-900">Level {{ $approval->level }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-secondary-500">Status Approval</p>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                Menunggu
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Action Form -->
                <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                    <div class="px-6 py-4 border-b border-secondary-200">
                        <h3 class="text-lg font-semibold text-secondary-900">Proses Approval</h3>
                    </div>
                    <form method="POST" action="{{ route('approvals.process', $approval) }}" class="p-6 space-y-4">
                        @csrf

                        <div>
                            <label class="block text-sm font-medium text-secondary-700 mb-2">Catatan (Opsional)</label>
                            <textarea name="notes" rows="3"
                                class="w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                placeholder="Tambahkan catatan untuk approval ini..."></textarea>
                        </div>

                        <input type="hidden" name="action" id="action-input" value="">

                        <div class="space-y-3">
                            <button type="button" onclick="submitApproval('disetujui')" class="w-full flex items-center justify-center px-4 py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-all duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Setujui Pengajuan
                            </button>
                            <button type="button" onclick="submitApproval('ditolak')" class="w-full flex items-center justify-center px-4 py-3 border border-red-300 text-red-600 rounded-xl hover:bg-red-50 transition-all duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Tolak Pengajuan
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Documents -->
                @if($approval->pengajuanDana->attachments && $approval->pengajuanDana->attachments->count() > 0)
                <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                    <div class="px-6 py-4 border-b border-secondary-200">
                        <h3 class="text-lg font-semibold text-secondary-900">Dokumen Lampiran</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-2">
                            @foreach($approval->pengajuanDana->attachments ?? [] as $attachment)
                            <a href="{{ Storage::url($attachment->path_dokumen) }}" target="_blank" class="flex items-center p-3 border border-secondary-200 rounded-xl hover:bg-secondary-50 transition-colors">
                                <svg class="w-5 h-5 text-secondary-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                                <span class="text-sm text-secondary-700 truncate">{{ $attachment->nama_dokumen }}</span>
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        function submitApproval(action) {
            const actionInput = document.getElementById('action-input');
            actionInput.value = action;

            const message = action === 'disetujui'
                ? 'Apakah Anda yakin ingin menyetujui pengajuan ini?'
                : 'Apakah Anda yakin ingin menolak pengajuan ini?';

            if (confirm(message)) {
                actionInput.closest('form').submit();
            }
        }
    </script>
</x-app-layout>
