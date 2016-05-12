<?php
namespace Lab123\Odin\Traits;

use Lab123\Odin\Requests\FilterRequest;
use Lab123\Odin\Libs\Api;
use App;
use DB;

trait ApiActionsChilds
{

    /**
     * Display a listing of the childs resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function childIndex(FilterRequest $filters, $id, $relation)
    {
        $id = $this->getRealId($id);
        
        $resource = $this->repository->getChilds($id, $relation, $filters);
        
        if (! $resource || count($resource) < 1) {
            return $this->notFound();
        }
        
        return $this->success($resource);
    }

    /**
     * Display a listing of the child resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function childShow(FilterRequest $filters, $id, $idChild, $relation)
    {
        $id = $this->getRealId($id);
        $idChild = $this->getRealId($idChild);
        
        $resource = $this->repository->getChild($id, $relation, $idChild);
        
        if (! $resource) {
            return $this->notFound();
        }
        
        return $this->success($resource);
    }

    /**
     * Create a new child
     *
     * @return \Illuminate\Http\Response
     */
    public function childStore($idParent, FilterRequest $filters, $relation)
    {
        $idParent = $this->getRealId($idParent);
        
        $resource = $this->repository->storeChild($idParent, $relation, $filters->all());
        
        if (! $resource) {
            return $this->notFound();
        }
        
        return $this->success($resource);
    }

    /**
     * Create and associate a new child
     *
     * @return \Illuminate\Http\Response
     */
    public function childStoreWithPivot($idParent, $request, $relation)
    {
        $idParent = $this->getRealId($idParent);
        
        $resource = $this->repository->storeChildAndPivot($idParent, $relation, $request->all());
        
        if (! $resource) {
            return $this->notFound();
        }
        
        return $this->success($resource);
    }

    /**
     * Associate a new child
     *
     * @return \Illuminate\Http\Response
     */
    public function childAssociate($request, $idParent, $idChild, $relation)
    {
        $idParent = $this->getRealId($idParent);
        $idChild = $this->getRealId($idChild);
        
        if (! $this->repository->attach($idParent, $idChild, $relation, $request->all())) {
            return $this->notFound();
        }
        
        return $this->success([]);
    }

    /**
     * Dissociate a new child
     *
     * @return \Illuminate\Http\Response
     */
    public function childDissociate($request, $idParent, $idChild, $relation)
    {
        $idParent = $this->getRealId($idParent);
        $idChild = $this->getRealId($idChild);
        
        if (! $this->repository->detach($idParent, $idChild, $relation)) {
            return $this->notFound();
        }
        
        return $this->success([]);
    }

    /**
     * Update a child
     *
     * @return \Illuminate\Http\Response
     */
    public function updateChild($idParent, FilterRequest $filters, $idChild, $relation)
    {
        $idParent = $this->getRealId($idParent);
        $idChild = $this->getRealId($idChild);
        
        $resource = $this->repository->updateChild($idParent, $relation, $idChild, $filters->all());
        
        if (! $resource) {
            return $this->notFound();
        }
        
        return $this->success($resource);
    }

    /**
     * Delete a child
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteChild($idParent, FilterRequest $filters, $idChild, $relation)
    {
        $idParent = $this->getRealId($idParent);
        $idChild = $this->getRealId($idChild);
        
        $resource = $this->repository->deleteChild($idParent, $relation, $idChild);
        
        if ($resource == null) {
            return $this->notFound();
        }
        
        return $this->success();
    }
}