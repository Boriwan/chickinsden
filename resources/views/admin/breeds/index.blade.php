<x-site-layout>
    <div class="mx-auto max-w-6xl px-5 py-8">
        <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
            <x-page-heading icon="folder" heading-class="">Breeds</x-page-heading>

            <x-button href="{{ route('admin.breeds.create') }}">
                <x-icon name="plus" class="h-4 w-4" />
                Add breed
            </x-button>
        </div>

        <div class="overflow-hidden rounded-xl border border-surface-border bg-surface shadow-sm">
            @if ($breeds->isEmpty())
                <div class="flex flex-col items-center gap-2 px-6 py-16 text-center">
                    <x-icon name="folder" class="h-10 w-10 text-stone-300" />
                    <p class="font-semibold text-stone-900">No breeds yet</p>
                    <p class="text-sm text-stone-500">Add the first one to start filing chickens under it.</p>
                </div>
            @else
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-surface-border bg-brand-50 text-xs uppercase tracking-wide text-brand-700">
                        <tr>
                            <th class="px-4 py-3 font-semibold">ID</th>
                            <th class="px-4 py-3 font-semibold">Name</th>
                            <th class="px-4 py-3 font-semibold">Description</th>
                            <th class="px-4 py-3 font-semibold">Chickens</th>
                            <th class="px-4 py-3 font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-border">
                        @foreach ($breeds as $breed)
                            <tr class="transition hover:bg-brand-50/50">
                                <td class="px-4 py-3 text-stone-500">{{ $breed->id }}</td>

                                <td class="px-4 py-3 font-medium text-stone-900 underline">
                                    <a href="{{ route('breeds.show', $breed) }}">{{ $breed->name }}</a>
                                </td>

                                <td class="px-4 py-3 text-stone-600">{{ $breed->description }}</td>

                                <td class="px-4 py-3 text-stone-600">{{ $breed->chickens_count }}</td>

                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-1">
                                        <a href="{{ route('admin.breeds.edit', $breed) }}"
                                            class="inline-flex items-center gap-1.5 rounded-lg px-2.5 py-1.5 font-medium text-stone-600 transition hover:bg-stone-100 hover:text-stone-900">
                                            <x-icon name="pencil" class="h-4 w-4" />
                                            Edit
                                        </a>

                                        <form method="POST" action="{{ route('admin.breeds.destroy', $breed) }}"
                                            onsubmit="return confirm('Delete {{ $breed->name }}? This cannot be undone.')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                class="inline-flex items-center gap-1.5 rounded-lg px-2.5 py-1.5 font-medium text-red-600 transition hover:bg-red-50 hover:text-red-700">
                                                <x-icon name="trash" class="h-4 w-4" />
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</x-site-layout>