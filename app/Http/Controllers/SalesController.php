<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Carbon\Carbon;

class SalesController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // ADMIN sees all
        if ($user->role == 'admin') {

            $products = Product::all();

        } else {

            // Cashier/Supervisor only see their category
            $products = Product::where(
                'category',
                $user->category
            )->get();
        }

        return view('sales.index', compact('products'));
    }

    public function findByBarcode($barcode)
    {
        $user = auth()->user();

        // Admin sees all
        if ($user->role == 'admin') {

            $product = Product::where(
                'barcode',
                $barcode
            )->first();

        } else {

            $product = Product::where('barcode', $barcode)
                ->where('category', $user->category)
                ->first();
        }

        if (!$product) {

            return response()->json([
                'error' => 'Product not found'
            ]);
        }

        return response()->json($product);
    }

    public function sell(Request $request)
    {
        $sale = Sale::create([
            'user_id' => auth()->id(),
            'total' => collect($request->cart)->sum('total')
        ]);

        foreach ($request->cart as $item) {
            SaleItem::create([
                'sale_id' => $sale->id,
                'product_id' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'total' => $item['total']
            ]);

            // reduce stock
            $product = Product::find($item['id']);
            if ($product) {
                $product->stock -= $item['quantity'];
                $product->save();
            }
        }

        return response()->json(['success' => true]);
    }

    public function history(Request $request)
    {
        $from = $request->from ?? Carbon::today()->toDateString();
        $to = $request->to ?? Carbon::today()->toDateString();

        $sales = Sale::with('items.product', 'refunds.items')
            ->whereDate('sold_at', '>=', $from)
            ->whereDate('sold_at', '<=', $to)
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('sales.history', compact(
            'sales',
            'from',
            'to'
        ));
    }
}