<?php

namespace App\Http\Controllers;
use App\Imports\ProductsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Models\Product;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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
    public function index(Request $request)
    {
        $search = $request->query('search');

        $productsQuery = Product::query();

        if ($search) {
            $productsQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('barcode', 'like', "%{$search}%");
            });
        }

        $products = $productsQuery->paginate(5)->withQueryString();

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

public function bulkDelete(Request $request)
{
    $ids = $request->input('ids', []);
    
    if (empty($ids)) {
        return response()->json(['error' => 'No products selected'], 400);
    }

    Product::whereIn('id', $ids)->delete();

    return response()->json(['success' => 'Products deleted successfully']);
}

public function exportStock(Request $request)
{
    $products = Product::query();

    // Respect pagination parameters if passed
    if ($request->has('all') && $request->input('all') == 'true') {
        $products = $products->get();
    } else {
        $products = $products->paginate(1000)->items();
    }

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Set headers
    $sheet->setCellValue('A1', 'ID');
    $sheet->setCellValue('B1', 'Name');
    $sheet->setCellValue('C1', 'Barcode');
    $sheet->setCellValue('D1', 'Category');
    $sheet->setCellValue('E1', 'Cost Price');
    $sheet->setCellValue('F1', 'Selling Price');
    $sheet->setCellValue('G1', 'Stock');
    $sheet->setCellValue('H1', 'Stock Value');

    // Style header
    $headerStyle = [
        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
        'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '4472C4']],
        'alignment' => ['horizontal' => 'center', 'vertical' => 'center']
    ];
    $sheet->getStyle('A1:H1')->applyFromArray($headerStyle);

    // Add data
    $row = 2;
    foreach ($products as $product) {
        $sheet->setCellValue('A' . $row, $product->id);
        $sheet->setCellValue('B' . $row, $product->name);
        $sheet->setCellValue('C' . $row, $product->barcode);
        $sheet->setCellValue('D' . $row, $product->category);
        $sheet->setCellValue('E' . $row, $product->cost_price);
        $sheet->setCellValue('F' . $row, $product->price);
        $sheet->setCellValue('G' . $row, $product->stock);
        $sheet->setCellValue('H' . $row, $product->stock * $product->cost_price);
        $row++;
    }

    // Auto-size columns
    foreach (['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'] as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    $writer = new Xlsx($spreadsheet);
    $fileName = 'products_stock_' . date('Y-m-d_H-i-s') . '.xlsx';

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $fileName . '"');

    $writer->save('php://output');
    exit;
}
}
