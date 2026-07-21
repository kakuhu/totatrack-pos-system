@extends('layouts.admin')

@section('content')

<div class="bg-white shadow rounded p-4 mb-6">

    <form method="GET" action="{{ route('sales.history') }}" class="flex items-end gap-4 flex-wrap">

        <div>
            <label class="block text-sm font-medium mb-1">
                From
            </label>

            <input
                type="date"
                name="from"
                value="{{ request('from') }}"
                class="border rounded px-3 py-2">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">
                To
            </label>

            <input
                type="date"
                name="to"
                value="{{ request('to') }}"
                class="border rounded px-3 py-2">
        </div>

        <button
            type="submit"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">

            Filter

        </button>

    </form>

</div>

<h2 class="text-3xl font-bold mb-6">Sales History</h2>

<table class="min-w-full bg-white shadow rounded overflow-hidden">
    <thead class="bg-gray-200">
        <tr>
            
            <th class="px-4 py-2">Total</th>
            <th class="px-4 py-2">Refund Qty</th>
            <th class="px-4 py-2">Refund Amount</th>
            <th class="px-4 py-2">Net</th>
            <th class="px-4 py-2">Sold At</th>
            <th class="px-4 py-2">Items</th>
            <th class="px-4 py-2">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($sales as $sale)
        @php
            $totalRefundQty = 0;
            $totalRefundAmount = 0;
            foreach($sale->refunds as $refund) {
                foreach($refund->items as $item) {
                    $totalRefundQty += $item->quantity;
                    $totalRefundAmount += $item->total;
                }
            }
            $netAmount = $sale->total - $totalRefundAmount;
        @endphp
        <tr class="border-b">
            
            <td class="px-4 py-2">ZMW {{ number_format($sale->total, 2) }}</td>
            <td class="px-4 py-2">{{ $totalRefundQty }}</td>
            <td class="px-4 py-2">ZMW {{ number_format($totalRefundAmount, 2) }}</td>
            <td class="px-4 py-2 font-semibold">ZMW {{ number_format($netAmount, 2) }}</td>
            <td class="px-4 py-2">{{ $sale->sold_at->format('Y-m-d H:i') }}</td>
            <td class="px-4 py-2">
                <ul class="list-disc pl-5">
                    @foreach($sale->items as $item)
                        <li>{{ $item->product->name }} - {{ $item->quantity }} x ZMW {{ number_format($item->price, 2) }}</li>
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