<nav x-data="{ open: false }" class="bg-white/95 backdrop-blur-md border-b border-secondary-200/50 sticky top-0 z-50 shadow-soft">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 group">
                        <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl flex items-center justify-center shadow-soft group-hover:shadow-medium transition-all duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-secondary-900">E-Budget</h1>
                            <p class="text-xs text-secondary-500">Sistem Pengelolaan Anggaran</p>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                @auth
                    <div class="hidden md:flex items-center space-x-1 ml-10">
                        <!-- Dashboard Link -->
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            <span>Dashboard</span>
                        </a>

                        <!-- Role-based Navigation -->
                        @if(auth()->user()->hasRole('superadmin'))
                            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <span>User</span>
                            </a>
                            <a href="{{ route('admin.approval-configs.index') }}" class="nav-link {{ request()->routeIs('admin.approval-configs.*') ? 'active' : '' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span>Kostumisasi Approval</span>
                            </a>
                        @endif

                        @if(auth()->user()->hasRole('direktur_utama'))
                            <a href="{{ route('periode-anggaran.index') }}" class="nav-link {{ request()->routeIs('periode-anggaran.*') ? 'active' : '' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>Periode</span>
                            </a>
                            <a href="{{ route('admin.vendors.index') }}" class="nav-link {{ request()->routeIs('admin.vendors.*') ? 'active' : '' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                <span>Vendor</span>
                            </a>
                            <a href="{{ route('approvals.index') }}" class="nav-link {{ request()->routeIs('approvals.*') ? 'active' : '' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Approval</span>
                            </a>
                            <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                <span>Laporan</span>
                            </a>
                        @endif

                        @if(auth()->user()->hasRole('direktur_keuangan'))
                            <a href="{{ route('perencanaan-penerimaan.index') }}" class="nav-link {{ request()->routeIs('perencanaan-penerimaan.*') ? 'active' : '' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Perencanaan</span>
                            </a>
                            <a href="{{ route('pencatatan-penerimaan.index') }}" class="nav-link {{ request()->routeIs('pencatatan-penerimaan.*') ? 'active' : '' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <span>Pencatatan</span>
                            </a>
                            <a href="{{ route('penetapan-pagu.index') }}" class="nav-link {{ request()->routeIs('penetapan-pagu.*') ? 'active' : '' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                                <span>Penetapan Pagu</span>
                            </a>
                            <a href="{{ route('approvals.index') }}" class="nav-link {{ request()->routeIs('approvals.*') ? 'active' : '' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Approval</span>
                            </a>
                            <a href="{{ route('pencairan-dana.index') }}" class="nav-link {{ request()->routeIs('pencairan-dana.*') ? 'active' : '' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <span>Pencairan</span>
                            </a>
                            <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                <span>Laporan</span>
                            </a>
                        @endif

                        @if(auth()->user()->hasAnyRole(['kepala_divisi', 'staff_divisi']))
                            <a href="{{ route('program-kerja.index') }}" class="nav-link {{ request()->routeIs('program-kerja.*') ? 'active' : '' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                                <span>Program Kerja</span>
                            </a>
                            <a href="{{ route('pengajuan-dana.index') }}" class="nav-link {{ request()->routeIs('pengajuan-dana.*') ? 'active' : '' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                <span>Pengajuan</span>
                            </a>
                            <a href="{{ route('lpj.index') }}" class="nav-link {{ request()->routeIs('lpj.*') ? 'active' : '' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span>LPJ</span>
                            </a>
                        @endif

                        @if(auth()->user()->hasRole('staff_keuangan'))
                            <a href="{{ route('pencairan-dana.index') }}" class="nav-link {{ request()->routeIs('pencairan-dana.*') ? 'active' : '' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <span>Pencairan</span>
                            </a>
                        @endif

                        <!-- Refund for Kepala Divisi & Staff Divisi -->
                        @if(auth()->user()->hasAnyRole(['kepala_divisi', 'staff_divisi']))
                            <a href="{{ route('refund.index') }}" class="nav-link {{ request()->routeIs('refund.*') ? 'active' : '' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                                </svg>
                                <span>Refund</span>
                            </a>
                        @endif

                        <!-- Notifications for all authenticated users -->
                        <a href="{{ route('notifications.index') }}" class="nav-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <span>Notifikasi</span>
                        </a>
                    </div>
                @endauth
            </div>

            <!-- Settings Dropdown -->
            @auth
                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <x-dropdown align="right" width="64">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-4 py-2 border border-secondary-200 text-sm leading-4 font-medium rounded-xl text-secondary-700 bg-white hover:bg-secondary-50 hover:border-secondary-300 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200 shadow-soft hover:shadow-medium">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-gradient-to-br from-primary-400 to-primary-600 rounded-full flex items-center justify-center">
                                        <span class="text-white font-semibold text-sm">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                        </span>
                                    </div>
                                    <div class="hidden md:block text-left">
                                        <div class="font-medium text-secondary-900">{{ Auth::user()->name }}</div>
                                        <div class="text-xs text-secondary-500">{{ Auth::user()->role->name ?? 'User' }}</div>
                                    </div>
                                    <svg class="fill-current h-4 w-4 text-secondary-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <!-- User Info -->
                            <div class="px-4 py-3 border-b border-secondary-200">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-primary-400 to-primary-600 rounded-full flex items-center justify-center">
                                        <span class="text-white font-semibold">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <div class="font-medium text-secondary-900">{{ Auth::user()->name }}</div>
                                        <div class="text-sm text-secondary-500">{{ Auth::user()->email }}</div>
                                        <div class="text-xs text-primary-600 font-medium">{{ Auth::user()->role->name ?? 'User' }}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Navigation Links -->
                            <div class="py-2">
                                <x-dropdown-link :href="route('profile.edit')">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        <span>Profile Saya</span>
                                    </div>
                                </x-dropdown-link>

                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                            onclick="event.preventDefault();
                                                        this.closest('form').submit();">
                                        <div class="flex items-center space-x-2 text-danger-600">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                            </svg>
                                            <span>Keluar</span>
                                        </div>
                                    </x-dropdown-link>
                                </form>
                            </div>
                        </x-slot>
                    </x-dropdown>
                </div>
            @endauth

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-xl text-secondary-400 hover:text-secondary-500 hover:bg-secondary-100 focus:outline-none focus:bg-secondary-100 focus:text-secondary-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-secondary-200 bg-white">
        <div class="pt-2 pb-3 space-y-1 px-4">
            @auth
                <a href="{{ route('dashboard') }}" class="block px-4 py-2 rounded-lg text-secondary-700 hover:bg-primary-50 hover:text-primary-700 font-medium">
                    Dashboard
                </a>

                <!-- Superadmin -->
                @if(auth()->user()->hasRole('superadmin'))
                    <a href="{{ route('admin.users.index') }}" class="block px-4 py-2 rounded-lg text-secondary-700 hover:bg-primary-50 hover:text-primary-700 font-medium">Manajemen User</a>
                    <a href="{{ route('admin.divisi.index') }}" class="block px-4 py-2 rounded-lg text-secondary-700 hover:bg-primary-50 hover:text-primary-700 font-medium">Divisi</a>
                    <a href="{{ route('admin.roles.index') }}" class="block px-4 py-2 rounded-lg text-secondary-700 hover:bg-primary-50 hover:text-primary-700 font-medium">Role</a>
                    <a href="{{ route('admin.approval-configs.index') }}" class="block px-4 py-2 rounded-lg text-secondary-700 hover:bg-primary-50 hover:text-primary-700 font-medium">Kostumisasi Approval</a>
                @endif

                <!-- Direktur Utama -->
                @if(auth()->user()->hasRole('direktur_utama'))
                    <a href="{{ route('periode-anggaran.index') }}" class="block px-4 py-2 rounded-lg text-secondary-700 hover:bg-primary-50 hover:text-primary-700 font-medium">Periode Anggaran</a>
                    <a href="{{ route('approvals.index') }}" class="block px-4 py-2 rounded-lg text-secondary-700 hover:bg-primary-50 hover:text-primary-700 font-medium">Approval</a>
                    <a href="{{ route('reports.index') }}" class="block px-4 py-2 rounded-lg text-secondary-700 hover:bg-primary-50 hover:text-primary-700 font-medium">Laporan</a>
                    <a href="{{ route('admin.users.index') }}" class="block px-4 py-2 rounded-lg text-secondary-700 hover:bg-primary-50 hover:text-primary-700 font-medium">Manajemen User</a>
                    <a href="{{ route('admin.vendors.index') }}" class="block px-4 py-2 rounded-lg text-secondary-700 hover:bg-primary-50 hover:text-primary-700 font-medium">Vendor</a>
                @endif

                <!-- Direktur Keuangan -->
                @if(auth()->user()->hasRole('direktur_keuangan'))
                    <a href="{{ route('perencanaan-penerimaan.index') }}" class="block px-4 py-2 rounded-lg text-secondary-700 hover:bg-primary-50 hover:text-primary-700 font-medium">Perencanaan</a>
                    <a href="{{ route('pencatatan-penerimaan.index') }}" class="block px-4 py-2 rounded-lg text-secondary-700 hover:bg-primary-50 hover:text-primary-700 font-medium">Pencatatan</a>
                    <a href="{{ route('penetapan-pagu.index') }}" class="block px-4 py-2 rounded-lg text-secondary-700 hover:bg-primary-50 hover:text-primary-700 font-medium">Penetapan Pagu</a>
                    <a href="{{ route('approvals.index') }}" class="block px-4 py-2 rounded-lg text-secondary-700 hover:bg-primary-50 hover:text-primary-700 font-medium">Approval</a>
                    <a href="{{ route('pencairan-dana.index') }}" class="block px-4 py-2 rounded-lg text-secondary-700 hover:bg-primary-50 hover:text-primary-700 font-medium">Pencairan</a>
                    <a href="{{ route('reports.index') }}" class="block px-4 py-2 rounded-lg text-secondary-700 hover:bg-primary-50 hover:text-primary-700 font-medium">Laporan</a>
                @endif

                <!-- Kepala Divisi & Staff Divisi -->
                @if(auth()->user()->hasAnyRole(['kepala_divisi', 'staff_divisi']))
                    <a href="{{ route('program-kerja.index') }}" class="block px-4 py-2 rounded-lg text-secondary-700 hover:bg-primary-50 hover:text-primary-700 font-medium">Program Kerja</a>
                    <a href="{{ route('pengajuan-dana.index') }}" class="block px-4 py-2 rounded-lg text-secondary-700 hover:bg-primary-50 hover:text-primary-700 font-medium">Pengajuan Dana</a>
                    <a href="{{ route('lpj.index') }}" class="block px-4 py-2 rounded-lg text-secondary-700 hover:bg-primary-50 hover:text-primary-700 font-medium">LPJ</a>
                    <a href="{{ route('refund.index') }}" class="block px-4 py-2 rounded-lg text-secondary-700 hover:bg-primary-50 hover:text-primary-700 font-medium">Refund</a>
                @endif

                <!-- Staff Keuangan -->
                @if(auth()->user()->hasRole('staff_keuangan'))
                    <a href="{{ route('pencairan-dana.index') }}" class="block px-4 py-2 rounded-lg text-secondary-700 hover:bg-primary-50 hover:text-primary-700 font-medium">Pencairan</a>
                    <a href="{{ route('approvals.index') }}" class="block px-4 py-2 rounded-lg text-secondary-700 hover:bg-primary-50 hover:text-primary-700 font-medium">Approval</a>
                @endif

                <!-- All authenticated users -->
                <a href="{{ route('notifications.index') }}" class="block px-4 py-2 rounded-lg text-secondary-700 hover:bg-primary-50 hover:text-primary-700 font-medium">Notifikasi</a>
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        @auth
            <div class="pt-4 pb-1 border-t border-secondary-200">
                <div class="px-4">
                    <div class="font-medium text-base text-secondary-900">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-secondary-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1 px-4">
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 rounded-lg text-secondary-700 hover:bg-secondary-50 font-medium">
                        Profile Saya
                    </a>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 rounded-lg text-danger-600 hover:bg-danger-50 font-medium">
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        @endauth
    </div>
</nav>
