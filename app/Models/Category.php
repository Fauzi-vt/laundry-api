<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = ['name', 'description', 'icon', 'accent_color'];

    /**
     * Relasi ke Layanan
     */
    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    /**
     * Accessor: Tailwind background class berdasarkan accent_color
     */
    public function getBgClassAttribute(): string
    {
        $color = $this->accent_color ?: 'slate';
        return "bg-{$color}-50 dark:bg-{$color}-900/20";
    }

    /**
     * Accessor: Tailwind text color class berdasarkan accent_color
     */
    public function getTextClassAttribute(): string
    {
        $color = $this->accent_color ?: 'slate';
        return "text-{$color}-600 dark:text-{$color}-400";
    }

    /**
     * Accessor: Tailwind border class berdasarkan accent_color
     */
    public function getBorderClassAttribute(): string
    {
        $color = $this->accent_color ?: 'slate';
        return "border-{$color}-100 dark:border-{$color}-800/50";
    }

    /**
     * Accessor: Tailwind accent color name
     */
    public function getAccentAttribute(): string
    {
        return $this->accent_color ?: 'slate';
    }
}
