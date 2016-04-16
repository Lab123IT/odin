<?php
namespace Lab123\Odin\Traits;

use Log;
use App;

trait ApiResponse
{

    /**
     * Return HTTP Continue (100)
     *
     * @return \Illuminate\Http\Response
     */
    protected function continues(array $data = array())
    {
        return response()->json($data, 100);
    }

    /**
     * Return HTTP Success (200)
     *
     * @return \Illuminate\Http\Response
     */
    protected function success(array $data = array())
    {
        return response()->json($data, 200);
    }

    /**
     * Return HTTP Created (201)
     *
     * @return \Illuminate\Http\Response
     */
    protected function created(array $data = array())
    {
        return response()->json($data, 201);
    }

    /**
     * Return HTTP Bad Request (400)
     *
     * @return \Illuminate\Http\Response
     */
    protected function bad(array $data = array())
    {
        return response()->json($data, 400);
    }

    /**
     * Return HTTP Unauthorized (401)
     *
     * @return \Illuminate\Http\Response
     */
    protected function unauthorized(array $data = array())
    {
        return response()->json($data, 401);
    }

    /**
     * Return HTTP Not Found (404)
     *
     * @return \Illuminate\Http\Response
     */
    protected function notFound(array $data = array())
    {
        return response()->json($data, 404);
    }

    /**
     * Return HTTP Internal Error (500)
     *
     * @return \Illuminate\Http\Response
     */
    protected function internalError(array $data = array())
    {
        return response()->json($data, 500);
    }

    /**
     * Return HTTP Conflict (409)
     *
     * @return \Illuminate\Http\Response
     */
    protected function conflict(array $data = array())
    {
        return response()->json($data, 409);
    }

    /**
     * Return HTTP Bad Request (400)
     *
     * @return \Illuminate\Http\Response
     */
    protected function exception(\Exception $ex)
    {
        $log = [
            'error' => $ex->getMessage(),
            'file' => $ex->getFile(),
            'line' => $ex->getLine()
        ];
        
        Log::error($log);
        
        $return = [
            "erro" => "Ocorreu um erro inesperado. Por favor, tente mais tarde."
        ];
        
        if (App::environment('local', 'staging')) {
            $return['server_error'] = $log;
        }
        
        return $this->internalError($return);
    }
}