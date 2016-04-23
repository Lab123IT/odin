<?php
namespace Lab123\Odin\Contracts;

interface IRepository
{

    /**
     * Return a resource by id
     *
     * @param $id int            
     * @return Illuminate\Database\Eloquent\Model
     */
    public function find($id);

    /**
     * Return collection of resources
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function findAll();

    /**
     * Return a new resource
     *
     * @param $data array            
     * @return Illuminate\Database\Eloquent\Model
     */
    public function create(array $data);

    /**
     * Retrieve the resource by the attributes, or create it if it doesn't exist
     *
     * @param $data array            
     * @return Illuminate\Database\Eloquent\Model
     */
    public function firstOrCreate(array $data);

    /**
     * Update a resource by id
     *
     * @param $data array            
     * @param $id int            
     * @return boolean
     */
    public function update(array $data, $id);

    /**
     * Delete a resource by id
     *
     * @param $id int            
     * @return boolean
     */
    public function delete($id);

    /**
     * Return collection of resources
     *
     * @param $criteria array            
     * @param $orderBy array            
     * @param $limit int            
     * @param $offset int            
     * @param $include array            
     * @param $fields string            
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null);

    /**
     * Return a resource by criteria
     *
     * @param $criteria array            
     * @return Illuminate\Database\Eloquent\Model
     */
    public function findOneBy(array $criteria);

    /**
     * Filter the Entity
     *
     * @param Lab123\Odin\Requests\FilterRequest $filters            
     * @return Lab123\Odin\Libs\Search
     */
    public function filter(\Lab123\Odin\Requests\FilterRequest $filters);
}
