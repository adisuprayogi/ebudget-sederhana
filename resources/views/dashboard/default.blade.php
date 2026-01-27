<x-app-layout>
    <x-slot name="header">
        <x-dashboard.header :userName="Auth::user()->name" />
    </x-slot>

    <div class="space-y-5">
        <!-- Mobile Quick Links -->
        <div class="md:hidden bg-white rounded-2xl border border-slate-100 shadow-sm">
            <div class="px-4 py-3 border-b border-slate-100">
                <h3 class="text-xs font-semibold text-slate-900">Quick Links</h3>
            </div>
            <div class="p-3">
                <div class="grid grid-cols-4 gap-2">
                    <a href="{{ route('pengajuan-dana.create') }}" class="flex flex-col items-center p-2 bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-xl hover:from-emerald-100 hover:to-emerald-200 transition-all duration-200">
                        <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center shadow-sm mb-1">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        <span class="text-[10px] font-medium text-slate-700 text-center leading-tight">Create</span>
                    </a>

                    <a href="{{ route('pengajuan-dana.index') }}" class="flex flex-col items-center p-2 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl hover:from-blue-100 hover:to-blue-200 transition-all duration-200">
                        <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center shadow-sm mb-1">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <span class="text-[10px] font-medium text-slate-700 text-center leading-tight">Pengajuan</span>
                    </a>

                    <a href="{{ route('lpj.index') }}" class="flex flex-col items-center p-2 bg-gradient-to-br from-amber-50 to-amber-100 rounded-xl hover:from-amber-100 hover:to-amber-200 transition-all duration-200">
                        <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center shadow-sm mb-1">
                            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <span class="text-[10px] font-medium text-slate-700 text-center leading-tight">LPJ</span>
                    </a>

                    <a href="mailto:support@ebudget.local" class="flex flex-col items-center p-2 bg-gradient-to-br from-violet-50 to-violet-100 rounded-xl hover:from-violet-100 hover:to-violet-200 transition-all duration-200">
                        <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center shadow-sm mb-1">
                            <svg class="w-4 h-4 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <span class="text-[10px] font-medium text-slate-700 text-center leading-tight">Help</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total Pengajuan -->
            <a href="{{ route('pengajuan-dana.index') }}" class="group bg-white rounded-2xl border border-slate-100 p-5 hover:border-blue-200 hover:shadow-lg hover:shadow-blue-100/50 transition-all duration-300">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/20 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ App\Models\PengajuanDana::count() }}</p>
                        <p class="text-xs text-slate-500">Total Pengajuan</p>
                    </div>
                </div>
            </a>

            <!-- Menunggu Approval -->
            <div class="bg-white rounded-2xl border border-slate-100 p-5">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl flex items-center justify-center shadow-lg shadow-amber-500/20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ App\Models\PengajuanDana::where('status', 'menunggu_approval')->count() }}</p>
                        <p class="text-xs text-slate-500">Menunggu Approval</p>
                    </div>
                </div>
            </div>

            <!-- Disetujui -->
            <div class="bg-white rounded-2xl border border-slate-100 p-5">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ App\Models\PengajuanDana::where('status', 'disetujui')->count() }}</p>
                        <p class="text-xs text-slate-500">Disetujui</p>
                    </div>
                </div>
            </div>

            <!-- Active Users -->
            <div class="bg-white rounded-2xl border border-slate-100 p-5">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-xl flex items-center justify-center shadow-lg shadow-cyan-500/20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ App\Models\User::where('is_active', true)->count() }}</p>
                        <p class="text-xs text-slate-500">Active Users</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            <div class="col-span-2 lg:col-span-2">
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
                    <div class="px-6 py-4 border-b border-slate-100">
                        <span class="text-sm font-semibold text-slate-900">Quick Actions</span>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <a href="{{ route('pengajuan-dana.create') }}" class="flex flex-col items-center p-4 bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-xl hover:from-emerald-100 hover:to-emerald-200 transition-all duration-200">
                                <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center mb-3 shadow-sm">
                                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-slate-700">Create Pengajuan</span>
                            </a>

                            <a href="{{ route('pengajuan-dana.index') }}" class="flex flex-col items-center p-4 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl hover:from-blue-100 hover:to-blue-200 transition-all duration-200">
                                <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center mb-3 shadow-sm">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-slate-700">My Pengajuan</span>
                            </a>

                            <a href="{{ route('lpj.index') }}" class="flex flex-col items-center p-4 bg-gradient-to-br from-amber-50 to-amber-100 rounded-xl hover:from-amber-100 hover:to-amber-200 transition-all duration-200">
                                <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center mb-3 shadow-sm">
                                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-slate-700">My LPJ</span>
                            </a>

                            <a href="{{ route('reports.refund') }}" class="flex flex-col items-center p-4 bg-gradient-to-br from-violet-50 to-violet-100 rounded-xl hover:from-violet-100 hover:to-violet-200 transition-all duration-200">
                                <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center mb-3 shadow-sm">
                                    <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-slate-700">Refund Report</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-blue-600 to-cyan-500 rounded-2xl">
                <div class="px-6 py-4 border-b border-blue-400">
                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-white text-blue-600">NEW</span>
                </div>
                <div class="p-5">
                    <p class="text-sm font-semibold text-white mb-2">Get Started</p>
                    <p class="text-blue-100 text-sm mb-4">Manage your budget workflow efficiently with our comprehensive tools.</p>
                    <a href="{{ route('pengajuan-dana.create') }}" class="w-full px-5 py-2.5 bg-white text-blue-600 text-sm font-medium rounded-lg hover:bg-blue-50 transition-colors text-center">Explore</a>
                </div>
            </div>
        </div>

        <!-- Status Panel & Help -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <!-- System Status -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
                <div class="px-6 py-4 border-b border-slate-100">
                    <span class="text-sm font-semibold text-slate-900">System Status</span>
                </div>
                <div class="p-5 space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-sm text-slate-600">Application Status</span>
                        </div>
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700">Online</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                            </svg>
                            <span class="text-sm text-slate-600">Database</span>
                        </div>
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">Active</span>
                    </div>
                </div>
            </div>

            <!-- Need Help -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
                <div class="px-6 py-4 border-b border-slate-100">
                    <span class="text-sm font-semibold text-slate-900">Need Help?</span>
                </div>
                <div class="p-5">
                    <p class="text-slate-600 text-sm mb-4">Contact your administrator or IT support for assistance with the eBudget system.</p>
                    <div class="flex items-center gap-4 p-4 bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl">
                        <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-sm">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-900">Email Support</p>
                            <p class="text-xs text-slate-500">support@ebudget.local</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
