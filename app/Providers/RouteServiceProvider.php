<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public const HOME = '/pmgplxmoto/danh-muc/giao-vien';

    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $webMiddleware = config('moto.standalone')
            ? ['web']
            : ['web', 'auth', 'phan.quyen'];

        $this->routes(function () use ($webMiddleware) {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            if (! config('moto.standalone')) {
                Route::middleware('web')
                    ->group(base_path('routes/auth.php'));
            }

            Route::middleware('web')
                ->group(base_path('routes/do-thong-tin-cong-khai.php'));

            Route::middleware($webMiddleware)
                ->group(base_path('routes/web.php'));

            Route::middleware($webMiddleware)
                ->group(base_path('routes/pmgplxmoto.php'));
        });
    }
}
