<x-site-layout>
    <h1>Edit Chicken {{ $chicken->name }}</h1>
    <form method="POST" action="{{ route('admin.chickens.update', $chicken->id) }}">
        @csrf
        @method('PUT')

        <div>
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" value="{{ $chicken->name }}" required>
        </div>


        <select name="gender" id="gender">
            <option value="male" {{ $chicken->gender === 'male' ? 'selected' : '' }}>Male</option>
            <option value="female" {{ $chicken->gender === 'female' ? 'selected' : '' }}>Female</option>
        </select>
        <div>
            <label for="born_date">Date of Birth:</label>
            <input type="date" name="born_date" id="born_date" value="{{ $chicken->born_date }}" required>
        </div>

        <div>
            <label for="breed_id">Breed ID:</label>
            <input type="number" name="breed_id" id="breed_id" value="{{ $chicken->breed_id }}" required>
        </div>

        <div>
            <label for="den_id">Den ID:</label>
            <input type="number" name="den_id" id="den_id" value="{{ $chicken->den_id }}" required>
        </div>

        <div>
            <label for="height">Height:</label>
            <input type="number" name="height" id="height" value="{{ $chicken->height }}" required>
        </div>

        <select name="weight" id="weight">
            <option value="light" {{ $chicken->weight === 'light' ? 'selected' : '' }}>light</option>
            <option value="medium" {{ $chicken->weight === 'medium' ? 'selected' : '' }}>medium</option>
            <option value="heavy" {{ $chicken->weight === 'heavy' ? 'selected' : '' }}>heavy</option>
        </select>


        <button type="submit">Edit Chicken</button>
    </form>
</x-site-layout>
