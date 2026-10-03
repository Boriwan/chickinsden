@props([
    'breed' => null,
    'cancelUrl' => null,
])

@php
    $editing = $breed !== null;

    $fieldClass = fn (string $field) => trim(
        'w-full rounded-lg shadow-sm ' . ($errors->has($field)
            ? 'border-red-400 focus:border-red-500 focus:ring-red-500'
            : 'border-stone-300 focus:border-brand-500 focus:ring-brand-500')
    );

    $cancelUrl ??= route('admin.breeds.index');
@endphp

{{--
    Live validation mirrors the server rules in AdminBreedController. The server
    stays authoritative; this only saves a round trip and points at the field.
--}}
<form method="POST"
    action="{{ $editing ? route('admin.breeds.update', $breed) : route('admin.breeds.store') }}"
    class="space-y-4 rounded-xl border border-surface-border bg-surface p-6 shadow-sm"
    x-data="{
        name: @js(old('name', $breed?->name ?? '')),
        description: @js(old('description', $breed?->description ?? '')),
        touched: { name: false, description: false },
        MAX_NAME: 20,
        MAX_DESCRIPTION: 2000,
        get nameError() {
            if (!this.touched.name) return ''
            if (this.name.trim() === '') return 'The name is required.'
            if (this.name.length > this.MAX_NAME) return `The name may not be longer than ${this.MAX_NAME} characters.`
            return ''
        },
        get descriptionError() {
            if (!this.touched.description) return ''
            if (this.description.trim() === '') return 'The description is required.'
            if (this.description.length > this.MAX_DESCRIPTION) return `The description may not be longer than ${this.MAX_DESCRIPTION} characters.`
            return ''
        },
        get valid() {
            return this.name.trim() !== ''
                && this.name.length <= this.MAX_NAME
                && this.description.trim() !== ''
                && this.description.length <= this.MAX_DESCRIPTION
        },
    }"
    x-on:submit="if (! valid) { $el.classList.add('was-validated') }">
    @csrf

    @if ($editing)
        @method('PUT')
    @endif

    <div>
        <label for="name" class="mb-1.5 block text-sm font-medium text-stone-700">Name</label>
        <input type="text" name="name" id="name" required maxlength="100"
            x-model="name"
            x-on:blur="touched.name = true"
            x-bind:class="nameError ? 'border-red-400 focus:border-red-500 focus:ring-red-500' : 'border-stone-300 focus:border-brand-500 focus:ring-brand-500'"
            class="w-full rounded-lg border shadow-sm focus:ring-2">

        {{-- Live hint replaces the server message for this field while typing. --}}
        <p class="mt-1 text-sm text-red-600" x-show="nameError" x-text="nameError"></p>

        @error('name')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror

        <p class="mt-1 text-right text-xs text-stone-400" x-show="! nameError" x-text="`${name.length}/${MAX_NAME}`"></p>
    </div>

    <div>
        <label for="description" class="mb-1.5 block text-sm font-medium text-stone-700">Description</label>
        <textarea name="description" id="description" rows="5" required maxlength="2000"
            x-model="description"
            x-on:blur="touched.description = true"
            x-bind:class="descriptionError ? 'border-red-400 focus:border-red-500 focus:ring-red-500' : 'border-stone-300 focus:border-brand-500 focus:ring-brand-500'"
            class="w-full rounded-lg border shadow-sm focus:ring-2"></textarea>

        <p class="mt-1 text-sm text-red-600" x-show="descriptionError" x-text="descriptionError"></p>

        @error('description')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror

        <p class="mt-1 text-right text-xs text-stone-400" x-show="! descriptionError"
            x-text="`${description.length}/${MAX_DESCRIPTION}`"></p>
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
                Create breed
            @endif
        </x-button>
    </div>
</form>