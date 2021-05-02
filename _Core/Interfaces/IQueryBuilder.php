<?php

namespace Core\Interfaces;

use Core\BaseModel;

interface IQueryBuilder {

    function setTable(string $table_name):void;

    function init(BaseModel $model);
    function where(array $whereArray): IQueryBuilder;
    function orderBy(array $fields, array $values): IQueryBuilder;
    function groupBy(array $fields, array $values): IQueryBuilder;
    function min(): IQueryBuilder;
    function max(): IQueryBuilder;
    function limit(int $limit): IQueryBuilder;
    function join(array $tables, array $fields, array $values): IQueryBuilder;
    
    function getInsertQuery(): string;
    function getSelectQuery(): string;
    function getUpdateQuery(): string;
    function getDeleteQuery(): string;

}

?>