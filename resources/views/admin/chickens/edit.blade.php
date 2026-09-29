<x-site-layout>
    <div class="mx-auto max-w-xl px-5 py-8">
        <h1 class="mb-6 text-3xl font-bold text-stone-900">Edit {{ $chicken->name }}</h1>

        <form method="POST" action="{{ route('admin.chickens.update', $chicken) }}"
            class="space-y-4 rounded-xl border border-surface-border bg-surface p-6 shadow-sm">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="mb-1.5 block text-sm font-medium text-stone-700">Name</label>
                <input type="text" name="name" id="name" value="{{ old('name', $chicken->name) }}" required
                    class="w-full rounded-lg border-stone-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="gender" class="mb-1.5 block text-sm font-medium text-stone-700">Gender</label>
                    <select name="gender" id="gender"
                        class="w-full rounded-lg border-stone-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
                        <option value="male" @selected(old('gender', $chicken->gender) === 'male')>Male</option>
                        <option value="female" @selected(old('gender', $chicken->gender) === 'female')>Female</option>
                    </select>
                </div>

                <div>
                    <label for="birth_date" class="mb-1.5 block text-sm font-medium text-stone-700">Birth date</label>
                    <input type="date" name="birth_date" id="birth_date"
                        value="{{ old('birth_date', \Carbon\Carbon::parse($chicken->birth_date)->format('Y-m-d')) }}" required
                        class="w-full rounded-lg border-stone-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
                </div>
            </div>

            <div>
                <label for="breed_id" class="mb-1.5 block text-sm font-medium text-stone-700">Breed</label>
                <select name="breed_id" id="breed_id"
                    class="w-full rounded-lg border-stone-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
                    @foreach ($breeds as $breed)
                        <option value="{{ $breed->id }}" @selected(old('breed_id', $chicken->breed_id) == $breed->id)>
                            {{ $breed->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="height" class="mb-1.5 block text-sm font-medium text-stone-700">Height (cm)</label>
                    <input type="number" name="height" id="height" step="any" value="{{ old('height', $chicken->height) }}"
                        required
                        class="w-full rounded-lg border-stone-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
                </div>

                <div>
                    <label for="weight" class="mb-1.5 block text-sm font-medium text-stone-700">Weight</label>
                    <select name="weight" id="weight"
                        class="w-full rounded-lg border-stone-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
                        <option value="light" @selected(old('weight', $chicken->weight) === 'light')>Light</option>
                        <option value="medium" @selected(old('weight', $chicken->weight) === 'medium')>Medium</option>
                        <option value="heavy" @selected(old('weight', $chicken->weight) === 'heavy')>Heavy</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <x-button color="gray" href="{{ route('chickens.show', $chicken) }}">Cancel</x-button>

                <x-button type="submit">
                    <x-icon name="pencil" class="h-4 w-4" />
                    Save changes
                </x-button>
            </div>
        </form>
    </div>
</x-site-layout>
