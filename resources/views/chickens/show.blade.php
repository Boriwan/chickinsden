<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>


</head>

<body>
    <x-site-layout>
        <div>
            <h1> {{ $chicken->name }} 🐓</h1>
            <img src="{{ asset('storage/' . $chicken->image) }}" alt="{{ $chicken->name }}" style="max-width: 300px;">
            <div>


                <ul>
                    <li>Gender: {{ $chicken->gender }}</li>
                    <li>Born: {{ $chicken->born_date }}</li>
                    <li>Breed: {{ $chicken->breed_id }}</li>
                    <li>Height: {{ $chicken->height }} cm</li>
                    <li>Weight: {{ $chicken->weight }}</li>
                    <li>Den: {{ $chicken->den_id }}</li>
                </ul>


            </div>
        </div>
    </x-site-layout>
</body>

</html>
