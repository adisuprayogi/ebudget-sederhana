<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-secondary-900">Pengembalian Dana (Refund)</h1>
                <p class="text-secondary-600 mt-1">Kelola pengembalian dana dari pencairan atau pengajuan</p>
            </div>
            @if(auth()->user()->hasAnyRole(['direktur_keuangan', 'direktur_utama', 'kepala_divisi', 'staff_keuangan']))
                <a href="{{ route('refund.select-lpj') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-all duration-200 shadow-soft hover:shadow-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Buat Refund Baru
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-8">
        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl p-6 text-white shadow-soft">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Total Refund</p>
                        <p class="text-3xl font-bold mt-1">{{ $statistics['total_count'] ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-amber-400 to-amber-600 rounded-2xl p-6 text-white shadow-soft">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-amber-100 text-sm font-medium">Menunggu Approval</p>
                        <p class="text-3xl font-bold mt-1">{{ $statistics['pending_count'] ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-400 to-green-600 rounded-2xl p-6 text-white shadow-soft">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">Disetujui/Selesai</p>
                        <p class="text-3xl font-bold mt-1">{{ ($statistics['approved_count'] ?? 0) + ($statistics['processed_count'] ?? 0) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-indigo-400 to-indigo-600 rounded-2xl p-6 text-white shadow-soft">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-indigo-100 text-sm font-medium">Total Nilai</p>
                        <p class="text-2xl font-bold mt-1">{{ formatRupiah($statistics['total_amount'] ?? 0) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-2xl shadow-soft p-6 mb-6">
            <form method="GET" action="{{ route('refund.index') }}" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-secondary-700 mb-2">Cari</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nomor atau alasan refund..." class="w-full px-4 py-2 border border-secondary-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 3.293A1 1 0 013 2.586V4z" />
                        </svg>
                        Filter
                    </button>
                    @if(request()->filled('search'))
                    <a href="{{ route('refund.index') }}" class="px-4 py-2 border border-secondary-200 text-secondary-600 rounded-xl hover:bg-secondary-50 transition-all duration-200 ml-2">
                        Reset
                    </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Tabs -->
        <div class="bg-white rounded-2xl shadow-soft overflow-hidden mb-6">
            <div class="flex flex-wrap border-b border-secondary-200 overflow-x-auto">
                <button onclick="showTab('draft')" id="tab-draft" class="flex-1 min-w-[120px] px-3 py-3 text-sm font-semibold border-b-2 border-slate-500 text-slate-600 bg-slate-50 transition-colors">
                    <div class="flex items-center justify-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span class="hidden md:inline">Draft</span>
                        <span class="bg-slate-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $stats['draft'] ?? 0 }}</span>
                    </div>
                </button>
                <button onclick="showTab('menunggu-approval')" id="tab-menunggu-approval" class="flex-1 min-w-[120px] px-3 py-3 text-sm font-semibold border-b-2 border-transparent text-secondary-600 hover:bg-secondary-50 transition-colors">
                    <div class="flex items-center justify-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="hidden md:inline">Menunggu Approval</span>
                        <span class="bg-amber-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $stats['menunggu_approval'] ?? 0 }}</span>
                    </div>
                </button>
                <button onclick="showTab('approved')" id="tab-approved" class="flex-1 min-w-[120px] px-3 py-3 text-sm font-semibold border-b-2 border-transparent text-secondary-600 hover:bg-secondary-50 transition-colors">
                    <div class="flex items-center justify-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="hidden md:inline">Disetujui</span>
                        <span class="bg-green-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $stats['approved'] ?? 0 }}</span>
                    </div>
                </button>
                <button onclick="showTab('processed')" id="tab-processed" class="flex-1 min-w-[120px] px-3 py-3 text-sm font-semibold border-b-2 border-transparent text-secondary-600 hover:bg-secondary-50 transition-colors">
                    <div class="flex items-center justify-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="hidden md:inline">Selesai</span>
                        <span class="bg-teal-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $stats['processed'] ?? 0 }}</span>
                    </div>
                </button>
                <button onclick="showTab('rejected')" id="tab-rejected" class="flex-1 min-w-[120px] px-3 py-3 text-sm font-semibold border-b-2 border-transparent text-secondary-600 hover:bg-secondary-50 transition-colors">
                    <div class="flex items-center justify-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="hidden md:inline">Ditolak</span>
                        <span class="bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $stats['rejected'] ?? 0 }}</span>
                    </div>
                </button>
            </div>
        </div>

        <!-- Tab Content: Draft -->
        <div id="content-draft" class="tab-content">
            @if($refundsDraft->count() > 0)
                <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                    @include('refund.partials.table', ['refunds' => $refundsDraft])
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-soft p-12 text-center">
                    <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-secondary-900 mb-2">Tidak Ada Draft</h3>
                    <p class="text-secondary-500">Refund draft akan ditampilkan di sini.</p>
                </div>
            @endif
        </div>

        <!-- Tab Content: Menunggu Approval -->
        <div id="content-menunggu-approval" class="tab-content hidden">
            @if($refundsMenungguApproval->count() > 0)
                <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                    @include('refund.partials.table', ['refunds' => $refundsMenungguApproval])
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-soft p-12 text-center">
                    <div class="w-20 h-20 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-secondary-900 mb-2">Tidak Ada Refund Menunggu Approval</h3>
                    <p class="text-secondary-500">Refund yang menunggu approval akan ditampilkan di sini.</p>
                </div>
            @endif
        </div>

        <!-- Tab Content: Disetujui -->
        <div id="content-approved" class="tab-content hidden">
            @if($refundsApproved->count() > 0)
                <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                    @include('refund.partials.table', ['refunds' => $refundsApproved])
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-soft p-12 text-center">
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-secondary-900 mb-2">Belum Ada Refund Disetujui</h3>
                    <p class="text-secondary-500">Refund yang disetujui akan ditampilkan di sini.</p>
                </div>
            @endif
        </div>

        <!-- Tab Content: Selesai -->
        <div id="content-processed" class="tab-content hidden">
            @if($refundsProcessed->count() > 0)
                <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                    @include('refund.partials.table', ['refunds' => $refundsProcessed])
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-soft p-12 text-center">
                    <div class="w-20 h-20 bg-teal-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-secondary-900 mb-2">Belum Ada Refund Selesai</h3>
                    <p class="text-secondary-500">Refund yang selesai akan ditampilkan di sini.</p>
                </div>
            @endif
        </div>

        <!-- Tab Content: Ditolak -->
        <div id="content-rejected" class="tab-content hidden">
            @if($refundsRejected->count() > 0)
                <div class="bg-white rounded-2xl shadow-soft overflow-hidden">
                    @include('refund.partials.table', ['refunds' => $refundsRejected])
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-soft p-12 text-center">
                    <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-secondary-900 mb-2">Tidak Ada Refund Ditolak</h3>
                    <p class="text-secondary-500">Refund yang ditolak akan ditampilkan di sini.</p>
                </div>
            @endif
        </div>

        <script>
            function showTab(tabName) {
                // Hide all tab contents
                document.querySelectorAll('.tab-content').forEach(function(el) {
                    el.classList.add('hidden');
                });

                // Remove active state from all tabs
                document.querySelectorAll('[id^="tab-"]').forEach(function(el) {
                    el.classList.remove('border-slate-500', 'border-amber-500', 'border-green-500', 'border-teal-500', 'border-red-500', 'text-slate-600', 'text-amber-600', 'text-green-600', 'text-teal-600', 'text-red-600', 'bg-slate-50', 'bg-amber-50', 'bg-green-50', 'bg-teal-50', 'bg-red-50');
                    el.classList.add('border-transparent', 'text-secondary-600');
                });

                // Show selected tab content
                document.getElementById('content-' + tabName).classList.remove('hidden');

                // Set active state for selected tab
                var activeTab = document.getElementById('tab-' + tabName);
                activeTab.classList.remove('border-transparent', 'text-secondary-600');

                if (tabName === 'draft') {
                    activeTab.classList.add('border-slate-500', 'text-slate-600', 'bg-slate-50');
                } else if (tabName === 'menunggu-approval') {
                    activeTab.classList.add('border-amber-500', 'text-amber-600', 'bg-amber-50');
                } else if (tabName === 'approved') {
                    activeTab.classList.add('border-green-500', 'text-green-600', 'bg-green-50');
                } else if (tabName === 'processed') {
                    activeTab.classList.add('border-teal-500', 'text-teal-600', 'bg-teal-50');
                } else if (tabName === 'rejected') {
                    activeTab.classList.add('border-red-500', 'text-red-600', 'bg-red-50');
                }
            }
        </script>
    </div>
</x-app-layout>
