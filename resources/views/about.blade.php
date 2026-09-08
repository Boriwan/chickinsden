<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>


</head>

<body>
    <x-site-layout>

        @if (Route::has('login'))
            <div class="h-14.5 hidden lg:block"></div>
        @endif
        <h1 class="mb-6 lg:mb-12 text-center" style="margin-top:8rem; font-weight: bold; font-size: 2rem;">About Chickins
            Den🐓☕️</h1>

        <div style="max-width: 800px; margin: 0 auto; padding: 20px; text-align: center;">


            <p class="text-lg mb-6 lg:mb-12">This is a simple web application for managing your
                chickens and their dens. You can create, view, and manage your chickens and dens with ease.</p>

            <p>Fugiat aliqua ad nostrud officia sit irure duis aute ipsum cillum. Est enim Lorem nostrud enim deserunt
                est
                pariatur tempor adipisicing velit commodo. Fugiat sint nisi fugiat enim dolore veniam quis sint sunt
                commodo
                non. Culpa amet ad culpa enim tempor voluptate. Cillum in est sit excepteur.

                Ex culpa proident adipisicing adipisicing exercitation. Tempor duis non mollit aliquip consectetur
                laboris
                aliquip ea incididunt amet tempor aliqua. Et minim amet ea deserunt nostrud ea duis fugiat proident
                enim.
                Dolor proident sit Lorem minim ex.
            </p>

            <img src="{{ asset('imgs/ChickinsLogo.png') }}" alt="Chicken App"
                style="display: block; margin: 10px auto 0; width: 200px; height: auto;">
        </div>
    </x-site-layout>
</body>

</html>
