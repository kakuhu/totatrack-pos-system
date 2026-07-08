<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Refund;
use App\Models\RefundItem;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RefundController extends Controller
{

    public function index()
    {
        $refunds = Refund::with([
            'items.product',
            'sale',
            'user'
        ])
        ->latest()
        ->get();

        return view('refunds.index', compact('refunds'));
    }

    public function create(Sale $sale)
    {
        $sale->load('items.product');

        return view('refunds.create', compact('sale'));
    }

    public function store(Request $request, Sale $sale)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
            'quantity' => 'required|array',
        ]);

        DB::transaction(function () use ($request, $sale) {

            $refund = Refund::create([
                'sale_id' => $sale->id,
                'user_id' => Auth::id(),
                'reason' => $request->reason,
                'total' => 0,
            ]);

            $grandTotal = 0;

            foreach ($sale->items as $item) {

                $qty = (int)($request->quantity[$item->id] ?? 0);

                if ($qty <= 0) {
                    continue;
                }

                if ($qty > $item->quantity) {
                    abort(422, 'Refund quantity cannot exceed quantity sold.');
                }

                $lineTotal = $qty * $item->price;

                RefundItem::create([
                    'refund_id' => $refund->id,
                    'product_id' => $item->product_id,
                    'quantity' => $qty,
                    'price' => $item->price,
                    'total' => $lineTotal,
                ]);

                $product = Product::find($item->product_id);
                $product->increment('stock', $qty);

                $grandTotal += $lineTotal;
            }

            $refund->update([
                'total' => $grandTotal
            ]);
        });

        return redirect()
            ->route('sales.history')
            ->with('success', 'Refund processed successfully.');
    }


}