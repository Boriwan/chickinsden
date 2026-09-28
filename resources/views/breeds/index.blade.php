<x-site-layout>

    <h1 style="font-size: 2rem; font-weight: bold; margin: 20px;">My breeds📂</h1>

    <hr>

    <div style="
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin: 20px;
    ">

        @foreach ($breeds as $breed)
            <div
                style="
                width: 220px;
                padding: 20px;
                border: 1px solid #d6b98c;
                border-radius: 10px;
                background-color: #fff9d6;
            ">

                <h2>
                    <a href="{{ route('breeds.show', $breed->id) }}" style="text-decoration: underline;">
                        {{ $breed->name }}
                    </a>
                </h2>

                <ul>
                    <li>Description: {{ $breed->description }}</li>
                </ul>

            </div>
        @endforeach

    </div>

</x-site-layout>
