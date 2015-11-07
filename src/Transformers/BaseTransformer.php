<?php
namespace Lab123\Odin\Transformers;

class BaseTransformer
{

    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [];

    /**
     * Transform object into a generic array
     *
     * @var Model
     */
    public function transform($model)
    {}
}