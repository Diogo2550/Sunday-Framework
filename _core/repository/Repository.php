<?php

include_once ROOT_DIR . '\\interfaces\\repository.php';
require_once ROOT_DIR . '\\_core\\database\\database_con.php';

class Repository implements IRepository {

    private $mysqli;

    function __construct($host, $username, $password, $name) {
        $this->mysqli = new mysqli($this->db_host, $this->db_username, $this->db_password, $this->db_name);
    }

    public function insert(IQueryBuilder $query_builder) {
        $query = $query_builder->get_insert_query();

        $result = $this->mysqli->query($query);
        
        return $this->mysqli->insert_id;
    }

    public function select(IQueryBuilder $query_builder) {
        $query = $query_builder->get_select_query();
        
        $result = $this->mysqli->query($query);
        $data = array();

        if($query) {
            while($row = $result->fetch_array(MYSQLI_ASSOC)) {
                array_push($data, $row);
            }
        }

        return $data;
    }

    public function delete(IQueryBuilder $query_builder) {
        $query = $query_builder->get_delete_query();

        return $this->mysqli->query($query);
    }

    public function update(IQueryBuilder $query_builder) {
        $query = $query_builder->get_update_query();

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