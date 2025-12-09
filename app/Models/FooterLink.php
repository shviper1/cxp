<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class FooterLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'label',
        'url',
        'type',
        'icon_path',
        'display_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saved(fn () => static::forgetCache());
        static::deleted(fn () => static::forgetCache());
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public static function allCached()
    {
        return Cache::remember('footer_links_cache', now()->addDay(), function () {
            return static::query()
                ->active()
                ->orderBy('display_order')
                ->orderBy('label')
                ->get();
        });
    }

    public static function forgetCache(): void
    {
        Cache::forget('footer_links_cache');
    }
}
