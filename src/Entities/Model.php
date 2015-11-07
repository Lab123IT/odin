<?php
namespace Lab123\Odin\Entities;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class Model extends EloquentModel
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

    public function getCreatedAtAttribute($date)
    {
        return \DateTime::createFromFormat($this->getDateFormat(), $date)->format('d/m/Y H:i');
    }

    public function getUpdatedAtAttribute($date)
    {
        return \DateTime::createFromFormat($this->getDateFormat(), $date)->format('d/m/Y H:i');
    }
}