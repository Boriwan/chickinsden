<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>


</head>

<body>
    <x-site-layout>

        <h1 class="text-4xl font-bold text-center mb-6 lg:mb-12" style="margin-top:8rem">Welcome to Chickins Den🐓☕️</h1>
        @if (Route::has('login'))
            <div class="h-14.5 hidden lg:block"></div>
        @endif
        <div class="text-center" style="max-width: 800px; margin: 0 auto; padding: 20px;">

            <p class="text-lg text-center mb-6 lg:mb-12">This is a simple web application for managing your
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

                Duis laborum laboris labore ut enim. Minim ut nulla aliqua eiusmod pariatur. Elit ipsum tempor
                adipisicing
                duis ad ullamco eiusmod ea anim. Nulla excepteur nostrud est non ipsum excepteur. Aliquip mollit irure
                sunt
                ut. Nostrud id consectetur nostrud eiusmod mollit labore laboris. In aute deserunt deserunt sunt officia
                laborum nostrud nostrud officia excepteur aliqua eu.

                Consectetur ipsum Lorem commodo ullamco exercitation esse nostrud voluptate in do reprehenderit nostrud.
                Voluptate dolore velit irure do anim ea commodo culpa esse nulla tempor labore eiusmod. Do esse commodo
                cillum excepteur adipisicing laborum ad id dolore Lorem ut. Sit anim elit veniam esse ullamco ad
                laborum.
            </p>

            <button
                style=" margin-top: 5rem;background-color: #f99d34; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">
                <a href="/chickens">Show my chickens</a>
            </button>
        </div>
    </x-site-layout>
</body>

</html>
