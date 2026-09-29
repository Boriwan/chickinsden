<x-site-layout>
    <div class="m-5">
        <x-button onclick="window.history.back()">&larr; Back</x-button>

        <h1 class="mt-4 text-3xl font-bold">{{ $breed->name }}🪹</h1>
        <p class="mt-2 font-bold">Description:</p>
        <p>{{ $breed->description }}</p>
    </div>
</x-site-layout>
