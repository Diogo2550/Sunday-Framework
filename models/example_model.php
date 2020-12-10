<?php

include_once ROOT_DIR . '\\_core\\base_model.php';

class ExampleModel extends BaseModel {
    
    protected $primary_key = 'id';
    public $id;
    public $name;

    public function __construct() { }

}

?>