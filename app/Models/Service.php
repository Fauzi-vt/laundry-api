<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['name', 'category', 'price', 'unit', 'description'];

    /**
     * Ikon emoji berdasarkan nama/kategori layanan.
     */
    public function getIconAttribute(): string
    {
        $name = strtolower($this->name);
        $cat  = strtolower($this->category ?? '');

        if (str_contains($name, 'sepatu') || str_contains($cat, 'sepatu'))   return '👟';
        if (str_contains($name, 'selimut') || str_contains($name, 'bedcover') || str_contains($cat, 'linen')) return '🛏️';
        if (str_contains($name, 'kilat') || str_contains($cat, 'kilat'))     return '⚡';
        if (str_contains($name, 'karpet') || str_contains($cat, 'karpet'))   return '🪄';
        if (str_contains($name, 'setrika') || str_contains($cat, 'setrika')) return '👔';
        if (str_contains($name, 'tas') || str_contains($cat, 'tas'))         return '👜';
        return '👕';
    }
}
