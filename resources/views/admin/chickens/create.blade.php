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
            <input type="text" name="gender" id="gender<" required>
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

        <select name="weight" id="weight">
            <option value="light">light</option>
            <option value="medium">medium</option>
            <option value="heavy">heavy</option>
        </select>


        <button type="submit">Create Chicken</button>
    </form>
</x-site-layout>
