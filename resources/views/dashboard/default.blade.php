<x-app-layout>
    <x-slot name="header">
        <x-dashboard.header :userName="Auth::user()->name" />
    </x-slot>

    <div class="space-y-4">
        <!-- Quick Stats -->
        <div class="grid grid-cols-4 gap-3">
            <!-- Total Pengajuan -->
            <a href="{{ route('pengajuan-dana.index') }}" class="bg-white rounded-xl border border-blue-100 p-4 hover:border-blue-200 transition-all">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xl font-semibold text-gray-900">{{ App\Models\PengajuanDana::count() }}</p>
                        <p class="text-xs text-gray-500">Total Pengajuan</p>
                    </div>
                </div>
            </a>

            <!-- Menunggu Approval -->
            <div class="bg-white rounded-xl border border-blue-100 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-amber-50 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xl font-semibold text-gray-900">{{ App\Models\PengajuanDana::where('status', 'menunggu_approval')->count() }}</p>
                        <p class="text-xs text-gray-500">Menunggu Approval</p>
                    </div>
                </div>
            </div>

            <!-- Disetujui -->
            <div class="bg-white rounded-xl border border-blue-100 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-emerald-50 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xl font-semibold text-gray-900">{{ App\Models\PengajuanDana::where('status', 'disetujui')->count() }}</p>
                        <p class="text-xs text-gray-500">Disetujui</p>
                    </div>
                </div>
            </div>

            <!-- Active Users -->
            <div class="bg-white rounded-xl border border-blue-100 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-cyan-50 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xl font-semibold text-gray-900">{{ App\Models\User::where('is_active', true)->count() }}</p>
                        <p class="text-xs text-gray-500">Active Users</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-3 gap-4">
            <div class="col-span-2">
                <div class="bg-white rounded-xl border border-blue-100">
                    <div class="px-4 py-3 border-b border-blue-100">
                        <span class="text-sm font-semibold text-gray-900">Quick Actions</span>
                    </div>
                    <div class="p-4">
                        <div class="grid grid-cols-4 gap-3">
                            <a href="{{ route('pengajuan-dana.create') }}" class="flex flex-col items-center p-3 bg-emerald-50 rounded-xl hover:bg-emerald-100 transition-colors">
                                <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center mb-2">
                                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                </div>
                                <span class="text-xs font-medium text-gray-700">Create Pengajuan</span>
                            </a>

                            <a href="{{ route('pengajuan-dana.index') }}" class="flex flex-col items-center p-3 bg-blue-50 rounded-xl hover:bg-blue-100 transition-colors">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mb-2">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <span class="text-xs font-medium text-gray-700">My Pengajuan</span>
                            </a>

                            <a href="{{ route('lpj.index') }}" class="flex flex-col items-center p-3 bg-amber-50 rounded-xl hover:bg-amber-100 transition-colors">
                                <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center mb-2">
                                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <span class="text-xs font-medium text-gray-700">My LPJ</span>
                            </a>

                            <a href="{{ route('reports.refund') }}" class="flex flex-col items-center p-3 bg-violet-50 rounded-xl hover:bg-violet-100 transition-colors">
                                <div class="w-10 h-10 bg-violet-100 rounded-lg flex items-center justify-center mb-2">
                                    <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                </div>
                                <span class="text-xs font-medium text-gray-700">Refund Report</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-blue-600 to-cyan-500 rounded-xl">
                <div class="px-4 py-3 border-b border-blue-400">
                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-white text-blue-600">NEW</span>
                </div>
                <div class="p-4">
                    <p class="text-sm font-semibold text-white mb-2">Get Started</p>
                    <p class="text-blue-100 text-xs mb-4">Manage your budget workflow efficiently with our comprehensive tools.</p>
                    <a href="{{ route('pengajuan-dana.create') }}" class="w-full px-4 py-2 bg-white text-blue-600 text-sm font-medium rounded-lg hover:bg-blue-50 transition-colors text-center">Explore</a>
                </div>
            </div>
        </div>

        <!-- Status Panel & Help -->
        <div class="grid grid-cols-2 gap-4">
            <!-- System Status -->
            <div class="bg-white rounded-xl border border-blue-100">
                <div class="px-4 py-3 border-b border-blue-100">
                    <span class="text-sm font-semibold text-gray-900">System Status</span>
                </div>
                <div class="p-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-sm text-gray-600">Application Status</span>
                        </div>
                        <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-emerald-100 text-emerald-700">Online</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                            </svg>
                            <span class="text-sm text-gray-600">Database</span>
                        </div>
                        <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-blue-100 text-blue-700">Active</span>
                    </div>
                </div>
            </div>

            <!-- Need Help -->
            <div class="bg-white rounded-xl border border-blue-100">
                <div class="px-4 py-3 border-b border-blue-100">
                    <span class="text-sm font-semibold text-gray-900">Need Help?</span>
                </div>
                <div class="p-4">
                    <p class="text-gray-600 text-sm mb-4">Contact your administrator or IT support for assistance with the eBudget system.</p>
                    <div class="flex items-center gap-3 p-3 bg-blue-50 rounded-xl">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Email Support</p>
                            <p class="text-xs text-gray-500">support@ebudget.local</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
