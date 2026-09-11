<x-site-layout>

Admin view chickens

<div>
    <table style="width: 100%; border-collapse: collapse;">
        <thead style="background-color: #f99d34; color: white; text-align: left; border: 2px solid #000000;">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Date of Birth</th>
                <th>Breed</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody style="background-color: #f2f2f2; border: 2px solid #000000;">
            @foreach ($chickens as $chicken)
                <tr style="border: 2px solid #000000;">
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