<?php

require_once ROOT_DIR . '/interfaces/query_builder.php';

interface IRepository {

    function insert(IQueryBuilder $query_builder);
    function select(IQueryBuilder $query_builder);
    function delete(IQueryBuilder $query_builder);
    function update(IQueryBuilder $query_builder);

}

?>