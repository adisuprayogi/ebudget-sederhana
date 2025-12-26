@auth
<!-- Mobile Backdrop -->
<div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition:enter="transition-opacity ease-linear duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/50 z-40 lg:hidden" style="display: none;"></div>

<!-- Sidebar -->
<div :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-64 bg-gradient-to-b from-blue-700 via-blue-800 to-indigo-900 shadow-2xl transition-transform duration-200 ease-in-out lg:translate-x-0 lg:static lg:inset-0">
    <div class="flex flex-col h-full">
        <!-- Logo -->
        <div class="flex items-center justify-between h-16 px-6 border-b border-white/10">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                <div class="w-9 h-9 bg-white/20 backdrop-blur-sm rounded-lg flex items-center justify-center shadow-lg">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="text-lg font-bold text-white tracking-tight">E-Budget</span>
            </a>
            <button @click="sidebarOpen = false" class="lg:hidden p-1.5 text-white/70 hover:text-white hover:bg-white/10 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-150 {{ request()->routeIs('dashboard') ? 'bg-white text-blue-700 shadow-lg' : 'text-white/90 hover:bg-white/10 hover:text-white' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span>Dashboard</span>
            </a>

            <!-- Superadmin -->
            @if(auth()->user()->hasRole('superadmin'))
                <!-- Section Header -->
                <div class="px-3 mt-6 mb-2">
                    <p class="text-xs font-semibold text-white/50 uppercase tracking-wider">Administrasi</p>
                </div>

                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-lg transition-all duration-150 {{ request()->routeIs('admin.users.*') ? 'bg-white text-blue-700 shadow-lg' : 'text-white/90 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span>Manajemen User</span>
                </a>
                <a href="{{ route('admin.divisi.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-lg transition-all duration-150 {{ request()->routeIs('admin.divisi.*') ? 'bg-white text-blue-700 shadow-lg' : 'text-white/90 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span>Manajemen Divisi</span>
                </a>
                <a href="{{ route('admin.roles.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-lg transition-all duration-150 {{ request()->routeIs('admin.roles.*') ? 'bg-white text-blue-700 shadow-lg' : 'text-white/90 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806-1.946 3.42 3.42 0 000-4.438 3.42 3.42 0 00-3.138-3.138 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 01-.806-1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 013.138 3.138 3.42 3.42 0 01.806 1.946" />
                    </svg>
                    <span>Manajemen Role</span>
                </a>

                <!-- Section Header -->
                <div class="px-3 mt-6 mb-2">
                    <p class="text-xs font-semibold text-white/50 uppercase tracking-wider">Pengaturan</p>
                </div>

                <a href="{{ route('admin.approval-configs.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-lg transition-all duration-150 {{ request()->routeIs('admin.approval-configs.*') ? 'bg-white text-blue-700 shadow-lg' : 'text-white/90 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>Kostumisasi Approval</span>
                </a>
            @endif

            <!-- Direktur Utama -->
            @if(auth()->user()->hasRole('direktur_utama'))
                <!-- Section Header -->
                <div class="px-3 mt-6 mb-2">
                    <p class="text-xs font-semibold text-white/50 uppercase tracking-wider">Administrasi</p>
                </div>

                <a href="{{ route('periode-anggaran.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-lg transition-all duration-150 {{ request()->routeIs('periode-anggaran.*') ? 'bg-white text-blue-700 shadow-lg' : 'text-white/90 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>Periode Anggaran</span>
                </a>
                <a href="{{ route('admin.vendors.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-lg transition-all duration-150 {{ request()->routeIs('admin.vendors.*') ? 'bg-white text-blue-700 shadow-lg' : 'text-white/90 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span>Vendor</span>
                </a>
                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-lg transition-all duration-150 {{ request()->routeIs('admin.users.*') ? 'bg-white text-blue-700 shadow-lg' : 'text-white/90 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span>Manajemen User</span>
                </a>

                <!-- Section Header -->
                <div class="px-3 mt-6 mb-2">
                    <p class="text-xs font-semibold text-white/50 uppercase tracking-wider">Laporan</p>
                </div>

                <a href="{{ route('approvals.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-lg transition-all duration-150 {{ request()->routeIs('approvals.*') ? 'bg-white text-blue-700 shadow-lg' : 'text-white/90 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Approval</span>
                </a>
                <a href="{{ route('reports.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-lg transition-all duration-150 {{ request()->routeIs('reports.*') ? 'bg-white text-blue-700 shadow-lg' : 'text-white/90 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <span>Laporan</span>
                </a>
            @endif

            <!-- Direktur Keuangan & Staff Keuangan -->
            @if(auth()->user()->hasAnyRole(['direktur_keuangan', 'staff_keuangan']))

                @if(auth()->user()->hasRole('direktur_keuangan'))
                <!-- Section Header -->
                <div class="px-3 mt-6 mb-2">
                    <p class="text-xs font-semibold text-white/50 uppercase tracking-wider">Administrasi</p>
                </div>

                <a href="{{ route('periode-anggaran.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-lg transition-all duration-150 {{ request()->routeIs('periode-anggaran.*') ? 'bg-white text-blue-700 shadow-lg' : 'text-white/90 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>Periode Anggaran</span>
                </a>
                <a href="{{ route('sumber-dana.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-lg transition-all duration-150 {{ request()->routeIs('sumber-dana.*') ? 'bg-white text-blue-700 shadow-lg' : 'text-white/90 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Sumber Dana</span>
                </a>
                @endif

                <!-- Section Header -->
                <div class="px-3 mt-6 mb-2">
                    <p class="text-xs font-semibold text-white/50 uppercase tracking-wider">Perencanaan</p>
                </div>

                <a href="{{ route('perencanaan-penerimaan.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-lg transition-all duration-150 {{ request()->routeIs('perencanaan-penerimaan.*') ? 'bg-white text-blue-700 shadow-lg' : 'text-white/90 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Perencanaan Penerimaan</span>
                </a>
                <a href="{{ route('pencatatan-penerimaan.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-lg transition-all duration-150 {{ request()->routeIs('pencatatan-penerimaan.*') ? 'bg-white text-blue-700 shadow-lg' : 'text-white/90 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <span>Pencatatan Penerimaan</span>
                </a>
                <a href="{{ route('penetapan-pagu.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-lg transition-all duration-150 {{ request()->routeIs('penetapan-pagu.*') ? 'bg-white text-blue-700 shadow-lg' : 'text-white/90 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                    <span>Penetapan Pagu</span>
                </a>

                <!-- Section Header -->
                <div class="px-3 mt-6 mb-2">
                    <p class="text-xs font-semibold text-white/50 uppercase tracking-wider">Verifikasi</p>
                </div>

                <a href="{{ route('approvals.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-lg transition-all duration-150 {{ request()->routeIs('approvals.*') ? 'bg-white text-blue-700 shadow-lg' : 'text-white/90 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Approval</span>
                </a>
                <a href="{{ route('pencairan-dana.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-lg transition-all duration-150 {{ request()->routeIs('pencairan-dana.*') ? 'bg-white text-blue-700 shadow-lg' : 'text-white/90 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span>Pencairan Dana</span>
                </a>
                <a href="{{ route('reports.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-lg transition-all duration-150 {{ request()->routeIs('reports.*') ? 'bg-white text-blue-700 shadow-lg' : 'text-white/90 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <span>Laporan</span>
                </a>
            @endif

            <!-- Kepala Divisi & Staff Divisi -->
            @if(auth()->user()->hasAnyRole(['kepala_divisi', 'staff_divisi']))
                <!-- Section Header -->
                <div class="px-3 mt-6 mb-2">
                    <p class="text-xs font-semibold text-white/50 uppercase tracking-wider">Anggaran</p>
                </div>

                <a href="{{ route('program-kerja.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-lg transition-all duration-150 {{ request()->routeIs('program-kerja.*') ? 'bg-white text-blue-700 shadow-lg' : 'text-white/90 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                    <span>Program Kerja</span>
                </a>
                <a href="{{ route('pengajuan-dana.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-lg transition-all duration-150 {{ request()->routeIs('pengajuan-dana.*') ? 'bg-white text-blue-700 shadow-lg' : 'text-white/90 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Pengajuan Dana</span>
                </a>
                <a href="{{ route('lpj.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-lg transition-all duration-150 {{ request()->routeIs('lpj.*') ? 'bg-white text-blue-700 shadow-lg' : 'text-white/90 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span>Laporan Pertanggungjawaban</span>
                </a>
                <a href="{{ route('refund.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-lg transition-all duration-150 {{ request()->routeIs('refund.*') ? 'bg-white text-blue-700 shadow-lg' : 'text-white/90 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                    </svg>
                    <span>Refund</span>
                </a>
            @endif

            <!-- Notifications - All Users -->
            <div class="px-3 mt-6 mb-2">
                <p class="text-xs font-semibold text-white/50 uppercase tracking-wider">Umum</p>
            </div>
            <a href="{{ route('notifications.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-lg transition-all duration-150 {{ request()->routeIs('notifications.*') ? 'bg-white text-blue-700 shadow-lg' : 'text-white/90 hover:bg-white/10 hover:text-white' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                <span>Notifikasi</span>
                <span class="ml-auto bg-white text-blue-700 text-xs px-2 py-0.5 rounded-full font-semibold">3</span>
            </a>
        </nav>

        <!-- User Profile -->
        <div class="p-4 border-t border-white/10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center ring-2 ring-white/30">
                    <span class="text-sm font-bold text-white">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-white/60 truncate">{{ Auth::user()->role->name ?? 'User' }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="p-2 text-white/60 hover:text-white hover:bg-white/10 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endauth
