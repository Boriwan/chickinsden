<x-site-layout>
    <div class="m-8">
        <h1 class="mb-4 text-3xl font-bold">Add a new Chicken</h1>

        <form method="POST" action="{{ route('admin.chickens.store') }}"
            class="max-w-lg space-y-3 rounded-lg bg-[#fff8f0] p-5">
            @csrf

            <div>
                <label for="name" class="block font-bold">Name:</label>
                <input type="text" name="name" id="name" required
                    class="mt-1 w-full rounded-md border-gray-300">
            </div>

            <div>
                <label for="gender" class="block font-bold">Gender:</label>
                <select name="gender" id="gender" class="mt-1 w-full rounded-md border-gray-300">
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>
            </div>

            <div>
                <label for="birth_date" class="block font-bold">Date of Birth:</label>
                <input type="date" name="birth_date" id="birth_date" required
                    class="mt-1 w-full rounded-md border-gray-300">
            </div>

            <div>
                <label for="breed_id" class="block font-bold">Breed:</label>
                <select name="breed_id" id="breed_id" class="mt-1 w-full rounded-md border-gray-300">
                    @foreach ($breeds as $breed)
                        <option value="{{ $breed->id }}">{{ $breed->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="height" class="block font-bold">Height (cm):</label>
                <input type="number" name="height" id="height" step="any" required
                    class="mt-1 w-full rounded-md border-gray-300">
            </div>

            <div>
                <label for="weight" class="block font-bold">Weight:</label>
                <select name="weight" id="weight" class="mt-1 w-full rounded-md border-gray-300">
                    <option value="light">Light</option>
                    <option value="medium">Medium</option>
                    <option value="heavy">Heavy</option>
                </select>
            </div>

            <x-button type="submit" class="mt-2">Create Chicken</x-button>
        </form>
    </div>
</x-site-layout>
