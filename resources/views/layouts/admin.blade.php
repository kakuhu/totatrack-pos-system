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
   <div class="min-h-screen flex flex-col md:flex-row">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow p-4">
            <h2 class="text-xl font-bold mb-6">Admin</h2>
<ul>

    {{-- ADMIN ONLY --}}
    @if(auth()->user()->role == 'admin')

        <li class="mb-2">
            <a href="{{ route('dashboard') }}" class="text-blue-600 hover:underline">
                Dashboard
            </a>
        </li>

        <li class="mb-2">
            <a href="{{ route('products.create') }}" class="text-blue-600 hover:underline">
                Add Product
            </a>
        </li>

        <li class="mb-2">
            <a href="{{ route('products.index') }}" class="text-blue-600 hover:underline">
                Product List
            </a>
        </li>

        <li class="mb-2">
            <a href="{{ route('users.index') }}" class="text-blue-600 hover:underline">
                Users
            </a>
        </li>

    @endif


    {{-- ADMIN + SUPERVISOR --}}
    @if(auth()->user()->role == 'admin' || auth()->user()->role == 'supervisor')

        <li class="mb-2">
            <a href="{{ route('sales.history') }}" class="text-blue-600 hover:underline">
                Sales History
            </a>
        </li>

    @endif


    {{-- ALL USERS --}}
    <li class="mb-2">
        <a href="{{ route('sales.index') }}" class="text-blue-600 hover:underline">
            POS / Sell
        </a>
    </li>


    {{-- LOGOUT --}}
    <li class="mt-6">
        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit"
                class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 w-full">
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