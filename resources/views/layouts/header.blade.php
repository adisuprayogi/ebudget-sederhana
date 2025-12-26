@auth
<!-- Header -->
<header class="bg-white border-b border-gray-200">
    <div class="flex items-center justify-between h-16 px-6">
        <!-- Sidebar Toggle -->
        <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 text-gray-600 hover:bg-gray-100 rounded-lg">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <!-- Page Title -->
        <h1 class="text-lg font-semibold text-gray-900 hidden sm:block">
            @if(request()->routeIs('dashboard'))
                Dashboard
            @elseif(request()->routeIs('*.index'))
                {{ str_replace('-', ' ', ucfirst((explode('.', request()->route()->getName())[0] ?? 'Menu'))) }}
            @elseif(request()->routeIs('*.create'))
                Tambah {{ ucfirst((explode('.', request()->route()->getName())[1] ?? 'Data')) }}
            @elseif(request()->routeIs('*.edit'))
                Edit {{ ucfirst((explode('.', request()->route()->getName())[1] ?? 'Data')) }}
            @elseif(request()->routeIs('*.show'))
                Detail {{ ucfirst((explode('.', request()->route()->getName())[1] ?? 'Data')) }}
            @else
                {{ config('app.name') }}
            @endif
        </h1>

        <!-- Right Actions -->
        <div class="flex items-center gap-3">
            <!-- Notifications -->
            <a href="{{ route('notifications.index') }}" class="relative p-2 text-gray-600 hover:bg-gray-100 rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
            </a>

            <!-- Profile Dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" @click.away="open = false" class="flex items-center gap-3 p-1 pr-3 text-gray-700 hover:bg-gray-100 rounded-lg">
                    <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                        <span class="text-sm font-bold text-indigo-600">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                    </div>
                    <div class="hidden md:block text-left">
                        <p class="text-sm font-medium">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500">{{ Auth::user()->role->name ?? 'User' }}</p>
                    </div>
                    <svg class="w-4 h-4 text-gray-400 hidden md:block" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <!-- Dropdown -->
                <div x-show="open" class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg py-1 z-50" style="display: none;">
                    <div class="px-4 py-2 border-b border-gray-100">
                        <p class="text-sm font-medium">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Profile</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">Keluar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
@endauth
