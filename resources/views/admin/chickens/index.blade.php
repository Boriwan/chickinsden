<x-site-layout>
    <div class="m-5">
        <h1 class="mb-4 text-3xl font-bold">Admin view chickens</h1>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left">
                <thead class="bg-brand text-white">
                    <tr>
                        <th class="border border-surface-border px-4 py-2">ID</th>
                        <th class="border border-surface-border px-4 py-2">Name</th>
                        <th class="border border-surface-border px-4 py-2">Date of Birth</th>
                        <th class="border border-surface-border px-4 py-2">Breed</th>
                        <th class="border border-surface-border px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-gray-100">
                    @foreach ($chickens as $chicken)
                        <tr>
                            <td class="border border-surface-border px-4 py-2">{{ $chicken->id }}</td>
                            <td class="border border-surface-border px-4 py-2">{{ $chicken->name }}</td>
                            <td class="border border-surface-border px-4 py-2">{{ $chicken->birth_date }}</td>
                            <td class="border border-surface-border px-4 py-2">{{ $chicken->breed_id }}</td>
                            <td class="border border-surface-border px-4 py-2"></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-site-layout>
