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
        $uris = [];
        $actions = [];
        
        if (count($this->items) < 1) {
            return parent::toArray();
        }
        
        $entity = $this->items[0];
        
        $entity->autoload();
        
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
        
        foreach ($data as $k => $value) {
            if (key_exists('uri', $value)) {
                $uriExploded = explode('/', $value['uri']);
                array_pop($uriExploded);
                $rootUriResource = implode('/', $uriExploded);
            }
            unset($data[$k]);
            $data[$k] = array_last($value);
            //$data['data'][$k] = array_last($value);
        }
        
        $data = [
            'uri' => $rootUriResource
        ] + $data;
        
        return $data;
    }
}
