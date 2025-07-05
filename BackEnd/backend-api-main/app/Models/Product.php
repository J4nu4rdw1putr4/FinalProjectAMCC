<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama',
        'deskripsi',
        'harga',
        'stok',
        'diskon',
        'gambar'
    ];

    // (Opsional) Jika produk dimiliki oleh user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getPriceAttribute()
{
    return $this->harga;
}

}
