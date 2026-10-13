<?php

namespace App\Models;

use App\Http\Middleware\ApplyRedirects;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Redirect extends Model
{
    protected $fillable = ['from_path', 'to_url', 'status_code'];

    protected static function booted(): void
    {
        $flush = fn () => Cache::forget(ApplyRedirects::CACHE_KEY);
        static::saved($flush);
        static::deleted($flush);
    }

    public static function normalize(string $path): string
    {
        return '/'.trim(parse_url($path, PHP_URL_PATH) ?? '', '/');
    }
}
