<x-site-layout>

Admin view chickens

<div>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Date of Birth</th>
                <th>Breed</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($chickens as $chicken)
                <tr>
                    <td>{{ $chicken->id }}</td>
                    <td>{{ $chicken->name }}</td>
                    <td>{{ $chicken->born_date}}</td>
                    <td>{{ $chicken->breed_id }}</td>
                    <td>
                        <!-- Add action buttons here (e.g., Edit, Delete) -->
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</x-site-layout>