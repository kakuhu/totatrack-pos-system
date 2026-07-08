<?php

namespace App\Models;
use App\Models\User;

use Illuminate\Database\Eloquent\Model;

class RefundItem extends Model
{
    protected $fillable = [
        'refund_id',
        'product_id',
        'quantity',
        'price',
        'total'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function refund()
    {
        return $this->belongsTo(Refund::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}