<x-site-layout>
    <div class="m-5">
        <h1 class="mb-4 text-3xl font-bold">Breeds administration</h1>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left">
                <thead class="bg-brand text-white">
                    <tr>
                        <th class="border border-surface-border px-4 py-2">ID</th>
                        <th class="border border-surface-border px-4 py-2">Name</th>
                        <th class="border border-surface-border px-4 py-2">Description</th>
                    </tr>
                </thead>
                <tbody class="bg-gray-100">
                    @foreach ($breeds as $breed)
                        <tr>
                            <td class="border border-surface-border px-4 py-2">{{ $breed->id }}</td>
                            <td class="border border-surface-border px-4 py-2">{{ $breed->name }}</td>
                            <td class="border border-surface-border px-4 py-2">{{ $breed->description }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-site-layout>
