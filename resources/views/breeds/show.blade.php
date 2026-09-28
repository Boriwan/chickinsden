<x-site-layout>
    <div style="margin: 20px;">
        <button onclick="window.history.back()"
            style="background-color: #f99d34; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">&larr;
            Back</button>
        <h1 style="font-size: 2rem; font-weight: bold;">{{ $breed->name }}🪹</h1>
        <p style="font-weight: bold">Description:</p> {{ $breed->description }}
    </div>
</x-site-layout>
