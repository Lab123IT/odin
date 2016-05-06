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
        
        if ($entity->children) {
            
            foreach ($entity->children as $child) {
                if (key_exists($child, $data[0])) {
                    
                    $newData = $data[0][$child];
                    unset($data[0][$child]);
                    
                    /* Carrega a URI do recurso filho */
                    $data[0][$child]['uri'] = $entity->getResourceChildURL($child);
                    $data[0][$child]['data'] = $newData;                 
                }
            }
        }
        
        return $data;
    }
}
