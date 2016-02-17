<?php
namespace Lab123\Odin\Services;

use Illuminate\Database\Eloquent\Model;

class MigrateHelper
{

    protected $model;

    public function __construct($model)
    {
        $this->model = new $model();
    }

    public function getTable()
    {
        return $this->model->getTable();
    }

    public function getKeyName()
    {
        return $this->model->getKeyName();
    }
}