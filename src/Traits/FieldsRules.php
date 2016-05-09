<?php
namespace Lab123\Odin\Traits;

trait FieldsRules
{

    /**
     * The params attributes.
     *
     * @var array
     */
    protected $fields = [];

    /**
     * Convert the model's attributes to an array.
     *
     * @return array
     */
    public function attributesToArray()
    {
        $array = parent::attributesToArray();
        
        return $this->transform($array);
    }

    /**
     * Transform attributes model.
     *
     * @return array
     */
    public function transform(array $array)
    {
        $transformation = $this->getTransformation();
        $transformed = [];
        
        if (key_exists('public_id', $array)) {
            $transformed['id'] = $array['public_id'];
        }
        
        foreach ($transformation as $name => $new_name) {
            if (! key_exists($name, $array)) {
                continue;
            }
            
            $transformed[$new_name] = $array[$name];
        }
        
        $transformed = array(
            'uri' => $this->getResourceData()
        ) + $transformed;
        
        return $transformed;
    }

    /**
     * Transform Front to Model
     *
     * @return array
     */
    public function transformFromFront(array $array)
    {
        $transformation = $this->getTransformation();
        $fillables = $this->getFillable();
        $transformed = [];
        
        /* Add fillables to array transformed */
        foreach ($fillables as $name) {
            if (! key_exists($name, $array)) {
                continue;
            }
            
            $transformed[$name] = $array[$name];
        }
        
        /* Add transform fields to array transformed */
        foreach ($transformation as $name => $new_name) {
            if (! key_exists($new_name, $array)) {
                continue;
            }
            
            $transformed[$name] = $array[$new_name];
        }
        
        return $transformed;
    }

    /**
     * Get the rules attributes for the model.
     *
     * @return array
     */
    public function getRules()
    {
        $rules = [];
        foreach ($this->fields as $field => $extra) {
            
            if (is_int($field)) {
                continue;
            }
            $rule = (key_exists('rules', $extra)) ? $extra['rules'] : '';
            
            $rules[$field] = $rule;
        }
        
        return $rules;
    }

    /**
     * Get the transformation attributes for the model.
     *
     * @return array
     */
    public function getTransformation()
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