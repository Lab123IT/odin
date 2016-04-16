<?php
namespace Lab123\Odin\Repositories;

use Lab123\Odin\Repositories\Contracts\IRepository;
use Lab123\Odin\Libs\Utils\Api;
use Lab123\Odin\Libs\Utils\Search;
use Illuminate\Http\Request;

abstract class Repository implements IRepository
{

    /**
     * Model reference
     */
    protected $model;

    /**
     * Tree URI reference
     */
    protected $tree_uri = [];

    /**
     * Return a resource by id
     *
     * @param $id int            
     */
    public function find($id)
    {
        return $this->model->find($id);
    }

    public function findAll()
    {
        return $this->model->all();
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(array $data, $id)
    {
        return $this->model->find($id)->update($data);
    }

    public function firstOrCreate(array $data)
    {
        return $this->model->firstOrCreate($data);
    }

    public function delete($id)
    {
        return $this->model->find($id)->delete();
    }

    public function filter(array $data)
    {
        return new Search($this->model, $data);
    }

    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null, $include = null, $fields = null)
    {
        $model = $this->model;
        
        if (count($criteria) == count($criteria, COUNT_RECURSIVE)) {
            if (count($criteria) > 0) {
                $model = $model->where($criteria[0], $criteria[1], $criteria[2]);
            }
        } else {
            foreach ($criteria as $c) {
                $model = $model->where($c[0], $c[1], $c[2]);
            }
        }
        
        if ($orderBy !== null) {
            foreach ($orderBy as $order) {
                $model = $model->orderBy($order[0], $order[1]);
            }
        }
        
        if ($limit !== null) {
            $model = $model->take((int) $limit);
        }
        
        if ($offset !== null) {
            $model = $model->skip((int) $offset);
        }
        
        if ($include !== null) {
            $model = $model->with($include);
        }
        
        if ($fields !== null) {
            $model = $model->select($fields);
        }
        
        return $model->get();
    }

    public function findOneBy(array $criteria)
    {
        return $this->findBy($criteria)->first();
    }
}