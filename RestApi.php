<?php

require_once './_Core/Repository/Repository.php';
require_once './Controllers/ExampleController.php';

class RestAPI {
    
    private $repository;
    private $httpResponseBuilder;
    private $queryBuilder;

    public function __construct(IRepository $repository, IHttpResponseBuilder $httpResponseBuilder)
    {
        $this->repository = $repository;
        $this->httpResponseBuilder = $httpResponseBuilder;
    }

    public function route($request) {
        $url = explode('/', $request['url']);

        $class = $this->getRequestedClass($url);
        $method = $this->getRequestedMethod($url);
        $params = $this->getRequestedParams($url);

        if(!class_exists($class)) 
            return $this->httpResponseBuilder->setCode(404)->setMessage('URL Inexistente!')->getResponse();

        $controller = new $class($this->repository, $this->queryBuilder);

        $controllerResponse = null;
        if(!method_exists($class, $method)) {
            $requestType = $_SERVER['REQUEST_METHOD'];
            $controllerResponse = call_user_func_array(array($controller, $requestType), $params);            
        } else {
            $controllerResponse = call_user_func_array(array($controller, $method), $params);
        }

        if(gettype($controllerResponse) === 'array' && array_key_exists('code', $controllerResponse)) 
            $this->httpResponseBuilder->setCode($controllerResponse['code'])->setMessage($controllerResponse['message']);
        else 
            $this->httpResponseBuilder->setMessage($controllerResponse);
        
        return $this->httpResponseBuilder->getResponse();
    }

    public function setQueryBuilder(IQueryBuilder $queryBuilder) {
        $this->queryBuilder = $queryBuilder;
    }

    private function getRequestedClass($url) {
        $class = $url[0] . "Controller";
        return $class;
    }
    
    private function getRequestedMethod($url) {
        if(count($url) < 2) 
            return null;

        if(!empty($url) && $url[1] != "" && !is_numeric($url[0])) {
            return $url[1];
        }
        return null;
    }
    
    private function getRequestedParams($url) {
        array_shift($url);
        if(count($url) > 1 && !is_numeric($url[1])) {
            array_shift($url);
        }
        return $url;
    }

}

?>