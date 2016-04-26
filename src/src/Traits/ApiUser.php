<?php
namespace Lab123\Odin\Traits;

use JWTAuth;

trait ApiUser
{

    /**
     * Return the current user.
     *
     * @return \Illuminate\Http\Response
     */
    protected function getCurrentUser()
    {
        try {
            return JWTAuth::parseToken()->authenticate();
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $ex) {
            return null;
        }
    }
}