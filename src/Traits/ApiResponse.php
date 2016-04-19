<?php
namespace Lab123\Odin\Traits;

use Lab123\Odin\Enums\Response;
use Lab123\Odin\Entities\Entity;
use Log;
use App;

trait ApiResponse
{

    /**
     * Return HTTP Continue (100)
     *
     * @return \Illuminate\Http\Response
     */
    protected function continues()
    {
        $args = func_get_args();
        return $this->response($this->getData($args), Response::HTTP_CONTINUE);
    }

    /**
     * Return HTTP Success (200)
     *
     * @return \Illuminate\Http\Response
     */
    protected function success()
    {
        $args = func_get_args();
        return $this->response($this->getData($args), Response::HTTP_OK);
    }

    /**
     * Return HTTP Created (201)
     *
     * @return \Illuminate\Http\Response
     */
    protected function created()
    {
        $args = func_get_args();
        return $this->response($this->getData($args), Response::HTTP_CREATED);
    }

    /**
     * Return HTTP Bad Request (400)
     *
     * @return \Illuminate\Http\Response
     */
    protected function bad()
    {
        $args = func_get_args();
        return $this->response($this->getData($args), Response::HTTP_BAD_REQUEST);
    }

    /**
     * Return HTTP Unauthorized (401)
     *
     * @return \Illuminate\Http\Response
     */
    protected function unauthorized()
    {
        $args = func_get_args();
        return $this->response($this->getData($args), Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Return HTTP Not Found (404)
     *
     * @return \Illuminate\Http\Response
     */
    protected function notFound()
    {
        $args = func_get_args();
        return $this->response($this->getData($args), Response::HTTP_NOT_FOUND);
    }

    /**
     * Return HTTP Internal Error (500)
     *
     * @return \Illuminate\Http\Response
     */
    protected function internalError()
    {
        $args = func_get_args();
        return $this->response($this->getData($args), Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Return HTTP Conflict (409)
     *
     * @return \Illuminate\Http\Response
     */
    protected function conflict()
    {
        $args = func_get_args();
        return $this->response($this->getData($args), Response::HTTP_CONFLICT);
    }

    /**
     * Return HTTP Code
     *
     * @return \Illuminate\Http\Response
     */
    protected function response(array $data, $http_code)
    {
        return response()->json($data, $http_code);
    }

    /**
     * Return Exception into HTTP Internal Error (500)
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

    /**
     * Return entity data array or array blank
     *
     * @return array
     */
    private function getData($args)
    {
        $data = [];
        
        /* Sem argumentos, retorna array em branco */
        if (count($args) < 1) {
            return $data;
        }
        
        /* Enviou um array como parâmetro */
        if (is_array($args[1])) {
            return $args[1];
        }
        
        /* Enviou uma Entidade como parâmetro */
        if (is_a($args[1], Entity::class)) {
            $entity = ($args[1]);
            $data = $entity->toArray();
        }
        
        return $data;
    }
}