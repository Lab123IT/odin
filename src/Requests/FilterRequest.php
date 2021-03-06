<?php
namespace Lab123\Odin\Requests;

use Illuminate\Http\Request;
use Lab123\Odin\Enums\RequestReservedWords;
use DB;
use Illuminate\Support\Facades\Config;

class FilterRequest
{

    public $request;

    public $fields;

    public $criteria;

    public $includes;

    public $limit = 12;

    public $maxLimit = 50;

    public $order;

    public $group;

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
        $this->activeQueryLog()
            ->setFields()
            ->setCriteriaByQueryString()
            ->setCriteria()
            ->setIncludes()
            ->setLimit()
            ->setOrder()
            ->setGroup();
    }

    /**
     * Seta os campos para a consulta
     *
     * @return this
     */
    public function activeQueryLog()
    {
        if ($this->request->get('queries', false) && (env('APP_ENV') === 'local' || env('APP_ENV') === 'staging')) {
            DB::enableQueryLog();
            config(['odin.queryRequest' => true]);
        }
        
        return $this;
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
        
        if (is_string($this->includes)) {
            $this->includes = explode(',', $this->includes);
        }
        
        return $this;
    }

    /**
     * Seta o número de recursos retornados (máximo 50)
     *
     * @return this
     */
    public function setLimit()
    {
        $this->limit = $this->request->get('limit', $this->limit);
        $this->limit = ($this->limit > $this->maxLimit) ? $this->maxLimit : $this->limit;
        
        return $this;
    }

    /**
     * Seta o número máximo de recursos retornados
     *
     * @return this
     */
    public function setMaxLimit($maxLimit = 50)
    {
        $this->maxLimit = $maxLimit;
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
     * Seta a ordenação dos recursos
     *
     * @return this
     */
    public function setGroup()
    {
        $this->group = $this->request->get('group', '');
        return $this;
    }

    /**
     * Seta os filtros não enviados por criteria
     *
     * @return this
     */
    public function setCriteriaByQueryString()
    {
    	if ($this->request->method() != 'GET') {
    		return $this;
    	}
    	
        $data = $this->request->except(RequestReservedWords::all());
        
        $request = $this->request->all();
        foreach ($data as $k => $v) {
            if (is_array($v)) {
                break;
            }
            
            if (strpos($k, ':')) {
                $k = str_replace(':', '.', $k);
            }
            $request['criteria'][] = $k . ',=,' . $v;
        }
        
        $this->request->merge($request);
        
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