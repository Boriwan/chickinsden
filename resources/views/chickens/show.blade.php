<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>


</head>

<body>
    <x-site-layout>
        <div style="margin: 20px;">
            <button onclick="window.history.back()"
                style="background-color: #f99d34; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">&larr;
                Back</button>
            <h1 style="font-size: 2rem; font-weight: bold;"> {{ $chicken->name }} 🐓
                @if ($chicken->gender == 'male')
                    ♂
                @else
                    ♀
                @endif
            </h1>
            <img src="{{ asset('storage/' . $chicken->image) }}" alt="{{ $chicken->name }}" style="max-width: 300px;">
            <div style="margin-top: 20px;">
                <ul style="list-style-type: none; padding: 0; font-size: 1.2rem;">
                    <li>Age: {{ \Carbon\Carbon::parse($chicken->born_date)->age }}</li>
                    <li>Born: {{ $chicken->born_date }}</li>
                    <li>Breed: {{ $chicken->breed_id }}</li>
                    <li>Height: {{ $chicken->height }} cm</li>
                    <li>Weight: {{ $chicken->weight }}</li>
                    <a href="/dens/{{ $chicken->den_id }}" style="text-decoration: underline; color: #f99d34;">
                        <li>Den: {{ $chicken->den_id }}</li>
                    </a>
                </ul>
            </div>
        </div>
    </x-site-layout>
</body>

</html>
