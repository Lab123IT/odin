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
        $this->publishHelper();
        
        parent::boot();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfigs();
        
        $this->registerCommands();
        
        $this->registerAlias();
        
        $this->registerFeaturesLumen();
        
        $this->registerFilesystem();
        
        parent::register();
    }

    /**
     * Publish configs.
     *
     * @return void
     */
    protected function publishConfigs()
    {
        $this->publishes([
            __DIR__ . '/../Config/filesystems.php' => $this->config_path('filesystems.php')
        ]);
        
        parent::publishConfigs();
    }

    /**
     * Publish helpers for Lumen.
     *
     * @return void
     */
    protected function publishHelper()
    {
        $this->publishes([
            __DIR__ . '/../Helpers/helpers.php' => app()->basePath('app/Supports/') . 'helpers.php'
        ]);
    }

    /**
     * Register for Lumen Application.
     *
     * @return void
     */
    protected function registerCommands()
    {
        $this->commands(\Lab123\Odin\Command\AppRestart::class);
        $this->commands(\Lab123\Odin\Command\AppStart::class);
        $this->commands(\Lab123\Odin\Command\GeneratePasswordCommand::class);
        $this->commands(\Lab123\Odin\Command\LumenAppNameCommand::class);
        $this->commands(\Lab123\Odin\Command\LumenRouteList::class);
        $this->commands(\Lab123\Odin\Command\LumenVendorPublish::class);
        $this->commands(\Lab123\Odin\Command\LumenModelMake::class);
    }

    /**
     * Register configs.
     *
     * @return void
     */
    protected function registerConfigs()
    {
        app()->configure('odin');
        app()->configure('filesystem');
    }

    /**
     * Active features Lumen.
     *
     * @return void
     */
    protected function registerFeaturesLumen()
    {
        app()->withFacades();
        app()->withEloquent();
    }

    /**
     * Alias to adapter Lumen.
     *
     * @return void
     */
    protected function registerAlias()
    {
        if (env('APP_ENV') != 'testing') {
            class_alias('Illuminate\Support\Facades\App', 'App');
            class_alias('Illuminate\Support\Facades\Request', 'Request');
            class_alias('Lab123\Odin\Controllers\LumenController', 'Lab123\Odin\Controllers\Controller');
        }
    }

    /**
     * Register component FileSystem.
     *
     * @return void
     */
    protected function registerFilesystem()
    {
        app()->singleton('filesystem', function ($app) {
            return $app->loadComponent('filesystems', 'Illuminate\Filesystem\FilesystemServiceProvider', 'filesystem');
        });
    }
}