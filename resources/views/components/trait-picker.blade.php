@props([
    'availableTraits',
    'selectedIds' => [],
])

{{--
    Trait picker for the chicken form.

    A trait is chosen by clicking rather than typing: the available list is
    already known to the server, so free text would only allow misspelled or
    duplicate traits. Selected traits keep their chip highlighted in place, so
    what is picked stays visible alongside what is still available.

    Selection rides on real traits[] checkboxes, which keeps the submit a plain
    POST that sync() consumes directly, with no JSON to parse. Each chip owns
    its own small state rather than sharing one object across the fieldset, so
    there is no parent scope for the expression to lose track of.
--}}
<fieldset>
    <legend class="mb-1.5 block text-sm font-medium text-stone-700">Traits</legend>

    @if ($availableTraits->isEmpty())
        <p class="rounded-lg border border-dashed border-surface-border px-4 py-6 text-center text-sm text-stone-500">
            No traits exist yet. An admin can add some in the admin area.
        </p>
    @else
        <div class="flex flex-wrap gap-2">
            @foreach ($availableTraits as $trait)
                @php
                    $selected = in_array($trait->id, $selectedIds);
                @endphp

                <label class="inline-flex cursor-pointer select-none items-center gap-1.5 rounded-full px-3 py-1.5 text-sm font-semibold transition"
                    x-data="{ on: @js($selected) }"
                    x-bind:class="on
                        ? 'bg-brand-100 text-brand-800 ring-2 ring-brand-500'
                        : 'bg-surface text-stone-600 ring-1 ring-surface-border hover:bg-brand-50 hover:text-brand-800'">
                    {{-- The checkbox is the source of truth, so the chip can never look picked when nothing is submitted. --}}
                    <input type="checkbox" name="traits[]" value="{{ $trait->id }}" class="sr-only"
                        @checked($selected)
                        x-on:change="on = $el.checked">

                    {{ $trait->name }}

                    <span class="-mr-1 text-lg leading-none" x-show="on" x-cloak
                        aria-hidden="true">&times;</span>
                </label>
            @endforeach
        </div>
    @endif
</fieldset>