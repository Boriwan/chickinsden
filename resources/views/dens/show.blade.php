<x-site-layout>
    <div style="margin: 20px;">
        <button onclick="window.history.back()"
            style="background-color: #f99d34; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">&larr;
            Back</button>
        <h1 style="font-size: 2rem; font-weight: bold;">{{ $den->name }}🪹</h1>
        <p>Created Date: {{ $den->created_date }}</p>
        <p>User ID: {{ $den->user_id }}</p>
    </div>
</x-site-layout>
