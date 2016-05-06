<?php
namespace Lab123\Odin\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Lab123\Odin\Facades\ApiResponse;
use Illuminate\Routing\Router;
use Lab123\Odin\Libs\Api;
use Hashids\Hashids;
use App;

class RouteServiceProvider extends ServiceProvider
{

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param \Illuminate\Routing\Router $router            
     * @return void
     */
    public function boot(Router $router)
    {
        parent::boot($router);
        
        /* Ativa o Hash Id automático nas entidades */
        $this->decodeId($router);
    }

    /**
     * Decode Id from Request
     *
     * @param \Illuminate\Routing\Router $router            
     * @return void
     */
    public function decodeId(Router $router)
    {
        $router->bind('id', function ($id) {
            return Api::decodeHashId($id);
        });
    }

    /**
     * Define the routes for the application.
     *
     * @param \Illuminate\Routing\Router $router            
     * @return void
     */
    public function map(Router $router)
    {}
}