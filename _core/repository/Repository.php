<?php

include_once ROOT_DIR . '/interfaces/repository.php';
require_once ROOT_DIR . '/_core/database/database_con.php';

class Repository implements IRepository {

    private $mysqli;

    function __construct($db_host, $db_username, $db_password, $db_name) {
        $this->mysqli = new mysqli($db_host, $db_username, $db_password);

        if(!$this->mysqli->select_db($db_name)) {
            $this->mysqli->query("CREATE DATABASE $db_name");
        }
    }

    public function insert(IQueryBuilder $query_builder) {
        $query = $query_builder->getInsertQuery();

        $result = $this->mysqli->query($query);
    
        if(!$result) {
            throw new Exception($this->mysqli->error);
        }

        return $this->mysqli->insert_id;
    }

    public function select(IQueryBuilder $query_builder):array {
        $query = $query_builder->getSelectQuery();
        
        $result = $this->mysqli->query($query);
        $data = array();

        if($result) {
            while($row = $result->fetch_assoc()) {
                $model_name = explode('_', array_keys($row)[0])[0] . 'Model';
                $model = new $model_name();

                $model->patch_values($row);
                array_push($data, $model);
            }
        }
        
        return $data;
    }

    public function delete(IQueryBuilder $query_builder) {
        $query = $query_builder->getDeleteQuery();

        return $this->mysqli->query($query);
    }

    public function update(IQueryBuilder $query_builder) {
        $query = $query_builder->getUpdateQuery();

        return $this->mysqli->query($query);
    }

    function get_last_id() {
        return $this->mysqli->insert_id;
    }

}

$mysqli = new Repository($db_host, $db_username, $db_password,$db_name);

if (mysqli_connect_errno()) {
    die('Error : ('. $mysqli->mysqli->connect_errno .') '. $mysqli->mysqli->connect_error);
}

?>