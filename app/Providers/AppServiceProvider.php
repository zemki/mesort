<?php

namespace App\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        if (App::environment('local')) {
            // The environment is local
            View::composer(['telescope::layout'], function ($view) {
                $view->with('telescopeScriptVariables', ['path' => 'telescope', 'timezone' => config('app.timezone'), 'recording' => ! cache('telescope:pause-recording')]);
            });
        } else {
            View::composer(['telescope::layout'], function ($view) {
                $view->with('telescopeScriptVariables', ['path' => 'mesort/telescope', 'timezone' => config('app.timezone'), 'recording' => ! cache('telescope:pause-recording')]);
            });
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
        $this->app->register(TelescopeServiceProvider::class);
        $this->app->register(BladeServiceProvider::class);
    }
}
