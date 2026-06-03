<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'invoice_code',
        'total_price',
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}
