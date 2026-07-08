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
                <li class="mb-2">
    <a href="{{ route('refunds.index') }}" class="text-blue-600 hover:underline">
        Refund History
    </a>
</li>
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
           

@section('content')
<h2 class="text-3xl font-bold mb-6">Welcome, Admin!</h2>
<p class="text-gray-700">Use the sidebar to manage products and view sales.</p>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

    <!-- HARDWARE SUMMARY -->
    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-xl font-bold mb-4">Hardware</h2>

        <p class="text-gray-600">Year to Date Sales</p>
        <p class="text-2xl font-bold text-green-600 mb-3">
            ZMW {{ number_format($hardwareYtdSales, 2) }}
        </p>

        <p class="text-gray-600">Monthly Sales</p>
        <p class="text-xl font-semibold text-blue-600 mb-3">
            ZMW {{ number_format($hardwareMonthlySales, 2) }}
        </p>

        <p class="text-gray-600">Stock Value</p>
        <p class="text-xl font-semibold text-gray-800">
            ZMW {{ number_format($hardwareStockValue, 2) }}
        </p>

        <p class="text-sm text-red-500 mt-2">
            Low Stock Items: {{ $hardwareLowStock }}
        </p>
    </div>

    <!-- GROCERY SUMMARY -->
    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-xl font-bold mb-4">Grocery</h2>

        <p class="text-gray-600">Year to Date Sales</p>
        <p class="text-2xl font-bold text-green-600 mb-3">
            ZMW {{ number_format($groceryYtdSales, 2) }}
        </p>

        <p class="text-gray-600">Monthly Sales</p>
        <p class="text-xl font-semibold text-blue-600 mb-3">
            ZMW {{ number_format($groceryMonthlySales, 2) }}
        </p>

        <p class="text-gray-600">Stock Value</p>
        <p class="text-xl font-semibold text-gray-800">
            ZMW {{ number_format($groceryStockValue, 2) }}
        </p>

        <p class="text-sm text-red-500 mt-2">
            Low Stock Items: {{ $groceryLowStock }}
        </p>
    </div>

</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    <!-- LOW STOCK ALERT -->
    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-lg font-bold mb-4">Low Stock Products</h2>

        <ul class="divide-y">
            @forelse($lowStockProducts as $product)
                <li class="py-2 flex justify-between">
                    <span>{{ $product->name }}</span>
                    <span class="text-red-500 font-bold">
                        {{ $product->stock }}
                    </span>
                </li>
            @empty
                <li class="text-gray-500">No low stock items</li>
            @endforelse
        </ul>
    </div>

    <!-- RECENT SALES -->
    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-lg font-bold mb-4">Recent Sales</h2>

        <ul class="divide-y">
            @forelse($recentSales as $sale)
                <li class="py-2 flex justify-between">
                    <span>{{ $sale->product->name ?? 'Deleted Product' }}</span>
                    <span class="text-green-600 font-bold">
                        ZMW {{ number_format($sale->total, 2) }}
                    </span>
                </li>
            @empty
                <li class="text-gray-500">No sales yet</li>
            @endforelse
        </ul>
    </div>

</div>
            
        </main>
    </div>

    
</body>
</html>