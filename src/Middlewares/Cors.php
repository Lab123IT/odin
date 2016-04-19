<?php
namespace Lab123\Odin\Middlewares;

use Closure;

class Cors
{

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request            
     * @param \Closure $next            
     * @return mixed
     */
    public function handle($request, Closure $next, array $origins = ['*'])
    {
        foreach ($origins as $origin) {
            return $next($request)->header('Access-Control-Allow-Origin', $origin)
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        }
        
        return $next($request);
    }
}
