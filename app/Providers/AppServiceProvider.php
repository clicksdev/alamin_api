<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Setting;

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
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        $settings = Setting::all();

        $settingsArray = $settings->mapWithKeys(function ($setting) {

            return [
                $setting->key => [
                    'value'     => $setting->value,
                ]
            ];
        })->toArray();

        view()->share('settingsArray', $settingsArray);
    }
}
