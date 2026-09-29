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
            <h1>
                Breeds administration
            </h1>
            <table style="width: 100%; border-collapse: collapse;">
                <thead style="background-color: #f99d34; color: white; text-align: left; border: 2px solid #000000;">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody style="background-color: #f2f2f2; border: 2px solid #000000;">
                    @foreach ($breeds as $breed)
                        <tr style="border: 2px solid #000000;">
                            <td>{{ $breed->id }}</td>
                            <td>{{ $breed->name }}</td>
                            <td>{{ $breed->description }}</td>
                            <td>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-site-layout>
</body>

</html>
