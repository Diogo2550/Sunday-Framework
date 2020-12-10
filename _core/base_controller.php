<?php

require_once ROOT_DIR . '/interfaces/repository.php';
require_once ROOT_DIR . '/interfaces/authenticate.php';
require_once ROOT_DIR . "/interfaces/query_builder.php";
require_once ROOT_DIR . "/_core/mysql_query_builder.php";
require_once ROOT_DIR . "/_core/http_response_builder.php";


abstract class BaseController {
    
    protected $auth;
    protected $repository;
    protected $table;
    protected $query;
    protected $data;

    public function __construct(IRepository $repository, IQueryBuilder $builder) {
        $this->repository = $repository;

        $this->table = $this->getControllerName();
        $this->query = $builder;

        $this->query->setTable($this->table);
        $this->data = json_decode(file_get_contents("php://input"), true);
    }

    protected function getControllerName() {
        $className = lcfirst(get_class($this));
        $tableName = substr($className, 0, strlen($className) - strlen("Controller"));

        return $tableName;
    }
    
}

?>