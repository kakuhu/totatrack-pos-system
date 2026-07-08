@extends('layouts.admin')

@section('content')

<h2 class="text-2xl font-bold mb-6">
    Refund Sale #{{ $sale->id }}
</h2>

<form method="POST" action="{{ route('refunds.store',$sale->id) }}">
    @csrf

    <table class="min-w-full bg-white rounded shadow mb-6">

        <thead class="bg-gray-100">
            <tr>
                <th class="p-3">Product</th>
                <th class="p-3">Sold Qty</th>
                <th class="p-3">Price</th>
                <th class="p-3">Refund Qty</th>
            </tr>
        </thead>

        <tbody>

        @foreach($sale->items as $item)

        <tr class="border-b">

            <td class="p-3">
                {{ $item->product->name }}
            </td>

            <td class="p-3">
                {{ $item->quantity }}
            </td>

            <td class="p-3">
                {{ number_format($item->price,2) }}
            </td>

            <td class="p-3">

                <input
                    type="number"
                    min="0"
                    max="{{ $item->quantity }}"
                    value="0"
                    name="quantity[{{ $item->id }}]"
                    class="border rounded p-2 w-24">

            </td>

        </tr>

        @endforeach

        </tbody>

    </table>

    <div class="mb-4">

        <label class="block font-semibold mb-2">
            Refund Reason
        </label>

        <select
            name="reason"
            class="border rounded p-2 w-full">

            <option>Customer Return</option>
            <option>Damaged</option>
            <option>Wrong Item</option>
            <option>Expired</option>
            <option>Other</option>

        </select>

    </div>

    <button
        class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded">

        Process Refund

    </button>

</form>

@endsection