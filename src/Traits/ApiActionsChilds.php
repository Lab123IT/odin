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
        $resource = $this->repository->getChilds($id, $relation);
        
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
    public function childShow(FilterRequest $filters, $id, $child_id, $relation)
    {
        $resource = $this->repository->getChild($id, $relation, $child_id);
        
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
    public function childStore($idFather, FilterRequest $filters, $relation)
    {
        $resource = $this->repository->addChilds($idFather, $relation, $filters->all());
        
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
    public function childAssociate($request, $idFather, $idChild, $relation)
    {
        if (! $this->repository->attach($idFather, $idChild, $relation, $request->all())) {
            return $this->notFound();
        }
        
        return $this->success([]);
    }

    /**
     * Dissociate a new child
     *
     * @return \Illuminate\Http\Response
     */
    public function childDissociate($request, $idFather, $idChild, $relation)
    {
        if (! $this->repository->detach($idFather, $idChild, $relation)) {
            return $this->notFound();
        }
        
        return $this->success([]);
    }

    /**
     * Update a child
     *
     * @return \Illuminate\Http\Response
     */
    public function updateChild($idFather, FilterRequest $filters, $child_id, $relation)
    {
        $resource = $this->repository->updateChild($idFather, $relation, $child_id, $filters->all());
        
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
    public function deleteChild($idFather, FilterRequest $filters, $child_id, $relation)
    {
        $resource = $this->repository->deleteChild($idFather, $relation, $child_id);
        
        if ($resource == null) {
            return $this->notFound();
        }
        
        return $this->success();
    }
}