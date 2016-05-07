<?php
namespace Lab123\Odin\Repositories;

use Lab123\Odin\Contracts\IRepository;
use Lab123\Odin\Requests\FilterRequest;
use Lab123\Odin\Facades\ApiResponse;
use Lab123\Odin\Libs\Search;
use Lab123\Odin\Libs\Api;
use Request;
use App;

abstract class Repository implements IRepository
{

    /**
     * Model reference
     */
    protected $model;

    /**
     * Builder of Model
     */
    protected $builder;

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
        $data = $this->adjustArray($data);
        return $this->model->create($this->model->transformFromFront($data));
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
        $resource = $this->model->find($id);
        
        if (! $resource) {
            return ApiResponse::notFound();
        }
        
        $data = $this->adjustArray($data);
        $resource->update($this->model->transformFromFront($data));
        
        return $resource;
    }

    /**
     * Delete a resource by id
     *
     * @param $id int            
     * @return boolean
     */
    public function delete($id)
    {
        $resource = $this->model->find($id);
        
        if (! $resource) {
            return ApiResponse::notFound();
        }
        
        return $resource->delete();
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
        $this->builder = $this->model;
        
        if (count($criteria) == count($criteria, COUNT_RECURSIVE)) {
            if (count($criteria) > 0) {
                $this->builder = $this->builder->where($criteria[0], $criteria[1], $criteria[2]);
            }
        } else {
            foreach ($criteria as $c) {
                $this->builder = $this->builder->where($c[0], $c[1], $c[2]);
            }
        }
        
        if ($orderBy !== null) {
            foreach ($orderBy as $order) {
                $this->builder = $this->builder->orderBy($order[0], $order[1]);
            }
        }
        
        if ($limit !== null) {
            $this->builder = $this->builder->take((int) $limit);
        }
        
        if ($offset !== null) {
            $this->builder = $this->builder->skip((int) $offset);
        }
        
        if ($include !== null) {
            $this->builder = $this->builder->with($include);
        }
        
        if ($fields !== null) {
            $this->builder = $this->builder->select($fields);
        }
        
        return $this->builder->get();
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
     * Paginate the given query into a simple paginator.
     *
     * @param int $perPage            
     * @param array $columns            
     * @param string $pageName            
     * @param int|null $page            
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function get()
    {
        return $this->builder->get();
    }

    /**
     * Paginate the given query into a simple paginator.
     *
     * @param int $perPage            
     * @param array $columns            
     * @param string $pageName            
     * @param int|null $page            
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate($perPage = 15, $columns = ['*'], $pageName = 'page', $page = null)
    {
        /* Strip param page from URL */
        $url = preg_replace('/&?page=[^&]*/', '', Request::fullUrl());
        
        $paginate = $this->builder->paginate($perPage, $columns, $pageName, $page);
        $paginate->setPath($url);
        return $paginate;
    }

    /**
     * Get a paginator only supporting simple next and previous links.
     *
     * This is more efficient on larger data-sets, etc.
     *
     * @param int $perPage            
     * @param array $columns            
     * @param string $pageName            
     * @return \Illuminate\Contracts\Pagination\Paginator
     */
    public function simplePaginate($perPage = 15, $columns = ['*'], $pageName = 'page')
    {
        /* Strip param page from URL */
        $url = preg_replace('/&?page=[^&]*/', '', Request::fullUrl());
        
        $paginate = $this->builder->simplePaginate($perPage, $columns, $pageName);
        $paginate->setPath($url);
        return $paginate;
    }

    /**
     * Filter the Entity
     *
     * @param Lab123\Odin\Requests\FilterRequest $filters            
     * @return Lab123\Odin\Libs\Search
     */
    public function filter(FilterRequest $filters)
    {
        $filters = $this->adjustFilters($filters);
        
        $search = new Search($this->model, $filters);
        $this->builder = $search->getBuilder();
        
        return $this;
    }

    /**
     * Rules of Entity
     *
     * @param array $fields            
     * @return array
     */
    public function getRules(array $fields = [])
    {
        $default_rules = $this->model->getRules();
        
        if (count($fields) < 1) {
            return $default_rules;
        }
        
        foreach ($fields as $field => $rule) {
            if (is_int($field)) {
                $rules[$rule] = $default_rules[$rule];
                continue;
            }
            
            if (! key_exists($field, $default_rules)) {
                continue;
            }
            
            $default_rules[$field] .= '|' . $rule;
        }
        
        $rules = [];
        $transformation = $this->model->getTransformation();
        foreach ($transformation as $original => $transformed) {
            $rules[$transformed] = $default_rules[$original];
        }
        
        foreach ($fields as $field => $rule) {
            if (! key_exists($field, $rules)) {
                continue;
            }
            
            $rules[$field] .= '|' . $rule;
        }
        
        return $rules;
    }

    /**
     * Adjust filters (criteria)
     *
     * @return $filters
     */
    private function adjustFilters($filters)
    {
        $fatherKey = $this->model->getFatherKeyName();
        
        foreach ($filters->criteria as &$critria) {
            
            /* Adiciona a chave certa para fltrar por pai */
            if (strpos($critria, 'father_id') !== false) {
                $critria = str_replace('father_id', $fatherKey, $critria);
            }
        }
        
        return $filters;
    }

    /**
     * Adjust array
     *
     * @return $array
     */
    private function adjustArray($data)
    {
        $fatherKey = $this->model->getFatherKeyName();
        
        if (key_exists('father_id', $data)) {
            $data[$fatherKey] = $data['father_id'];
            unset($data['father_id']);
        }
        
        return $data;
    }
}