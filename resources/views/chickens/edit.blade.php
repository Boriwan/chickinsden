<x-site-layout>
    <div class="mx-auto max-w-xl px-5 py-8">
        <x-page-heading icon="pencil">Edit {{ $chicken->name }}</x-page-heading>

        <x-chicken-form :chicken="$chicken" :breeds="$breeds" :traits="$traits" />
    </div>
</x-site-layout>
