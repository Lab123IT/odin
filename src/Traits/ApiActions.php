<?php
namespace Lab123\Odin\Traits;

use Lab123\Odin\Requests\FilterRequest;
use Lab123\Odin\Libs\Api;
use App;
use DB;

trait ApiActions
{

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
        $this->fieldManager = new $this->fieldManager();
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
        $this->fieldManager = new $this->fieldManager();
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
        
        if (! $resource) {
            return $this->notFound();
        }
        
        $result = $resource->delete();
        
        return $this->success($result);
    }
}