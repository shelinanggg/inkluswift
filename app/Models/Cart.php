<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'menu_id',
        'quantity',
        'price'
    ];

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Relasi ke Menu
     */
    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id', 'menu_id');
    }

    /**
     * Accessor untuk mendapatkan subtotal
     */
    public function getSubtotalAttribute()
    {
        return $this->quantity * $this->price;
    }

    /**
     * Accessor untuk mendapatkan harga setelah diskon
     */
    public function getPriceAfterDiscountAttribute()
    {
        $discount = $this->menu->discount ?? 0;
        return $this->price - ($this->price * $discount / 100);
    }

    /**
     * Accessor untuk subtotal setelah diskon
     */
    public function getSubtotalAfterDiscountAttribute()
    {
        return $this->quantity * $this->price_after_discount;
    }
}