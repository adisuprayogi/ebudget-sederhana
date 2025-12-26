<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Konfigurasi Approval</h1>
                <p class="text-gray-600 mt-1">Atur flow approval untuk setiap jenis pengajuan</p>
            </div>
            <a href="{{ route('admin.approval-configs.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Tambah Konfigurasi
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <!-- Tabs -->
        <div class="mb-6 border-b border-gray-200">
            <nav class="-mb-px flex space-x-8">
                <a href="{{ route('admin.approval-configs.index', ['jenis' => 'pengajuan_dana']) }}"
                   class="py-4 px-1 border-b-2 font-medium text-sm {{ $jenisPengajuan === 'pengajuan_dana' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Pengajuan Dana
                </a>
                <a href="{{ route('admin.approval-configs.index', ['jenis' => 'lpj']) }}"
                   class="py-4 px-1 border-b-2 font-medium text-sm {{ $jenisPengajuan === 'lpj' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Laporan Pertanggungjawaban
                </a>
                <a href="{{ route('admin.approval-configs.index', ['jenis' => 'refund']) }}"
                   class="py-4 px-1 border-b-2 font-medium text-sm {{ $jenisPengajuan === 'refund' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Refund
                </a>
                <a href="{{ route('admin.approval-configs.index', ['jenis' => 'pencairan_dana']) }}"
                   class="py-4 px-1 border-b-2 font-medium text-sm {{ $jenisPengajuan === 'pencairan_dana' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Pencairan Dana
                </a>
            </nav>
        </div>

        <!-- Approval Configs Table -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Minimal Nominal</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Level Approval</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Urutan</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($configs as $config)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <span class="font-semibold text-gray-900">
                                    {{ number_format($config->minimal_nominal, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $levelLabels = [
                                        'kepala_divisi' => 'Kepala Divisi',
                                        'direktur_keuangan' => 'Direktur Keuangan',
                                        'direktur_utama' => 'Direktur Utama',
                                    ];
                                @endphp
                                <span class="text-sm text-gray-700">{{ $levelLabels[$config->level] ?? $config->level }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-700">
                                    Level {{ $config->urutan }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($config->is_active)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <form method="POST" action="{{ route('admin.approval-configs.toggle-status', $config) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="p-2 {{ $config->is_active ? 'text-yellow-600 hover:text-yellow-800 hover:bg-yellow-50' : 'text-green-600 hover:text-green-800 hover:bg-green-50' }} rounded-lg" title="{{ $config->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                            @if($config->is_active)
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                                </svg>
                                            @else
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            @endif
                                        </button>
                                    </form>
                                    <a href="{{ route('admin.approval-configs.edit', $config) }}" class="p-2 text-gray-600 hover:text-yellow-600 hover:bg-yellow-50 rounded-lg" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.approval-configs.destroy', $config) }}" class="inline" onsubmit="return confirm('Yakin ingin menghapus konfigurasi approval ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg" title="Hapus">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                    <p class="text-gray-500">Belum ada konfigurasi approval untuk jenis ini</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($configs->hasPages())
            <div class="mt-6">
                {{ $configs->links() }}
            </div>
        @endif

        <!-- Info Box -->
        <div class="mt-6 bg-blue-50 rounded-lg p-4">
            <div class="flex items-start space-x-3">
                <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="text-sm text-blue-800">
                    <p class="font-medium">Tentang Konfigurasi Approval</p>
                    <p class="text-blue-600 mt-1">Konfigurasi approval menentukan siapa yang perlu melakukan approval berdasarkan nominal pengajuan. Approval akan diproses secara berurutan dari urutan terkecil ke terbesar yang sesuai dengan nominal pengajuan.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
