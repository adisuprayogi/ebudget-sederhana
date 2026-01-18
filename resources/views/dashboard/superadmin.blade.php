<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Dashboard Superadmin</h1>
                <p class="text-gray-500 text-sm mt-1">Kelola pengguna, peran, dan konfigurasi sistem</p>
            </div>
            <div class="text-sm text-gray-500">
                <span class="inline-flex items-center px-3 py-1 rounded-full bg-blue-100 text-blue-700 font-medium">
                    <span class="w-2 h-2 bg-blue-500 rounded-full mr-2 animate-pulse"></span>
                    {{ now()->translatedFormat('l, d F Y') }}
                </span>
            </div>
        </div>
    </x-slot>

    <!-- Welcome Banner -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-2xl shadow-xl p-8 mb-8 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/10 rounded-full translate-y-1/2 -translate-x-1/2"></div>
        <div class="relative z-10">
            <div class="flex items-center gap-2 mb-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                </svg>
                <span class="text-blue-100 text-sm font-medium">Selamat Datang, Superadmin!</span>
            </div>
            <h2 class="text-3xl font-bold mb-2">{{ Auth::user()->name }}</h2>
            <p class="text-blue-100 max-w-xl">Anda memiliki akses penuh untuk mengelola sistem. Kelola pengguna, divisi, role, dan konfigurasi approval dengan mudah.</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Users -->
        <div class="bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 p-6 border border-gray-100">
            <div class="flex items-start justify-between">
                <div class="flex-shrink-0 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-3 shadow-lg shadow-blue-500/30">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <span class="text-xs font-medium text-blue-600 bg-blue-100 px-2 py-1 rounded-full">All Users</span>
            </div>
            <div class="mt-4">
                <p class="text-3xl font-bold text-gray-900">{{ App\Models\User::count() }}</p>
                <p class="text-sm text-gray-500 mt-1">Total Pengguna</p>
            </div>
        </div>

        <!-- User Aktif -->
        <div class="bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 p-6 border border-gray-100">
            <div class="flex items-start justify-between">
                <div class="flex-shrink-0 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-3 shadow-lg shadow-blue-500/30">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="text-xs font-medium text-blue-600 bg-blue-100 px-2 py-1 rounded-full">Active</span>
            </div>
            <div class="mt-4">
                <p class="text-3xl font-bold text-gray-900">{{ App\Models\User::where('is_active', true)->count() }}</p>
                <p class="text-sm text-gray-500 mt-1">Pengguna Aktif</p>
            </div>
        </div>

        <!-- User Tidak Aktif -->
        <div class="bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 p-6 border border-gray-100">
            <div class="flex items-start justify-between">
                <div class="flex-shrink-0 bg-gradient-to-br from-red-500 to-rose-600 rounded-xl p-3 shadow-lg shadow-red-500/30">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="text-xs font-medium text-red-600 bg-red-100 px-2 py-1 rounded-full">Inactive</span>
            </div>
            <div class="mt-4">
                <p class="text-3xl font-bold text-gray-900">{{ App\Models\User::where('is_active', false)->count() }}</p>
                <p class="text-sm text-gray-500 mt-1">Pengguna Non-Aktif</p>
            </div>
        </div>

        <!-- Total Divisi -->
        <div class="bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 p-6 border border-gray-100">
            <div class="flex items-start justify-between">
                <div class="flex-shrink-0 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl p-3 shadow-lg shadow-orange-500/30">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <span class="text-xs font-medium text-orange-600 bg-orange-100 px-2 py-1 rounded-full">Division</span>
            </div>
            <div class="mt-4">
                <p class="text-3xl font-bold text-gray-900">{{ App\Models\Divisi::count() }}</p>
                <p class="text-sm text-gray-500 mt-1">Total Divisi</p>
            </div>
        </div>
    </div>

    <!-- Quick Actions & Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Quick Actions -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        Quick Actions
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">Akses cepat ke menu yang sering digunakan</p>
                </div>
                <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <a href="{{ route('admin.users.index') }}" class="group flex items-center gap-4 p-4 rounded-xl border-2 border-gray-100 hover:border-blue-500 hover:bg-blue-50/50 transition-all duration-200">
                        <div class="flex-shrink-0 bg-blue-100 group-hover:bg-blue-600 rounded-xl p-3 transition-colors duration-200">
                            <svg class="w-6 h-6 text-blue-600 group-hover:text-white transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 group-hover:text-blue-700 transition-colors">Manajemen User</p>
                            <p class="text-sm text-gray-500">Tambah & edit pengguna</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600 ml-auto transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>

                    <a href="{{ route('admin.divisi.index') }}" class="group flex items-center gap-4 p-4 rounded-xl border-2 border-gray-100 hover:border-orange-500 hover:bg-orange-50/50 transition-all duration-200">
                        <div class="flex-shrink-0 bg-orange-100 group-hover:bg-orange-600 rounded-xl p-3 transition-colors duration-200">
                            <svg class="w-6 h-6 text-orange-600 group-hover:text-white transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 group-hover:text-orange-700 transition-colors">Manajemen Divisi</p>
                            <p class="text-sm text-gray-500">Kelola divisi perusahaan</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-orange-600 ml-auto transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>

                    <a href="{{ route('admin.roles.index') }}" class="group flex items-center gap-4 p-4 rounded-xl border-2 border-gray-100 hover:border-orange-500 hover:bg-orange-50/50 transition-all duration-200">
                        <div class="flex-shrink-0 bg-orange-100 group-hover:bg-orange-600 rounded-xl p-3 transition-colors duration-200">
                            <svg class="w-6 h-6 text-orange-600 group-hover:text-white transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806-1.946 3.42 3.42 0 000-4.438 3.42 3.42 0 00-3.138-3.138 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 01-.806-1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 013.138 3.138 3.42 3.42 0 01.806 1.946" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 group-hover:text-orange-700 transition-colors">Manajemen Role</p>
                            <p class="text-sm text-gray-500">Atur hak akses user</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-orange-600 ml-auto transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>

                    <a href="{{ route('admin.approval-configs.index') }}" class="group flex items-center gap-4 p-4 rounded-xl border-2 border-gray-100 hover:border-blue-500 hover:bg-blue-50/50 transition-all duration-200">
                        <div class="flex-shrink-0 bg-blue-100 group-hover:bg-blue-600 rounded-xl p-3 transition-colors duration-200">
                            <svg class="w-6 h-6 text-blue-600 group-hover:text-white transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 group-hover:text-blue-700 transition-colors">Approval Config</p>
                            <p class="text-sm text-gray-500">Kustomisasi alur approval</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600 ml-auto transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- System Info -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                        </svg>
                        System Info
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Total Role</p>
                                <p class="font-semibold text-gray-900">{{ App\Models\Role::count() }}</p>
                            </div>
                        </div>
                    </div>


                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Rekening Perusahaan</p>
                                <p class="font-semibold text-gray-900">{{ App\Models\RekeningPerusahaan::count() }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <a href="{{ route('dashboard') }}" class="w-full flex items-center justify-center gap-2 py-3 px-4 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-medium rounded-xl transition-all duration-200 shadow-lg shadow-blue-500/30 hover:shadow-xl hover:scale-[1.02]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            Lihat Dashboard Utama
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
