@extends('layouts.admin')

@section('content')

<h2 class="text-3xl font-bold mb-6">Sales Report</h2>

<form method="GET" action="{{ route('sales.report') }}" class="mb-6 flex flex-wrap gap-3 items-end">
    <div>
        <label class="block font-semibold mb-1">From</label>
        <input type="date" name="from" value="{{ $from }}" class="border rounded p-2" />
    </div>
    <div>
        <label class="block font-semibold mb-1">To</label>
        <input type="date" name="to" value="{{ $to }}" class="border rounded p-2" />
    </div>
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Filter</button>
    <a href="{{ route('sales.report.pdf', ['from' => $from, 'to' => $to]) }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Export PDF</a>
</form>

<div class="mb-6">
    <p class="font-semibold">Showing sales from <strong>{{ $from }}</strong> to <strong>{{ $to }}</strong>.</p>
    <p class="font-semibold">Total Sales: ZMW {{ number_format($totalSales, 2) }}</p>
</div>

<table class="min-w-full bg-white shadow rounded overflow-hidden mb-6">
    <thead class="bg-gray-200">
        <tr>
            <th class="px-4 py-2 text-left">Date</th>
            <th class="px-4 py-2 text-left">Sale ID</th>
            <th class="px-4 py-2 text-left">Products</th>
            <th class="px-4 py-2 text-left">Total</th>
        </tr>
    </thead>
    <tbody>
        @forelse($sales as $sale)
            <tr class="border-t">
                <td class="px-4 py-3">{{ $sale->sold_at->format('Y-m-d H:i') }}</td>
                <td class="px-4 py-3">{{ $sale->id }}</td>
                <td class="px-4 py-3">
                    @foreach($sale->items as $item)
                        <div>{{ $item->product->name ?? 'Deleted Product' }} x {{ $item->quantity }}</div>
                    @endforeach
                </td>
                <td class="px-4 py-3">ZMW {{ number_format($sale->total, 2) }}</td>
            </tr>
        @empty
            <tr>
                <td class="px-4 py-3" colspan="4">No sales found for this range.</td>
            </tr>
        @endforelse
    </tbody>
</table>

{{ $sales->links() }}

@endsection
