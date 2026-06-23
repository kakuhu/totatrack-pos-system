<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Product([
            'name'       => $row['name'],
            'barcode'    => $row['barcode'],
            'category'   => $row['category'],
            'cost_price' => $row['cost_price'],
            'price'      => $row['price'],
            'stock'      => $row['stock'],
        ]);
    }
}