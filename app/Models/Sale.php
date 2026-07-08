<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'total',
        'sold_at',
    ];

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function refunds()
    {
    return $this->hasMany(Refund::class);
    }
}