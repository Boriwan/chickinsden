<x-site-layout>
    <h1>My dens🪹</h1>
    <div>
        @foreach ($dens as $den)
            <div>
                <h2>{{ $den->name }}</h2>
                <p>Created Date: {{ $den->created_date }}</p>
                <p>User ID: {{ $den->user_id }}</p>
            </div>
             <hr>
        @endforeach
    </div>
</x-site-layout>
