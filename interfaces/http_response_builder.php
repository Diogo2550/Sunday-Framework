<?php

interface IHttpResponseBuilder {

    public function code(int $code):IHttpResponseBuilder;
    public function message($data):IHttpResponseBuilder;
    public function getResponse();

}

?>