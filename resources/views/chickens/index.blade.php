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
            <h1>Test den's chickens🐓🪹</h1>


            <div>
                @foreach ($chickens as $chicken)
                    <h2>{{ $chicken->name }}</h2>

                    <ul>
                        @if ($chicken->gender == 'male')
                            <li>Gender: ♂</li>
                        @else
                            <li>Gender: ♀</li>
                        @endif
                        <li>Den: {{ $chicken->den_id }}</li>
                    </ul>

                    <img src="{{ $chicken->image }}" alt="{{ $chicken->name }}" width="200px">
                    <a style="text-decoration: underline;" href="/chickens/{{ $chicken->id }}">View
                        Details</a>
                    <hr>
                @endforeach
            </div>
        </div>
    </x-site-layout>
</body>

</html>
