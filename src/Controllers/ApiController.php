<?php
namespace Lab123\Odin\Controllers;

use Lab123\Odin\Traits\ApiResponse;
use Lab123\Odin\Traits\ApiUser;
use Lab123\Odin\Requests\FilterRequest;
//use Laravel\Lumen\Routing\Controller;
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
        
        return $this->success($data);
    }

    /**
     * Display one resource by id.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id, FilterRequest $filters)
    {
        $id = $this->getRealId($id);
        
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
        $input = $request->all();
        $data = $this->repository->create($input);
        
        return $this->created($data);
    }

    /**
     * Update and display the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(FilterRequest $request, $id)
    {
        $id = $this->getRealId($id);
        
        $resource = $this->repository->find($id);
        
        if (! $resource) {
            return $this->notFound();
        }
        
        $result = $resource->update($input);
        
        return $this->success($result);
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
     * Return decoded Id or actual Id.
     *
     * @return $id
     */
    private function getRealId($id)
    {
        if (config('odin.hashid.active')) {
            $hashids = App::make('Hashids');
            
            $id_decoded = $hashids->decode($id);
            
            if (count($id_decoded) < 1) {
                return ApiResponse::notFound();
            }
            
            $id = $id_decoded[0];
        }
        
        return $id;
    }
}