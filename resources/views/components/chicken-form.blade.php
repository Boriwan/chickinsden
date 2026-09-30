@props([
    'chicken' => null,
    'breeds',
    'cancelUrl' => null,
])

@php
    $editing = $chicken !== null;

    $value = fn (string $field) => old($field, $chicken?->{$field});

    $birthDate = old('birth_date', $chicken?->birth_date);
    $birthDate = $birthDate ? \Carbon\Carbon::parse($birthDate)->format('Y-m-d') : null;

    $cancelUrl ??= $editing ? route('chickens.show', $chicken) : null;

    $fieldClass = fn (string $field) => trim(
        'w-full rounded-lg shadow-sm ' . ($errors->has($field)
            ? 'border-red-400 focus:border-red-500 focus:ring-red-500'
            : 'border-stone-300 focus:border-brand-500 focus:ring-brand-500')
    );
@endphp

<form method="POST"
    action="{{ $editing ? route('chickens.update', $chicken) : route('chickens.store') }}"
    class="space-y-4 rounded-xl border border-surface-border bg-surface p-6 shadow-sm">
    @csrf

    @if ($editing)
        @method('PUT')
    @endif

    <div>
        <label for="name" class="mb-1.5 block text-sm font-medium text-stone-700">Name</label>
        <input type="text" name="name" id="name" value="{{ $value('name') }}" required
            class="{{ $fieldClass('name') }}">
        @error('name')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="gender" class="mb-1.5 block text-sm font-medium text-stone-700">Gender</label>
            <select name="gender" id="gender" class="{{ $fieldClass('gender') }}">
                <option value="male" @selected($value('gender') === 'male')>Male</option>
                <option value="female" @selected($value('gender') === 'female')>Female</option>
            </select>
            @error('gender')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="birth_date" class="mb-1.5 block text-sm font-medium text-stone-700">Birth date</label>
            <input type="date" name="birth_date" id="birth_date" value="{{ $birthDate }}" required
                class="{{ $fieldClass('birth_date') }}">
            @error('birth_date')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div>
        <label for="breed_id" class="mb-1.5 block text-sm font-medium text-stone-700">Breed</label>
        <select name="breed_id" id="breed_id" class="{{ $fieldClass('breed_id') }}">
            @foreach ($breeds as $breed)
                <option value="{{ $breed->id }}" @selected(old('breed_id', $chicken?->breed_id) == $breed->id)>
                    {{ $breed->name }}
                </option>
            @endforeach
        </select>
        @error('breed_id')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="height" class="mb-1.5 block text-sm font-medium text-stone-700">Height (cm)</label>
            <input type="number" name="height" id="height" step="any" value="{{ $value('height') }}" required
                class="{{ $fieldClass('height') }}">
            @error('height')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="weight" class="mb-1.5 block text-sm font-medium text-stone-700">Weight</label>
            <select name="weight" id="weight" class="{{ $fieldClass('weight') }}">
                <option value="light" @selected($value('weight') === 'light')>Light</option>
                <option value="medium" @selected($value('weight') === 'medium')>Medium</option>
                <option value="heavy" @selected($value('weight') === 'heavy')>Heavy</option>
            </select>
            @error('weight')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="flex justify-end gap-3 pt-2">
        @if ($cancelUrl)
            <x-button color="gray" href="{{ $cancelUrl }}">Cancel</x-button>
        @endif

        <x-button type="submit">
            @if ($editing)
                <x-icon name="pencil" class="h-4 w-4" />
                Save changes
            @else
                <x-icon name="plus" class="h-4 w-4" />
                Create chicken
            @endif
        </x-button>
    </div>
</form>
