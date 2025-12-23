<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'transaction_code',
        'user_id',
        'package_id',
        'phone_number',
        'customer_name',
        'quantity',
        'price',
        'total_amount',
        'status',
        'payment_method',
        'notes',
        'processed_at',
        'completed_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'processed_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    // Relationship: Transaction belongs to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship: Transaction belongs to Package
    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    // Auto generate transaction code
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaction) {
            if (empty($transaction->transaction_code)) {
                $transaction->transaction_code = 'TRX-' . date('Ymd') . '-' . str_pad(
                    Transaction::whereDate('created_at', today())->count() + 1,
                    4,
                    '0',
                    STR_PAD_LEFT
                );
            }
        });
    }
}
