<?php

require_once './interfaces/query_builder.php';

class MySQLQueryBuilder implements IQueryBuilder {

    private $where;
    private $orderBy;
    private $groupBy;
    private $table;
    private $limit;
    private $join;

    private $select;
    private $insert;
    private $update;
    private $delete;

    function setTable(string $tableName):void {
        $this->table = $tableName;
    }

    function select(BaseModel $model):IQueryBuilder {
        $this->table = $model->getModelName() . "s";
        $this->select['fields'] = array();

        foreach($model->getProperties() as $property) {
            $fieldName = $model->getModelName() . '_' . $property;
            array_push($this->select['fields'], $fieldName);
        }

        return $this;
    }

    function insert(BaseModel $model):IQueryBuilder {
        $this->table = $model->getModelName() . "s";

        $this->insert['fields'] = array();
        $this->insert['values'] = array();

        foreach($model->getProperties() as $property) {
            if($model->$property !== null) {
                $fieldName = $model->getModelName() . '_' . $property;
                $value = $model->$property === false ? 0 : $model->$property;
                
                array_push($this->insert['fields'], $fieldName);
                array_push($this->insert['values'], $value);
            }
        }

        return $this;
    }

    function update(BaseModel $model):IQueryBuilder {
        $this->table = $model->getModelName() . "s";

        $this->update['fields'] = array();
        $this->update['values'] = array();

        $this->where['fields'] = array();
        $this->where['values'] = array();

        $primaryKey = $model->getPrimaryKey();
        $fieldName = $model->getModelName() . '_' . $primaryKey;

        array_push($this->where['fields'], $fieldName);
        array_push($this->where['values'], $model->$primaryKey);

        foreach($model->getProperties() as $property) {
            if($model->$property != null) {
                $fieldName = $model->getModelName() . '_' . $property;
                $value = $model->$property === false ? 0 : $model->$property;
                
                array_push($this->update['fields'], $fieldName);
                array_push($this->update['values'], $value);
            }
        }

        return $this;
    }

    function delete(BaseModel $model):IQueryBuilder {
        $this->table = $model->getModelName() . "s";

        $this->where['fields'] = array();
        $this->where['values'] = array();

        $primaryKey = $model->getPrimaryKey();
        $fieldName = $model->getModelName() . '_' . $primaryKey;

        array_push($this->where['fields'], $fieldName);
        array_push($this->where['values'], $model->$primaryKey);
        
        return $this;
    }

    function where(BaseModel $model, string $fieldName):IQueryBuilder {
        $this->where['fields'] = array();
        $this->where['values'] = array();

        if($model->$fieldName !== null) {
            $field = $model->getModelName() . '_' . $fieldName;
            $value = $model->$fieldName === false ? 0 : $model->$fieldName;
            
            array_push($this->where['fields'], $field);
            array_push($this->where['values'], $value);
        } else {
            throw new Exception("Campo $fieldName com valor nulo detectado");
        }
        
        return $this;
    }

    function orderBy(array $fields, array $values):IQueryBuilder {
        $this->orderBy['fields'] = $fields;
        $this->orderBy['values'] = $values;

        return $this;
    }

    function groupBy(array $fields, array $values):IQueryBuilder {
        $this->groupBy['fields'] = $fields;
        $this->groupBy['values'] = $values;

        return $this;
    }

    function limit(int $limit):IQueryBuilder {
        $this->limit = $limit;

        return $this;
    }

    function min():IQueryBuilder {
        
        return $this;
    }

    function max():IQueryBuilder {

        return $this;
    }

    function join(array $tables, array $fields, array $values):IQueryBuilder {
        $this->join['tables'] = $tables;
        $this->join['fields'] = $fields;
        $this->join['values'] = $values;

        return $this;
    }

    public function getInsertQuery():string {
        $query = ["INSERT", "INTO", "$this->table"];

        if($this->insert['fields']) {
            $insertFields = implode(',', $this->insert['fields']);

            $query = $this->insertOnQuery($query, ["($insertFields)"]);
        }

        $insertValues = implode("','", $this->insert['values']);
        $query = $this->insertOnQuery($query, ["VALUES", "('$insertValues')"]);

        $this->restartQuery();
        return implode(' ', $query);
    }

    public function getSelectQuery():string {
        $selectFields = $this->select['fields'] ? implode(',', $this->select['fields']) : '*';

        $query = ["SELECT", "$selectFields"];

        $query = $this->insertOnQuery($query, ["FROM", "`$this->table`"]);

        $join = $this->join;
        if(isset($join) && !empty($join)) {
            for($i = 0; $i < count($join['fields']); $i++) {
                $table = $join['tables'][$i];
                $field = $join['fields'][$i];
                $value = $join['values'][$i];
                $query = $this->insertOnQuery($query, [$table, 'ON', "($table.$field=$this->table.$value)"], "JOIN");
            }
        }

        $clausureCommand = $this->makeClausure($this->where['fields'], $this->where['values']);
        $query = $this->insertOnQuery($query, [$clausureCommand], 'WHERE');

        $this->restartQuery();
        return implode(' ', $query);
    }

    public function getUpdateQuery():string {
        $query = ["UPDATE", "$this->table", "SET"];

        $clausureCommand = $this->makeClausure($this->update['fields'], $this->update['values']);
        $query = $this->insertOnQuery($query, [$clausureCommand]);

        $clausureCommand = $this->makeClausure($this->where['fields'], $this->where['values']);
        $query = $this->insertOnQuery($query, [$clausureCommand], 'WHERE');
        
        $this->restartQuery();
        return implode(' ', $query);
    }

    public function getDeleteQuery():string {
        $query = ["DELETE", "FROM", "$this->table"];

        $clausureCommand = null;
        $clausureCommand = $this->makeClausure($this->where['fields'], $this->where['values']);
        
        $query = $this->insertOnQuery($query, [$clausureCommand], 'WHERE');

        $this->restartQuery();
        return implode(' ', $query);
    }

    private function insertOnQuery($query, $array, $clausure = null) {
        if($clausure == null) {
            array_push($query, ...$array);

            return $query;
        }

        $property = strtolower($clausure);
        if($this->$property) {
            array_push($query, $clausure, ...$array);
        }

        return $query;
    }
    
    private function makeClausure($fields, $values):string {
        if(!$fields) {
            return "";
        }

        $clausure = "";
        for($i = 0; $i < count($fields); $i++) {
            $field = $fields[$i];
            $value = $values[$i];

            if($i != 0) {
                $clausure .= ',';
            }
            if(gettype($value) == 'string') {
                $clausure .= "$field='$value'"; 
            } else {
                $clausure .= "$field=$value";
            }
        }

        return $clausure;
    }

    private function restartQuery() {
        $this->where = null;
        $this->orderBy = null;
        $this->groupBy = null;
        $this->limit = null;
        $this->join = null;

        $this->select = null;
        $this->insert = null;
        $this->update = null;
        $this->delete = null;
    }

}