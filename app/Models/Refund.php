<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Refund extends Model
{
    protected $fillable = [
        'sale_id',
        'user_id',
        'reason',
        'total'
    ];

    public function items()
    {
        return $this->hasMany(RefundItem::class);
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

        public function user()
    {
        return $this->belongsTo(User::class);
    }
}