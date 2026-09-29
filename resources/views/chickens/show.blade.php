<x-site-layout>
    <div class="m-5">
        <div class="flex flex-wrap items-center gap-4">
            <x-button onclick="window.history.back()">&larr; Back</x-button>

            <x-button href="{{ route('admin.chickens.edit', $chicken) }}">Edit &#9998;</x-button>

            <form action="{{ route('admin.chickens.destroy', $chicken) }}" method="POST">
                @method('DELETE')
                @csrf

                <x-button type="submit" color="red">Delete &#128465;</x-button>
            </form>
        </div>

        <h1 class="mt-6 text-3xl font-bold">
            {{ $chicken->name }} &#128019;
            @if ($chicken->gender == 'male')
                &#9794;
            @else
                &#9792;
            @endif
        </h1>

        @if ($chicken->image)
            <img src="{{ asset('storage/' . $chicken->image) }}" alt="{{ $chicken->name }}"
                class="mt-5 h-64 w-64 rounded-lg object-cover">
        @else
            <div class="mt-5 flex h-64 w-64 items-center justify-center rounded-lg bg-gray-200 text-gray-400">
                No image
            </div>
        @endif

        <ul class="mt-6 list-none space-y-2 p-0 text-xl">
            <li>
                <span class="font-semibold">Birth date:</span>
                {{ \Carbon\Carbon::parse($chicken->birth_date)->format('d.m.Y') }}
            </li>
            <li>
                <span class="font-semibold">Age:</span>
                {{ \Carbon\Carbon::parse($chicken->birth_date)->diffForHumans(['parts' => 2, 'syntax' => \Carbon\CarbonInterface::DIFF_ABSOLUTE]) }}
            </li>
            <li>
                <span class="font-semibold">Breed:</span>
                <a href="{{ route('breeds.show', $chicken->breed_id) }}" class="text-brand underline">
                    {{ $chicken->breed->name }}
                </a>
            </li>
            <li>
                <span class="font-semibold">Height:</span>
                {{ $chicken->height }} cm
            </li>
            <li>
                <span class="font-semibold">Weight:</span>
                {{ ucfirst($chicken->weight) }}
            </li>
        </ul>
    </div>
</x-site-layout>
