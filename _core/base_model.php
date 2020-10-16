<?php

class BaseModel {

    public function patch_values($array) {
        $class_name = get_class($this);

        $propertys = get_class_vars($class_name);
        foreach(array_keys($propertys) as $property) {
            $this->$property = array_key_exists($property, $array) ? $array[$property] : null;
        }
    }

    public function to_lower_propertys() {
        $class_name = get_class($this);
        
        $propertys = get_class_vars($class_name);
        $values = get_object_vars($this);
        foreach(array_keys($propertys) as $property) {
            if(gettype($property) == 'string') {
                $this->$property = strtolower($values[$property]);
            }
        }
    }


}

?>