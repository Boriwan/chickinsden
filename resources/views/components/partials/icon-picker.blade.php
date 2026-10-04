@props([
    // The icon currently stored on the trait.
    'selected' => null,
    'name' => 'icon',
    'label' => 'Icon',
])

@php
    $icons = App\Models\ChickenTrait::icons();
@endphp

{{--
    A preset list rather than an upload. The value stored is the key into the
    <x-icon> map, which is why the options come from the model.
--}}
<div>
    @if ($label !== '')
        <span class="mb-1.5 block text-sm font-medium text-stone-700">{{ $label }}</span>
    @endif

    <div class="flex flex-wrap gap-1.5 rounded-lg border border-surface-border bg-surface p-2">
        @foreach ($icons as $icon)
            <label class="group relative cursor-pointer" title="{{ ucfirst(str_replace('-', ' ', $icon)) }}">
                <input type="radio" name="{{ $name }}" value="{{ $icon }}" class="peer sr-only"
                    @checked(old($name, $selected) === $icon)>

                <span
                    class="flex h-8 w-8 items-center justify-center rounded-lg text-stone-500 ring-1 ring-transparent transition peer-checked:bg-brand-100 peer-checked:text-brand-800 peer-checked:ring-brand-500 peer-focus-visible:outline peer-focus-visible:outline-2 peer-focus-visible:outline-offset-2 peer-focus-visible:outline-brand-500 hover:bg-stone-100 hover:text-stone-900">
                    <x-icon :name="$icon" class="h-4 w-4" />
                </span>

                <span class="sr-only">{{ ucfirst(str_replace('-', ' ', $icon)) }}</span>
            </label>
        @endforeach
    </div>

    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror

    <p class="mt-1 text-xs text-stone-400">Optional. A trait without one shows a generic tag.</p>
</div>