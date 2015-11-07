<?php
namespace Lab123\Odin\Http\Controllers;

use Lab123\Odin\Traits\ApiResponse;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ApiController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, ApiResponse;

    protected $repository;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            
            $data = $this->repository->filter($request->all())
                ->get()
                ->toArray();
            
            return $this->success($data);
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }
}
