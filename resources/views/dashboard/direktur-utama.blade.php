<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black gradient-text">Dashboard Direktur Utama</h1>
                <p class="text-secondary-600 mt-1">Monitoring dan approval level tertinggi untuk semua pengajuan dana</p>
            </div>
            <div class="flex items-center space-x-4">
                <!-- Date Display -->
                <div class="hidden sm:flex items-center space-x-2 bg-white/60 backdrop-blur-sm px-4 py-2 rounded-xl border border-secondary-200/50">
                    <svg class="w-4 h-4 text-secondary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="text-sm font-semibold text-secondary-900">{{ now()->locale('id')->isoFormat('DD MMMM YYYY') }}</span>
                </div>

                <!-- Quick Stats -->
                <div class="flex items-center space-x-2">
                    <div class="w-2 h-2 bg-success-500 rounded-full animate-pulse-soft"></div>
                    <span class="text-xs text-secondary-600">Live Data</span>
                </div>
            </div>
        </div>
    </x-slot>

        <!-- Executive Overview -->
        <div class="relative mb-8">
            <!-- Background Pattern -->
            <div class="absolute inset-0 bg-gradient-to-br from-primary-600 via-accent-600 to-purple-700 rounded-3xl overflow-hidden">
                <div class="absolute inset-0 bg-black/10"></div>
                <div class="absolute -top-40 -right-40 w-80 h-80 bg-white/5 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-20 -left-20 w-60 h-60 bg-white/10 rounded-full blur-2xl"></div>
            </div>

            <div class="relative p-8 lg:p-12">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="w-3 h-3 bg-success-400 rounded-full animate-pulse-soft"></div>
                            <span class="text-white/90 text-sm font-medium tracking-wide">SYSTEM ACTIVE</span>
                        </div>
                        <h2 class="text-4xl lg:text-5xl font-black text-white mb-4 leading-tight">
                            Executive<br>
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-white to-primary-100">
                                Command Center
                            </span>
                        </h2>
                        <p class="text-primary-100 text-lg lg:text-xl mb-8 max-w-2xl leading-relaxed">
                            Sistem monitoring dan pengelolaan anggaran perusahaan dengan real-time analytics dan intelligent approvals
                        </p>

                        <!-- Feature Pills -->
                        <div class="flex flex-wrap gap-3">
                            <div class="inline-flex items-center space-x-2 bg-white/20 backdrop-blur-sm border border-white/30 rounded-full px-4 py-2">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                                <span class="text-white text-sm font-medium">Real-time Updates</span>
                            </div>
                            <div class="inline-flex items-center space-x-2 bg-white/20 backdrop-blur-sm border border-white/30 rounded-full px-4 py-2">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-white text-sm font-medium">Secure Processing</span>
                            </div>
                            <div class="inline-flex items-center space-x-2 bg-white/20 backdrop-blur-sm border border-white/30 rounded-full px-4 py-2">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                                </svg>
                                <span class="text-white text-sm font-medium">Advanced Analytics</span>
                            </div>
                        </div>
                    </div>

                    <!-- Approval Rate Card -->
                    <div class="lg:w-80">
                        <div class="bg-white/10 backdrop-blur-lg border border-white/20 rounded-2xl p-6 relative overflow-hidden group">
                            <div class="absolute inset-0 bg-gradient-to-br from-white/5 to-transparent transform scale-0 group-hover:scale-100 transition-transform duration-500 rounded-2xl"></div>
                            <div class="relative">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-white/90 text-sm font-medium uppercase tracking-wider">Performance</h3>
                                    <div class="w-8 h-8 bg-success-500/20 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-success-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="text-5xl font-black text-white mb-2">
                                    {{ $data['totalPagu'] > 0 ? round(($data['pengajuanDisetujui'] / max($data['pengajuanDisetujui'] + $data['pengajuanDitolak'] + $data['pengajuanMenunggu'], 1)) * 100, 1) : 0 }}%
                                </div>
                                <div class="text-primary-100 text-sm mb-4">Approval Rate</div>

                                <!-- Mini Progress Bar -->
                                <div class="w-full bg-white/20 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-success-400 to-success-300 h-2 rounded-full transition-all duration-1000" style="width: {{ $data['totalPagu'] > 0 ? round(($data['pengajuanDisetujui'] / max($data['pengajuanDisetujui'] + $data['pengajuanDitolak'] + $data['pengajuanMenunggu'], 1)) * 100, 1) : 0 }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Key Metrics -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 lg:gap-6 mb-8">
            <!-- Total Pagu -->
            <div class="stat-card primary">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <div class="text-xs font-bold text-secondary-500 uppercase tracking-wider mb-1">Total Pagu</div>
                        <div class="text-2xl lg:text-3xl font-black text-secondary-900 leading-tight">
                            Rp {{ number_format($data['totalPagu'], 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl p-3 lg:p-4 shadow-medium group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-5 h-5 lg:w-6 lg:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2zm0 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2z" />
                        </svg>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="flex-1 bg-primary-100 rounded-full h-1.5">
                        <div class="bg-gradient-to-r from-primary-500 to-primary-600 h-1.5 rounded-full" style="width: 75%"></div>
                    </div>
                    <span class="text-xs text-secondary-500 font-medium">{{ now()->year }}</span>
                </div>
            </div>

            <!-- Menunggu Approval -->
            <div class="stat-card warning">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <div class="text-xs font-bold text-secondary-500 uppercase tracking-wider mb-1">Menunggu</div>
                        <div class="text-2xl lg:text-3xl font-black text-warning-600 leading-tight">
                            {{ $data['pengajuanMenunggu'] }}
                        </div>
                        <div class="text-xs text-secondary-500 mt-1">Pengajuan</div>
                    </div>
                    <div class="bg-gradient-to-br from-warning-500 to-orange-600 rounded-xl p-3 lg:p-4 shadow-medium group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-5 h-5 lg:w-6 lg:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-2 h-2 bg-warning-500 rounded-full animate-pulse-soft"></div>
                    <span class="text-xs text-warning-600 font-medium">Need Action</span>
                </div>
            </div>

            <!-- Disetujui -->
            <div class="stat-card success">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <div class="text-xs font-bold text-secondary-500 uppercase tracking-wider mb-1">Disetujui</div>
                        <div class="text-2xl lg:text-3xl font-black text-success-600 leading-tight">
                            {{ $data['pengajuanDisetujui'] }}
                        </div>
                        <div class="text-xs text-secondary-500 mt-1">Pengajuan</div>
                    </div>
                    <div class="bg-gradient-to-br from-success-500 to-emerald-600 rounded-xl p-3 lg:p-4 shadow-medium group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-5 h-5 lg:w-6 lg:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-2 h-2 bg-success-500 rounded-full"></div>
                    <span class="text-xs text-success-600 font-medium">Completed</span>
                </div>
            </div>

            <!-- Ditolak -->
            <div class="stat-card danger">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <div class="text-xs font-bold text-secondary-500 uppercase tracking-wider mb-1">Ditolak</div>
                        <div class="text-2xl lg:text-3xl font-black text-danger-600 leading-tight">
                            {{ $data['pengajuanDitolak'] }}
                        </div>
                        <div class="text-xs text-secondary-500 mt-1">Pengajuan</div>
                    </div>
                    <div class="bg-gradient-to-br from-danger-500 to-red-600 rounded-xl p-3 lg:p-4 shadow-medium group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-5 h-5 lg:w-6 lg:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-2 h-2 bg-danger-500 rounded-full"></div>
                    <span class="text-xs text-danger-600 font-medium">Rejected</span>
                </div>
            </div>

            <!-- Pencairan Pending -->
            <div class="stat-card">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <div class="text-xs font-bold text-secondary-500 uppercase tracking-wider mb-1">Pencairan</div>
                        <div class="text-2xl lg:text-3xl font-black text-blue-600 leading-tight">
                            {{ $data['pencairanPending'] }}
                        </div>
                        <div class="text-xs text-secondary-500 mt-1">Pending</div>
                    </div>
                    <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl p-3 lg:p-4 shadow-medium group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-5 h-5 lg:w-6 lg:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
                    <span class="text-xs text-blue-600 font-medium">Processing</span>
                </div>
            </div>
        </div>

        <!-- High Value Pengajuan Alert -->
        @if($data['highValuePengajuan']->count() > 0)
            <div class="relative mb-8">
                <!-- Alert Header -->
                <div class="absolute inset-0 bg-gradient-to-r from-danger-500 via-red-600 to-danger-600 rounded-2xl opacity-90"></div>
                <div class="relative bg-white/10 backdrop-blur-lg border border-danger-200/30 rounded-2xl p-1">
                    <div class="bg-white rounded-xl p-6">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center space-x-4">
                                <div class="relative">
                                    <div class="absolute inset-0 bg-danger-500 rounded-full animate-ping"></div>
                                    <div class="relative bg-danger-500 rounded-full p-3">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.694 0L3.34 16c-.77.833-1.964.833-2.694 0L3.34 7c-.77.833.192-2.694 1.732-2.5L12.998 3c.77-.833 1.964-.833 2.694 0l6.938 6.938c.77.833 1.964.833 2.694 0l1.732-2.5c.77-.833.192-2.694-1.732-2.5z" />
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-xl font-black text-danger-800">High Value Transactions</h3>
                                    <p class="text-danger-600 text-sm mt-1">Pengajuan di atas 50 juta memerlukan persetujuan Anda</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <span class="bg-danger-100 text-danger-800 px-4 py-2 rounded-full text-sm font-bold border-2 border-danger-200">
                                    {{ $data['highValuePengajuan']->count() }} Transactions
                                </span>
                                <span class="text-danger-500 text-sm">
                                    Total: Rp {{ number_format($data['highValuePengajuan']->sum('total_pengajuan'), 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        <!-- Modern Table -->
                        <div class="overflow-x-auto">
                            <table class="table-modern">
                                <thead>
                                    <tr>
                                        <th class="rounded-tl-xl">Nomor</th>
                                        <th>Judul Pengajuan</th>
                                        <th>Divisi</th>
                                        <th>Pengaju</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th class="rounded-tr-xl">Quick Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data['highValuePengajuan'] as $index => $pengajuan)
                                        <tr class="group">
                                            <td class="font-medium">
                                                <span class="inline-flex items-center px-2 py-1 rounded-lg bg-danger-100 text-danger-800 text-xs font-bold">
                                                    {{ $pengajuan->nomor_pengajuan ?? '-' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="max-w-xs">
                                                    <p class="font-medium text-secondary-900 truncate">{{ $pengajuan->judul_pengajuan }}</p>
                                                    <p class="text-xs text-secondary-500 mt-1">{{ \Carbon\Carbon::parse($pengajuan->created_at)->locale('id')->isoFormat('DD MMM YYYY') }}</p>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="flex items-center space-x-2">
                                                    <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center">
                                                        <span class="text-xs font-bold text-primary-600">{{ substr($pengajuan->divisi->nama_divisi ?? 'U', 0, 1) }}</span>
                                                    </div>
                                                    <span class="text-sm font-medium">{{ $pengajuan->divisi->nama_divisi ?? '-' }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="flex items-center space-x-2">
                                                    <div class="w-6 h-6 bg-secondary-100 rounded-full flex items-center justify-center">
                                                        <span class="text-xs font-bold text-secondary-600">{{ substr($pengajuan->user->full_name ?? 'U', 0, 1) }}</span>
                                                    </div>
                                                    <span class="text-sm">{{ $pengajuan->user->full_name ?? '-' }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="font-black text-danger-600">Rp {{ number_format($pengajuan->total_pengajuan, 0, ',', '.') }}</div>
                                                <div class="text-xs text-danger-500 mt-1">{{ round(($pengajuan->total_pengajuan / 50000000) - 1, 1) }}x limit</div>
                                            </td>
                                            <td>
                                                @php
                                                    $statusClass = [
                                                        'menunggu_approval' => 'pill-gradient animate-pulse',
                                                        'disetujui' => 'bg-success-100 text-success-800',
                                                        'ditolak' => 'bg-danger-100 text-danger-800',
                                                    ][$pengajuan->status] ?? 'bg-secondary-100 text-secondary-800';
                                                @endphp
                                                <span class="pill {{ $statusClass }}">
                                                    {{ ucfirst(str_replace('_', ' ', $pengajuan->status)) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="flex items-center space-x-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                                    <button class="btn btn-sm bg-white hover:bg-primary-50 text-primary-600 border border-primary-200 hover:border-primary-300 hover:shadow-soft transition-all duration-200">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                    </button>
                                                    <button class="btn btn-sm bg-success-500 hover:bg-success-600 text-white shadow-soft hover:shadow-medium transition-all duration-200">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    </button>
                                                    <button class="btn btn-sm bg-danger-500 hover:bg-danger-600 text-white shadow-soft hover:shadow-medium transition-all duration-200">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Pending Approvals & Quick Actions -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-8 mb-8">
            <!-- Pending Approvals -->
            <div class="card">
                <div class="card-header">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-secondary-900">Pending Approvals</h3>
                        <span class="bg-warning-100 text-warning-800 px-3 py-1 rounded-full text-sm font-semibold">
                            {{ $data['pendingApprovals']->count() }} Items
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    @if($data['pendingApprovals']->count() > 0)
                        <div class="space-y-4">
                            @foreach($data['pendingApprovals'] as $approval)
                                <div class="flex items-start space-x-4 p-4 bg-warning-50/50 rounded-xl hover:bg-warning-100/50 transition-colors cursor-pointer card-hover">
                                    <div class="flex-shrink-0 bg-warning-100 rounded-full p-2 mt-1">
                                        <svg class="w-5 h-5 text-warning-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-bold text-secondary-900">
                                                {{ $approval->pengajuanDana->nomor_pengajuan ?? '-' }}
                                            </p>
                                            <span class="text-xs text-warning-600 font-medium">{{ $approval->level }}</span>
                                        </div>
                                        <p class="text-sm text-secondary-700 mt-1">{{ $approval->pengajuanDana->judul_pengajuan }}</p>
                                        <p class="text-xs text-secondary-500 mt-2">
                                            Menunggu approval: <span class="font-semibold">{{ $approval->approver->full_name ?? 'N/A' }}</span>
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-16 h-16 text-success-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-secondary-500 font-medium">Tidak ada approval pending</p>
                            <p class="text-secondary-400 text-sm mt-1">Semua pengajuan telah diproses</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-bold text-secondary-900">Quick Actions</h3>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-1 gap-4">
                        <a href="{{ route('approvals.index') }}" class="flex items-center p-5 bg-gradient-to-r from-primary-50 to-primary-100/50 rounded-xl hover:from-primary-100 hover:to-primary-200/50 transition-all duration-300 group card-hover">
                            <div class="flex-shrink-0 bg-primary-600 rounded-xl p-4 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                </svg>
                            </div>
                            <div class="ml-5">
                                <h4 class="text-base font-bold text-secondary-900 group-hover:text-primary-700">All Approvals</h4>
                                <p class="text-sm text-secondary-600 mt-1">Kelola semua persetujuan</p>
                            </div>
                        </a>

                        <a href="{{ route('reports.index') }}" class="flex items-center p-5 bg-gradient-to-r from-accent-50 to-accent-100/50 rounded-xl hover:from-accent-100 hover:to-accent-200/50 transition-all duration-300 group card-hover">
                            <div class="flex-shrink-0 bg-accent-600 rounded-xl p-4 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <div class="ml-5">
                                <h4 class="text-base font-bold text-secondary-900 group-hover:text-accent-700">Generate Reports</h4>
                                <p class="text-sm text-secondary-600 mt-1">Download laporan lengkap</p>
                            </div>
                        </a>

                        <a href="#" class="flex items-center p-5 bg-gradient-to-r from-success-50 to-success-100/50 rounded-xl hover:from-success-100 hover:to-success-200/50 transition-all duration-300 group card-hover">
                            <div class="flex-shrink-0 bg-success-600 rounded-xl p-4 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                            </div>
                            <div class="ml-5">
                                <h4 class="text-base font-bold text-secondary-900 group-hover:text-success-700">Buat Pengajuan</h4>
                                <p class="text-sm text-secondary-600 mt-1">Ajukan dana baru</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>