<x-site-layout>
    <div class="mx-auto max-w-3xl px-5 py-12">
        <div class="mb-4 flex items-center gap-2">
            <x-button color="ghost" onclick="window.history.back()">
                <x-icon name="arrow-left" class="h-4 w-4" />
                Back
            </x-button>
        </div>

        <h1 class="text-3xl font-bold text-stone-900">{{ $breed->name }}</h1>

        <p class="mt-4 text-stone-600">{{ $breed->description }}</p>
    </div>
</x-site-layout>
