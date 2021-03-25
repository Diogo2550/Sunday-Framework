<?php

namespace Core;

use Core\Interfaces\IQueryBuilder;
use Core\Interfaces\IRepository;

abstract class BaseController {
    
    protected $repository;
    protected $query;
    protected $data;

    public function __construct(IRepository $repository, IQueryBuilder $builder) {
        $this->repository = $repository;
        $this->query = $builder;
        $this->data = json_decode(file_get_contents("php://input"), true);
    }

    protected function getControllerName() {
        $className = lcfirst(get_class($this));
        $tableName = substr($className, 0, strlen($className) - strlen("Controller"));

        return $tableName;
    }
    
}

?>