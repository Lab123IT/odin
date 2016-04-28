<?php
namespace Lab123\Odin\Controllers;

use Lab123\Odin\Traits\ApiResponse;
use Lab123\Odin\Traits\ApiUser;
use Lab123\Odin\Requests\FilterRequest;
use App;

class LumenApiController extends ApiController
{

    protected $repository;

    /**
     * Display one resource by id.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id, FilterRequest $filters)
    {
        $id = $this->getRealId($id);
        
        return parent::show($id, $filters);
    }

    /**
     * Create and display the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(FilterRequest $request)
    {
        return parent::store($request);
    }

    /**
     * Update and display the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(FilterRequest $request, $id)
    {
        $id = $this->getRealId($id);
        
        return parent::update($request, $id);
    }

    /**
     * Delete a resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id = $this->getRealId($id);
        
        return parent::destroy($id);
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