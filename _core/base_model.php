<?php

class BaseModel {

    protected $primaryKey;
    protected $foreignKey;

    public function patchValues($array) {
        $modelName = $this->getModelName();

        $class = new ReflectionClass($this);
        $props = $class->getProperties(ReflectionProperty::IS_PUBLIC);
        foreach($props as $property) {
            $propName = $property->getName();
            $propertyOnTable = $modelName . '_' . $propName;

            if(array_key_exists($propertyOnTable, $array)) {
                $this->$propName = $array[$propertyOnTable];
                continue;
            }

            $this->$propName = array_key_exists($propName, $array) ? $array[$propName] : null;
        }
    }

    public function toLowerProperties() {
        $className = get_class($this);
        
        $propertys = get_class_vars($className);
        $values = get_object_vars($this);
        foreach(array_keys($propertys) as $property) {
            if(gettype($property) == 'string') {
                $this->$property = strtolower($values[$property]);
            }
        }
    }

    public function getModelName() {
        $className = get_class($this);
        $modelName = substr($className, 0, strlen($className) - strlen('Model'));

        return strtolower($modelName);
    }

    public function getProperties() {
        $class = new ReflectionClass($this);
        $properties = $class->getProperties(ReflectionProperty::IS_PUBLIC);
        $ownProperties = array();

        foreach ($properties as $property) {
            $ownProperties[] = $property->getName();
        }

        return $ownProperties;
    }

    public function getPrimaryKey() {
        return $this->primaryKey;
    }

    function convertToArrayAssoc() {
        $reflectionClass = new ReflectionClass(get_class($this));
        $array = array();
        foreach ($reflectionClass->getProperties(ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PRIVATE) as $property) {
            $property->setAccessible(true);
            $array[$property->getName()] = $property->getValue($this);
            $property->setAccessible(false);
        }
        return $array;
    }

}

?>