@extends('layouts.admin')

@section('content')
<h2 class="text-3xl font-bold mb-6">Sales History</h2>

<table class="min-w-full bg-white shadow rounded overflow-hidden">
    <thead class="bg-gray-200">
        <tr>
            <th class="px-4 py-2">ID</th>
            <th class="px-4 py-2">Total</th>
            <th class="px-4 py-2">Sold At</th>
            <th class="px-4 py-2">Items</th>
            <th class="px-4 py-2">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($sales as $sale)
        <tr class="border-b">
            <td class="px-4 py-2">{{ $sale->id }}</td>
            <td class="px-4 py-2">ZMW {{ $sale->total }}</td>
            <td class="px-4 py-2">{{ $sale->sold_at }}</td>
            <td class="px-4 py-2">
                <ul class="list-disc pl-5">
                    @foreach($sale->items as $item)
                        <li>{{ $item->product->name }} - {{ $item->quantity }} x ZMW {{ $item->price }}</li>
                    @endforeach
                </ul>
            </td>
            <td class="px-4 py-2">
                    <a href="{{ route('refunds.create', $sale->id) }}"
                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">
                        Refund
                        </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection