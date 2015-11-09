<?php
namespace Lab123\Odin\Transformers;

use Lab123\Odin\Transformers\Transformer;

class ExceptionTransformer extends Transformer
{

    /**
     * Transform object into a generic array
     *
     * @var Exception
     */
    public function transform(\Exception $ex)
    {
        return [
            'code' => $ex->getCode()
        ];
    }
}
