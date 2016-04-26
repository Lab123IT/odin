<?php
namespace Lab123\Odin\Validations;

use Illuminate\Foundation\Validation\ValidatesRequests;

class BaseValidation
{
    use ValidatesRequests;

    protected $rules = [];

    protected $original_rules = [];

    protected $relations = [];

    protected $original_relations = [];

    public function __construct()
    {
        $this->original_rules = $this->rules;
        $this->original_relations = $this->relations;
    }

    public function getRules()
    {
        return $this->rules;
    }

    public function getRelations()
    {
        return $this->relations;
    }

    public function getNameFields()
    {
        return array_keys($this->rules);
    }

    public function getNameFieldsFilled($request)
    {
        $rules = [];
        foreach ($request->all() as $field => $value) {
            if (key_exists($field, $this->rules)) {
                $rules[$field] = $this->rules[$field];
            }
        }
        
        return array_keys($rules);
    }

    public function getNameRelations()
    {
        return array_keys($this->relations);
    }

    public function getOriginalRules()
    {
        return $this->rules;
    }

    public function allRequired()
    {
        foreach ($this->rules as $k => $v) {
            $this->rules[$k] = 'required|' . $v;
        }
        
        foreach ($this->relations as $k => $v) {
            $this->relations[$k] = 'required|' . $v;
        }
        return $this;
    }

    public function required(array $fields)
    {
        if (count($fields) < 1) {
            return $this;
        }
        
        foreach ($fields as $field) {
            if (! key_exists($field, $this->rules)) {
                continue;
            }
            $this->rules[$field] = 'required|' . $this->rules[$field];
        }
        
        foreach ($fields as $field) {
            if (! key_exists($field, $this->relations)) {
                continue;
            }
            $this->relations[$field] = 'required|' . $this->relations[$field];
        }
        
        return $this;
    }

    public function except(array $excepts)
    {
        if (count($excepts) < 1) {
            return $this;
        }
        
        foreach ($this->rules as $k => $v) {
            foreach ($excepts as $except) {
                
                if ($k == $except) {
                    unset($this->rules[$k]);
                }
            }
        }
        
        return $this;
    }

    public function only(array $rules)
    {
        if (count($rules) < 1) {
            return $this;
        }
        
        $only_rules = [];
        foreach ($rules as $v) {
            if (! key_exists($v, $this->rules)) {
                continue;
            }
            
            $only_rules[$v] = $this->rules[$v];
        }
        $this->rules = $only_rules;
        
        return $this;
    }

    public function onlyRelation(array $rules)
    {
        if (count($rules) < 1) {
            return $this;
        }
        
        $only_rules = [];
        foreach ($rules as $v) {
            if (! key_exists($v, $this->relations)) {
                continue;
            }
            $only_rules[$v] = $this->relations[$v];
        }
        $this->relations = $only_rules;
        
        return $this;
    }

    public function oneField($request, $fields)
    {
        $data = $request->only($fields);
        
        if (count($data) > 1) {
            foreach ($data as $k => $v) {
                
                if (is_null($v)) {
                    unset($data[$k]);
                    continue;
                }
                
                unset($data);
                $data[$k] = $v;
                break;
            }
        }
        
        return $data;
    }
}