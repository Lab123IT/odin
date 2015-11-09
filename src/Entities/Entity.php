<?php
namespace Lab123\Odin\Entities;

use Illuminate\Database\Eloquent\Model;

class Entity extends Model
{

    /**
     * Return the default date format from database
     *
     * @return string
     */
    protected function getDateFormat()
    {
        return (config('database.default') == 'sqlsrv') ? 'M d Y H:iA' : 'Y-m-d H:i:s';
    }

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