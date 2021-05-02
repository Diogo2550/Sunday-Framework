<?php

namespace Controllers;

use Core\BaseController;
use Models\ExampleModel;

class ExampleController extends BaseController {

    public function get($id = null) {
        $example = new ExampleModel;
        $example->id = $id ? $id : 0;

        $this->query->init($example);
        if($id) {
            $this->query->where([
                'id' => $example->id
            ]);
        }

        return $this->repository->select($this->query);
    }

    public function getAll() {
        $example = new ExampleModel;
        $this->query->init($example);
        return $this->repository->selectAll($this->query);
    }

    public function post() {
        $data = $this->data;

        $example = new ExampleModel;
        $example->patchValues($data);

        $this->query->init($example);
        return $this->repository->insert($this->query);
    }

    public function delete($id) {
        $example = new ExampleModel;
        $example->id = $id;

        $this->query->init($example)->where([
            'id' => $example->id
        ]);
        try {
            $this->repository->delete($this->query);
            
            //return "usuário deletado com sucesso!";
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function put() {
        $data = $this->data;

        $example = new ExampleModel;
        $example->patchValues($data);

        $this->query->init($example)->where([
            'id' => $example->id
        ]);
        return $this->repository->update($this->query);
    }

}

?>