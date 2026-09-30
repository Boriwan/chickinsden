<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Chickins Den</title>
    <meta name="description" content="A simple web application for logging and managing your chickens.">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="flex min-h-screen flex-col bg-page font-sans text-stone-700">
    <header class="flex h-20 items-center justify-between gap-4 bg-shell px-5 shadow-sm">
        <a href="{{ route('home') }}" class="flex shrink-0 items-center">
            <img src="{{ asset('imgs/ChickinsLogo.png') }}" alt="Chickins Den"
                class="h-14 w-14 rounded-lg object-contain">
        </a>

        <nav class="flex flex-wrap items-center gap-1">
            @foreach ($menu as $item)
                @php
                    $itemRoute = is_array($item) ? ($item['route'] ?? null) : null;
                    $isCurrentRoute = is_string($itemRoute) && request()->routeIs($itemRoute);
                @endphp

                <a href="{{ $itemRoute ? route($itemRoute) : '#' }}" @class([
                    'flex items-center gap-2 rounded-lg px-3 py-2 font-semibold transition',
                    'bg-white/60 text-stone-900' => $isCurrentRoute,
                    'text-stone-800 hover:bg-white/40' => !$isCurrentRoute,
                ])>
                    <x-icon :name="$item['icon']" class="h-5 w-5 shrink-0" />
                    <span>{{ $item['label'] }}</span>
                </a>
            @endforeach

            @auth
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex items-center gap-2 rounded-lg px-3 py-2 font-semibold text-stone-800 transition hover:bg-white/40">
                        <x-icon name="log-out" class="h-5 w-5 shrink-0" />
                        <span>Logout</span>
                    </button>
                </form>
            @endauth
        </nav>
    </header>

    @if (session('status'))
        <div class="mx-auto w-full max-w-6xl px-5 pt-5">
            <div class="rounded-lg border border-brand-200 bg-brand-50 px-4 py-3 text-sm font-medium text-brand-700">
                {{ session('status') }}
            </div>
        </div>
    @endif

    @if ($errors->any() && ! $errors->has('userDeletion'))
        <div class="mx-auto w-full max-w-6xl px-5 pt-5">
            <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
                {{ $errors->first() }}
            </div>
        </div>
    @endif

    <main class="flex-1">
        {{ $slot }}
    </main>

    <footer class="bg-shell px-4 py-4 text-center text-sm text-stone-700">
        Chickins Den &copy; {{ date('Y') }} &middot; All rights reserved.
    </footer>
</body>

</html>
