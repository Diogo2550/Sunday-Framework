<?php

namespace Models;

use Core\BaseModel;

class ExampleModel extends BaseModel {
    
    protected $primaryKey = 'id';
    public int $id;
    public string $name;
    public int $idade;

    public function __construct() { }

}

?>