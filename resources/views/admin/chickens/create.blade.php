<!DOCTYPE html>


<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <style>
    </style>
</head>

<body>
    <x-site-layout>
        <div style="margin: 2rem;">
            <h1 style=" font-size: 2rem; font-weight: bold;">Add a new Chicken</h1>

            <form method="POST" action="{{ route('admin.chickens.store') }}"
                style="max-width: 500px; padding: 20px; background: #fff8f0; border-radius: 10px;">

                @csrf

                <div style="margin-bottom: 12px;">
                    <label>Name:</label>
                    <input type="text" name="name" required style="width: 100%; padding: 8px;">
                </div>

                <div style="margin-bottom: 12px;">
                    <label>Gender:</label>
                    <select name="gender" style="width: 100%; padding: 8px;">
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>

                <div style="margin-bottom: 12px;">
                    <label>Date of Birth:</label>
                    <input type="date" name="born_date" required style="width: 100%; padding: 8px;">
                </div>

                <div style="margin-bottom: 12px;">
                    <label>Breed ID:</label>
                    <input type="number" name="breed_id" required style="width: 100%; padding: 8px;">
                </div>

                <div style="margin-bottom: 12px;">
                    <label>Den ID:</label>
                    <input type="number" name="den_id" required style="width: 100%; padding: 8px;">
                </div>

                <div style="margin-bottom: 12px;">
                    <label>Height:</label>
                    <input type="number" name="height" required style="width: 100%; padding: 8px;">
                </div>

                <div style="margin-bottom: 15px;">
                    <label>Weight:</label>
                    <select name="weight" style="width: 100%; padding: 8px;">
                        <option value="light">Light</option>
                        <option value="medium">Medium</option>
                        <option value="heavy">Heavy</option>
                    </select>
                </div>

                <button type="submit"
                    style="background: #f99d34; color: white; border: none; padding: 10px 20px; border-radius: 5px;">
                    Create Chicken
                </button>

            </form>
        </div>
    </x-site-layout>
</body>

</html>
