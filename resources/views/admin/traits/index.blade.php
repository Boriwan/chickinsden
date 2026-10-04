<x-site-layout>
    <div class="mx-auto max-w-6xl px-5 py-8">
        <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
            <x-page-heading icon="tag" heading-class="">Traits</x-page-heading>
        </div>

        {{--
            A trait is only a name, so it is added inline rather than through a
            separate form page.
        --}}
        <div class="mb-6 rounded-xl border border-surface-border bg-surface p-4 shadow-sm">
            <form method="POST" action="{{ route('admin.traits.store') }}">
                @csrf

                <div class="min-w-56 flex-1">
                    <label for="name" class="mb-1.5 block text-sm font-medium text-stone-700">
                        New trait
                    </label>

                    <input type="text" name="name" id="name" required maxlength="50"
                        value="{{ old('name') }}" placeholder="e.g. Friendly"
                        class="w-full rounded-lg border shadow-sm focus:ring-2 {{ $errors->has('name') ? 'border-red-400 focus:border-red-500 focus:ring-red-500' : 'border-stone-300 focus:border-brand-500 focus:ring-brand-500' }}">

                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <x-partials.icon-picker class="mt-4" />

                <div class="mt-4 flex flex-wrap items-center justify-end gap-3">
                    <x-button type="submit">
                        <x-icon name="plus" class="h-4 w-4" />
                        Add trait
                    </x-button>
                </div>
            </form>
        </div>

        <div class="overflow-hidden rounded-xl border border-surface-border bg-surface shadow-sm">
            @if ($traits->isEmpty())
                <div class="flex flex-col items-center gap-2 px-6 py-16 text-center">
                    <x-icon name="tag" class="h-10 w-10 text-stone-300" />
                    <p class="font-semibold text-stone-900">No traits yet</p>
                    <p class="text-sm text-stone-500">Add the first one above, then pick it when logging a chicken.</p>
                </div>
            @else
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-surface-border bg-brand-50 text-xs uppercase tracking-wide text-brand-700">
                        <tr>
                            <th colspan="2" class="px-4 py-3 font-semibold">Name and icon</th>
                            <th class="px-4 py-3 font-semibold">Chickens</th>
                            <th class="px-4 py-3 font-semibold">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-surface-border">
                        @foreach ($traits as $trait)
                            <tr class="transition hover:bg-brand-50/50">
                                {{-- Name and icon share one form, so a rename and an icon change are saved
                                     together by the same tick. --}}
                                <td colspan="2" class="px-4 py-3">
                                    <form method="POST" action="{{ route('admin.traits.update', $trait) }}">
                                        @csrf
                                        @method('PUT')

                                        <div class="group flex flex-wrap items-start gap-3">
                                            <input type="text" name="name" value="{{ $trait->name }}" required
                                                maxlength="50"
                                                aria-label="Rename {{ $trait->name }}"
                                                class="w-full max-w-xs rounded-lg border border-transparent bg-transparent px-2 py-1 font-medium text-stone-900 shadow-sm transition focus:border-stone-300 focus:bg-surface focus:ring-2 focus:ring-brand-500">

                                            <x-partials.icon-picker :selected="$trait->icon" label=""
                                                class="w-auto flex-1" />

                                            <button type="submit" aria-label="Save {{ $trait->name }}"
                                                class="mt-0.5 shrink-0 rounded-lg p-1.5 text-stone-500 opacity-0 transition group-focus-within:opacity-100 hover:bg-stone-100 hover:text-stone-900 focus-visible:opacity-100">
                                                <x-icon name="check" class="h-4 w-4" />
                                            </button>
                                        </div>
                                    </form>
                                </td>

                                <td class="px-4 py-3 text-stone-600">{{ $trait->chickens_count }}</td>

                                <td class="px-4 py-3">
                                    <form method="POST" action="{{ route('admin.traits.destroy', $trait) }}"
                                        onsubmit="return confirm('Delete {{ $trait->name }}? It will be removed from every chicken that has it.')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                            class="inline-flex items-center gap-1.5 rounded-lg px-2.5 py-1.5 font-medium text-red-600 transition hover:bg-red-50 hover:text-red-700">
                                            <x-icon name="trash" class="h-4 w-4" />
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        @if ($traits->hasPages())
            <div class="mt-6">
                {{ $traits->links() }}
            </div>
        @endif
    </div>
</x-site-layout>