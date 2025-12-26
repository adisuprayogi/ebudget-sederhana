<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Detail Konfigurasi Approval</h1>
                <p class="text-gray-600 mt-1">ID: #{{ $approvalConfig->id }}</p>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.approval-configs.edit', $approvalConfig) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Edit Konfigurasi
                </a>
                <a href="{{ route('admin.approval-configs.index') }}" class="text-gray-600 hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto py-8">
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-xl font-bold text-gray-900">Konfigurasi Approval</h2>
                        @if($approvalConfig->is_active)
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                Nonaktif
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Details -->
            <div class="p-6">
                <dl class="grid grid-cols-1 gap-6">
                    @php
                        $jenisPengajuanLabels = [
                            'pengajuan_dana' => 'Pengajuan Dana',
                            'lpj' => 'Laporan Pertanggungjawaban',
                            'refund' => 'Refund',
                            'pencairan_dana' => 'Pencairan Dana',
                        ];

                        $levelLabels = [
                            'kepala_divisi' => 'Kepala Divisi',
                            'direktur_keuangan' => 'Direktur Keuangan',
                            'direktur_utama' => 'Direktur Utama',
                        ];
                    @endphp

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Jenis Pengajuan</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $jenisPengajuanLabels[$approvalConfig->jenis_pengajuan] ?? $approvalConfig->jenis_pengajuan }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Minimal Nominal</dt>
                        <dd class="mt-1 text-sm text-gray-900">Rp {{ number_format($approvalConfig->minimal_nominal, 0, ',', '.') }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Level Approval</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $levelLabels[$approvalConfig->level] ?? $approvalConfig->level }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Urutan Approval</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-700">
                                Level {{ $approvalConfig->urutan }}
                            </span>
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Dibuat</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $approvalConfig->created_at ? $approvalConfig->created_at->format('d M Y H:i') : '-' }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Diperbarui</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $approvalConfig->updated_at ? $approvalConfig->updated_at->format('d M Y H:i') : '-' }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Actions -->
            <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
                <a href="{{ route('admin.approval-configs.edit', $approvalConfig) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Edit Konfigurasi
                </a>
                <form method="POST" action="{{ route('admin.approval-configs.destroy', $approvalConfig) }}" class="inline" onsubmit="return confirm('Yakin ingin menghapus konfigurasi approval ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                        Hapus Konfigurasi
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
