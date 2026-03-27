<?php


namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Setting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (Schema::hasTable('settings')) {
            $settings = \Illuminate\Support\Facades\Cache::rememberForever('site_settings', function () {
                return Setting::pluck('value', 'key')->all();
            });
            View::share('siteSettings', $settings);
        }
    }
}
