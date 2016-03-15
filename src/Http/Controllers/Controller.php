<?php
namespace Lab123\Odin\Http\Controllers;

use Illuminate\Routing\Controller as IlluminateController;
use Illuminate\Http\Request;
use Lab123\Odin\Http\Controllers\ApiController;
use JWTAuth;

class Controller extends ApiController
{

    protected function getCurrentUser()
    {
        return JWTAuth::parseToken()->authenticate();
    }
}
