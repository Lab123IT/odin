<?php
namespace Lab123\Odin\Controllers;

use Lab123\Odin\Traits\ApiResponse;
use Lab123\Odin\Traits\ApiUser;
use Lab123\Odin\Requests\FilterRequest;
use App;
use DB;

class ApiController extends Controller
{
    use ApiResponse, ApiUser;

    /**
     * Instance of Repository.
     *
     * @var $repository Repository
     */
    protected $repository;

    /**
     * Array autoload Entities.
     *
     * @var $loads array
     */
    protected $loads = [];

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
        
        $resources = $this->autoloadRelationships($resources);
        
        return $this->success($resources);
    }

    /**
     * Display one resource by id.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id, FilterRequest $filters)
    {
        $resource = $this->repository->find($id);
        
        if (! $resource) {
            return $this->notFound();
        }
        
        $resources = $this->autoloadRelationships($resource);
        
        return $this->success($resource);
    }

    /**
     * Create and display the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(FilterRequest $request)
    {
        $this->validateStore($request);
        
        $input = $request->all();
        $resource = $this->repository->create($input);
        
        if (! $resource) {
            return $this->notFound();
        }
        
        $resources = $this->autoloadRelationships($resource);
        
        return $this->created($resource);
    }

    /**
     * Validate store action
     *
     * @return void
     */
    protected function validateStore(FilterRequest $request)
    {
        $this->validate($request->request, $this->repository->getRules([]));
    }

    /**
     * Update and display the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(FilterRequest $request, $id)
    {
        $this->validateUpdate($request);
        
        $resource = $this->repository->find($id);
        
        if (! $resource) {
            return $this->notFound();
        }
        
        $resource = $resource->update($request->all());
        
        $resources = $this->autoloadRelationships($resource);
        
        return $this->success($resource);
    }

    /**
     * Validate store action
     *
     * @return void
     */
    protected function validateUpdate(FilterRequest $request)
    {
        $this->validate($request->request, $this->repository->getRules([]));
    }

    /**
     * Delete a resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $resource = $this->repository->find($id);
        
        if (! $resource) {
            return $this->notFound();
        }
        
        $result = $resource->delete();
        
        return $this->success($result);
    }

    private function autoloadRelationships($resources)
    {
        foreach ($this->loads as $load) {
            $resources->load($load);
        }
        
        return $resources;
    }
}