<?php

interface IAuthenticate {

    public function createToken($array_params, $array_values);
    public function checkToken();
    public function setToken($token);
    public function hasToken():bool;

}