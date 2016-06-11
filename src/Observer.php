<?php
namespace Lab123\Odin;

use Lab123\Odin\Entities\Entity;

abstract class Observer
{

    /**
     * Active observer in trigger creating
     */
    protected function creating(Entity $model)
    {}

    /**
     * Active observer in trigger created
     */
    protected function created(Entity $model)
    {}

    /**
     * Active observer in trigger saving
     */
    protected function saving(Entity $model)
    {}

    /**
     * Active observer in trigger saved
     */
    protected function saved(Entity $model)
    {}

    /**
     * Active observer in trigger updating
     */
    protected function updating(Entity $model)
    {}

    /**
     * Active observer in trigger updated
     */
    protected function updated(Entity $model)
    {}

    /**
     * Active observer in trigger deleting
     */
    protected function deleting(Entity $model)
    {}

    /**
     * Active observer in trigger deleted
     */
    protected function deleted(Entity $model)
    {}

    /**
     * Active observer in trigger restoring
     */
    protected function restoring(Entity $model)
    {}

    /**
     * Active observer in trigger restored
     */
    protected function restored(Entity $model)
    {}
}