<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Models\Module;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

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
                
                $modules = Module::with(['values' => function($children) {
                            $children->whereHas('userType', function($query){
                                $query->where('user_type_id', Auth::user()->user_type_id);
                            });
                        }
                    ])
                    ->whereHas('values', function($query){
                        $query->whereHas('userType', function($query){
                            $query->where('user_type_id', Auth::user()->user_type_id);
                        });
                    })
                    ->get();

                View::share('modules', $modules);

            } catch (\Exception $e) {
            dd($e);
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
