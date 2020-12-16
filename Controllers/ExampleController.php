<?php

require_once './_Core/BaseController.php';
require_once './Models/ExampleModel.php';

class ExampleController extends BaseController {

    public function get($id = null) {
        $example = new ExampleModel;
        $example->id = $id ? $id : 0;

        $this->query->select($example);
        if($id) {
            $this->query->where($example, 'id');
            return $this->repository->select($this->query);
        }
        
        return $this->repository->selectAll($this->query);
    }

    public function post() {
        $data = $this->data;

        $example = new ExampleModel;
        $example->patchValues($data);

        $this->query->insert($example);
        return $this->repository->insert($this->query);
    }

    public function delete($id) {
        $example = new ExampleModel;
        $example->id = $id;

        $this->query->delete($example);
        try {
            $this->repository->delete($this->query);
            
            return "usuário deletado com sucesso!";
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function put() {
        $data = $this->data;

        $example = new ExampleModel;
        $example->patchValues($data);

        $this->query->update($example);
        return $this->repository->update($this->query);
    }

}

?>