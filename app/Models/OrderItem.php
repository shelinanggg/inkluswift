<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'menu_id', 
        'menu_name',
        'quantity',
        'price',
        'discount_percent',
        'subtotal',
        'discount_amount',
        'subtotal_after_discount',
    ];

    /**
     * Relasi ke Order
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    /**
     * Relasi ke Menu
     */
    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id', 'menu_id');
    }

    /**
     * Accessor untuk format rupiah
     */
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function getFormattedSubtotalAttribute()
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }

    public function getFormattedDiscountAmountAttribute()
    {
        return 'Rp ' . number_format($this->discount_amount, 0, ',', '.');
    }

    public function getFormattedSubtotalAfterDiscountAttribute()
    {
        return 'Rp ' . number_format($this->subtotal_after_discount, 0, ',', '.');
    }

    /**
     * Accessor untuk informasi diskon
     */
    public function getHasDiscountAttribute()
    {
        return $this->discount_percent > 0;
    }

    public function getDiscountInfoAttribute()
    {
        if ($this->discount_percent > 0) {
            return "Diskon {$this->discount_percent}%";
        }
        return null;
    }

    /**
     * Method untuk hitung total per item
     */
    public static function calculateItemTotal($quantity, $price, $discountPercent = 0)
    {
        $subtotal = $quantity * $price;
        $discountAmount = $subtotal * ($discountPercent / 100);
        $subtotalAfterDiscount = $subtotal - $discountAmount;

        return [
            'subtotal' => $subtotal,
            'discount_amount' => $discountAmount,
            'subtotal_after_discount' => $subtotalAfterDiscount
        ];
    }
}