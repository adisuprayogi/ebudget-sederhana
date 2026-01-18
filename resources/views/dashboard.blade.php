<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/30">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
                    <p class="text-gray-500 text-sm mt-0.5">Selamat datang di E-Budget System</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="hidden sm:flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-300 rounded-xl shadow-sm">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="text-sm font-medium text-gray-700">{{ now()->translatedFormat('l, d F Y') }}</span>
                </div>
            </div>
        </div>
    </x-slot>

    <!-- Welcome Banner -->
    <div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 rounded-2xl shadow-xl p-8 mb-8 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/10 rounded-full translate-y-1/2 -translate-x-1/2"></div>
        <div class="relative z-10">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-3 h-3 bg-green-400 rounded-full animate-pulse"></div>
                <span class="text-blue-100 text-sm font-medium tracking-wide">SYSTEM ACTIVE</span>
            </div>
            <h2 class="text-3xl font-bold mb-2">Selamat Datang, {{ Auth::user()->name }}!</h2>
            <p class="text-blue-100 max-w-2xl">Anda telah login ke sistem E-Budget. Kelola pengajuan dana, pencairan, dan laporan pertanggungjawaban dengan mudah dan efisien.</p>
        </div>
    </div>

    <!-- Quick Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Pengajuan -->
        <a href="{{ route('pengajuan-dana.index') }}" class="group bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 p-6 border border-gray-100">
            <div class="flex items-start justify-between">
                <div class="flex-shrink-0 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-3 shadow-lg shadow-blue-500/30 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <p class="text-3xl font-bold text-gray-900">{{ \App\Models\PengajuanDana::count() }}</p>
                <p class="text-sm text-gray-500 mt-1">Total Pengajuan</p>
            </div>
        </a>

        <!-- Pending Approvals -->
        <a href="{{ route('approvals.index') }}" class="group bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 p-6 border border-gray-100">
            <div class="flex items-start justify-between">
                <div class="flex-shrink-0 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl p-3 shadow-lg shadow-amber-500/30 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <p class="text-3xl font-bold text-gray-900">{{ \App\Models\Approval::where('status', 'pending')->count() }}</p>
                <p class="text-sm text-gray-500 mt-1">Pending Approval</p>
            </div>
        </a>

        <!-- Pencairan -->
        <a href="{{ route('pencairan-dana.index') }}" class="group bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 p-6 border border-gray-100">
            <div class="flex items-start justify-between">
                <div class="flex-shrink-0 bg-gradient-to-br from-purple-500 to-violet-600 rounded-xl p-3 shadow-lg shadow-purple-500/30 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <p class="text-3xl font-bold text-gray-900">{{ \App\Models\PencairanDana::count() }}</p>
                <p class="text-sm text-gray-500 mt-1">Total Pencairan</p>
            </div>
        </a>

        <!-- LPJ -->
        <a href="{{ route('lpj.index') }}" class="group bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 p-6 border border-gray-100">
            <div class="flex items-start justify-between">
                <div class="flex-shrink-0 bg-gradient-to-br from-emerald-500 to-green-600 rounded-xl p-3 shadow-lg shadow-emerald-500/30 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <p class="text-3xl font-bold text-gray-900">{{ \App\Models\LaporanPertanggungJawaban::count() }}</p>
                <p class="text-sm text-gray-500 mt-1">Total LPJ</p>
            </div>
        </a>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
        <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                Quick Actions
            </h3>
        </div>
        <div class="p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('pengajuan-dana.create') }}" class="group flex items-center gap-4 p-4 rounded-xl border-2 border-gray-100 hover:border-blue-500 hover:bg-blue-50/50 transition-all duration-200">
                <div class="flex-shrink-0 bg-blue-100 group-hover:bg-blue-600 rounded-xl p-3 transition-colors duration-200">
                    <svg class="w-6 h-6 text-blue-600 group-hover:text-white transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-gray-900 group-hover:text-blue-700 transition-colors">Buat Pengajuan</p>
                    <p class="text-sm text-gray-500">Ajukan dana baru</p>
                </div>
                <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>

            <a href="{{ route('approvals.index') }}" class="group flex items-center gap-4 p-4 rounded-xl border-2 border-gray-100 hover:border-amber-500 hover:bg-amber-50/50 transition-all duration-200">
                <div class="flex-shrink-0 bg-amber-100 group-hover:bg-amber-600 rounded-xl p-3 transition-colors duration-200">
                    <svg class="w-6 h-6 text-amber-600 group-hover:text-white transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-gray-900 group-hover:text-amber-700 transition-colors">Approvals</p>
                    <p class="text-sm text-gray-500">Proses persetujuan</p>
                </div>
                <svg class="w-5 h-5 text-gray-400 group-hover:text-amber-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>

            <a href="{{ route('lpj.index') }}" class="group flex items-center gap-4 p-4 rounded-xl border-2 border-gray-100 hover:border-emerald-500 hover:bg-emerald-50/50 transition-all duration-200">
                <div class="flex-shrink-0 bg-emerald-100 group-hover:bg-emerald-600 rounded-xl p-3 transition-colors duration-200">
                    <svg class="w-6 h-6 text-emerald-600 group-hover:text-white transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-gray-900 group-hover:text-emerald-700 transition-colors">Laporan LPJ</p>
                    <p class="text-sm text-gray-500">Lihat LPJ</p>
                </div>
                <svg class="w-5 h-5 text-gray-400 group-hover:text-emerald-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>

            <a href="{{ route('reports.index') }}" class="group flex items-center gap-4 p-4 rounded-xl border-2 border-gray-100 hover:border-purple-500 hover:bg-purple-50/50 transition-all duration-200">
                <div class="flex-shrink-0 bg-purple-100 group-hover:bg-purple-600 rounded-xl p-3 transition-colors duration-200">
                    <svg class="w-6 h-6 text-purple-600 group-hover:text-white transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-gray-900 group-hover:text-purple-700 transition-colors">Laporan</p>
                    <p class="text-sm text-gray-500">Generate laporan</p>
                </div>
                <svg class="w-5 h-5 text-gray-400 group-hover:text-purple-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>
    </div>

    <!-- User Info -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Profile Information
            </h3>
        </div>
        <div class="p-6">
            <div class="flex items-center gap-6">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-500/30">
                    <span class="text-2xl font-bold text-white">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                </div>
                <div class="flex-1">
                    <p class="text-xl font-bold text-gray-900">{{ Auth::user()->name }}</p>
                    <p class="text-gray-500">{{ Auth::user()->email }}</p>
                    @if(Auth::user()->divisi)
                        <p class="text-sm text-gray-600 mt-1">Divisi: {{ Auth::user()->divisi->nama_divisi }}</p>
                    @endif
                </div>
                <div class="text-right">
                    @foreach(Auth::user()->roles as $role)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                            {{ $role->name }}
                        </span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
