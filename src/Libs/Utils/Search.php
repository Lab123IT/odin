<?php
namespace Lab123\Odin\Libs\Utils;

use Lab123\Odin\Entities\Entity;
use Illuminate\Http\Request;

class Search
{

    protected $model;

    protected $request;

    protected $fields = '*';

    protected $criteria;

    protected $includes;

    protected $limit = '50';

    protected $order;

    public function __construct(Entity $model, array $data)
    {
        $this->model = $model;
        
        $this->setFields(array_get($data, 'fields'));
        $this->setCriteria(array_get($data, 'criteria'));
        $this->setIncludes(array_get($data, 'includes'));
        $this->setLimit(array_get($data, 'limit'));
        $this->setOrder(array_get($data, 'order'));
        
        $this->filter();
    }

    /**
     * Transform object into a generic filter
     *
     * @var Model
     */
    public function filter()
    {
        $this->fields();
        $this->includes();
        $this->limit();
        $this->orderBy();
        
        return $this;
    }

    public function paginate()
    {
        $paginate = $this->model->paginate($this->limit);
        $paginate->setPath('');
        return $paginate;
    }

    public function simplePaginate()
    {
        $paginate = $this->model->simplePaginate($this->limit);
        $paginate->setPath('');
        return $paginate;
    }

    public function get()
    {
        return $this->model->get();
    }

    public function first()
    {
        return $this->model->first();
    }

    public function fields()
    {
        $this->model = $this->model->select($this->fields);
    }

    public function criteria($criteria)
    {
        foreach ($criteria as $c) {
            $this->model = $this->model->where($c[0], $c[1], $c[2]);
        }
    }

    public function includes()
    {
        foreach ($this->includes as $include) {
            $this->model = $this->model->with($include);
        }
    }

    public function orderBy()
    {
        foreach ($this->order as $order) {
            
            $order = explode(',', $order);
            
            $order[1] = (array_key_exists(1, $order)) ? $order[1] : '';
            
            $this->model = $this->model->orderBy($order[0], $order[1]);
        }
    }

    public function limit()
    {
        $this->model = $this->model->take((int) $this->limit);
    }

    private function setFields($fields)
    {
        $this->fields = (empty($fields)) ? '*' : array_filter(explode(',', $fields));
    }

    public function setCriteria($criteria)
    {
        $this->criteria = (empty($criteria)) ? [] : array_filter(explode(',', $criteria));
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
}