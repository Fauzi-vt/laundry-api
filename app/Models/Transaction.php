<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'invoice_code',
        'total_price',
        'down_payment',
        'status',
        'address',
        'phone',
        'payment_method',
        'delivery_type',
        'payment_proof',
    ];

    protected $appends = [
        'payment_proof_url',
    ];

    public function getPaymentProofUrlAttribute()
    {
        return $this->payment_proof ? url($this->payment_proof) : null;
    }

    protected static function booted()
    {
        static::updated(function ($transaction) {
            if ($transaction->wasChanged('status')) {
                \App\Services\FcmService::sendStatusNotification($transaction);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}
