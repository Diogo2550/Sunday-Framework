<?php

require_once ROOT_DIR . '\\interfaces\\repository.php';
require_once ROOT_DIR . '\\interfaces\\authenticate.php';
require_once ROOT_DIR . "\\interfaces\\query_builder.php";
require_once ROOT_DIR . '\\models\\user_model.php';
require_once ROOT_DIR . "\\_core\\mysql_query_builder.php";

abstract class BaseController {
    
    protected $auth;
    protected $repository;
    protected $table;
    protected $query;

    public function __construct(IAuthenticate $auth, IRepository $repository, IQueryBuilder $builder) {
        $this->repository = $repository;
        $this->auth = $auth;

        $this->table = $this->get_controller_name();
        $this->query = $builder;

        $this->query->set_table($this->table);
    }

    protected function get_controller_name() {
        $class_name = lcfirst(get_class($this));
        $table_name = substr($class_name, 0, strlen($class_name) - strlen("Controller"));

        return $table_name;
    }
    
}

?>