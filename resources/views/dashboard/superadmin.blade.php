<x-app-layout>
    <x-slot name="header">
        <x-dashboard.header :userName="Auth::user()->name" />
    </x-slot>

    <div class="space-y-4">
        <!-- Quick Stats -->
        <div class="grid grid-cols-4 gap-3">
            <!-- Total Users -->
            <a href="{{ route('admin.users.index') }}" class="bg-white rounded-xl border border-blue-100 p-4 hover:border-blue-200 transition-all">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xl font-semibold text-gray-900">{{ App\Models\User::count() }}</p>
                        <p class="text-xs text-gray-500">Total Users</p>
                    </div>
                </div>
            </a>

            <!-- Active Users -->
            <div class="bg-white rounded-xl border border-blue-100 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-emerald-50 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xl font-semibold text-gray-900">{{ App\Models\User::where('is_active', true)->count() }}</p>
                        <p class="text-xs text-gray-500">Active Users</p>
                    </div>
                </div>
            </div>

            <!-- Inactive Users -->
            <div class="bg-white rounded-xl border border-blue-100 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-red-50 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xl font-semibold text-gray-900">{{ App\Models\User::where('is_active', false)->count() }}</p>
                        <p class="text-xs text-gray-500">Inactive Users</p>
                    </div>
                </div>
            </div>

            <!-- Total Divisions -->
            <a href="{{ route('admin.divisi.index') }}" class="bg-white rounded-xl border border-blue-100 p-4 hover:border-blue-200 transition-all">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-cyan-50 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xl font-semibold text-gray-900">{{ App\Models\Divisi::count() }}</p>
                        <p class="text-xs text-gray-500">Total Divisions</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl border border-blue-100">
            <div class="px-4 py-3 border-b border-blue-100">
                <h3 class="text-sm font-semibold text-gray-900">Quick Actions</h3>
            </div>
            <div class="p-4">
                <div class="grid grid-cols-4 gap-3">
                    <a href="{{ route('admin.users.index') }}" class="flex flex-col items-center p-4 bg-blue-50 rounded-xl hover:bg-blue-100 transition-colors">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mb-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-gray-700">Users</span>
                    </a>

                    <a href="{{ route('admin.divisi.index') }}" class="flex flex-col items-center p-4 bg-cyan-50 rounded-xl hover:bg-cyan-100 transition-colors">
                        <div class="w-10 h-10 bg-cyan-100 rounded-lg flex items-center justify-center mb-2">
                            <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-gray-700">Divisions</span>
                    </a>

                    <a href="{{ route('admin.roles.index') }}" class="flex flex-col items-center p-4 bg-amber-50 rounded-xl hover:bg-amber-100 transition-colors">
                        <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center mb-2">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-gray-700">Roles</span>
                    </a>

                    <a href="{{ route('admin.approval-configs.index') }}" class="flex flex-col items-center p-4 bg-indigo-50 rounded-xl hover:bg-indigo-100 transition-colors">
                        <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center mb-2">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-gray-700">Approval Config</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- System Information -->
        <div class="grid grid-cols-2 gap-4">
            <!-- System Stats -->
            <div class="bg-white rounded-xl border border-blue-100">
                <div class="px-4 py-3 border-b border-blue-100">
                    <h3 class="text-sm font-semibold text-gray-900">System Information</h3>
                </div>
                <div class="p-4 grid grid-cols-2 gap-3">
                    <div class="bg-blue-50 rounded-xl p-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Total Roles</p>
                                <p class="font-semibold text-gray-900">{{ App\Models\Role::count() }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-gray-200 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Bank Accounts</p>
                                <p class="font-semibold text-gray-900">{{ App\Models\RekeningPerusahaan::count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Status -->
            <div class="bg-white rounded-xl border border-blue-100">
                <div class="px-4 py-3 border-b border-blue-100">
                    <h3 class="text-sm font-semibold text-gray-900">Quick Stats</h3>
                </div>
                <div class="p-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-sm text-gray-600">{{ App\Models\Role::count() }} active roles</span>
                        </div>
                        <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-emerald-100 text-emerald-700">Online</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-sm text-gray-600">System Status</span>
                        </div>
                        <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-emerald-100 text-emerald-700">Operational</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
