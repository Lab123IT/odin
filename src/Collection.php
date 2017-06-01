<?php
namespace Lab123\Odin;

use Illuminate\Database\Eloquent\Collection as IlluminateCollection;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Collection extends IlluminateCollection
{

    static $loaded = false;

    /**
     * Get the collection of items as a plain array.
     *
     * @return array
     */
    public function toArray()
    {
        $urls = [];
        $actions = [];
        
        if (count($this->items) < 1) {
            return parent::toArray();
        }
        
        $entity = $this->items[0];
        
        if (is_array($entity->load)) {
            foreach ($entity->load as $k => $load) {
                $this->load($load);
            }
        }
        
        $data = parent::toArray();
        $actions = $entity->getActions();
        
        if (count($actions) > 0) {
            foreach ($data as $k => $resource) {
                
                if (count($resource) < 2) {
                    continue;
                }
                
                $data[$k] += [
                    'actions' => $actions
                ];
            }
        }
        
        $data = $this->normalizeArray($data);
        
        return $data;
    }

    private function normalizeArray($data)
    {
        $onlyOneProperty = false;
        
        foreach ($data as $resource) {
            if (count($resource) < 2) {
                $onlyOneProperty = true;
            }
        }
        
        if (! $onlyOneProperty) {
            return $data;
        }
        
        $newData = [];
        $rootUriResource = '';
        foreach ($data as $k => $value) {
            if (key_exists('uri', $value)) {
                $urlExploded = explode('/', $value['uri']);
                array_pop($urlExploded);
                $rootUriResource = implode('/', $urlExploded);
            }
            unset($data[$k]);
            $data[$k] = array_last($value);
            //$data['data'][$k] = array_last($value);
        }
        
        $data = [
            'url' => $rootUriResource
        ] + $data;
        
        return $data;
    }
}
