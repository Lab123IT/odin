<?php
namespace Lab123\Odin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;
use Lab123\Odin\Libs\Api;
use Request;

abstract class Entity extends Model
{

    /**
     * The resource name from model.
     *
     * @var string
     */
    protected $resource = '';

    /**
     * Load model from relation.
     *
     * @var array
     */
    public $load = [];

    /**
     * Load only URI.
     *
     * @var array
     */
    public $loadUri = [];

    /**
     * Return entities loads to response API.
     *
     * @var boolean
     */
    static $loaded = false;

    /**
     * Class Fields Manage.
     *
     * @var Lab123\Odin\FieldManager
     */
    protected $fieldManager = '';

    /**
     * The parent resource.
     *
     * @var string
     */
    protected $parent = '';

    /**
     * The actions from resource.
     *
     * @var array
     */
    public $actions = [];

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
     * Fill the model with an array of attributes.
     *
     * @param array $attributes            
     * @return $this
     *
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    public function fill(array $attributes)
    {
        if ($this->getFieldManager()) {
            $attributes = $this->getFieldManager()->transformToResource($attributes);
        }
        
        return parent::fill($attributes);
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
     * Return parent URI
     *
     * @return string
     */
    public function getParentUri()
    {
        if ($this->getParentName()) {
            
            $func = $this->getParentName();
            if (! is_string($func)) {
                return;
            }
            $relat = $this->$func();
            $parentResourceName = $relat->getRelated()->getResourceName();
            
            $field = $relat->getForeignKey();
            
            if (! $this->$field/* || ! Request::is($parentResourceName . '/*')*/) {
                return Api::url();
            }
            
            return Api::url() . '/' . $parentResourceName . '/' . Api::encodeHashId($this->$field);
        }
        
        return Api::url();
    }

    /**
     * Return parent key name
     *
     * @return string
     */
    public function getParentKeyName()
    {
        if ($this->getParentName()) {
            
            $func = $this->getParentName();
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

    public function getParentName()
    {
        return $this->parent;
    }

    /**
     * Return resource URL
     *
     * @return array
     */
    public function getResourceURL()
    {
        return $this->getParentUri() . '/' . $this->getResourceName() . '/' . $this->getId();
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
     * Create link attribute to client
     *
     * @return array
     */
    public function getActions()
    {
        $actions = [];
        
        if (is_array($this->actions)) {
            
            foreach ($this->actions as $action) {
                $actions[$action] = $action;
            }
        }
        
        return $actions;
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
        
        if ($this->getFieldManager()) {
            $array = $this->getFieldManager()->transformToFrontName($array);
        }
        
        $array = array(
            'uri' => $this->getResourceData()
        ) + $array;
        
        return $array;
    }

    /**
     * Convert the model's attributes to an array.
     *
     * @return array
     */
    public function autoload()
    {
        if (self::$loaded) {
            return;
        }
        
        if (is_array($this->load)) {
            foreach ($this->load as $k => $load) {
                $this->load($load);
            }
        }
        
        if (is_array($this->loadUri)) {
            foreach ($this->loadUri as $k => $load) {
                $this->load([
                    $load => function ($query) {
                        $query->select('id', $query->getForeignKey());
                    }
                ]);
            }
        }
        
        self::$loaded = true;
    }

    /**
     * Get the rules attributes for the model.
     *
     * @return array
     */
    public function getRules()
    {
        return $this->getFieldManager()->rules();
    }

    /**
     * Get the config of autocomplete.
     *
     * @return array
     */
    public function getAutocomplete()
    {
        return $this->getFieldManager()->getAutocomplete();
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

    /**
     * Return instance Field Manager of controller
     *
     * @return object Lab123\Odin\FieldManager
     */
    public function getFieldManager()
    {
        /* Verifica se existe Field Manager com prefixo igual a controller */
        if (! $this->fieldManager) {
            $pathClassExploded = explode('\\', get_class($this));
            $namespace = array_first($pathClassExploded);
            $resourceName = array_last($pathClassExploded);
            
            /* Verifica se existe o Field Manager para o recurso */
            if (! class_exists("{$namespace}\\FieldManagers\\{$resourceName}FieldManager")) {
                return null;
                echo "Crie o Field Manager {$resourceName}FieldManager em {$namespace}\\FieldManagers";
                exit();
            }
            
            $this->fieldManager = $namespace . "\\FieldManagers\\{$resourceName}FieldManager";
        }
        
        /* Ainda precisa instanciar o objeto */
        if (is_string($this->fieldManager)) {
            return new $this->fieldManager();
        }
        
        /* Objeto já instanciado, só retornar */
        return $this->fieldManager;
    }

    /**
     * Copia a entidade e suas relações
     *
     * @return object
     */
    public function copy()
    {
        // copy attributes
        $new = $this->replicate();
        
        // save model before you recreate relations (so it has an id)
        $new->save();
        
        // re-sync everything
        foreach ($this->relations as $relationName => $values) {
            if ($new->{$relationName}() instanceof \Illuminate\Database\Eloquent\Relations\BelongsToMany) {
                $new->{$relationName}()->sync($values);
            } else {
                $new->{$relationName}()->attach($values);
            }
        }
        
        return $new;
    }
}