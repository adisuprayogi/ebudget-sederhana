<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-secondary-900">Edit Refund</h1>
                <p class="text-secondary-600 mt-1">Ubah data pengembalian dana</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('refund.show', $refund) }}" class="inline-flex items-center px-4 py-2 bg-white border border-secondary-300 text-secondary-700 rounded-xl hover:bg-secondary-50 transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    Lihat Detail
                </a>
                <a href="{{ route('refund.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-secondary-300 text-secondary-700 rounded-xl hover:bg-secondary-50 transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto py-8">
        @if($refund->status !== 'draft')
            <div class="bg-amber-50 border-l-4 border-amber-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-amber-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-amber-700">
                            Hanya refund dengan status draft yang dapat diedit.
                        </p>
                    </div>
                </div>
            </div>
        @else
            <form method="POST" action="{{ route('refund.update', $refund) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="bg-white rounded-2xl shadow-soft p-8 mb-6">
                    <h2 class="text-lg font-semibold text-secondary-900 mb-6">Informasi Refund</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Referensi (Cannot be changed) -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-secondary-700 mb-2">Referensi</label>
                            <div class="px-4 py-3 bg-secondary-50 border border-secondary-200 rounded-xl text-secondary-700">
                                @if($refund->pencairanDana)
                                    Pencairan: {{ $refund->pencairanDana->nomor_pencairan }}
                                @elseif($refund->pengajuanDana)
                                    Pengajuan: {{ $refund->pengajuanDana->nomor_pengajuan }}
                                @else
                                    -
                                @endif
                            </div>
                        </div>

                        <!-- Tanggal Refund -->
                        <div>
                            <label class="block text-sm font-medium text-secondary-700 mb-2">Tanggal Refund <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_refund" value="{{ old('tanggal_refund', $refund->tanggal_refund?->format('Y-m-d')) }}" required class="w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            @error('tanggal_refund')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Jenis Refund -->
                        <div>
                            <label class="block text-sm font-medium text-secondary-700 mb-2">Jenis Refund <span class="text-red-500">*</span></label>
                            <select name="jenis_refund" required class="w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                <option value="">Pilih Jenis Refund</option>
                                <option value="kelebihan" {{ old('jenis_refund', $refund->jenis_refund) == 'kelebihan' ? 'selected' : '' }}>Kelebihan Transfer</option>
                                <option value="dana_kembali" {{ old('jenis_refund', $refund->jenis_refund) == 'dana_kembali' ? 'selected' : '' }}>Dana Kembali</option>
                                <option value="batal" {{ old('jenis_refund', $refund->jenis_refund) == 'batal' ? 'selected' : '' }}>Pembatalan</option>
                                <option value="pengembalian lainnya" {{ old('jenis_refund', $refund->jenis_refund) == 'pengembalian lainnya' ? 'selected' : '' }}>Pengembalian Lainnya</option>
                            </select>
                            @error('jenis_refund')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Jumlah Refund -->
                        <div>
                            <label class="block text-sm font-medium text-secondary-700 mb-2">Jumlah Refund <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-secondary-500 font-medium">Rp</span>
                                <input type="number" name="jumlah_refund" value="{{ old('jumlah_refund', $refund->jumlah_refund) }}" required min="0" step="0.01" class="w-full pl-12 pr-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="0">
                            </div>
                            @error('jumlah_refund')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Rekening Tujuan -->
                        <div>
                            <label class="block text-sm font-medium text-secondary-700 mb-2">Rekening Tujuan</label>
                            <input type="text" name="rekening_tujuan" value="{{ old('rekening_tujuan', $refund->rekening_tujuan) }}" placeholder="Nomor rekening tujuan pengembalian" class="w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            @error('rekening_tujuan')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Alasan Refund -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-secondary-700 mb-2">Alasan Refund <span class="text-red-500">*</span></label>
                            <textarea name="alasan_refund" rows="4" required class="w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="Jelaskan alasan pengembalian dana...">{{ old('alasan_refund', $refund->alasan_refund) }}</textarea>
                            @error('alasan_refund')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Bukti Transfer (Optional) -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-secondary-700 mb-2">Bukti Transfer</label>
                            @if($refund->bukti_transfer)
                                <div class="mt-1 flex items-center justify-between bg-secondary-50 rounded-xl px-4 py-3">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-primary-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                        <span class="text-sm text-secondary-700">{{ basename($refund->bukti_transfer) }}</span>
                                    </div>
                                    <a href="{{ asset('storage/' . $refund->bukti_transfer) }}" target="_blank" class="text-primary-600 hover:underline text-sm">Lihat</a>
                                </div>
                            @endif
                            <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-secondary-300 border-dashed rounded-xl hover:border-primary-400 transition-colors">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-8 w-8 text-secondary-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-secondary-600">
                                        <label class="relative cursor-pointer bg-white rounded-md font-medium text-primary-600 hover:text-primary-500 focus-within:outline-none">
                                            <span>Ganti file</span>
                                            <input type="file" name="bukti_transfer" class="sr-only" accept=".pdf,.jpg,.jpeg,.png">
                                        </label>
                                    </div>
                                    <p class="text-xs text-secondary-500">PDF, JPG, PNG hingga 5MB</p>
                                </div>
                            </div>
                            @error('bukti_transfer')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end space-x-4">
                    <a href="{{ route('refund.show', $refund) }}" class="px-6 py-3 bg-white border border-secondary-300 text-secondary-700 rounded-xl hover:bg-secondary-50 transition-all duration-200">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-3 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-all duration-200 shadow-soft hover:shadow-medium">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        @endif
    </div>
</x-app-layout>
