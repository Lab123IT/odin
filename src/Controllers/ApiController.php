<?php
namespace Lab123\Odin\Controllers;

use Lab123\Odin\Traits\ApiActionsChilds;
use Lab123\Odin\Requests\FilterRequest;
use Lab123\Odin\Traits\ApiResponse;
use Lab123\Odin\Traits\ApiActions;
use Lab123\Odin\Traits\ApiUser;
use Lab123\Odin\Libs\Api;
use App;
use DB;

class ApiController extends Controller
{
    use ApiResponse, ApiUser, ApiActions, ApiActionsChilds;

    /**
     * Instance of Repository.
     *
     * @var $repository Repository
     */
    protected $repository;

    /**
     * Class Fields Manage.
     *
     * @var $fieldManager
     */
    protected $fieldManager = '';

    /**
     * Return decoded Id or actual Id.
     *
     * @return $id
     */
    protected function getRealId($id)
    {
        return Api::decodeHashId($id);
    }
}