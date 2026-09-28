<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>


</head>

<body>
    <x-site-layout>
        <div style="padding: 1rem; background-color:burlywood; width: auto">

            <h1 style="font-size: 2rem;">
                Admin page
            </h1>

            <div style="background-color: bisque; font-weight:bold;">
                <h2>
                    <a href="/admin/chickens">Administrate chickens</a>
                </h2>

                <h2>
                    <a href="/admin/breeds">Administrate breeds</a>
                </h2>
            </div>
        </div>

    </x-site-layout>
</body>

</html>
