<?php
namespace Lab123\Odin\Libs\Lists;

class HttpList extends GenericList
{

    public function __construct()
    {
        $this->list = [
            '200' => 'OK',
            '201' => 'CREATED',
            '202' => 'ACCEPTED',
            '204' => 'NO CONTENT',
            '301' => 'MOVED PERMANENTLY',
            '400' => 'BAD REQUEST',
            '401' => 'ANAUTHORIZED',
            '403' => 'FORBIDDEN',
            '404' => 'NOT FOUND',
            '405' => 'METHOD NOT ALLOWED',
            '409' => 'CONFLICT',
            '415' => 'UNSUPPOTED MEDIA TYPE',
            '422' => 'UNPROCESSABLE ENTITY',
            '500' => 'INTERNAL SERVER ERROR'
        ];
    }
}