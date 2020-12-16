<?php

include_once './_Core/Interfaces/IRepository.php';

class Repository implements IRepository {

    private $mysqli;

    function __construct($mysqli) {
        $this->mysqli = $mysqli;
    }

    public function insert(IQueryBuilder $query_builder) {
        $query = $query_builder->getInsertQuery();

        $result = $this->mysqli->query($query);
    
        if(!$result) {
            throw new Exception($this->mysqli->error);
        }

        return $this->mysqli->insert_id;
    }

    public function select(IQueryBuilder $query_builder): object {
        $query = $query_builder->getSelectQuery();
        
        $result = $this->mysqli->query($query);
        $model = new ExampleModel;

        if($row = $result->fetch_assoc()) {
            $model_name = explode('_', array_keys($row)[0])[0] . 'Model';
            $model = new $model_name();

            $model->patchValues($row);
        }

        return $model;
    }

    function selectAll(IQueryBuilder $query_builder): array {
        $query = $query_builder->getSelectQuery();
        
        $result = $this->mysqli->query($query);
        
        $data = array();
        if($result) {
            while($row = $result->fetch_assoc()) {
                $model_name = explode('_', array_keys($row)[0])[0] . 'Model';
                $model = new $model_name();

                $model->patchValues($row);
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

    function count(): int {
        return 0;
    }

    function getLastId(): int {
        return 0;
    }

}

?>