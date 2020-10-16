<?php

include_once ROOT_DIR . '\\_core\\base_model.php';

class ExampleModel extends BaseModel {
    
    public $id;
    public $full_name;
    public $email;
    public $password;
    public $creation_date;

    public function __construct() {
        $this->email_confirmed = false;
        $this->creation_date = date('d/m/Y');
    }

    public function patch_values($user) {
        parent::patch_values($user);
        $this->creation_date =  array_key_exists('creation_date', $user) ? $user['creation_date'] : date('Y-m-d');
    }

    public function to_lower_propertys() {
        $this->full_name = strtolower($this->full_name);        
        $this->email = strtolower($this->email);
    }

}

?>