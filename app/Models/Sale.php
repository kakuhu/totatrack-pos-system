<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    // Table name (optional if follows Laravel convention)
    protected $table = 'sales';

    protected $fillable = [
        'total',      // total amount of the sale
        'sold_at',    // timestamp
    ];

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }
}