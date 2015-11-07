<?php
namespace Lab123\Odin\Transformers;

class ExceptionTransformer extends BaseTransformer
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
