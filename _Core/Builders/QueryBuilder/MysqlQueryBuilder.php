<?php

namespace Core\Builders\QueryBuilder;

use Core\BaseModel;
use Core\Interfaces\IQueryBuilder;
use Exception;
use ReflectionClass;

class MySQLQueryBuilder implements IQueryBuilder {

    private string $table;
    private array $modelToQuery;

    private array $where;
    private $orderBy;
    private $groupBy;
    private $limit;
    private $join;

    private $select;
    private $insert;
    private $update;

    public function setTable(string $tableName): void {
        $this->table = $tableName;
    }

    public function init(BaseModel $model): IQueryBuilder {
        $this->setTable($model->getModelName());

        $this->modelToQuery = array();

        foreach($model->getProperties() as $property) {
            if(isset($model->$property)) {
                $this->modelToQuery[$property] = $model->$property;
            } else {
                $this->modelToQuery[$property] = null;
            }
        }

        return $this;
    }

    public function where(array $whereArray):IQueryBuilder {
        $this->where = $whereArray;
        
        return $this;
    }

    public function orderBy(array $fields, array $values): IQueryBuilder {
        $this->orderBy['fields'] = $fields;
        $this->orderBy['values'] = $values;

        return $this;
    }

    public function groupBy(array $fields, array $values): IQueryBuilder {
        $this->groupBy['fields'] = $fields;
        $this->groupBy['values'] = $values;

        return $this;
    }

    public function limit(int $limit):IQueryBuilder {
        $this->limit = $limit;

        return $this;
    }

    public function min():IQueryBuilder {
        
        return $this;
    }

    public function max():IQueryBuilder {

        return $this;
    }

    public function join(array $tables, array $fields, array $values): IQueryBuilder {
        $this->join['tables'] = $tables;
        $this->join['fields'] = $fields;
        $this->join['values'] = $values;

        return $this;
    }

    public function getSelectQuery(): string {
        if(!isset($this->modelToQuery)) {
            throw new Exception("Error ao tentar pegar a string de seleção. Talvez você tenha esquecido de chamar a função 'init()'");
        }

        $qb = new QueryBuilder;
        $selectFields = array_keys($this->modelToQuery);

        $qb->insertOnQuery(["SELECT"]);
        $qb->insertSelectFieldsOnQuery($selectFields, $this->table);
        $qb->insertOnQuery(["FROM", "`$this->table`"]);

        if($this->where) {
            $qb->insertWhereFieldsOnQuery($this->where, $this->table);
        }

        $this->restartQuery();
        return $qb->getQuery();
    }

    public function getInsertQuery(): string {
        if(!isset($this->modelToQuery)) {
            throw new Exception("Error ao tentar pegar a string de inserção. Talvez você tenha esquecido de chamar a função 'init()'");
        }

        $qb = new QueryBuilder;
        $qb->insertOnQuery(["INSERT", "INTO", "$this->table"]);
        $qb->insertInsertFieldsOnQuery($this->modelToQuery);

        $this->restartQuery();
        return $qb->getQuery();
    }

    public function getUpdateQuery(): string {
        if(!isset($this->modelToQuery)) {
            throw new Exception("Error ao tentar pegar a string de atualização. Talvez você tenha esquecido de chamar a função 'init()'");
        }

        $qb = new QueryBuilder;
        $qb->insertOnQuery(["UPDATE", "$this->table"]);
        $qb->insertUpdateFieldsOnQuery($this->modelToQuery);

        if(!$this->where) {
            throw new Exception("Clausura WHERE vazia. Tentativa de atualizar todo o banco de dados impedida.");
        }

        $qb->insertWhereFieldsOnQuery($this->where, $this->table);
        
        $this->restartQuery();
        return $qb->getQuery();
    }

    public function getDeleteQuery(): string {
        if(!isset($this->modelToQuery)) {
            throw new Exception("Error ao tentar pegar a string de atualização. Talvez você tenha esquecido de chamar a função 'init()'");
        }

        $qb = new QueryBuilder;
        $qb->insertOnQuery(["DELETE", "FROM", "$this->table"]);

        if(!$this->where) {
            throw new Exception("Clausura WHERE vazia. Tentativa de deletar todo o banco de dados impedida.");
        }
        $qb->insertWhereFieldsOnQuery($this->where, $this->table);

        $this->restartQuery();
        return $qb->getQuery();
    }

    private function restartQuery() {
        $this->where = array();
    }

}