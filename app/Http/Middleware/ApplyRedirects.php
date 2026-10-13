<?php

namespace App\Http\Middleware;

use App\Models\Redirect;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

/**
 * Serves admin-managed 301/302 redirects before routing.
 */
class ApplyRedirects
{
    public const CACHE_KEY = 'redirect-map';

    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isMethod('GET') && ! $request->is('admin', 'admin/*')) {
            $redirect = $this->map()[Redirect::normalize($request->getPathInfo())] ?? null;
            if ($redirect) {
                Redirect::whereKey($redirect['id'])->increment('hits');

                return redirect()->to($redirect['to'], $redirect['code']);
            }
        }

        return $next($request);
    }

    private function map(): array
    {
        return Cache::remember(self::CACHE_KEY, 3600, fn () => Schema::hasTable('redirects')
            ? Redirect::all()->mapWithKeys(fn ($r) => [$r->from_path => ['id' => $r->id, 'to' => $r->to_url, 'code' => $r->status_code]])->all()
            : []);
    }
}
