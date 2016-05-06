<?php
namespace Lab123\Odin\Libs;

use Lab123\Odin\Requests\FilterRequest;
use Lab123\Odin\Entities\Entity;
use Lab123\Odin\Libs\Api;
use DB;

class Search
{

    protected $entity;

    protected $builder;

    protected $filters;

    public function __construct(Entity $entity, FilterRequest $filters)
    {
        $this->builder = $this->entity = $entity;
        $this->filters = $filters;
        
        $this->setFilters();
    }

    /**
     * Transform object into a generic filter
     *
     * @return this
     */
    public function setFilters()
    {
        $this->fields()
            ->includes()
            ->limit()
            ->orderBy()
            ->criteria();
    }

    /**
     * Return Query Builder with filters
     *
     * @return this
     */
    public function getBuilder()
    {
        return $this->builder;
    }

    /**
     * Set fields to return in select
     *
     * @return this
     */
    public function fields()
    {
        $fields = $this->filters->fields;
        if ($this->filters->fields[0] != '*') {
            $fields = $this->getOnlyAvailableAttributes($fields);
        }
        
        $this->builder = $this->builder->select($fields);
        
        return $this;
    }

    /**
     * Set criteria where to return in select
     *
     * @return this
     */
    public function criteria()
    {
        foreach ($this->filters->criteria as $criteria) {
            
            list ($field, $operator, $value) = explode(',', $criteria);
            
            /*
             * if (! $fields = $this->getOnlyAvailableAttributes([])) {
             * dd($fields);
             * continue;
             * }
             */
            
            $this->builder = $this->builder->where($field, $operator, $value);
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
     * Random data frm database
     *
     * @return raw
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

    /**
     * Return only fields of Entity
     *
     * @return array
     */
    private function getOnlyAvailableAttributes(array $verify = [])
    {
        $fillable = array_flip($this->entity->getFillable());
        
        // $this->entity->getTransformation()
        
        $availableAttributes = [];
        foreach ($verify as $k => $v) {
            if (key_exists($v, $fillable)) {
                $availableAttributes[$k] = $v;
            }
        }
        
        return $availableAttributes;
    }
}