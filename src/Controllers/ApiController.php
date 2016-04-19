<?php
namespace Lab123\Odin\Controllers;

use Lab123\Odin\Traits\ApiResponse;
use Lab123\Odin\Traits\ApiUser;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    use ApiResponse, ApiUser;

    protected $repository;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = $this->repository->filter($request->all())
            ->get()
            ->toArray();
        
        return $this->success($data);
    }

    /**
     * Display one resource by id.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $filters = $request->all();
        $filters['criteria'][] = 'id,=,' . $id;
        
        $data = $this->repository->filter($filters)
            ->first()
            ->toArray();
        
        return $this->success($data);
    }

    /**
     * Create and display the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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
    public function update(Request $request, $id)
    {
        $input = $request->all();
        
        $data = $this->repository->find($id)->update($input);
        
        return $this->success($data);
    }

    /**
     * Delete a resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = $this->repository->where('id', $id)->delete();
        
        return $this->success($data);
    }
}