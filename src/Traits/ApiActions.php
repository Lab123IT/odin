<?php
namespace Lab123\Odin\Traits;

use Lab123\Odin\Requests\FilterRequest;
use Lab123\Odin\Libs\Api;
use App;
use DB;

trait ApiActions
{

    /**
     * Class Fields Manage.
     *
     * @var $fieldManager
     */
    protected $fieldManager = '';

    /**
     * Define resource is List to return full data
     *
     * @var boolean $list
     */
    protected $list = false;

    /**
     * Display a listing of the resource.
     * paginate
     *
     * @return \Illuminate\Http\Response
     */
    public function index(FilterRequest $request)
    {
        $limit = $request->request->get('limit', 15);
        $limit = ($limit > 49) ? 50 : $limit;
        
        $filter = $this->repository->filter($request);
        
        if ($this->list || $request->request->get('search_type') == 'list') {
            $resources = $filter->get(1000);
        } else {
            $resources = $filter->paginate($limit);
        }
        
        if ($resources->count() < 1) {
            // return $this->notFound();
        }
        
        return $this->success($resources);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function lists(FilterRequest $request)
    {
        $resources = $this->repository->filter($request)->get();
        
        if ($resources->count() < 1) {
            // return $this->notFound();
        }
        
        return $this->success($resources);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function autocomplete(FilterRequest $request)
    {
        $this->fieldManager = $this->getFieldManager();
        $this->validate($request->request, $this->fieldManager->autocomplete());
        
        $text = $request->request->get('text');
        
        $resources = $this->repository->filter($request)->autocomplete($text)->get();
        
        if ($resources->count() < 1) {
            // return $this->notFound();
        }
        
        return $this->success($resources);
    }

    /**
     * Display one resource by id.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id, FilterRequest $request)
    {
        $id = $this->getRealId($id);
        
        $request->criteria[] = 'id,=,' . $id;
        
        $resource = $this->repository->filter($request)->first();
        
        //$resource = $this->repository->find($id);
        
        if (! $resource) {
            // return $this->notFound();
        }
        
        return $this->success($resource);
    }

    /**
     * Create and display the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(FilterRequest $request)
    {
        $this->fieldManager = $this->getFieldManager();
        $this->validate($request->request, $this->fieldManager->store());
        
        $input = $request->all();
        
        $resource = $this->repository->create($input);
        
        if (! $resource) {
            // return $this->notFound();
        }
        
        return $this->created($resource);
    }

    /**
     * Update and display the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(FilterRequest $request, $id)
    {
        $this->fieldManager = $this->getFieldManager();
        $this->validate($request->request, $this->fieldManager->update());
        
        $id = $this->getRealId($id);
        
        $resource = $this->repository->update($request->all(), $id);
        
        if (! $resource) {
            // return $this->notFound();
        }
        
        return $this->success($resource);
    }

    /**
     * Delete a resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id = $this->getRealId($id);
        
        $resource = $this->repository->find($id);
        
        if (! $resource) {
            return $this->notFound();
        }
        
        $result = $resource->delete();
        
        return $this->success($result);
    }

    /**
     * Return instance Field Manager of controller
     *
     * @return object Lab123\Odin\FieldManager
     */
    protected function getFieldManager()
    {
        /* Verifica se existe Field Manager com prefixo igual a controller */
        if (! $this->fieldManager) {
        	$this->fieldManager = $this->repository->getFieldManager();
        }
        
        /* Ainda precisa instanciar o objeto */
        if (is_string($this->fieldManager)) {
            return new $this->fieldManager();
        }
        
        /* Objeto já instanciado, só retornar */
        return $this->fieldManager;
    }
}
