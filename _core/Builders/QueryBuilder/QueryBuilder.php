<?php

namespace Core\Builders\QueryBuilder;

class QueryBuilder {

    private array $query;

    public function __construct()
    {
        $this->query = array();
    }

    public function getQuery() {
        return implode(' ', $this->query);
    }

    public function insertOnQuery(array $array) {
        array_push($this->query, ...$array);
    }

    public function insertSelectFieldsOnQuery(array $fields, string $tableName) {
        foreach($fields as $i => $field) {
            $query = "";

            if($i > 0) {
                $query .= ",";
            }
            
            $query .= "$field AS $tableName" . "_" . "$field";
            
            $this->query[] = $query;
        }
    }

    public function insertInsertFieldsOnQuery(array $modelArray) {
        $fields = array();
        $values = array();

        foreach(array_keys($modelArray) as $key) {
            if(isset($modelArray[$key])) {
                $fields[] = $key;

                if(gettype($modelArray[$key]) === 'object') {
                    $reflection = new \ReflectionClass($modelArray[$key]);
                    if($reflection->name == 'DateTime') {
                        $values[] = $modelArray[$key]->format('Y-m-d H:i:s');
                    }
                } else {
                    $values[] = $modelArray[$key];
                }
            }
        }

        $this->query[] = "(`" . implode("`,`", $fields) . "`)";
        $this->query[] = "VALUES";
        $this->query[] = "('" . implode("','", $values) . "')";
    }

    public function insertUpdateFieldsOnQuery(array $modelArray) {
        $this->query[] = "SET";
        foreach(array_keys($modelArray) as $i => $key) {
            if(!isset($modelArray[$key]) || $modelArray[$key] === null) {
                continue;
            }
            $query = "";

            if($i > 0) {
                $query .= ",";
            }

            if(gettype($modelArray[$key]) === 'object') {
                $reflection = new \ReflectionClass($modelArray[$key]);
                if($reflection->name == 'DateTime') {
                    $query .= "$key='" . $modelArray[$key]->format('Y-m-d H:i:s') ."'";
                }
            } else {
                $query .= "$key='$modelArray[$key]'";
            }

            $this->query[] = $query;
        }
    }
    
    public function insertWhereFieldsOnQuery(array $fields, array $values, string $tableName) {
        $this->query[] = "WHERE";
        foreach($fields as $i => $field) {
            $query = "";

            if($i > 0) {
                $query .= "AND ";
            }

            $query .= "$tableName.$field='$values[$i]'";

            $this->query[] = $query;
        }
    }

}