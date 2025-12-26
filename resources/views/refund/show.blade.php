<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-secondary-900">Detail Refund</h1>
                <p class="text-secondary-600 mt-1">Informasi lengkap pengembalian dana</p>
            </div>
            <div class="flex items-center space-x-3">
                @if(in_array($refund->status, ['draft', 'rejected']))
                    <a href="{{ route('refund.edit', $refund) }}" class="inline-flex items-center px-4 py-2 bg-amber-500 text-white rounded-xl hover:bg-amber-600 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit
                    </a>
                @endif
                @if($refund->status === 'draft')
                    <form method="POST" action="{{ route('refund.submit', $refund) }}" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-all duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                            Submit untuk Approval
                        </button>
                    </form>
                @endif
                @if($refund->status === 'menunggu_approval' && auth()->user()->hasAnyRole(['direktur_keuangan', 'direktur_utama']))
                    <button type="button" x-data="{ open: false }" @click="open = true" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Setujui/Tolak
                    </button>
                @endif
                @if($refund->status === 'approved' && auth()->user()->hasAnyRole(['direktur_keuangan', 'staff_keuangan']))
                    <button type="button" x-data="{ open: false }" @click="open = true" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                        Proses Transfer
                    </button>
                @endif
                <a href="{{ route('refund.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-secondary-300 text-secondary-700 rounded-xl hover:bg-secondary-50 transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-6xl mx-auto py-8">
        <!-- Status & Info Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-2xl shadow-soft p-6">
                <div class="text-sm text-secondary-500 mb-1">Status</div>
                @if($refund->status === 'draft')
                    <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-slate-100 text-slate-700">Draft</div>
                @elseif($refund->status === 'menunggu_approval')
                    <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-amber-100 text-amber-700">Menunggu Approval</div>
                @elseif($refund->status === 'approved')
                    <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-700">Disetujui</div>
                @elseif($refund->status === 'rejected')
                    <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-700">Ditolak</div>
                @elseif($refund->status === 'processed')
                    <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-700">Diproses</div>
                @endif
            </div>
            <div class="bg-white rounded-2xl shadow-soft p-6">
                <div class="text-sm text-secondary-500 mb-1">Tanggal Refund</div>
                <div class="text-xl font-bold text-secondary-900">{{ \Carbon\Carbon::parse($refund->tanggal_refund)->format('d F Y') }}</div>
            </div>
            <div class="bg-white rounded-2xl shadow-soft p-6">
                <div class="text-sm text-secondary-500 mb-1">Jenis Refund</div>
                <div class="text-xl font-bold text-primary-600">{{ ucfirst(str_replace('_', ' ', $refund->jenis_refund)) }}</div>
            </div>
            <div class="bg-white rounded-2xl shadow-soft p-6">
                <div class="text-sm text-secondary-500 mb-1">Jumlah Refund</div>
                <div class="text-xl font-bold text-primary-600">{{ formatRupiah($refund->jumlah_refund) }}</div>
            </div>
        </div>

        <!-- Detail Refund -->
        <div class="bg-white rounded-2xl shadow-soft p-8 mb-6">
            <h2 class="text-lg font-semibold text-secondary-900 mb-6">Informasi Refund</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-sm text-secondary-500">Tanggal Refund</label>
                    <div class="mt-1 font-medium text-secondary-900">{{ \Carbon\Carbon::parse($refund->tanggal_refund)->format('d F Y') }}</div>
                </div>
                <div>
                    <label class="text-sm text-secondary-500">Jenis Refund</label>
                    <div class="mt-1">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-indigo-100 text-indigo-700">
                            {{ ucfirst(str_replace('_', ' ', $refund->jenis_refund)) }}
                        </span>
                    </div>
                </div>

                <!-- Referensi -->
                <div class="md:col-span-2">
                    <label class="text-sm text-secondary-500">Referensi</label>
                    <div class="mt-1">
                        @if($refund->pencairanDana)
                            <div class="bg-secondary-50 rounded-xl p-4">
                                <div class="text-sm text-secondary-500 mb-1">Pencairan Dana</div>
                                <a href="{{ route('pencairan-dana.show', $refund->pencairanDana) }}" class="text-primary-600 hover:underline font-semibold">
                                    {{ $refund->pencairanDana->nomor_pencairan }}
                                </a>
                                <div class="text-sm text-secondary-700 mt-1">
                                    {{ $refund->pencairanDana->pengajuanDana->uraian ?? '-' }}
                                </div>
                            </div>
                        @elseif($refund->pengajuanDana)
                            <div class="bg-secondary-50 rounded-xl p-4">
                                <div class="text-sm text-secondary-500 mb-1">Pengajuan Dana</div>
                                <a href="{{ route('pengajuan-dana.show', $refund->pengajuanDana) }}" class="text-primary-600 hover:underline font-semibold">
                                    {{ $refund->pengajuanDana->nomor_pengajuan }}
                                </a>
                                <div class="text-sm text-secondary-700 mt-1">
                                    {{ $refund->pengajuanDana->uraian }}
                                </div>
                            </div>
                        @else
                            <span class="text-secondary-700">-</span>
                        @endif
                    </div>
                </div>

                <div>
                    <label class="text-sm text-secondary-500">Jumlah Refund</label>
                    <div class="mt-1 text-2xl font-bold text-primary-600">{{ formatRupiah($refund->jumlah_refund) }}</div>
                </div>
                <div>
                    <label class="text-sm text-secondary-500">Rekening Tujuan</label>
                    <div class="mt-1 text-secondary-900">{{ $refund->rekening_tujuan ?? '-' }}</div>
                </div>
                <div class="md:col-span-2">
                    <label class="text-sm text-secondary-500">Alasan Refund</label>
                    <div class="mt-1 text-secondary-900">{{ $refund->alasan_refund }}</div>
                </div>

                <!-- Bukti Transfer -->
                @if($refund->bukti_transfer)
                    <div class="md:col-span-2">
                        <label class="text-sm text-secondary-500">Bukti Transfer</label>
                        <div class="mt-1">
                            <a href="{{ asset('storage/' . $refund->bukti_transfer) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-secondary-100 hover:bg-secondary-200 rounded-xl transition-colors">
                                <svg class="w-5 h-5 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                                Lihat Bukti Transfer
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Approval Info -->
        @if($refund->catatan_approval)
            <div class="bg-white rounded-2xl shadow-soft p-8 mb-6 @if($refund->status === 'rejected') border-l-4 border-red-500 @else border-l-4 border-green-500 @endif">
                <h2 class="text-lg font-semibold text-secondary-900 mb-4">
                    @if($refund->status === 'rejected') Catatan Penolakan @else Catatan Approval @endif
                </h2>
                <p class="text-secondary-700">{{ $refund->catatan_approval }}</p>
                <div class="mt-4 text-sm text-secondary-500">
                    Oleh: {{ $refund->approvedBy->name ?? '-' }}
                </div>
            </div>
        @endif

        <!-- Process Info -->
        @if($refund->status === 'processed' && $refund->tanggal_transfer)
            <div class="bg-white rounded-2xl shadow-soft p-8 mb-6 border-l-4 border-blue-500">
                <h2 class="text-lg font-semibold text-secondary-900 mb-4">Informasi Proses</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm text-secondary-500">Tanggal Transfer</label>
                        <div class="mt-1 font-medium text-secondary-900">{{ \Carbon\Carbon::parse($refund->tanggal_transfer)->format('d F Y') }}</div>
                    </div>
                    <div>
                        <label class="text-sm text-secondary-500">Status</label>
                        <div class="mt-1">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-700">
                                Diproses
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Audit Info -->
        <div class="flex items-center justify-between text-sm text-secondary-500">
            <div>Dibuat oleh: {{ $refund->createdBy->name ?? '-' }}</div>
            <div>{{ \Carbon\Carbon::parse($refund->created_at)->format('d F Y, H:i') }}</div>
        </div>
    </div>

    <!-- Approve/Reject Modal -->
    @if($refund->status === 'menunggu_approval' && auth()->user()->hasAnyRole(['direktur_keuangan', 'direktur_utama']))
        <div x-data="{ open: false }" x-show="open" x-transition class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="open = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form method="POST" action="{{ route('refund.approve', $refund) }}">
                        @csrf
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg font-medium text-secondary-900 mb-4" id="modal-title">Approval Refund</h3>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-secondary-700 mb-2">Keputusan</label>
                                <select name="status" required class="w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                    <option value="approved">Setujui</option>
                                    <option value="rejected">Tolak</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-secondary-700 mb-2">Catatan <span class="text-red-500">(Wajib jika ditolak)</span></label>
                                <textarea name="catatan_approval" rows="3" class="w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="Tambahkan catatan approval..."></textarea>
                            </div>
                        </div>
                        <div class="bg-secondary-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-primary-600 text-base font-medium text-white hover:bg-primary-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                                Simpan
                            </button>
                            <button type="button" @click="open = false" class="mt-3 w-full inline-flex justify-center rounded-xl border border-secondary-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-secondary-700 hover:bg-secondary-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Process Modal -->
    @if($refund->status === 'approved' && auth()->user()->hasAnyRole(['direktur_keuangan', 'staff_keuangan']))
        <div x-data="{ open: false }" x-show="open" x-transition class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="open = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form method="POST" action="{{ route('refund.process', $refund) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg font-medium text-secondary-900 mb-4" id="modal-title">Proses Transfer Refund</h3>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-secondary-700 mb-2">Tanggal Transfer <span class="text-red-500">*</span></label>
                                <input type="date" name="tanggal_transfer" required class="w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-secondary-700 mb-2">Bukti Transfer <span class="text-red-500">*</span></label>
                                <input type="file" name="bukti_transfer" required accept=".pdf,.jpg,.jpeg,.png" class="w-full px-4 py-3 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            </div>
                        </div>
                        <div class="bg-secondary-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-primary-600 text-base font-medium text-white hover:bg-primary-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                                Proses
                            </button>
                            <button type="button" @click="open = false" class="mt-3 w-full inline-flex justify-center rounded-xl border border-secondary-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-secondary-700 hover:bg-secondary-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</x-app-layout>
