<?php
namespace Lab123\Odin\Entities;

use Illuminate\Database\Eloquent\Model;

class Entity extends Model
{

    /**
     * Return the default date format to created at attribute
     *
     * @return string
     */
    public function getCreatedAtAttribute($date)
    {
        return \DateTime::createFromFormat($this->getDateFormat(), $date)->format('d/m/Y H:i');
    }

    /**
     * Return the default date format to updated at attribute
     *
     * @return string
     */
    public function getUpdatedAtAttribute($date)
    {
        return \DateTime::createFromFormat($this->getDateFormat(), $date)->format('d/m/Y H:i');
    }
}