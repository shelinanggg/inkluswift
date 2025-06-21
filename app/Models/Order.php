<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    use HasFactory;

    protected $primaryKey = 'order_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'order_id',
        'user_id',
        'method_id',
        'total_amount',
        'total_discount',
        'service_charge',
        'final_amount',
        'customer_name',
        'customer_phone', 
        'customer_address',
        'status',
        'notes',
        'proof_image',
        'confirmed_at',
        'completed_at',
    ];

    protected $casts = [
        'confirmed_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Boot function untuk auto generate order_id
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($order) {
            if (empty($order->order_id)) {
                $order->order_id = self::generateOrderId();
            }
        });
    }

    /**
     * Generate unique order ID
     */
    public static function generateOrderId()
    {
        do {
            // Format: ORD + 6 digit random number
            $orderId = 'ORD' . str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
        } while (self::where('order_id', $orderId)->exists());
        
        return $orderId;
    }

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Relasi ke Payment Method
     */
    public function payment()
    {
        return $this->belongsTo(Payment::class, 'method_id', 'method_id');
    }

    /**
     * Relasi ke Order Items
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'order_id');
    }

    /**
     * Scope untuk filter berdasarkan status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk filter berdasarkan user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Accessor untuk format rupiah
     */
    public function getFormattedTotalAmountAttribute()
    {
        return 'Rp ' . number_format($this->total_amount, 0, ',', '.');
    }

    public function getFormattedFinalAmountAttribute()
    {
        return 'Rp ' . number_format($this->final_amount, 0, ',', '.');
    }

    public function getFormattedServiceChargeAttribute()
    {
        return 'Rp ' . number_format($this->service_charge, 0, ',', '.');
    }

    public function getFormattedTotalDiscountAttribute()
    {
        return 'Rp ' . number_format($this->total_discount, 0, ',', '.');
    }

    /**
     * Accessor untuk status label
     */
    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'Menunggu Konfirmasi',
            'confirmed' => 'Dikonfirmasi',
            'preparing' => 'Sedang Diproses',
            'ready' => 'Siap Diambil',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan'
        ];

        return $labels[$this->status] ?? 'Unknown';
    }

    /**
     * Accessor untuk status color (untuk UI)
     */
    public function getStatusColorAttribute()
    {
        $colors = [
            'pending' => 'warning',
            'confirmed' => 'info',
            'preparing' => 'primary',
            'ready' => 'success',
            'completed' => 'success',
            'cancelled' => 'danger'
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    /**
     * Method untuk update status
     */
    public function updateStatus($status)
    {
        $this->status = $status;
        
        if ($status === 'confirmed') {
            $this->confirmed_at = now();
        } elseif ($status === 'completed') {
            $this->completed_at = now();
        }
        
        return $this->save();
    }

    /**
     * Method untuk cek apakah order bisa dibatalkan
     */
    public function canBeCancelled()
    {
        return in_array($this->status, ['pending', 'confirmed']);
    }

    /**
     * Method untuk cek apakah order sudah selesai
     */
    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    /**
     * Method untuk hitung final amount dengan service charge
     */
    public static function calculateFinalAmount($totalAfterDiscount, $serviceCharge = 10000)
    {
        return $totalAfterDiscount + $serviceCharge;
    }

    /**
     * Accessor untuk informasi pengiriman
     */
    public function getShippingInfoAttribute()
    {
        return [
            'name' => $this->customer_name,
            'phone' => $this->customer_phone,
            'address' => $this->customer_address
        ];
    }

    /**
     * Accessor untuk format nomor HP
     */
    public function getFormattedPhoneAttribute()
    {
        $phone = $this->customer_phone;
        
        // Format nomor HP Indonesia
        if (substr($phone, 0, 1) === '0') {
            return '+62' . substr($phone, 1);
        } elseif (substr($phone, 0, 2) === '62') {
            return '+' . $phone;
        }
        
        return $phone;
    }
}