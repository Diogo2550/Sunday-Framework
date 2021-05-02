<?php

namespace Core;

use ReflectionClass;
use ReflectionProperty;

class BaseModel {

    protected $primaryKey = null;
    protected $foreignKey = null;

    public function patchValues($array) {
        $modelName = $this->getModelName();

        $class = new ReflectionClass($this);
        $props = $class->getProperties(ReflectionProperty::IS_PUBLIC);
        foreach($props as $property) {
            $propName = $property->getName();

            $propertyOnTable = $modelName . '_' . $propName;

            if(array_key_exists($propertyOnTable, $array)) {
                if($property->getType()->getName() == 'DateTime') {
                    $this->$propName = new \DateTime($array[$propertyOnTable]);
                } else {
                    $this->$propName = $array[$propertyOnTable];
                }
                continue;
            }

            if(array_key_exists($propName, $array))
                $this->$propName = $array[$propName];
        }
    }

    public function getModelName() {
        $className = get_class($this);
        $className = explode('\\', $className);
        $className = end($className);
        
        $modelName = substr($className, 0, strlen($className) - strlen('Model'));

        return $modelName;
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