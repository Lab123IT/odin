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
     * Class Fields Manage.
     *
     * @var $fieldManager
     */
    protected $fieldManager = '';

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
        if ($this->fieldManager) {
            $this->fieldManager = new $this->fieldManager();
            $attributes = $this->fieldManager->transformToResource($attributes);
        }
        
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
        if ($this->getFatherName()) {
            
            $func = $this->getFatherName();
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
        if ($this->getFatherName()) {
            
            $func = $this->getFatherName();
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

    public function getFatherName()
    {
        return $this->father;
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
        
        if ($this->fieldManager) {
            
            $array = $this->fieldManager->transformToFrontName($array);
        }
        
        $array = array(
            'uri' => $this->getResourceData()
        ) + $array;
        
        return $array;
    }

    /**
     * Get the rules attributes for the model.
     *
     * @return array
     */
    public function getRules()
    {
        return $this->fieldManager->rules();
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