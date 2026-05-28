<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['name', 'category_id', 'price', 'unit', 'description', 'image'];

    protected $with = ['categoryRelation'];

    protected $appends = ['category', 'icon'];

    /**
     * Relasi ke model Category
     */
    public function categoryRelation()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Accessor untuk category (kembalikan string nama kategori demi kompatibilitas)
     */
    public function getCategoryAttribute(): string
    {
        return $this->categoryRelation ? $this->categoryRelation->name : 'Umum';
    }

    /**
     * Ikon emoji berdasarkan kategori atau nama layanan.
     */
    public function getIconAttribute(): string
    {
        if ($this->categoryRelation && $this->categoryRelation->icon) {
            return $this->categoryRelation->icon;
        }

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
