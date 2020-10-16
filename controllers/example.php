<?php

require_once ROOT_DIR . "\\_core\\base_controller.php";
require_once ROOT_DIR . "\\_security\\jwt_auth.php";

class ExampleController extends BaseController {

    public function get($id = null) {
        if(isset($id) && !empty($id))
            $this->query->where(['id'], [$id]);
        return $this->repository->select($this->query);
    }

    public function post() {
        $data = json_decode(file_get_contents("php://input"), true);
        $this->query->insert(['valores'],['campos']);

        return $this->repository->insert($this->query);
    }

    public function delete($id) {
        $this->query->where(['id'],[$id]);

        try {
            $this->repository->delete($this->query);
            
            return "usuário deletado com sucesso!";
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function put() {
        $data = json_decode(file_get_contents("php://input"), true);

        $id = $data['id'];
        $fields = [];
        $values = [];

        $keys = array_keys($data);
        foreach ($keys as $key => $value) {
            if($value != 'id') {
                array_push($fields, $value);
                array_push($values, $data[$value]);
            }
        }

        $this->query->where(['id'], [$id]);
        $this->query->update($fields, $values);

        return $this->repository->update($this->query);
    }

}

?>