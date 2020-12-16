<?php

require_once './_Core/Interfaces/IQueryBuilder.php';

interface IRepository {

    function insert(IQueryBuilder $query_builder);
    function select(IQueryBuilder $query_builder): object;
    function selectAll(IQueryBuilder $query_builder): array;
    function delete(IQueryBuilder $query_builder);
    function update(IQueryBuilder $query_builder);
    function getLastId(): int;
    function count(): int;

}

?>