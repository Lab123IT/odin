<?php
namespace Lab123\Odin\Transformers;

use Illuminate\Support\Str;

class Transformer
{

    protected $model;

    protected $data = [];

    protected $mapper = [];

    protected $hidden = [];

    protected $appends = [];

    protected $with = [];

    /**
     * Transform object into a generic array
     *
     * @var Work
     */
    private function transform($mapper, $inverse)
    {
        if (! is_array($mapper)) {
            return '';
        }
        
        $branch = array();
        
        // $mapper = ($inverse == true) ? array_flip($mapper) : $mapper;
        
        foreach ($mapper as $k => $v) {
            $branch[$k] = (is_array($v)) ? $this->transform($v, $inverse) : $this->model->$v;
        }
        
        return $branch;
    }

    public function convertToClient()
    {
        return array_merge($this->convert(), $this->additionalConvert());
    }

    public function convertFromClient()
    {
        return $this->convert(true);
    }

    private function convert($inverse = false)
    {
        $data = $this->transform($this->mapper, $inverse);
        
        foreach ($this->with as $value) {
            if (! $this->hasGetMutator($value)) {
                continue;
            }
            
            $data[$value] = $this->mutateAttribute($value);
        }
        
        return $data;
    }

    public function with()
    {
        $this->with = func_get_args();
        return $this;
    }

    public function hasGetMutator($key)
    {
        return method_exists($this, 'get' . Str::studly($key) . 'Attribute');
    }

    protected function mutateAttribute($key)
    {
        return $this->{'get' . Str::studly($key) . 'Attribute' }();
    }

    protected function additionalConvert()
    {
        return [];
    }

    public function setModel($model)
    {
        $this->model = $model;
        return $this;
    }
}