<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total',
        'sold_at',
    ];

    protected $casts = [
        'sold_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function refunds()
    {
    return $this->hasMany(Refund::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}