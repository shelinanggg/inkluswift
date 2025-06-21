<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'menus';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'menu_id';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The data type of the primary key.
     *
     * @var string
     */
    protected $keyType = 'string';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'menu_id',
        'menu_name',
        'price',
        'stock',
        'discount',
        'category',
        'description',
        'ingredients',
        'storage',
        'image',
    ];

    // ===== TAMBAHAN UNTUK CART =====
    
    /**
     * Relasi ke Cart
     */
    public function carts()
    {
        return $this->hasMany(Cart::class, 'menu_id', 'menu_id');
    }

    /**
     * Helper method untuk harga setelah diskon
     */
    public function getFinalPriceAttribute()
    {
        $discountAmount = ($this->price * $this->discount) / 100;
        return $this->price - $discountAmount;
    }

    /**
     * Helper method untuk format harga original
     */
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    /**
     * Helper method untuk format harga final (setelah diskon)
     */
    public function getFormattedFinalPriceAttribute()
    {
        return 'Rp ' . number_format($this->final_price, 0, ',', '.');
    }

    /**
     * Helper method untuk format diskon
     */
    public function getFormattedDiscountAttribute()
    {
        return $this->discount . '%';
    }

    /**
     * Helper method untuk cek apakah ada diskon
     */
    public function getHasDiscountAttribute()
    {
        return $this->discount > 0;
    }

    /**
     * Helper method untuk jumlah penghematan
     */
    public function getSavingsAmountAttribute()
    {
        return ($this->price * $this->discount) / 100;
    }

    /**
     * Helper method untuk format jumlah penghematan
     */
    public function getFormattedSavingsAttribute()
    {
        return 'Rp ' . number_format($this->savings_amount, 0, ',', '.');
    }
}