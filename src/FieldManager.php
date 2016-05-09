<?php
namespace Lab123\Odin;

abstract class FieldManager
{

    /**
     * General rules to validate this request.
     *
     * @var $rules
     */
    protected $fields = [];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules($extraRules = [])
    {
        $rules = $this->getRules();
        
        /* Adiciona as regras extras as regras padrão do objeto */
        foreach ($extraRules as $field => $rule) {
            
            if (! key_exists($field, $rules)) {
                continue;
            }
            
            $rules[$field] .= '|' . $rule;
        }
        
        return $this->transformToFrontName($rules);
    }

    private function getRules()
    {
        $rules = [];
        foreach ($this->fields as $field => $extra) {
            if (! key_exists('rules', $extra)) {
                continue;
            }
            
            $rules[$field] = $extra['rules'];
        }
        
        return $rules;
    }

    /**
     * Transform attributes model.
     *
     * @return array
     */
    public function transformToResource(array $array = [])
    {
        if (count($array) < 1) {
            return $array;
        }
        
        $transformation = array_reverse($this->getTransformation());
        $transformed = [];
        
        foreach ($transformation as $name => $new_name) {
            if (! key_exists($name, $array)) {
                
                if (key_exists($new_name, $array)) {
                    $transformed[$name] = $array[$new_name];
                }
                continue;
            }
            
            $transformed[$new_name] = $array[$name];
        }
        
        return $transformed;
    }

    /**
     * Transform attributes model.
     *
     * @return array
     */
    public function transformToFrontName(array $array = [])
    {
        $transformed = [];
        $transformation = $this->getTransformation();
        
        if (key_exists('public_id', $array)) {
            $transformed['id'] = $array['public_id'];
        }
        
        foreach ($transformation as $name => $new_name) {
            if (! key_exists($name, $array)) {
                continue;
            }
            
            $transformed[$new_name] = $array[$name];
        }
        
        return $transformed;
    }

    private function getTransformation()
    {
        $transforms = [];
        foreach ($this->fields as $field => $extra) {
            if (is_int($field)) {
                $transforms[$extra] = $extra;
                continue;
            }
            
            $transform = (key_exists('transform', $extra)) ? $extra['transform'] : $field;
            
            if ($transform === false) {
                continue;
            }
            
            $transforms[$field] = $transform;
        }
        
        return $transforms;
    }
}