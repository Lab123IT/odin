<?php
namespace Lab123\Odin\Controllers;

use Lab123\Odin\Traits\ApiResponse;
use Lab123\Odin\Traits\ApiUser;
use Lab123\Odin\Requests\FilterRequest;
use App;

class ApiController extends Controller
{
    use ApiResponse, ApiUser;

    protected $repository;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(FilterRequest $filters)
    {
        $data = $this->repository->filter($filters)->paginate();
        
        if ($data->count() < 1) {
            return $this->notFound();
        }
        
        return $this->success($data);
    }

    /**
     * Display one resource by id.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id, FilterRequest $filters)
    {
        $data = $this->repository->find($id);
        
        if (! $data) {
            return $this->notFound();
        }
        
        return $this->success($data);
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
        $data = $this->repository->create($input);
        
        return $this->created($data);
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
        
        $resource->update($request->all());
        
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
}