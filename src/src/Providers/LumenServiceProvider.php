<?php
namespace Lab123\Odin\Providers;

use Lab123\Odin\Providers\ServiceProvider;

class LumenServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
        
        $this->publishHelper();
    }

    /**
     * Publish helpers for Lumen.
     *
     * @return void
     */
    public function publishHelper()
    {
        if (! function_exists('config_path')) {
            $this->publishes([
                __DIR__ . '/../Helpers/helpers.php' => $this->config_path('helpers.php')
            ]);
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        parent::register();
        
        $this->registerLumen();
    }

    /**
     * Register for Lumen Application.
     *
     * @return void
     */
    public function registerLumen()
    {
        app()->configure('odin');
        
        if (! class_exists('App')) {
            class_alias('Illuminate\Support\Facades\App', 'App');
            class_alias('Lab123\Odin\Controllers\LumenController', 'Lab123\Odin\Controllers\Controller');
            
            app()->withFacades();
            app()->withEloquent();
        }
    }
}