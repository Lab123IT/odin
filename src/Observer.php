<?php

namespace Lab123\Odin;

use Lab123\Odin\Entities\Entity;
use Lab123\Odin\Contracts\IObserver;

abstract class Observer implements IObserver
{

    /**
     * Entity Reference (App\Entities\Entity::class)
     */
    protected $entity;

    /**
     * Observers available
     */
    protected $observers = [
        'creating',
        'created',
        'saving',
        'saved',
        'updating',
        'updated',
        'deleting',
        'deleted'
    ];

    /**
     * Create a new Observer instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->activeObservers();
    }

    /**
     * Active observers
     *
     * @return void
     */
    protected function activeObservers()
    {
        foreach ($this->observers as $observer) {
            
            $entity = $this->entity;
            
            $entity::$observer(function ($entity) use ($observer) {
                $this->$observer($entity);
            });
        }
    }

    /**
     * Active observer in trigger creating
     */
    public function creating($entity)
    {}

    /**
     * Active observer in trigger created
     */
    public function created($entity)
    {}

    /**
     * Active observer in trigger saving
     */
    public function saving($entity)
    {}

    /**
     * Active observer in trigger saved
     */
    public function saved($entity)
    {}

    /**
     * Active observer in trigger updating
     */
    public function updating($entity)
    {}

    /**
     * Active observer in trigger updated
     */
    public function updated($entity)
    {}

    /**
     * Active observer in trigger deleting
     */
    public function deleting($entity)
    {}

    /**
     * Active observer in trigger deleted
     */
    public function deleted($entity)
    {}
}