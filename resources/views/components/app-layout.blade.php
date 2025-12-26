@props(['header' => null])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'E-Budget Sederhana') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>💰</text></svg>">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-50" x-data="{ sidebarOpen: false }">
        @auth
            <div class="flex h-screen overflow-hidden">
                <!-- Sidebar -->
                @include('layouts.sidebar')

                <!-- Main Content -->
                <div class="flex-1 flex flex-col overflow-hidden">
                    <!-- Header -->
                    @include('layouts.header')

                    <!-- Page Content -->
                    <main class="flex-1 overflow-y-auto py-4 sm:py-6 lg:py-8">
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 animate-fade-in">
                            <!-- Page Heading -->
                            @if($header)
                                <div class="mb-6">
                                    {{ $header }}
                                </div>
                            @endif

                            <!-- Slot Content -->
                            {{ $slot }}
                        </div>
                    </main>
                </div>
            </div>
        @else
            <!-- Guest Layout -->
            <div class="min-h-screen bg-gradient-to-br from-secondary-50 via-white to-primary-50">
                <main class="animate-fade-in">
                    {{ $slot }}
                </main>
            </div>
        @endauth
    </body>
</html>
