<x-site-layout>
    <div class="mx-auto max-w-6xl px-5 py-8">
        <h1 class="mb-6 text-3xl font-bold text-stone-900">Breeds</h1>

        <div class="overflow-hidden rounded-xl border border-surface-border bg-surface shadow-sm">
            @if ($breeds->isEmpty())
                <div class="flex flex-col items-center gap-2 px-6 py-16 text-center">
                    <x-icon name="folder" class="h-10 w-10 text-stone-300" />
                    <p class="font-semibold text-stone-900">No breeds yet</p>
                </div>
            @else
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-surface-border bg-brand-50 text-xs uppercase tracking-wide text-brand-700">
                        <tr>
                            <th class="px-4 py-3 font-semibold">ID</th>
                            <th class="px-4 py-3 font-semibold">Name</th>
                            <th class="px-4 py-3 font-semibold">Description</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-border">
                        @foreach ($breeds as $breed)
                            <tr class="transition hover:bg-brand-50/50">
                                <td class="px-4 py-3 text-stone-500">{{ $breed->id }}</td>
                                <td class="px-4 py-3 font-medium text-stone-900 underline"> <a href="{{ route('breeds.show', $breed) }}">{{ $breed->name }}</a></td>
                                <td class="px-4 py-3 text-stone-600">{{ $breed->description }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</x-site-layout>
