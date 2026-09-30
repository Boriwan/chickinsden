<x-site-layout>
    <div class="mx-auto max-w-6xl px-5 py-8">
        <h1 class="mb-6 text-3xl font-bold text-stone-900">Breeds Wiki</h1>

        @if ($breeds->isEmpty())
            <div class="flex flex-col items-center gap-4 rounded-xl border border-dashed border-surface-border bg-surface px-6 py-16 text-center">
                <x-icon name="folder" class="h-12 w-12 text-stone-300" />
                <p class="font-semibold text-stone-900">No breeds yet</p>
            </div>
        @else
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($breeds as $breed)
                    <a href="{{ route('breeds.show', $breed) }}"
                        class="flex flex-col gap-2 rounded-xl border border-surface-border bg-surface p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                        <h2 class="text-lg font-bold text-stone-900">{{ $breed->name }}</h2>
                        <p class="text-sm text-stone-500">{{ $breed->description }}</p>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</x-site-layout>
