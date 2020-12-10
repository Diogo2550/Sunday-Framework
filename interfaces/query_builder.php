<?php

interface IQueryBuilder {

    function setTable(string $table_name):void;

    function where(BaseModel $model, string $field_name):IQueryBuilder;
    function orderBy(array $fields, array $values):IQueryBuilder;
    function groupBy(array $fields, array $values):IQueryBuilder;
    function min():IQueryBuilder;
    function max():IQueryBuilder;
    function limit(int $limit):IQueryBuilder;
    function join(array $tables, array $fields, array $values):IQueryBuilder;
    function select(BaseModel $model):IQueryBuilder;
    function insert(BaseModel $model):IQueryBuilder;
    function update(BaseModel $model):IQueryBuilder;
    function delete(BaseModel $model):IQueryBuilder;

    function getInsertQuery():string;
    function getSelectQuery():string;
    function getUpdateQuery():string;
    function getDeleteQuery():string;

}

?>