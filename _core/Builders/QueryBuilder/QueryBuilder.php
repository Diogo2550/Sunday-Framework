<?php

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

    public function insertWhereFieldsOnQuery(array $fields, array $values, string $tableName) {
        $this->query[] = "WHERE";
        foreach($fields as $i => $field) {
            $query = "";

            if($i > 0) {
                $query .= ",";
            }

            $query .= "$tableName.$field=$values[$i]";

            $this->query[] = $query;
        }
    }

    public function insertUpdateFieldsOnQuery(array $fields, array $values) {
        $this->query[] = "SET";
        foreach($fields as $i => $field) {
            $query = "";

            if($i > 0) {
                $query .= ",";
            }

            $query .= "$field='$values[$i]'";

            $this->query[] = $query;
        }
    }

}