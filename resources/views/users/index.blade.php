
@extends('layouts.admin')

@section('content')
<h2 class="text-3xl font-bold mb-6">User Management</h2>

<form id="userForm" class="grid grid-cols-2 gap-4 mb-6">
    @csrf

    <input name="name" placeholder="Name" class="border p-2 rounded">
    <input name="email" placeholder="Email" class="border p-2 rounded">
    <input name="password" placeholder="Password" class="border p-2 rounded">

    <select name="role" class="border p-2 rounded">
        <option value="admin">Admin</option>
        <option value="supervisor">Supervisor</option>
        <option value="cashier">Cashier</option>
    </select>

        <select name="category" class="border p-2 rounded">

            <option value="all">All (Admin)</option>

            <option value="grocery">Grocery</option>

            <option value="hardware">Hardware</option>

        </select>

    <input name="contact" placeholder="Contact" class="border p-2 rounded">
    <input name="nrc" placeholder="NRC" class="border p-2 rounded">
    <input name="address" placeholder="Address" class="border p-2 rounded">

    <button class="bg-blue-600 text-white px-4 py-2 rounded col-span-2">
        Add User
    </button>
</form>

<table class="min-w-full bg-white">
    <thead>
        <tr class="bg-gray-200">
            <th class="p-2">Name</th>
            <th class="p-2">Email</th>
            <th class="p-2">Role</th>
            <th class="p-2">Category</th>
            
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
        <tr class="border-t">
            <td class="p-2">{{ $user->name }}</td>
            <td class="p-2">{{ $user->email }}</td>
            <td class="p-2">{{ $user->role }}</td>
            <td class="p-2">{{ $user->category }}</td>
            <td class="p-2"><button onclick="deleteUser({{ $user->id }})"
        class="bg-red-500 text-white px-2 py-1 rounded">
    Delete
</button> </td>
            
        </tr>
        @endforeach
    </tbody>
</table>
@endsection

@section('scripts')
<script>
document.getElementById('userForm').addEventListener('submit', function(e){
    e.preventDefault();

    let formData = new FormData(this);

    fetch("{{ route('users.store') }}", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        alert("User added!");
        location.reload();
    });
});

function deleteUser(id) {
    if (!confirm('Are you sure you want to delete this user?')) return;

    fetch(`/users/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(res => res.json())
    .then(data => {
        alert('User deleted');
        location.reload();
    });
}
</script>
@endsection