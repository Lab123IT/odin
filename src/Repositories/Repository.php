<?php
namespace Lab123\Odin\Repositories;

use Lab123\Odin\Contracts\IRepository;
use Lab123\Odin\Libs\Api;
use Lab123\Odin\Libs\Search;
use Lab123\Odin\Requests\FilterRequest;

abstract class Repository implements IRepository
{

    /**
     * Model reference
     */
    protected $model;

    /**
     * Return a resource by id
     *
     * @param $id int            
     * @return Illuminate\Database\Eloquent\Model
     */
    public function find($id)
    {
        return $this->model->find($id);
    }

    /**
     * Return collection of resources
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function findAll()
    {
        return $this->model->all();
    }

    /**
     * Return a new resource
     *
     * @param $data array            
     * @return Illuminate\Database\Eloquent\Model
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Retrieve the resource by the attributes, or create it if it doesn't exist
     *
     * @param $data array            
     * @return Illuminate\Database\Eloquent\Model
     */
    public function firstOrCreate(array $data)
    {
        return $this->model->firstOrCreate($data);
    }

    /**
     * Update a resource by id
     *
     * @param $data array            
     * @param $id int            
     * @return boolean
     */
    public function update(array $data, $id)
    {
        return $this->model->find($id)->update($data);
    }

    /**
     * Delete a resource by id
     *
     * @param $id int            
     * @return boolean
     */
    public function delete($id)
    {
        return $this->model->find($id)->delete();
    }

    /**
     * Return collection of resources
     *
     * @param $criteria array            
     * @param $orderBy array            
     * @param $limit int            
     * @param $offset int            
     * @param $include array            
     * @param $fields string            
     * @return Illuminate\Database\Eloquent\Collection
     */
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

    /**
     * Return a resource by criteria
     *
     * @param $criteria array            
     * @return Illuminate\Database\Eloquent\Model
     */
    public function findOneBy(array $criteria)
    {
        return $this->findBy($criteria)->first();
    }

    /**
     * Filter the Entity
     *
     * @param Lab123\Odin\Requests\FilterRequest $filters            
     * @return Lab123\Odin\Libs\Search
     */
    public function filter(FilterRequest $filters)
    {
        return new Search($this->model, $filters);
    }
}