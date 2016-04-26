<?php
namespace Lab123\Odin\Entities;

use Illuminate\Database\Eloquent\Model;
use App;

abstract class Entity extends Model
{

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes            
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        
        /* Ativa o Hash Id automático nas entidades */
        if (config('odin.hashid')) {
            self::hideId();
            self::addPublicId();
        }
    }

    /**
     * Esconde o id na serialização pro client
     *
     * @return void
     */
    public function hideId()
    {
        parent::addHidden([
            'id'
        ]);
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
     * Gera propriedade link do recurso
     *
     * @return array
     */
    public function generateLink()
    {
        return [
            'link' => [
                'rel' => 'self',
                'uri' => '/books/' . $this->id
            ]
        ];
    }

    /**
     * Retorna o hash id a partir do Id
     *
     * @return void
     */
    protected function getPublicIdAttribute()
    {
        $hashids = App::make('Hashids');
        return $hashids->encode($this->attributes['id']);
    }

    /**
     * Cria o hash id a partir do Id
     *
     * @return void
     */
    protected function setPublicIdAttribute($value)
    {
        $hashids = App::make('Hashids');
        $this->attributes['public_id'] = $hashids->decode($this->attributes['id']);
    }

    /**
     * Transforma a entidade
     *
     * @return array
     */
    public function transform()
    {
        return $this->toArray();
    }
}