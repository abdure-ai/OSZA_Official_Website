<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Document Reader — OSZA')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            overflow: hidden;
            font-family: 'Inter', sans-serif;
        }

        .reader-frame {
            height: calc(100vh - 64px);
        }
    </style>
</head>

<body class="antialiased bg-gray-100 flex flex-col h-screen">
    @yield('content')
</body>

</html>