<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <style>
        img {
            border-radius: 10px;
        }
        .card {
            width: 220px;
            padding: 20px;
            border: 1px solid #d6b98c;
            border-radius: 10px;
            transition: transform 0.2s ease;
        }

        .card:hover {
            transform: scale(1.05);
            cursor: pointer;
        }
    </style>

</head>

<body>

    <x-site-layout>
        <div>
            <h1 style="font-size: 2rem; font-weight: bold; margin: 20px;">My chickens🐓</h1>
            <hr>

            <div style="display: flex; flex-wrap: wrap; gap: 20px; margin: 20px;">

                @foreach ($chickens as $chicken)
                    <a href="/chickens/{{ $chicken->id }}">
                        <div class="card"
                            style="background-color: {{ $chicken->gender == 'male' ? '#e8f0ff' : '#fff0f5' }};">
                            <h2>{{ $chicken->name }}
                                @if ($chicken->gender == 'male')
                                    ♂
                                @else
                                    ♀
                                @endif
                            </h2>

                            <img src="{{ $chicken->image }}" alt="{{ $chicken->name }}"
                                style=" width: 200px; height: 200px; object-fit: cover; display: block; margin: 10px auto;">

                            <ul>
                                <li>Age: {{ \Carbon\Carbon::parse($chicken->born_date)->age }}</li>
                                <li>Den: {{ $chicken->den_id }}</li>
                            </ul>
                        </div>
                    </a>
                @endforeach

            </div>
        </div>
    </x-site-layout>
</body>

</html>
