<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Prevent key length error on cPanel MySQL/MariaDB with utf8mb4
        Schema::defaultStringLength(191);

        // Force HTTPS URL generation in production or behind SSL reverse proxies (cPanel AutoSSL / Cloudflare)
        // Only enforce when not accessing via localhost / 127.0.0.1
        if (!app()->runningInConsole()) {
            $host = request()->getHost();
            $isLocal = in_array($host, ['127.0.0.1', 'localhost']) || str_ends_with($host, '.test');
            if (!$isLocal && (
                config('app.env') === 'production' ||
                request()->server('HTTP_X_FORWARDED_PROTO') === 'https' ||
                request()->server('HTTPS') === 'on' ||
                request()->isSecure()
            )) {
                URL::forceScheme('https');
            }
        }

        // Use Bootstrap for standard pagination
        Paginator::useBootstrap();
    }
}
