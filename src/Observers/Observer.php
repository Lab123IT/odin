<?php

namespace Lab123\Odin\Observers;

use Lab123\Odin\Entities\Entity;
use Lab123\Odin\Contracts\IObserver;

abstract class Observer implements IObserver
{
    /**
     * Active observer in trigger creating
     * 
     * @param Entity $entity
     */
    public function creating($entity) {}

    /**
     * Active observer in trigger created
     * 
     * @param Entity $entity
     */
    public function created($entity) {}

    /**
     * Active observer in trigger saving
     * 
     * @param Entity $entity
     */
    public function saving($entity) {}

    /**
     * Active observer in trigger saved
     * 
     * @param Entity $entity
     */
    public function saved($entity) {}

    /**
     * Active observer in trigger updating
     * 
     * @param Entity $entity
     */
    public function updating($entity) {}

    /**
     * Active observer in trigger updated
     * 
     * @param Entity $entity
     */
    public function updated($entity) {}

    /**
     * Active observer in trigger deleting
     * 
     * @param Entity $entity
     */
    public function deleting($entity) {}

    /**
     * Active observer in trigger deleted
     * 
     * @param Entity $entity
     */
    public function deleted($entity) {}
}