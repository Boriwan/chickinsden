<x-site-layout>
    <div class="mx-auto max-w-xl px-5 py-8">
        <h1 class="mb-6 text-3xl font-bold text-stone-900">Edit {{ $chicken->name }}</h1>

        <x-chicken-form :chicken="$chicken" :breeds="$breeds" />
    </div>
</x-site-layout>
