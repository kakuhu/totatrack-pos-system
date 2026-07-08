<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

protected $fillable = [
    'name', 'barcode', 'category', 'cost_price', 'price', 'stock'
];

    // Relation: a product can have many sales
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function refundItems()
    {
    return $this->hasMany(RefundItem::class);
    }
}
