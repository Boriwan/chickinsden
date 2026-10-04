<x-site-layout>
    <div class="mx-auto max-w-3xl px-5 py-12">
        <div class="mb-6 flex flex-wrap items-center gap-3">
            <x-button color="ghost" onclick="window.history.back()">
                <x-icon name="arrow-left" class="h-4 w-4" />
                Back
            </x-button>

            {{-- Editing a breed is admin-only, so only admins see these. --}}
            @if (auth()->user()?->is_admin)
                <x-button href="{{ route('admin.breeds.edit', $breed) }}">
                    <x-icon name="pencil" class="h-4 w-4" />
                    Edit
                </x-button>

                <form method="POST" action="{{ route('admin.breeds.destroy', $breed) }}"
                    onsubmit="return confirm('Delete {{ $breed->name }}? This cannot be undone.')">
                    @csrf
                    @method('DELETE')

                    <x-button type="submit" color="red">
                        <x-icon name="trash" class="h-4 w-4" />
                        Delete
                    </x-button>
                </form>
            @endif
        </div>

        <x-page-heading icon="book" heading-class="">{{ $breed->name }}</x-page-heading>

        <p class="mt-4 text-stone-600">{{ $breed->description }}</p>
    </div>
</x-site-layout>