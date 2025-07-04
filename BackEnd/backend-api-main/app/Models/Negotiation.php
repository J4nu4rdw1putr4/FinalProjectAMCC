<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Negotiation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'offered_price',
        'status'
    ];

    // Relasi ke tabel user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke tabel product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
