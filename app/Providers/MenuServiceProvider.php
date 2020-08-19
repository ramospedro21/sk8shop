<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Models\Module;
use Illuminate\Support\Facades\View;

class MenuServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('*', function ($view) {
            try {
                
                $modules = Module::with(['values'])->get();
                
                View::share('modules', $modules);

            } catch (\Exception $e) {

                View::share('tipo_usuario', 'erro');

            }
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
