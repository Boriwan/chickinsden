<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Chickins Den🐓🪹☕️</title>
    <meta name="description" content="">
    <meta name="keywords" content="">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        html {
            font-family: Monospace;
        }

        header div a:hover {
            background-color: #f99d34;
            padding: 10px 20px;
            border-radius: 5px;
            /*color: #fff;*/
            font-weight: bold;
            transition: all 0.2s ease;
        }
    </style>
</head>

<body style="background-color: #fff9b9;" class="min-h-screen flex flex-col">
    <header
        style="background-color: #f0ce8f;    height: 80px;
    display: flex;
    align-items: center;
    padding: 0 30px;
    gap: 50px;">
        <a href="/">
            <img src="{{ asset('imgs/ChickinsLogo.png') }}" alt="Chicken App"
                style="width: 90px; height: 90px; object-fit: contain;"> </a>

        <div style="display: flex; gap: 5rem;">
            @foreach ($menu as $item)
                <a href="{{ $item['link'] }}" style="padding-right: 8px;"> {{ $item['label'] }} </a>
            @endforeach
        </div>
    </header>

    <main class="flex-1">
        {{ $slot }}
    </main>


    <footer style="background-color: #f0ce8f ; padding: 15px; text-align: center;">
        Chickins Den🐓🪹☕️ - &copy; {{ date('Y') }} All rights reserved.
    </footer>
</body>

</html>
