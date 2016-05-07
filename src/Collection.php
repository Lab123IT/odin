<?php
namespace Lab123\Odin;

use Illuminate\Database\Eloquent\Collection as IlluminateCollection;

class Collection extends IlluminateCollection
{

    /**
     * Get the collection of items as a plain array.
     *
     * @return array
     */
    public function toArray()
    {
        $arr = [];
        $data = parent::toArray();
        
        if (count($this->items) < 1) {
            return $data;
        }
        
        $entity = $this->items[0];
        
        foreach ($data as $k => $resource) {
            if (key_exists('pivot', $resource)) {
                unset($data[$k]['pivot']);
            }
        }
        
        return $data;
        
        foreach ($data as $k => $resource) {
            if (! is_array($resource)) {
                continue;
            }
            
            foreach ($resource as $key => $value) {
                if (! is_array($value)) {
                    continue;
                }
                
                $newData = $value;
                unset($data[$k][$key]);
                
                /* Carrega a URI do recurso filho */
                $data[$k][$key]['uri'] = $entity->getResourceChildURL($key);
                $data[$k][$key]['data'] = $newData;
            }
        }
        
        return $data;
    }
}
