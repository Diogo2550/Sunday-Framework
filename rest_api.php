<?php

require_once ROOT_DIR . '/_core/repository/repository.php';
require_once ROOT_DIR . '/interfaces/repository.php';
require_once ROOT_DIR . '/interfaces/authenticate.php';

require_once ROOT_DIR . '/controllers/example.php';

class RestAPI {
    
    private $repository;
    private $httpResponseBuilder;

    public function __construct(IRepository $repository, IHttpResponseBuilder $httpResponseBuilder)
    {
        $this->repository = $repository;
        $this->httpResponseBuilder = $httpResponseBuilder;
    }

    public function route($request) {
        $url = explode('/', $request['url']);
        
        $class = $url[0] . "Controller";
        array_shift($url);

        $method = null;
        if((!empty($url) && $url[0] != "") && !is_numeric($url[0])) {
            $method = $url[0];
            array_shift($url);
        }

        $params = array();
        $params = $url;

        if(class_exists($class)) {
            //$auth = new JWTAuth();
            $queryBuilder = new MySQLQueryBuilder();

            /*
            if(array_key_exists('REDIRECT_HTTP_AUTHORIZATION', $_SERVER) && $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] != '') {
                $token = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
                $jwt = explode(' ', $token);
                
                $auth->set_token($jwt[1]);
                $auth->set_key(SETTINGS["security"]["jwt_secret"]);
            }
            */
            
            $controller = new $class($this->repository, $queryBuilder);

            if(method_exists($class, $method)) {
                $data = call_user_func_array(array($controller, $method), $params);
                
                if(gettype($data) === 'array' && array_key_exists('code', $data)) {
                    $this->httpResponseBuilder->code($data['code'])->message($data['message']);
                } else {
                    $this->httpResponseBuilder->message($data);
                }
                return $this->httpResponseBuilder->getResponse();
            } else if($method == null) {
                $request_type = $_SERVER['REQUEST_METHOD'];
                if($request_type === "GET" || $request_type === "POST" || $request_type === "DELETE" || $request_type === "PUT") {
                    $data = call_user_func_array(array($controller, $request_type), $params);
                    
                    if(gettype($data) === 'array' && array_key_exists('code', $data)) {
                        $this->httpResponseBuilder->code($data['code'])->message($data['message']);
                    } else {
                        $this->httpResponseBuilder->message($data);
                    }
                    return $this->httpResponseBuilder->getResponse();
                }
            } else {
                return $this->httpResponseBuilder->message('Url inexistente')->code(404)->getResponse();
            }
        } else {
            return $this->httpResponseBuilder->message('Url inexistente')->code(404)->getResponse();
        }

    }

}

?>