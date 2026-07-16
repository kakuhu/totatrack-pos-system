<?php

namespace App\Http\Controllers;
use App\Imports\ProductsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductsController extends Controller
{
    // Show the Add Product page
    public function create()
    {
        $categories = ['Grocery', 'Hardware']; // Could later come from DB
        return view('products.create', compact('categories'));
    }
        // Store the product in the database
    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'name' => 'required|string|max:255',
            'barcode' => 'required|string|max:255',
            'category' => 'required|string|max:255',       // NEW
            'cost_price' => 'required|numeric|min:0',      // NEW
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        // Create product
        Product::create($request->all());

        // Return JSON for AJAX
        return response()->json(['success' => 'Product added successfully!']);
    }

    // List all products
public function index()
{
    $products = Product::paginate(10); // Get all products from DB
    return view('products.index', compact('products'));
}

public function update(Request $request, Product $product)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'barcode' => 'required|string|max:255',
        'category' => 'required|string|max:255',
        'cost_price' => 'required|numeric|min:0',
        'price' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0',
    ]);

    $product->update($request->all());

    return response()->json(['success' => 'Product updated successfully']);
}

public function destroy($id)
{
    $product = Product::findOrFail($id);

    $product->delete();

    return response()->json([
        'success' => 'Product deleted successfully'
    ]);
}

public function import(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,xls,csv'
    ]);

    Excel::import(new ProductsImport, $request->file('file'));

    return redirect()->back()->with('success', 'Products imported successfully.');
}
}
