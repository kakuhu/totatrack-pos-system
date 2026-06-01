<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow p-4">
            <h2 class="text-xl font-bold mb-6">Admin</h2>
            <ul>
                <li class="mb-2"><a href="/dashboard" class="text-blue-600 hover:underline">Home</a></li>
                <li class="mb-2"><a href="{{ route('products.create') }}" class="text-blue-600 hover:underline">Add Product</a></li>
                <li class="mb-2"><a href="{{ route('products.index') }}" class="text-blue-600 hover:underline">Product List</a></li>
                <li class="mb-2"><a href="{{ route('sales.index') }}" class="text-blue-600 hover:underline">POS / Sell</a></li>
                <li class="mb-2"><a href="{{ route('sales.history') }}" class="text-blue-600 hover:underline">Sales History</a></li>
                   <li class="mb-2"><a href="{{ route('users.index') }}" class="text-blue-600 hover:underline">Users</a></li>
                                      <li class="mt-6">
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="w-full text-left text-red-600 hover:underline">
            Logout
        </button>
    </form>
</li>
            </ul>
        </aside>

        <!-- Main content -->
        <main class="flex-1 p-6">
            @yield('content')
        </main>
    </div>

    @yield('scripts')
</body>
</html>