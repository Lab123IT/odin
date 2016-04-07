<?php
namespace Lab123\Odin\Libs\Utils;

use Lab123\Odin\Entities\Entity;
use DB;

class Search
{

    protected $model;

    protected $request;

    protected $fields = '*';

    protected $criteria;

    protected $includes;

    protected $limit = '50';

    protected $order;

    protected $builder;

    public function __construct(Entity $model, array $data)
    {
        $this->model = $model;
        $this->builder = $model;
        
        $this->setFields(array_get($data, 'fields'));
        $this->setCriteria(array_get($data, 'criteria'));
        $this->setIncludes(array_get($data, 'includes'));
        $this->setLimit(array_get($data, 'limit'));
        $this->setOrder(array_get($data, 'order'));
        
        $this->searchFields($data);
        
        $this->filter();
    }

    protected function searchFields($data)
    {
        $builder = '';
        if (method_exists($this->model, 'searchFields')) {
            $builder = $this->model->searchFields($data);
        }
        
        $this->builder = ($builder) ? $builder : $this->builder;
    }

    /**
     * Transform object into a generic filter
     *
     * @var Model
     */
    public function filter()
    {
        $this->fields()
            ->includes()
            ->limit()
            ->orderBy()
            ->criteria();
        
        return $this;
    }

    public function paginate()
    {
        $paginate = $this->builder->paginate($this->limit);
        $paginate->setPath('');
        return $paginate;
    }

    public function simplePaginate()
    {
        $paginate = $this->builder->simplePaginate($this->limit);
        $paginate->setPath('');
        return $paginate;
    }

    public function get()
    {
        return $this->builder->get();
    }

    public function first()
    {
        return $this->builder->first();
    }

    public function fields()
    {
        $this->builder = $this->builder->select($this->fields);
        
        return $this;
    }

    public function criteria()
    {
        foreach ($this->criteria as $c) {
            
            $c = explode(',', $c);
            
            $this->builder = $this->builder->where($c[0], $c[1], $c[2]);
        }
        
        return $this;
    }

    public function includes()
    {
        foreach ($this->includes as $include) {
            $this->builder = $this->builder->with($include);
        }
        
        return $this;
    }

    public function orderBy()
    {
        if (end($this->order) == "random") {
            $this->builder = $this->builder->orderBy($this->random());
            return $this;
        }
        
        foreach ($this->order as $order) {
            
            $order = explode(',', $order);
            
            $order[1] = (array_key_exists(1, $order)) ? $order[1] : '';
            
            $this->builder = $this->builder->orderBy($order[0], $order[1]);
        }
        
        return $this;
    }

    public function limit()
    {
        $this->builder = $this->builder->take((int) $this->limit);
        
        return $this;
    }

    private function setFields($fields)
    {
        $this->fields = (empty($fields)) ? '*' : array_filter(explode(',', $fields));
    }

    public function setCriteria($criteria)
    {
        $this->criteria = (empty($criteria)) ? [] : $criteria;
    }

    private function setIncludes($includes)
    {
        $this->includes = (empty($includes)) ? [] : array_filter(explode(',', $includes));
    }

    private function setLimit($limit)
    {
        $this->limit = (empty($limit)) ? $this->limit : $limit;
    }

    private function setOrder($order)
    {
        $this->order = (empty($order)) ? [] : $order;
    }

    private function random()
    {
        /* Funções para random de cada um dos SGBDs tradicionais */
        $randomFunctions = [
            'mysql' => 'RAND()',
            'pgsql' => 'RANDOM()',
            'sqlite' => 'RANDOM()',
            'sqlsrv' => 'NEWID()'
        ];
        
        /* Drive padrão da entidade */
        $driver = $this->model->getConnection()->getDriverName();
        
        return DB::raw($randomFunctions[$driver]);
    }
}