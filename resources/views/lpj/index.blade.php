<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">Laporan Pertanggung Jawaban</h1>
                <p class="text-sm text-gray-500 mt-0.5">Kelola LPJ untuk pencairan dana yang telah dilakukan</p>
            </div>
            @if(auth()->user()->hasAnyRole(['kepala_divisi', 'staff_divisi']))
                <a href="{{ route('lpj.select-pengajuan') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Buat LPJ Baru
                </a>
            @endif
        </div>
    </x-slot>

    <!-- Quick Stats -->
    <div class="grid grid-cols-4 gap-3 mb-4">
        <div class="bg-white rounded-xl border border-blue-100 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xl font-semibold text-gray-900">{{ $statistics['total_count'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500">Total LPJ</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-blue-100 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-amber-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xl font-semibold text-gray-900">{{ $statistics['pending_count'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500">Menunggu Verifikasi</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-blue-100 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-emerald-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xl font-semibold text-gray-900">{{ $statistics['approved_count'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500">Disetujui</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-blue-100 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-cyan-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-900">{{ formatRupiah($statistics['total_amount'] ?? 0) }}</p>
                    <p class="text-xs text-gray-500">Total Nilai</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-xl border border-blue-100 p-3 mb-4">
        <form method="GET" action="{{ route('lpj.index') }}" class="flex flex-wrap items-center gap-3">
            <div class="min-w-[140px]">
                <select name="periode_anggaran_id" class="w-full px-3 py-2 text-sm border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all bg-white">
                    <option value="">Semua Periode</option>
                    @foreach($periodeAnggarans ?? [] as $periode)
                        <option value="{{ $periode->id }}" {{ request('periode_anggaran_id') == $periode->id ? 'selected' : '' }}>
                            {{ $periode->nama_periode }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="min-w-[200px] flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor LPJ..." class="w-full px-3 py-2 text-sm border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
            </div>
            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                Filter
            </button>
            @if(request()->anyFilled(['periode_anggaran_id', 'search']))
                <a href="{{ route('lpj.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm font-medium rounded-lg transition-colors">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Tabs -->
    <div class="bg-white rounded-xl border border-blue-100 overflow-hidden mb-4">
        <div class="flex flex-wrap border-b border-blue-100 overflow-x-auto">
            <button onclick="showTab('draft')" id="tab-draft" class="flex-1 min-w-[100px] px-3 py-3 text-sm font-semibold border-b-2 border-gray-500 text-gray-600 bg-gray-50 transition-colors">
                <div class="flex items-center justify-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="hidden md:inline">Draft</span>
                    <span class="bg-gray-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $stats['draft'] ?? 0 }}</span>
                </div>
            </button>
            <button onclick="showTab('menunggu-verifikasi')" id="tab-menunggu-verifikasi" class="flex-1 min-w-[100px] px-3 py-3 text-sm font-semibold border-b-2 border-transparent text-gray-600 hover:bg-blue-50 transition-colors">
                <div class="flex items-center justify-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="hidden md:inline">Verifikasi</span>
                    <span class="bg-amber-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $stats['menunggu_verifikasi'] ?? 0 }}</span>
                </div>
            </button>
            <button onclick="showTab('approved')" id="tab-approved" class="flex-1 min-w-[100px] px-3 py-3 text-sm font-semibold border-b-2 border-transparent text-gray-600 hover:bg-blue-50 transition-colors">
                <div class="flex items-center justify-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="hidden md:inline">Disetujui</span>
                    <span class="bg-emerald-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $stats['approved'] ?? 0 }}</span>
                </div>
            </button>
            <button onclick="showTab('revisi')" id="tab-revisi" class="flex-1 min-w-[100px] px-3 py-3 text-sm font-semibold border-b-2 border-transparent text-gray-600 hover:bg-blue-50 transition-colors">
                <div class="flex items-center justify-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span class="hidden md:inline">Revisi</span>
                    <span class="bg-amber-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $stats['revisi'] ?? 0 }}</span>
                </div>
            </button>
            <button onclick="showTab('rejected')" id="tab-rejected" class="flex-1 min-w-[100px] px-3 py-3 text-sm font-semibold border-b-2 border-transparent text-gray-600 hover:bg-blue-50 transition-colors">
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
        @if($lpjsDraft->count() > 0)
            <div class="bg-white rounded-xl border border-blue-100 overflow-hidden">
                @include('lpj.partials.table', ['lpjs' => $lpjsDraft])
            </div>
        @else
            <div class="bg-white rounded-xl border border-blue-100 p-12 text-center">
                <div class="w-12 h-12 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <p class="text-gray-700 font-medium">Tidak Ada Draft</p>
                <p class="text-gray-400 text-sm mt-1">LPJ draft akan ditampilkan di sini.</p>
            </div>
        @endif
    </div>

    <!-- Tab Content: Menunggu Verifikasi -->
    <div id="content-menunggu-verifikasi" class="tab-content hidden">
        @if($lpjsMenungguVerifikasi->count() > 0)
            <div class="bg-white rounded-xl border border-blue-100 overflow-hidden">
                @include('lpj.partials.table', ['lpjs' => $lpjsMenungguVerifikasi])
            </div>
        @else
            <div class="bg-white rounded-xl border border-blue-100 p-12 text-center">
                <div class="w-12 h-12 bg-amber-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="text-gray-700 font-medium">Tidak Ada LPJ Menunggu Verifikasi</p>
                <p class="text-gray-400 text-sm mt-1">LPJ yang menunggu verifikasi akan ditampilkan di sini.</p>
            </div>
        @endif
    </div>

    <!-- Tab Content: Disetujui -->
    <div id="content-approved" class="tab-content hidden">
        @if($lpjsApproved->count() > 0)
            <div class="bg-white rounded-xl border border-blue-100 overflow-hidden">
                @include('lpj.partials.table', ['lpjs' => $lpjsApproved])
            </div>
        @else
            <div class="bg-white rounded-xl border border-blue-100 p-12 text-center">
                <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="text-gray-700 font-medium">Belum Ada LPJ Disetujui</p>
                <p class="text-gray-400 text-sm mt-1">LPJ yang disetujui akan ditampilkan di sini.</p>
            </div>
        @endif
    </div>

    <!-- Tab Content: Revisi -->
    <div id="content-revisi" class="tab-content hidden">
        @if($lpjsRevisi->count() > 0)
            <div class="bg-white rounded-xl border border-blue-100 overflow-hidden">
                @include('lpj.partials.table', ['lpjs' => $lpjsRevisi])
            </div>
        @else
            <div class="bg-white rounded-xl border border-blue-100 p-12 text-center">
                <div class="w-12 h-12 bg-amber-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </div>
                <p class="text-gray-700 font-medium">Tidak Ada LPJ Perlu Revisi</p>
                <p class="text-gray-400 text-sm mt-1">LPJ yang perlu revisi akan ditampilkan di sini.</p>
            </div>
        @endif
    </div>

    <!-- Tab Content: Ditolak -->
    <div id="content-rejected" class="tab-content hidden">
        @if($lpjsRejected->count() > 0)
            <div class="bg-white rounded-xl border border-blue-100 overflow-hidden">
                @include('lpj.partials.table', ['lpjs' => $lpjsRejected])
            </div>
        @else
            <div class="bg-white rounded-xl border border-blue-100 p-12 text-center">
                <div class="w-12 h-12 bg-red-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="text-gray-700 font-medium">Tidak Ada LPJ Ditolak</p>
                <p class="text-gray-400 text-sm mt-1">LPJ yang ditolak akan ditampilkan di sini.</p>
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
                el.classList.remove('border-gray-500', 'border-amber-500', 'border-emerald-500', 'border-red-500', 'text-gray-600', 'text-amber-600', 'text-emerald-600', 'text-red-600', 'bg-gray-50', 'bg-amber-50', 'bg-emerald-50', 'bg-red-50');
                el.classList.add('border-transparent', 'text-gray-600');
            });

            // Show selected tab content
            document.getElementById('content-' + tabName).classList.remove('hidden');

            // Set active state for selected tab
            var activeTab = document.getElementById('tab-' + tabName);
            activeTab.classList.remove('border-transparent', 'text-gray-600');

            if (tabName === 'draft') {
                activeTab.classList.add('border-gray-500', 'text-gray-600', 'bg-gray-50');
            } else if (tabName === 'menunggu-verifikasi') {
                activeTab.classList.add('border-amber-500', 'text-amber-600', 'bg-amber-50');
            } else if (tabName === 'approved') {
                activeTab.classList.add('border-emerald-500', 'text-emerald-600', 'bg-emerald-50');
            } else if (tabName === 'revisi') {
                activeTab.classList.add('border-amber-500', 'text-amber-600', 'bg-amber-50');
            } else if (tabName === 'rejected') {
                activeTab.classList.add('border-red-500', 'text-red-600', 'bg-red-50');
            }
        }
    </script>
</x-app-layout>
