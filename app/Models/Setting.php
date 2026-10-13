<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * Key/value site settings. Values are JSON-encoded so arrays (e.g. process steps) work too.
 */
class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    public const CACHE_KEY = 'site-settings';

    public static function all_values(): array
    {
        return Cache::rememberForever(self::CACHE_KEY, fn () => static::query()
            ->pluck('value', 'key')
            ->map(fn ($v) => json_decode($v, true))
            ->all());
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $value = static::all_values()[$key] ?? null;

        return ($value === null || $value === '') ? $default : $value;
    }

    public static function put(array $values): void
    {
        foreach ($values as $key => $value) {
            static::updateOrCreate(['key' => $key], ['value' => json_encode($value)]);
        }
        Cache::forget(self::CACHE_KEY);
    }
}
