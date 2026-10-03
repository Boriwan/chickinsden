<x-site-layout>
    <div class="mx-auto max-w-3xl px-5 py-8">
        <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
            <h1 class="text-3xl font-bold text-stone-900">Add breed</h1>

            <x-button color="gray" href="{{ route('admin.breeds.index') }}">
                <x-icon name="arrow-left" class="h-4 w-4" />
                Back
            </x-button>
        </div>

        <x-breed-form />
    </div>
</x-site-layout>