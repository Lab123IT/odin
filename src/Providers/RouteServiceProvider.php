<?php
namespace Lab123\Odin\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Lab123\Odin\Facades\ApiResponse;
use Illuminate\Routing\Router;
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
        if (config('odin.hashid.active')) {
            $this->decodeId($router);
        }
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
            
            $hashids = App::make('Hashids');
            
            $id_decoded = $hashids->decode($id);
            
            if (count($id_decoded) < 1) {
                return ApiResponse::notFound();
            }
            
            return $id_decoded[0];
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