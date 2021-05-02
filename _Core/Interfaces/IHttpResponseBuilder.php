<?php

namespace Core\Interfaces;

interface IHttpResponseBuilder {

    public function setCode(int $code): IHttpResponseBuilder;
    public function setMessage($data): IHttpResponseBuilder;
    public function getResponse();

}

?>