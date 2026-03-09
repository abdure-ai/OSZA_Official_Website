<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'am' ? 'ltr' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Oromo Special Zone Administration')</title>
    <meta name="description"
        content="@yield('description', 'Official portal of the Oromo Special Zone Administration. Access government services, latest news, and development reports.')">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('head')
</head>

<body class="antialiased bg-white flex flex-col min-h-screen">

    {{-- Emergency Alert Banner --}}
    @include('components.emergency-alert')

    {{-- Navbar --}}
    @include('components.navbar')

    {{-- Page Content --}}
    <main class="flex-1">
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('components.footer')

    @livewireScripts
    @stack('scripts')
</body>

</html>