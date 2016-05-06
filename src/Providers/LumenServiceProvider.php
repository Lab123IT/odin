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
        $this->publishes([
            __DIR__ . '/../Helpers/helpers.php' => app()->basePath('app/Supports/') . 'helpers.php'
        ]);
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
        
        class_alias('Illuminate\Support\Facades\App', 'App');
        class_alias('Illuminate\Support\Facades\Request', 'Request');
        class_alias('Lab123\Odin\Controllers\LumenController', 'Lab123\Odin\Controllers\Controller');
        
        app()->withFacades();
        app()->withEloquent();
        
        app()->singleton('filesystem', function ($app) {
            return $app->loadComponent('filesystems', 'Illuminate\Filesystem\FilesystemServiceProvider', 'filesystem');
        });
    }
}