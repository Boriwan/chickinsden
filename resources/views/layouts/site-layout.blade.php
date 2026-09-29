<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="font-mono">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Chickins Den🐓🪹☕️</title>
    <meta name="description" content="A simple web application for logging and managing your chickens.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="flex min-h-screen flex-col bg-page">
    <header class="flex h-20 items-center gap-12 bg-shell px-5">
        <a href="{{ route('home') }}">
            <img src="{{ asset('imgs/ChickinsLogo.png') }}" alt="Chickins Den" class="h-[90px] w-[90px] object-contain">
        </a>

        <nav class="flex flex-wrap gap-8">
            @foreach ($menu as $item)
                <a href="{{ route($item['route']) }}" class="rounded px-2.5 py-1.5 font-bold transition hover:bg-brand-light">
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>
    </header>

    <main class="flex-1">
        {{ $slot }}
    </main>

    <footer class="bg-shell px-4 py-4 text-center">
        Chickins Den🐓🪹☕️ - &copy; {{ date('Y') }} All rights reserved.
    </footer>
</body>

</html>
