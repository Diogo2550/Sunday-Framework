<?php

class TestModel extends BaseModel {
    
    protected $primaryKey = 'id';
    public int $id;
    public DateTime $dt_nasc;

    public function __construct() { }

}