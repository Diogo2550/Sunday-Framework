<?php

interface IQueryBuilder {

    function set_table(string $table_name):void;

    function where(array $fields, array $values):IQueryBuilder;
    function order_by(array $fields, array $values):IQueryBuilder;
    function group_by(array $fields, array $values):IQueryBuilder;
    function min():IQueryBuilder;
    function max():IQueryBuilder;
    function limit(int $limit):IQueryBuilder;
    function insert(array $values, array $fields = null):IQueryBuilder;
    function select(array $values = null):IQueryBuilder;
    function update(array $fields, array $values);

    function get_insert_query():string;
    function get_select_query():string;
    function get_update_query():string;
    function get_delete_query():string;
    
    //function inner_join():IQueryBuilder;
    //function first():IQueryBuilder;
    //function last():IQueryBuilder;
}

?>