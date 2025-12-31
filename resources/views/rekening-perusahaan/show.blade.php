<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Detail Rekening Perusahaan</h1>
                <p class="text-slate-600 mt-1">Informasi lengkap rekening perusahaan</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('rekening-perusahaan.edit', $rekening) }}"
                   class="inline-flex items-center px-4 py-2 bg-amber-600 text-white rounded-xl hover:bg-amber-700 transition-colors shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto">
        <div class="mb-4">
            <a href="{{ route('rekening-perusahaan.index') }}"
               class="inline-flex items-center text-slate-600 hover:text-slate-900">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali ke Daftar Rekening
            </a>
        </div>

        <!-- Rekening Info -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <!-- Main Info -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-semibold text-slate-900">Informasi Rekening</h2>
                        @if($rekening->is_default)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-amber-100 text-amber-800">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                Default
                            </span>
                        @endif
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="w-32 flex-shrink-0 text-sm font-medium text-slate-500">Bank</div>
                            <div class="flex-1">
                                <div class="text-slate-900 font-medium">{{ $rekening->bank->nama_bank }}</div>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="w-32 flex-shrink-0 text-sm font-medium text-slate-500">Nomor Rekening</div>
                            <div class="flex-1">
                                <div class="text-slate-900 font-mono text-lg">{{ $rekening->nomor_rekening_formatted }}</div>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="w-32 flex-shrink-0 text-sm font-medium text-slate-500">Atas Nama</div>
                            <div class="flex-1">
                                <div class="text-slate-900">{{ $rekening->atas_nama }}</div>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="w-32 flex-shrink-0 text-sm font-medium text-slate-500">Cabang</div>
                            <div class="flex-1">
                                <div class="text-slate-900">{{ $rekening->cabang ?: '-' }}</div>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="w-32 flex-shrink-0 text-sm font-medium text-slate-500">Mata Uang</div>
                            <div class="flex-1">
                                <div class="text-slate-900">{{ $rekening->mata_uang }}</div>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="w-32 flex-shrink-0 text-sm font-medium text-slate-500">Saldo Awal</div>
                            <div class="flex-1">
                                <div class="text-slate-900">{{ formatRupiah($rekening->saldo_awal) }}</div>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="w-32 flex-shrink-0 text-sm font-medium text-slate-500">Status</div>
                            <div class="flex-1">
                                @if($rekening->is_active)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-800">
                                        Non-Aktif
                                    </span>
                                @endif
                            </div>
                        </div>

                        @if($rekening->catatan)
                            <div class="flex items-start">
                                <div class="w-32 flex-shrink-0 text-sm font-medium text-slate-500">Catatan</div>
                                <div class="flex-1">
                                    <div class="text-slate-900">{{ $rekening->catatan }}</div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Status Card -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <h2 class="text-lg font-semibold text-slate-900 mb-4">Status</h2>

                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                            <span class="text-sm text-slate-600">Status Rekening</span>
                            @if($rekening->is_active)
                                <span class="text-sm font-medium text-green-600">Aktif</span>
                            @else
                                <span class="text-sm font-medium text-slate-600">Non-Aktif</span>
                            @endif
                        </div>

                        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                            <span class="text-sm text-slate-600">Rekening Default</span>
                            @if($rekening->is_default)
                                <span class="text-sm font-medium text-amber-600">Ya</span>
                            @else
                                <span class="text-sm font-medium text-slate-600">Tidak</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Timestamps -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mt-6">
                    <h2 class="text-lg font-semibold text-slate-900 mb-4">Informasi Waktu</h2>

                    <div class="space-y-3">
                        <div>
                            <div class="text-xs text-slate-500">Dibuat pada</div>
                            <div class="text-sm text-slate-900">{{ $rekening->created_at->format('d M Y H:i') }}</div>
                            @if($rekening->created_by)
                                <div class="text-xs text-slate-600">oleh {{ $rekening->created_by }}</div>
                            @endif
                        </div>

                        @if($rekening->updated_at != $rekening->created_at)
                            <div>
                                <div class="text-xs text-slate-500">Diperbarui pada</div>
                                <div class="text-sm text-slate-900">{{ $rekening->updated_at->format('d M Y H:i') }}</div>
                                @if($rekening->updated_by)
                                    <div class="text-xs text-slate-600">oleh {{ $rekening->updated_by }}</div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-lg font-semibold text-slate-900 mb-4">Aksi Cepat</h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <a href="{{ route('rekening-perusahaan.edit', $rekening) }}"
                   class="flex items-center justify-center px-4 py-3 bg-amber-50 text-amber-700 font-medium rounded-xl hover:bg-amber-100 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Rekening
                </a>
                <a href="{{ route('pencairan-dana.select-pengajuan') }}"
                   class="flex items-center justify-center px-4 py-3 bg-blue-50 text-blue-700 font-medium rounded-xl hover:bg-blue-100 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Buat Pencairan
                </a>
                <form action="{{ route('rekening-perusahaan.destroy', $rekening) }}" method="POST"
                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus rekening ini?');"
                      class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="w-full flex items-center justify-center px-4 py-3 bg-red-50 text-red-700 font-medium rounded-xl hover:bg-red-100 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Hapus Rekening
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
