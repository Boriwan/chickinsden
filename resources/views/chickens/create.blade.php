<x-site-layout>
    <div class="mx-auto max-w-xl px-5 py-8">
        <x-page-heading icon="plus">Add a new chicken</x-page-heading>

        <x-chicken-form :breeds="$breeds" :traits="$traits" />
    </div>
</x-site-layout>
