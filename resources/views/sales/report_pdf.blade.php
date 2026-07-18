<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sales Report</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; margin: 24px; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background: #f4f4f4; }
        h1, h2, h3 { margin: 0; }
        .summary { margin-top: 16px; }
    </style>
</head>
<body>
    <h1>Sales Report</h1>
    <p>Period: {{ $from }} - {{ $to }}</p>
    <p>Total Sales: ZMW {{ number_format($totalSales, 2) }}</p>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Sale ID</th>
                <th>Products</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sales as $sale)
                <tr>
                    <td>{{ $sale->sold_at->format('Y-m-d H:i') }}</td>
                    <td>{{ $sale->id }}</td>
                    <td>
                        @foreach($sale->items as $item)
                            {{ $item->product->name ?? 'Deleted Product' }} x {{ $item->quantity }}<br>
                        @endforeach
                    </td>
                    <td>ZMW {{ number_format($sale->total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
