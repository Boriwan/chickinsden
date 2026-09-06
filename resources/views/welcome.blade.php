<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

</head>

<body class=" flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">
    <x-site-layout>

        <h1 class="text-4xl font-bold text-center mb-6 lg:mb-12">Welcome to <span class="text-blue-600">Chickins
                Den🐓☕️</span></h1>
        @if (Route::has('login'))
            <div class="h-14.5 hidden lg:block"></div>
        @endif

        <button
            style="background-color: #f0f0f0; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">
            <a href="/chickens">Show my chickens</a>
        </button>
    </x-site-layout>
</body>

</html>
