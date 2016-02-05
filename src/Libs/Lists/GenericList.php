<?php
namespace Lab123\Odin\Libs\Lists;

abstract class GenericList
{

    protected $list = [];

    /**
     * Retorna a lista
     *
     * @return array lista
     */
    public function get()
    {
        return $this->list;
    }
}
