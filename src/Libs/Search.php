<?php
namespace Lab123\Odin\Libs;

use Lab123\Odin\Entities\Entity;
use Lab123\Odin\Requests\FilterRequest;
use DB;

class Search
{

    protected $entity;

    protected $builder;

    protected $filters;

    public function __construct(Entity $entity, FilterRequest $filters)
    {
        $this->builder = $this->entity = $entity;
        // $this->builder = $entity;
        $this->filters = $filters;
        
        $this->filter();
    }

    /**
     * Transform object into a generic filter
     *
     * @return this
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

    /**
     * Generate Paginate data
     *
     * @return paginate
     */
    public function paginate()
    {
        $paginate = $this->builder->paginate($this->filters->limit);
        
        $paginate->setPath('');
        return $paginate;
    }

    /**
     * Generate Simple Paginate data
     *
     * @return paginate
     */
    public function simplePaginate()
    {
        $paginate = $this->builder->simplePaginate($this->filters->limit);
        $paginate->setPath('');
        return $paginate;
    }

    /**
     * Return Collection of resource
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function get()
    {
        return $this->builder->get();
    }

    /**
     * Return one resource
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function first()
    {
        return $this->builder->first();
    }

    /**
     * Set fields to return in select
     *
     * @return this
     */
    public function fields()
    {
        $this->builder = $this->builder->select($this->filters->fields);
        
        return $this;
    }

    /**
     * Set criteria where to return in select
     *
     * @return this
     */
    public function criteria()
    {
        foreach ($this->filters->criteria as $c) {
            
            $c = explode(',', $c);
            
            $this->builder = $this->builder->where($c[0], $c[1], $c[2]);
        }
        
        return $this;
    }

    /**
     * Set includes to return in select
     *
     * @return this
     */
    public function includes()
    {
        foreach ($this->filters->includes as $include) {
            $this->builder = $this->builder->with($include);
        }
        
        return $this;
    }

    /**
     * Set order to return in select
     *
     * @return this
     */
    public function orderBy()
    {
        if (end($this->filters->order) == "random") {
            $this->builder = $this->builder->orderBy($this->random());
            return $this;
        }
        
        foreach ($this->filters->order as $order) {
            
            $order = explode(',', $order);
            
            $order[1] = (array_key_exists(1, $order)) ? $order[1] : '';
            
            $this->builder = $this->builder->orderBy($order[0], $order[1]);
        }
        
        return $this;
    }

    /**
     * Set limit to return in select
     *
     * @return this
     */
    public function limit()
    {
        $this->builder = $this->builder->take((int) $this->filters->limit);
        
        return $this;
    }

    /**
     * Set limit to return in select
     *
     * @return this
     */
    private function random()
    {
        /* Funções para random de cada um dos SGBDs tradicionais */
        $randomFunctions = [
            'mysql' => 'RAND()',
            'pgsql' => 'RANDOM()',
            'sqlite' => 'RANDOM()',
            'sqlsrv' => 'NEWID()',
            'dblib' => 'NEWID()'
        ];
        
        /* Drive padrão da entidade */
        $driver = $this->entity->getConnection()->getDriverName();
        
        return DB::raw($randomFunctions[$driver]);
    }
}