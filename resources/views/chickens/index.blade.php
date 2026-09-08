<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href="/src/style.css" rel="stylesheet">

</head>

<body>

    <x-site-layout>
        <div>
            <h1 style="font-size: 2rem; font-weight: bold; margin: 20px;">My chickens🐓🪹</h1>
            <hr>

            <div style="display: flex; flex-wrap: wrap; gap: 20px; margin: 20px;">

                @foreach ($chickens as $chicken)
                    <div
                        style="width: 220px; padding: 20px; border: 1px solid #d6b98c; border-radius: 10px; background-color: {{ $chicken->gender == 'male' ? '#e8f0ff' : '#fff0f5' }};        ">

                        <h2>{{ $chicken->name }}</h2>

                        <img src="{{ $chicken->image }}" alt="{{ $chicken->name }}"
                            style=" width: 200px; height: 200px; object-fit: cover; display: block; margin: 10px auto;">

                        <ul>
                            @if ($chicken->gender == 'male')
                                <li>Gender: ♂</li>
                            @else
                                <li>Gender: ♀</li>
                            @endif

                            <li>Den: {{ $chicken->den_id }}</li>
                        </ul>

                        <a href="/chickens/{{ $chicken->id }}" style="text-decoration: underline;">
                            View Details
                        </a>

                    </div>
                @endforeach

            </div>
        </div>
    </x-site-layout>
</body>

</html>
