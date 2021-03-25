<?php

namespace Core\Interfaces;

interface IRepository {

    function insert(IQueryBuilder $query_builder);
    function select(IQueryBuilder $query_builder);
    function selectAll(IQueryBuilder $query_builder): array;
    function delete(IQueryBuilder $query_builder);
    function update(IQueryBuilder $query_builder);
    function getLastId(): int;
    function count(): int;

}

?>