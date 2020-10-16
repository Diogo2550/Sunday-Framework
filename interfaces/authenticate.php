<?php

interface IAuthenticate {

    public function create_token($array_params, $array_values);
    public function check_token();
    public function set_token($token);
    public function has_token():bool;

}