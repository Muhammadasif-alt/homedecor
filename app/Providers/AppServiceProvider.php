<?php

namespace App\Providers;

use App\Support\Seo;
use App\View\Composers\SiteComposer;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->scoped(Seo::class);
    }

    public function boot(): void
    {
        Model::preventLazyLoading(! $this->app->isProduction());

        if ($this->app->isProduction()) {
            URL::forceScheme('https');
        }

        // Public forms: 5 submissions per minute and 20 per day per IP.
        RateLimiter::for('inquiries', fn (Request $request) => [
            Limit::perMinute(5)->by($request->ip()),
            Limit::perDay(20)->by($request->ip()),
        ]);

        View::composer('components.layouts.app', SiteComposer::class);
        View::composer(['pages.*', 'services.*', 'projects.*', 'blog.*', 'areas.*'], fn ($view) => $view->with('seo', app(Seo::class)));
    }
}
