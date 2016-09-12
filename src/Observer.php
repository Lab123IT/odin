<?php
namespace Lab123\Odin;

use Lab123\Odin\Entities\Entity;

abstract class Observer
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
    protected function creating($entity)
    {}

    /**
     * Active observer in trigger created
     */
    protected function created($entity)
    {}

    /**
     * Active observer in trigger saving
     */
    protected function saving($entity)
    {}

    /**
     * Active observer in trigger saved
     */
    protected function saved($entity)
    {}

    /**
     * Active observer in trigger updating
     */
    protected function updating($entity)
    {}

    /**
     * Active observer in trigger updated
     */
    protected function updated($entity)
    {}

    /**
     * Active observer in trigger deleting
     */
    protected function deleting($entity)
    {}

    /**
     * Active observer in trigger deleted
     */
    protected function deleted($entity)
    {}
}