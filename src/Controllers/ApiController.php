<?php
namespace Lab123\Odin\Controllers;

use Lab123\Odin\Requests\FilterRequest;
use Lab123\Odin\Traits\ApiResponse;
use Lab123\Odin\Traits\ApiUser;
use Lab123\Odin\Libs\Api;
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
     * Array autoload Entities Resources.
     *
     * @var $loads array
     */
    protected $smallLoads = [];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(FilterRequest $filters, $father_id = '')
    {
        if ($father_id) {
            $father_id = $this->getRealId($father_id);
            $filters->criteria[] = "father_id,=,{$father_id}";
        }
        
        $this->queryLog();
        
        $resources = $this->repository->filter($filters)->paginate();
        
        if ($resources->count() < 1) {
            return $this->notFound();
        }
        
        $resources = $this->autoloadRelationships($resources);
        $resources = $this->autoloadSmallRelationships($resources);
        
        return $this->success($resources);
    }

    /**
     * Display one resource by id.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id, FilterRequest $filters, $father_id = '')
    {
        if ($father_id) {
            $father_id = $this->getRealId($father_id);
            $filters->criteria[] = "father_id,=,{$father_id}";
        }
        
        $id = $this->getRealId($id);
        
        $this->queryLog();
        
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
    public function store(FilterRequest $request, $father_id = '')
    {
        $this->validateStore($request);
        
        $this->queryLog();
        
        $input = $request->all();
        if ($father_id) {
            $father_id = $this->getRealId($father_id);
            $input['father_id'] = $father_id;
        }
        
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
        
        $this->queryLog();
        
        $id = $this->getRealId($id);
        
        $resource = $this->repository->update($request->all(), $id);
        
        $resource = $this->autoloadRelationships($resource);
        
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
        $this->queryLog();
        
        $id = $this->getRealId($id);
        
        $resource = $this->repository->find($id);
        
        if (! $resource) {
            return $this->notFound();
        }
        
        $result = $resource->delete();
        
        return $this->success($result);
    }

    /**
     * Auto load Relations.
     *
     * @return $resources
     */
    private function autoloadRelationships($resources)
    {
        foreach ($this->loads as $load) {
            $resources->load($load);
        }
        
        return $resources;
    }

    /**
     * Auto load Relations.
     *
     * @return $resources
     */
    private function autoloadSmallRelationships($resources)
    {
        foreach ($this->smallLoads as $relationship) {
            
            $key = $resources[0]->$relationship()->getForeignKey();
            
            $fields = [
                'id',
                $key
            ];
            
            $resources->load([
                $relationship => function ($q) use($fields) {
                    $q->select($fields);
                }
            ]);
        }
        
        return $resources;
    }

    /**
     * Active Query log When active config Odin
     *
     * @return void
     */
    private function queryLog()
    {
        if (! config('odin.queryRequest')) {
            return;
        }
        
        DB::enableQueryLog();
    }

    /**
     * Return decoded Id or actual Id.
     *
     * @return $id
     */
    private function getRealId($id)
    {
        return Api::decodeHashId($id);
    }
    
    private function getFatherField() {
        
        dd($this->father);
        
        return '';
    }
}