<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $table = 'payments';
    protected $primaryKey = 'method_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false; // Karena tidak ada created_at dan updated_at

    protected $fillable = [
        'method_id',
        'method_name',
        'description',
        'static_proof',
        'destination_account',
    ];

    /**
     * Relasi ke Orders
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'method_id', 'method_id');
    }

    /**
     * Scope untuk metode pembayaran aktif
     * (jika nanti ada field is_active)
     */
    public function scopeActive($query)
    {
        // Untuk saat ini return semua, nanti bisa ditambah kondisi
        return $query;
    }

    /**
     * Accessor untuk cek apakah butuh bukti transfer
     */
    public function getNeedProofAttribute()
    {
        // Metode yang butuh upload bukti transfer
        $methodsNeedProof = ['BANK', 'QRIS', 'EWALLET'];
        
        return in_array($this->method_id, $methodsNeedProof);
    }

    /**
     * Accessor untuk cek apakah cash on delivery
     */
    public function getIsCodAttribute()
    {
        return $this->method_id === 'COD';
    }

    /**
     * Accessor untuk cek metode yang perlu auto-confirm
     */
    public function getAutoConfirmAttribute()
    {
        // COD langsung confirmed, yang lain pending
        return $this->is_cod;
    }

    /**
     * Accessor untuk URL static proof image
     */
    public function getStaticProofUrlAttribute()
    {
        if ($this->static_proof) {
            return asset('storage/payments/' . $this->static_proof);
        }
        return null;
    }

    /**
     * Method untuk get payment instructions
     */
    public function getInstructions()
    {
        $instructions = [
            'BANK' => [
                'title' => 'Transfer Bank',
                'steps' => [
                    'Transfer ke rekening: ' . $this->destination_account,
                    'Gunakan nominal yang tepat',
                    'Upload bukti transfer',
                    'Tunggu konfirmasi dari admin'
                ]
            ],
            'QRIS' => [
                'title' => 'QRIS',
                'steps' => [
                    'Scan QR Code yang tersedia',
                    'Masukkan nominal yang tepat',
                    'Selesaikan pembayaran',
                    'Screenshot bukti pembayaran dan upload'
                ]
            ],
            'COD' => [
                'title' => 'Cash on Delivery',
                'steps' => [
                    'Siapkan uang pas',
                    'Tunggu pesanan siap',
                    'Bayar saat pengambilan',
                    'Tidak perlu upload bukti'
                ]
            ],
            'EWALLET' => [
                'title' => 'E-Wallet',
                'steps' => [
                    'Transfer via aplikasi e-wallet',
                    'Ke nomor: ' . $this->destination_account,
                    'Screenshot bukti transfer',
                    'Upload bukti pembayaran'
                ]
            ]
        ];

        return $instructions[$this->method_id] ?? [
            'title' => $this->method_name,
            'steps' => ['Ikuti instruksi pembayaran', 'Upload bukti jika diperlukan']
        ];
    }
}