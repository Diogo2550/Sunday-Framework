<?php

require_once './interfaces/query_builder.php';

class MySQLQueryBuilder implements IQueryBuilder {

    private $where;
    private $order_by;
    private $group_by;
    private $table;
    private $limit;

    private $insert;
    private $select;
    private $update;

    function set_table(string $table_name):void {
        $this->table = $table_name;
    }

    function insert(array $values, array $fields = null):IQueryBuilder {
        $this->insert['fields'] = $fields;
        $this->insert['values'] = $values;

        return $this;
    }

    function select(array $values = null):IQueryBuilder {
        $this->select = $values;

        return $this;
    }

    function update(array $fields, array $values) {
        $this->update['fields'] = $fields;
        $this->update['values'] = $values;

        return $this;
    }

    function where(array $fields, array $values):IQueryBuilder {
        $this->where['fields'] = $fields;
        $this->where['values'] = $values;

        return $this;
    }

    function order_by(array $fields, array $values):IQueryBuilder {
        $this->order_by['fields'] = $fields;
        $this->order_by['values'] = $values;

        return $this;
    }

    function group_by(array $fields, array $values):IQueryBuilder {
        $this->group_by['fields'] = $fields;
        $this->group_by['values'] = $values;

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

    public function get_insert_query():string {
        $query = ["INSERT", "INTO", "$this->table"];

        if($this->insert['fields']) {
            $insert_fields = implode(',', $this->insert['fields']);

            $query = $this->insert_on_query($query, ["($insert_fields)"]);
        }

        $insert_values = implode("','", $this->insert['values']);
        $query = $this->insert_on_query($query, ["VALUES", "('$insert_values')"]);

        $this->restart_query();
        return implode(' ', $query);
    }

    public function get_select_query():string {
        $select = $this->select ? implode(',', $this->select) : '*';

        $query = ["SELECT", "$select"];

        $query = $this->insert_on_query($query, ["FROM", "`$this->table`"]);

        $clausure_command = $this->make_clausure($this->where['fields'], $this->where['values']);
        $query = $this->insert_on_query($query, [$clausure_command], 'WHERE');

        $this->restart_query();
        return implode(' ', $query);
    }

    public function get_update_query():string {
        $query = ["UPDATE", "$this->table", "SET"];

        $clausure_command = $this->make_clausure($this->update['fields'], $this->update['values']);
        $query = $this->insert_on_query($query, [$clausure_command]);

        $clausure_command = $this->make_clausure($this->where['fields'], $this->where['values']);
        $query = $this->insert_on_query($query, [$clausure_command], 'WHERE');
        
        $this->restart_query();
        return implode(' ', $query);
    }

    public function get_delete_query():string {
        $query = ["DELETE", "FROM", "$this->table"];

        $clausure_command = $this->make_clausure($this->where['fields'], $this->where['values']);
        $query = $this->insert_on_query($query, [$clausure_command], 'WHERE');

        $this->restart_query();
        return implode(' ', $query);
    }

    private function insert_on_query($query, $array, $clausure = null) {
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
    
    private function make_clausure($fields, $values):string {
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

    private function restart_query() {
        $this->where = null;
        $this->order_by = null;
        $this->group_by = null;
        $this->limit = null;
    }
    
}