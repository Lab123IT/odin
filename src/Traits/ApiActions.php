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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(FilterRequest $filters)
    {
        $resources = $this->repository->filter($filters)->paginate();
        
        if ($resources->count() < 1) {
            return $this->notFound();
        }
        
        return $this->success($resources);
    }

    /**
     * Display one resource by id.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id, FilterRequest $filters)
    {
        $id = $this->getRealId($id);
        
        $resource = $this->repository->find($id);
        
        if (! $resource) {
            return $this->notFound();
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
            return $this->notFound();
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
            return $this->notFound();
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
    private function getFieldManager()
    {
        /* Verifica se existe Field Manager com prefixo igual a controller */
        if (! $this->fieldManager) {
            $pathClassExploded = explode('\\', get_class($this));
            $namespace = array_first($pathClassExploded);
            $nameController = array_last($pathClassExploded);
            $resourceName = str_replace('Controller', '', $nameController);
            
            /* Verifica se existe o Field Manager para o recurso */
            if (! class_exists("{$namespace}\\FieldManagers\\{$resourceName}FieldManager")) {
                echo "Crie o Field Manager {$resourceName}FieldManager em {$namespace}\\FieldManagers";
                exit();
            }
            
            $this->fieldManager = $namespace . "\\FieldManagers\\{$resourceName}FieldManager";
        }
        
        /* Ainda precisa instanciar o objeto */
        if (is_string($this->fieldManager)) {
            return new $this->fieldManager();
        }
        
        /* Objeto já instanciado, só retornar */
        return $this->fieldManager;
    }
}