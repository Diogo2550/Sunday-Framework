<?php

require_once ROOT_DIR . '/interfaces/http_response_builder.php';

class HttpResponseBuilder implements IHttpResponseBuilder {

    private $code = 200;
    private $message;

    public function code(int $code):IHttpResponseBuilder {
        $this->code = $code;
     
        return $this;
    }

    public function message($data):IHttpResponseBuilder {
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