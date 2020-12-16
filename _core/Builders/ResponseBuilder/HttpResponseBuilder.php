<?php

require_once './_Core/Interfaces/IHttpResponseBuilder.php';

class HttpResponseBuilder implements IHttpResponseBuilder {

    private $code = 200;
    private $message;

    public function setCode(int $code): IHttpResponseBuilder {
        $this->code = $code;
     
        return $this;
    }

    public function setMessage($data): IHttpResponseBuilder {
        $this->message = $data;

        return $this;
    }

    public function getResponse()
    {
        if($_SERVER['REQUEST_METHOD'] !== 'OPTIONS') {
            http_response_code($this->code);
        }
        
        return $this->message;
    }
}

?>