<?php

require_once './_Core/Interfaces/IQueryBuilder.php';
require_once 'QueryBuilder.php';

class MySQLQueryBuilder implements IQueryBuilder {

    private $table;

    private $where;
    private $orderBy;
    private $groupBy;
    private $limit;
    private $join;

    private $select;
    private $insert;
    private $update;

    function setTable(string $tableName): void {
        $this->table = $tableName;
    }

    function select(BaseModel $model): IQueryBuilder {
        $this->setTable($model->getModelName());
        $this->select['fields'] = array();

        foreach($model->getProperties() as $property) {
            $this->select['fields'][] = $property;
        }

        return $this;
    }

    function insert(BaseModel $model): IQueryBuilder {
        $this->setTable($model->getModelName());

        $this->insert['fields'] = array();
        $this->insert['values'] = array();

        foreach($model->getProperties() as $property) {
            if(isset($model->$property) && $model->$property !== null) {
                $value = $model->$property;
                
                if($value === false) {
                    $value = 0;
                }
                
                $this->insert['fields'][] = $property;
                $this->insert['values'][] = $value;
            }
        }

        return $this;
    }

    function update(BaseModel $model): IQueryBuilder {
        $this->setTable($model->getModelName());

        $this->update['fields'] = array();
        $this->update['values'] = array();

        $this->where['fields'] = array();
        $this->where['values'] = array();

        $primaryKey = $model->getPrimaryKey();
        $fieldName = $primaryKey;

        $this->where['fields'][] = $fieldName;
        $this->where['values'][] = $model->$primaryKey;

        foreach($model->getProperties() as $property) {
            if(isset($model->$property) && $model->$property != null) {
                $value = $model->$property;
                
                if($value === false) {
                    $value = 0;
                }
                
                $this->update['fields'][] = $property;
                $this->update['values'][] = $value;
            }
        }

        return $this;
    }

    function delete(BaseModel $model): IQueryBuilder {
        $this->setTable($model->getModelName());

        $this->where['fields'] = array();
        $this->where['values'] = array();

        $primaryKey = $model->getPrimaryKey();

        $this->where['fields'][] = $primaryKey;
        $this->where['values'][] = $model->$primaryKey;
        
        return $this;
    }

    function where(BaseModel $model, string $fieldName):IQueryBuilder {
        $this->where['fields'] = array();
        $this->where['values'] = array();

        if(isset($model->$fieldName) && $model->$fieldName !== null) {
            $value = $model->$fieldName === false ? 0 : $model->$fieldName;
            
            $this->where['fields'][] = $fieldName;
            $this->where['values'][] = $value;
        } else {
            throw new Exception("Campo $fieldName com valor nulo detectado");
        }
        
        return $this;
    }

    function orderBy(array $fields, array $values): IQueryBuilder {
        $this->orderBy['fields'] = $fields;
        $this->orderBy['values'] = $values;

        return $this;
    }

    function groupBy(array $fields, array $values): IQueryBuilder {
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

    function join(array $tables, array $fields, array $values): IQueryBuilder {
        $this->join['tables'] = $tables;
        $this->join['fields'] = $fields;
        $this->join['values'] = $values;

        return $this;
    }

    public function getSelectQuery(): string {
        if(!$this->select) {
            throw new Exception("Error ao tentar pegar a string de seleção. Talvez você tenha esquecido de colocar o 'query->select()'");
        }

        $qb = new QueryBuilder;
        $selectFields = $this->select['fields'] ? $this->select['fields'] : '*';

        $qb->insertOnQuery(["SELECT"]);
        $qb->insertSelectFieldsOnQuery($selectFields, $this->table);
        $qb->insertOnQuery(["FROM", "`$this->table`"]);

        if($this->where) {
            $qb->insertWhereFieldsOnQuery($this->where['fields'], $this->where['values'], $this->table);
        }

        return $qb->getQuery();
    }

    public function getInsertQuery(): string {
        if(!$this->insert) {
            throw new Exception("Error ao tentar pegar a string de inserção. Talvez você tenha esquecido de colocar o 'query->insert()'");
        }

        $qb = new QueryBuilder;
        $qb->insertOnQuery(["INSERT", "INTO", "$this->table"]);

        $insertFields = implode(',', $this->insert['fields']);
        $qb->insertOnQuery(["($insertFields)"]);

        $insertValues = implode("','", $this->insert['values']);
        $qb->insertOnQuery(["VALUES", "('$insertValues')"]);

        return $qb->getQuery();
    }

    public function getUpdateQuery(): string {
        if(!$this->update) {
            throw new Exception("Error ao tentar pegar a string de atualização. Talvez você tenha esquecido de colocar o 'query->update()'");
        }

        $qb = new QueryBuilder;
        $qb->insertOnQuery(["UPDATE", "$this->table"]);
        $qb->insertUpdateFieldsOnQuery($this->update['fields'], $this->update['values']);
        $qb->insertWhereFieldsOnQuery($this->where['fields'], $this->where['values'], $this->table);
        
        return $qb->getQuery();
    }

    public function getDeleteQuery(): string {
        $qb = new QueryBuilder;
        $qb->insertOnQuery(["DELETE", "FROM", "$this->table"]);

        if($this->where) {
            $qb->insertWhereFieldsOnQuery($this->where['fields'], $this->where['values'], $this->table);
        }

        $this->restartQuery();
        return $qb->getQuery();
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