<?php
namespace Lab123\Odin\Entities;

use Illuminate\Database\Eloquent\Model;
use Lab123\Odin\Libs\Api;
use Request;

abstract class Entity extends Model
{

    /**
     * The params attributes.
     *
     * @var array
     */
    protected $fields = [];

    /**
     * The resource name from model.
     *
     * @var string
     */
    protected $resource = '';

    /**
     * The father resource.
     *
     * @var array
     */
    protected $father = '';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $children = [];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes            
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        
        /* Adiciona o Hash Id nas entidades */
        if (config('odin.hashid.active')) {
            self::addPublicId();
        }
    }

    /**
     * Adiciona propriedade public_id na serialização do recurso
     *
     * @return void
     */
    public function addPublicId()
    {
        parent::append([
            'public_id'
        ]);
    }

    /**
     * Return resource name
     *
     * @return array
     */
    public function getResourceName()
    {
        return ($this->resource) ? $this->resource : $this->getTable();
    }

    /**
     * Return father URI
     *
     * @return string
     */
    public function getFatherUri()
    {
        if ($this->father) {
            
            $func = $this->father;
            $relat = $this->$func();
            $fatherResourceName = $relat->getRelated()->getResourceName();
            
            $field = $relat->getForeignKey();
            
            if (! $this->$field) {
                return url();
            }
            
            return url() . '/' . $fatherResourceName . '/' . Api::encodeHashId($this->$field);
        }
        
        return url();
    }

    /**
     * Return father key name
     *
     * @return string
     */
    public function getFatherKeyName()
    {
        if ($this->father) {
            
            $func = $this->father;
            $relat = $this->$func();
            
            /*
             * if (! $relat instanceof \Illuminate\Database\Eloquent\Relations\BelongsTo) {
             * $field = $relat->getForeignKey();
             * } else {
             * $field = 'id';
             * }
             */
            
            $field = $relat->getForeignKey();
            
            return $field;
        }
        
        return 'id';
    }

    /**
     * Return resource URL
     *
     * @return array
     */
    public function getResourceURL()
    {
        return $this->getFatherUri() . '/' . $this->getResourceName() . '/' . $this->getId();
    }

    /**
     * Return resource URL
     *
     * @return array
     */
    public function getResourceChildURL($child)
    {
        $entity = ($this->$child()->getModel());
        return $this->getResourceURL() . '/' . $entity->getResourceName();
    }

    /**
     * Create link attribute to client
     *
     * @return array
     */
    public function getResourceData()
    {
        return $this->getResourceURL();
        
        /*
         * return [
         * 'type' => $this->getResourceName(),
         * 'uri' => $this->getResourceURL()
         * ];
         */
    }

    /**
     * Retorna o Id do resource
     *
     * @return array
     */
    public function getId()
    {
        if (config('odin.hashid.active')) {
            return $this->getPublicIdAttribute();
        }
        
        return $this->id;
    }

    /**
     * Retorna o hash id a partir do Id
     *
     * @return void
     */
    protected function getPublicIdAttribute()
    {
        if ($id = Api::decodeHashId($this->attributes['id'])) {
            return $id;
        }
        
        return Api::encodeHashId($this->attributes['id']);
    }

    /**
     * Cria o hash id a partir do Id
     *
     * @return void
     */
    protected function setPublicIdAttribute($value)
    {
        $this->attributes['public_id'] = Api::decodeHashId($this->attributes['id']);
    }

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

    /**
     * Create a new Eloquent Collection instance.
     *
     * @param array $models            
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function newCollection(array $models = [])
    {
        return new \Lab123\Odin\Collection($models);
    }
}