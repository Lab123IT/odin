<?php
namespace Lab123\Odin\Repositories;

use Lab123\Odin\Contracts\IRepository;
use Lab123\Odin\Requests\FilterRequest;
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
        $resource = $this->model->find($id);
        
        if (! $resource) {
            return '';
        }
        
        $resource->update($data);
        
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
            return '';
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
    public function get($limit = null)
    {
        if ($limit) {
            $this->builder->take($limit);
        }
        return $this->builder->get();
    }

    /**
     * Return one.
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function first()
    {
        return $this->builder->first();
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
     * Rules of Entity
     *
     * @param array $fields            
     * @return array
     */
    public function getRulesFromChild($relation, array $fields = [])
    {
        $model = $this->model->$relation()->getRelated();
        
        $default_rules = $model->getRules();
        
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
        $transformation = $model->getTransformation();
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
     * Get only fields fillable from Pivot Relation
     *
     * @return array
     */
    public function onlyFillablePivot($pivotRelation, $data)
    {
        $fillable = $this->getPivotFields($pivotRelation, 'pivotColumns');
        
        return array_only($data, $fillable);
    }

    /**
     * Get array fields fillable from Pivot Relation
     *
     * @return array
     */
    public function getPivotFields($obj, $prop)
    {
        $reflection = new \ReflectionClass($obj);
        $property = $reflection->getProperty($prop);
        $property->setAccessible(true);
        $value = $property->getValue($obj);
        $property->setAccessible(false);
        
        /* Remove timestamp from pivot */
        return array_diff($value, [
            'deleted_at',
            'created_at',
            'updated_at'
        ]);
    }

    /**
     * Create child One-to-One OR Many-to-Many without Pivot data
     *
     * @return \Illuminate\Database\Eloquent\Model;
     */
    public function storeChild($id, $relation, array $data)
    {
        $parent = $this->model->find($id);
        
        if (! $parent) {
            return null;
        }
        
        $resource = $parent->$relation()->create($data);
        
        return $resource;
    }

    /**
     * Attach relation
     *
     * @return \Illuminate\Database\Eloquent\Model;
     */
    public function storeChildAndPivot($idParent, $relation, $data = [])
    {
        $parent = $this->find($idParent);
        $childEntity = $parent->$relation()->getRelated();
        
        $child = $childEntity->create($data);
        
        $data = $this->onlyFillablePivot($parent->$relation(), $data);
        
        $parent->$relation()->attach($child->id, $data);
        
        return $child;
    }

    /**
     * Attach relation
     *
     * @return \Illuminate\Database\Eloquent\Model;
     */
    public function attach($idParent, $idChild, $relation, $data = [])
    {
        $parent = $this->find($idParent);
        
        $data = $this->onlyFillablePivot($parent->$relation(), $data);
        
        $parent->$relation()->attach($idChild, $data);
        
        return $parent->$relation()->find($idChild);
    }

    /**
     * Detach relation
     *
     * @return \Illuminate\Database\Eloquent\Model;
     */
    public function detach($idParent, $idChild, $relation)
    {
        $parent = $this->find($idParent);
        
        $parent->$relation()->detach($idChild);
        
        return true;
    }

    /**
     * Return all childs from relation
     *
     * @return \Illuminate\Database\Eloquent\Model;
     */
    public function getChilds($id, $relation, $filters = null)
    {
        $parent = $this->model->find($id);
        
        if (! $parent) {
            return null;
        }
        
        if (count($filters->request->all()) > 0) {
            $child = $parent->$relation()->getRelated();
            
            $search = new Search($child, $filters, $parent->$relation());
            $this->builder = $search->getBuilder();
            $data = $this->builder->get();
            
            dd($parent);
            dd($data[0]);
            
            return $this->builder->get();
        }
        
        $resource = $parent->$relation;
        
        return $resource;
    }

    /**
     * Return one child by id
     *
     * @return \Illuminate\Database\Eloquent\Model;
     */
    public function getChild($id, $relation, $idChild)
    {
        $parent = $this->model->find($id);
        
        if (! $parent) {
            return null;
        }
        
        $resource = $parent->$relation()->find($idChild);
        
        return $resource;
    }

    /**
     * Update Child
     *
     * @return \Illuminate\Database\Eloquent\Model;
     */
    public function updateChild($id, $relation, $idChild, array $data)
    {
        $parent = $this->model->find($id);
        
        if (! $parent) {
            return null;
        }
        
        $resource = $parent->$relation()->find($idChild);
        
        if (! $resource) {
            return null;
        }
        $resource->update($data);
        
        return $resource;
    }

    /**
     * Delete Child
     *
     * @return boolean;
     */
    public function deleteChild($id, $relation, $idChild)
    {
        $parent = $this->model->find($id);
        
        if (! $parent) {
            return null;
        }
        
        $resource = $parent->$relation()->find($idChild);
        
        if (! $resource) {
            return null;
        }
        
        return $resource->delete();
    }

    /**
     * Autocomplete
     *
     * @return array;
     */
    public function autocomplete($text)
    {
        if (! $this->builder) {
            $this->builder = $this->model;
        }
        
        $fields = $this->model->getAutocomplete();
        
        foreach ($fields as $field) {
            
            $this->builder = $this->builder->where(function ($query) use($field, $text) {
                $query->orWhere($field, 'like', "%$text%");
            });
        }
        
        return $this;
    }
}