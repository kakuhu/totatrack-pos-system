<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\SaleItem;

class DashboardController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | HARDWARE
        |--------------------------------------------------------------------------
        */

        $hardwareYtdSales = SaleItem::whereHas('product', function ($q) {
            $q->where('category', 'hardware');
        })
        ->whereYear('created_at', now()->year)
        ->sum('total');

        $hardwareMonthlySales = SaleItem::whereHas('product', function ($q) {
            $q->where('category', 'hardware');
        })
        ->whereYear('created_at', now()->year)
        ->whereMonth('created_at', now()->month)
        ->sum('total');

        $hardwareStockValue = Product::where('category', 'hardware')
            ->get()
            ->sum(fn($p) => $p->cost_price * $p->stock);

        $hardwareLowStock = Product::where('category', 'hardware')
            ->where('stock', '<=', 10)
            ->count();

        /*
        |--------------------------------------------------------------------------
        | GROCERY
        |--------------------------------------------------------------------------
        */

        $groceryYtdSales = SaleItem::whereHas('product', function ($q) {
            $q->where('category', 'grocery');
        })
        ->whereYear('created_at', now()->year)
        ->sum('total');

        $groceryMonthlySales = SaleItem::whereHas('product', function ($q) {
            $q->where('category', 'grocery');
        })
        ->whereYear('created_at', now()->year)
        ->whereMonth('created_at', now()->month)
        ->sum('total');

        $groceryStockValue = Product::where('category', 'grocery')
            ->get()
            ->sum(fn($p) => $p->cost_price * $p->stock);

        $groceryLowStock = Product::where('category', 'grocery')
            ->where('stock', '<=', 10)
            ->count();

        /*
        |--------------------------------------------------------------------------
        | TABLES
        |--------------------------------------------------------------------------
        */

        $lowStockProducts = Product::where('stock', '<=', 10)
            ->orderBy('stock')
            ->take(10)
            ->get();

        $recentSales = SaleItem::with('product')
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard', compact(
            'hardwareYtdSales',
            'hardwareMonthlySales',
            'hardwareStockValue',
            'hardwareLowStock',

            'groceryYtdSales',
            'groceryMonthlySales',
            'groceryStockValue',
            'groceryLowStock',

            'lowStockProducts',
            'recentSales'
        ));
    }
}