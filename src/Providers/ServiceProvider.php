<?php
namespace Lab123\Odin\Providers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Hashids\Hashids;

class ServiceProvider extends BaseServiceProvider
{

    /**
     * Register any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishHelper();
        
        $this->publishConfigs();
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
     * Publish configs.
     *
     * @return void
     */
    public function publishConfigs()
    {
        $this->publishes([
            __DIR__ . '/../Config/odin.php' => $this->config_path('odin.php')
        ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->facades();
        $this->makes();
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
        }
    }

    /**
     * Register Facades from Odin.
     *
     * @return void
     */
    public function facades()
    {
        $this->app->bind('ApiResponse', function () {
            return new \Lab123\Odin\Libs\ApiResponse();
        });
    }

    /**
     * Register all Binds from Odin.
     *
     * @return void
     */
    public function makes()
    {
        $this->app->bind('Hashids', function ($app) {
            return new Hashids(config('key'), config('odin.hashid.length_key'));
        });
    }

    /**
     * Return Config Path
     *
     * @return $path
     */
    public function config_path($path)
    {
        return app()->basePath('config/') . $path;
    }
}