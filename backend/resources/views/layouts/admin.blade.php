<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — OSZA Admin Panel</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="antialiased bg-gray-100 font-sans" x-data="{ sidebarOpen: false }">

    <div class="flex h-screen overflow-hidden">

        {{-- Sidebar --}}
        @include('components.admin.sidebar')

        {{-- Content area --}}
        <div class="flex flex-col flex-1 overflow-hidden">

            {{-- Topbar --}}
            @include('components.admin.topbar')

            {{-- Page Content --}}
            <main class="flex-1 overflow-y-auto p-6 bg-gray-50">

                {{-- Flash messages --}}
                @if(session('success'))
                    <div
                        class="mb-4 bg-green-50 border border-green-200 text-green-800 rounded-lg px-4 py-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-4 bg-red-50 border border-red-200 text-red-800 rounded-lg px-4 py-3">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @livewireScripts
    @stack('scripts')
</body>

</html>