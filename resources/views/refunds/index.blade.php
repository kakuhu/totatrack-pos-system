@extends('layouts.admin')

@section('content')

<h2 class="text-3xl font-bold mb-6">Refund History</h2>

<table class="min-w-full bg-white shadow rounded overflow-hidden">

    <thead class="bg-gray-200">
        <tr>
            <th class="px-4 py-2">Refund ID</th>
            <th class="px-4 py-2">Sale ID</th>
            <th class="px-4 py-2">Total</th>
            <th class="px-4 py-2">Reason</th>
            <th class="px-4 py-2">Items</th>
            <th class="px-4 py-2">Refunded At</th>
        </tr>
    </thead>


    <tbody>

        @foreach($refunds as $refund)

        <tr class="border-b">

            <td class="px-4 py-2">
                {{ $refund->id }}
            </td>


            <td class="px-4 py-2">
                {{ $refund->sale_id }}
            </td>


            <td class="px-4 py-2">
                ZMW {{ number_format($refund->total, 2) }}
            </td>


            <td class="px-4 py-2">
                {{ $refund->reason }}
            </td>


            <td class="px-4 py-2">

                <ul class="list-disc pl-5">

                    @foreach($refund->items as $item)

                        <li>
                            {{ $item->product->name }}
                            -
                            {{ $item->quantity }}
                            x ZMW {{ $item->price }}
                        </li>

                    @endforeach

                </ul>

            </td>


            <td class="px-4 py-2">
                {{ $refund->created_at }}
            </td>


        </tr>

        @endforeach

    </tbody>

</table>

@endsection