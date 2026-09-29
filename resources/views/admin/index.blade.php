<x-site-layout>
    <div class="m-5 w-fit rounded-lg border border-surface-border bg-[#deb887] p-4">
        <h1 class="text-3xl font-bold">Admin page</h1>

        <div class="mt-4 rounded-lg bg-[#ffe4c4] p-4 font-bold">
            <h2 class="text-xl">
                <a href="{{ route('admin.chickens.index') }}" class="hover:text-brand">Administrate chickens</a>
            </h2>

            <h2 class="text-xl">
                <a href="{{ route('admin.breeds.index') }}" class="hover:text-brand">Administrate breeds</a>
            </h2>
        </div>
    </div>
</x-site-layout>
