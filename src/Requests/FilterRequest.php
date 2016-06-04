<?php
namespace Lab123\Odin\Requests;

use Illuminate\Http\Request;

class FilterRequest
{

    public $request;

    public $fields;

    public $criteria;

    public $includes;

    public $limit = 10000;

    public $order;

    public function __construct(Request $request)
    {
        $this->request = $request;
        
        $this->setFilters();
    }

    /**
     * Configura o objeto conforme os parametros enviados do client
     *
     * @return void
     */
    public function setFilters()
    {
        $this->setFields()
            ->setCriteria()
            ->setIncludes()
            ->setLimit()
            ->setOrder();
    }

    /**
     * Seta os campos para a consulta
     *
     * @return this
     */
    public function setFields()
    {
        $fields = $this->request->get('fields', '*');
        $this->fields = array_filter(explode(',', $fields));
        return $this;
    }

    /**
     * Seta os campos do where para a consulta
     *
     * @return this
     */
    public function setCriteria()
    {
        $this->criteria = $this->request->get('criteria', []);
        return $this;
    }

    /**
     * Seta os recusos que serão incluídos na consulta
     *
     * @return this
     */
    public function setIncludes()
    {
        $this->includes = $this->request->get('includes', []);
        return $this;
    }

    /**
     * Seta o número de recursos retornados
     *
     * @return this
     */
    public function setLimit()
    {
        $this->limit = $this->request->get('limit', $this->limit);
        
        return $this;
    }

    /**
     * Seta a ordenação dos recursos
     *
     * @return this
     */
    public function setOrder()
    {
        $this->order = $this->request->get('order', []);
        return $this;
    }

    /**
     * Quando a função não existir nesse objeto
     * Retorna a função do objeto Request
     *
     * @return this
     */
    public function __call($func, $args)
    {
        if (method_exists($this->request, $func)) {
            return $this->request->$func($args);
        }
    }
}