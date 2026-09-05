<div>
    Here are my chickens 🐓🐓🐓

    <ul>
        @foreach ($chickens as $chicken)
            <li>{{ $chicken->name }}</li>
            <li>{{ $chicken->gender }}</li>
            <li>{{ $chicken->born_date }}</li>
            <li>{{ $chicken->breed_id }}</li>
            <li>{{ $chicken->height }}</li>
            <li>{{ $chicken->weight }}</li>
            <li>{{ $chicken->den_id }}</li>
            <li><img src="{{ $chicken->image }}" alt="Chicken Image"></li
        @endforeach
    </ul>
</div>
