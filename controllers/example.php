<?php

require_once ROOT_DIR . "/_core/base_controller.php";
require_once ROOT_DIR . "/models/example_model.php";

class ExampleController extends BaseController {

    public function get($id = null) {
        $example = new ExampleModel;
        $example->id = $id;

        if(isset($id) && !empty($id))
            $this->query->where($example, 'id');

        return $this->repository->select($this->query);
    }

    public function post() {
        $data = json_decode(file_get_contents("php://input"), true);

        $example = new ExampleModel;
        $example->patchValues($data);

        $this->query->insert($data);
        return $this->repository->insert($this->query);
    }

    public function delete($id) {
        $example = new ExampleModel;
        $example->id = $id;
        
        $example->patchValues($example);

        $this->query->delete($example);
        try {
            $this->repository->delete($this->query);
            
            return "usuário deletado com sucesso!";
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function put() {
        $data = json_decode(file_get_contents("php://input"), true);

        $example = new ExampleModel;
        $example->patchValues($data);

        $this->query->update($example);
        return $this->repository->update($this->query);
    }

}

?>