<?php
namespace Lab123\Odin\Middlewares;

use Closure;
use App;

class HashId
{

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request            
     * @param \Closure $next            
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request);
        
        if ($id = $this->getId($request)) {
            
            $id_decoded = $this->decodeId($request, $id);
            
            $request->server->set('REQUEST_URI', "");
            
            return $next($request);
        }
        
        return $next($request);
    }

    private function decodeId($request, $id)
    {
        $hashids = App::make('Hashids');
        
        $id_decoded = $hashids->decode($id);
        
        if (count($id_decoded) < 1) {
            return ApiResponse::notFound();
        }
        
        return $id_decoded[0];
    }

    private function getId($request)
    {
        $id = ($request->has('id')) ? $request->get('id') : null;
        
        if (! $id) {
            
            if (key_exists(2, $request->route())) {
                if (key_exists('id', $request->route()[2])) {
                    $id = $request->route()[2]['id'];
                }
            }
        }
        
        return $id;
    }
}
