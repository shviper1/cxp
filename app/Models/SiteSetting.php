<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
    ];

    protected static function booted(): void
    {
        static::saved(function () {
            static::forgetCache();
        });

        static::deleted(function () {
            static::forgetCache();
        });
    }

    /**
     * Get a setting value by key
     */
    public static function get(string $key, $default = null)
    {
        $settings = static::allCached();

        return $settings[$key] ?? $default;
    }

    /**
     * Retrieve all settings from cache
     */
    public static function allCached(): array
    {
        return Cache::remember('site_settings_cache', now()->addDay(), function () {
            try {
                return static::query()->pluck('value', 'key')->toArray();
            } catch (\Throwable $exception) {
                return [];
            }
        });
    }

    /**
     * Forget the cached settings
     */
    public static function forgetCache(): void
    {
        Cache::forget('site_settings_cache');
    }

    /**
     * Set a setting value
     */
    public static function set(string $key, $value, string $type = 'text', string $group = 'general')
    {
        $setting = static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'group' => $group,
            ]
        );

        static::forgetCache();

        return $setting;
    }
}
