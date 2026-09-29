<x-site-layout>
    <div class="m-5">
        <h1 class="mb-4 text-3xl font-bold">Edit Chicken {{ $chicken->name }}</h1>

        <form method="POST" action="{{ route('admin.chickens.update', $chicken) }}"
            class="max-w-lg space-y-3 rounded-lg bg-[#fff8f0] p-5">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block font-bold">Name:</label>
                <input type="text" name="name" id="name" value="{{ $chicken->name }}" required
                    class="mt-1 w-full rounded-md border-gray-300">
            </div>

            <div>
                <label for="gender" class="block font-bold">Gender:</label>
                <select name="gender" id="gender" class="mt-1 w-full rounded-md border-gray-300">
                    <option value="male" @selected($chicken->gender === 'male')>Male</option>
                    <option value="female" @selected($chicken->gender === 'female')>Female</option>
                </select>
            </div>

            <div>
                <label for="birth_date" class="block font-bold">Date of Birth:</label>
                <input type="date" name="birth_date" id="birth_date" value="{{ $chicken->birth_date }}" required
                    class="mt-1 w-full rounded-md border-gray-300">
            </div>

            <div>
                <label for="breed_id" class="block font-bold">Breed ID:</label>
                <input type="number" name="breed_id" id="breed_id" value="{{ $chicken->breed_id }}" required
                    class="mt-1 w-full rounded-md border-gray-300">
            </div>

            <div>
                <label for="height" class="block font-bold">Height (cm):</label>
                <input type="number" name="height" id="height" step="any" value="{{ $chicken->height }}" required
                    class="mt-1 w-full rounded-md border-gray-300">
            </div>

            <div>
                <label for="weight" class="block font-bold">Weight:</label>
                <select name="weight" id="weight" class="mt-1 w-full rounded-md border-gray-300">
                    <option value="light" @selected($chicken->weight === 'light')>Light</option>
                    <option value="medium" @selected($chicken->weight === 'medium')>Medium</option>
                    <option value="heavy" @selected($chicken->weight === 'heavy')>Heavy</option>
                </select>
            </div>

            <x-button type="submit" class="mt-2">Save changes</x-button>
        </form>
    </div>
</x-site-layout>
