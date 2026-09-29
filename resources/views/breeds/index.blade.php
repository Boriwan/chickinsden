<x-site-layout>
    <h1 class="m-5 text-3xl font-bold">My breeds📂</h1>
    <hr>

    <div class="m-5 flex flex-wrap gap-5">
        @foreach ($breeds as $breed)
            <div class="w-56 rounded-lg border border-surface-border bg-surface p-5">
                <h2 class="text-xl font-bold">
                    <a href="{{ route('breeds.show', $breed) }}" class="underline hover:text-brand">
                        {{ $breed->name }}
                    </a>
                </h2>

                <ul class="mt-2 list-none p-0">
                    <li>Description: {{ $breed->description }}</li>
                </ul>
            </div>
        @endforeach
    </div>
</x-site-layout>
