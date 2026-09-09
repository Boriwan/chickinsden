<x-site-layout>
    Fill out form

    <form method="POST" action="{{ route('admin.chickens.store') }}">
        @csrf

        <div>
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" required>
        </div>


        <div>
            <label for="gender">Gender:</label>
            <select name="gender" id="gender">
                <option value="male">Male</option>
                <option value="female">Female</option>
            </select>
        </div>
        <div>
            <label for="born_date">Date of Birth:</label>
            <input type="date" name="born_date" id="born_date" required>
        </div>

        <div>
            <label for="breed_id">Breed ID:</label>
            <input type="number" name="breed_id" id="breed_id" required>
        </div>

        <div>
            <label for="den_id">Den ID:</label>
            <input type="number" name="den_id" id="den_id" required>
        </div>

        <div>
            <label for="height">Height:</label>
            <input type="number" name="height" id="height" required>
        </div>

        <div>
            <label for="weight">Weight:</label>
            <select name="weight" id="weight">
                <option value="light">light</option>
                <option value="medium">medium</option>
                <option value="heavy">heavy</option>
            </select>
        </div>


        <button type="submit"
            style="background-color: #f99d34; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">Create
            Chicken</button>
    </form>
</x-site-layout>
