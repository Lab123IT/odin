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
        $this->publishConfigs();
        
        $this->publishTranslate();
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
     * Publish translate.
     *
     * @return void
     */
    public function publishTranslate()
    {
        $this->publishes([
            __DIR__ . '/../Resources/Lang/pt-BR' => app()->basePath('resources/lang/pt-BR')
        ]);
        
        app('translator')->setLocale('pt-BR');
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