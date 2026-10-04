<x-site-layout>
    <div class="mx-auto max-w-5xl px-5 py-8">
        <x-page-heading icon="settings">Admin</x-page-heading>

        <div class="grid gap-4 sm:grid-cols-2">
            <a href="{{ route('admin.chickens.index') }}"
                class="flex items-center gap-4 rounded-xl border border-surface-border bg-surface p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                <span class="flex h-11 w-11 items-center justify-center rounded-lg bg-brand-100 text-brand-700">
                    <x-icon name="egg" class="h-5 w-5" />
                </span>
                <span>
                    <span class="block font-semibold text-stone-900">Chickens</span>
                    <span class="text-sm text-stone-500">View and manage all chickens</span>
                </span>
            </a>

            <a href="{{ route('admin.breeds.index') }}"
                class="flex items-center gap-4 rounded-xl border border-surface-border bg-surface p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                <span class="flex h-11 w-11 items-center justify-center rounded-lg bg-brand-100 text-brand-700">
                    <x-icon name="folder" class="h-5 w-5" />
                </span>
                <span>
                    <span class="block font-semibold text-stone-900">Breeds</span>
                    <span class="text-sm text-stone-500">View and manage all breeds</span>
                </span>
            </a>

            <a href="{{ route('admin.traits.index') }}"
                class="flex items-center gap-4 rounded-xl border border-surface-border bg-surface p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                <span class="flex h-11 w-11 items-center justify-center rounded-lg bg-brand-100 text-brand-700">
                    <x-icon name="tag" class="h-5 w-5" />
                </span>
                <span>
                    <span class="block font-semibold text-stone-900">Traits</span>
                    <span class="text-sm text-stone-500">View and manage all traits</span>
                </span>
            </a>
        </div>
    </div>
</x-site-layout>
