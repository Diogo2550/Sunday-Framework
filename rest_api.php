<?php

require_once ROOT_DIR . '\\controllers\\users.php';
require_once ROOT_DIR . '\\controllers\\admin.php';
require_once ROOT_DIR . '\\controllers\\product.php';
require_once ROOT_DIR . '\\util.php';
require_once ROOT_DIR . '\\_core\\repository\\repository.php';
require_once ROOT_DIR . '\\interfaces\\repository.php';
require_once ROOT_DIR . '\\interfaces\\authenticate.php'; 

class RestAPI {
    
    private $repository;

    public function define_repository(IRepository $repository) {
        $this->repository = $repository;
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
            $auth = new JWTAuth();
            $query_builder = new MySQLQueryBuilder();

            if(array_key_exists('REDIRECT_HTTP_AUTHORIZATION', $_SERVER) && $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] != '') {
                $token = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
                $jwt = explode(' ', $token);
                
                $auth->set_token($jwt[1]);
                $auth->set_key(SETTINGS["security"]["jwt_secret"]);
            }
            
            $controller = new $class($auth, $this->repository, $query_builder);

            if(method_exists($class, $method)) {
                $data = call_user_func_array(array($controller, $method), $params);
                
                return create_response_message(200, 'OK', $data);
            } else if($method == null) {
                $request_type = $_SERVER['REQUEST_METHOD'];
                if($request_type === "GET" || $request_type === "POST" || $request_type === "DELETE" || $request_type === "PUT") {
                    $data = call_user_func_array(array($controller, $request_type), $params);
                    
                    // TODO
                    // A mensagem deverá ser criada pelo controlador
                    return create_response_message(200, 'OK', $data);
                }
            } else {
                return create_response_message(404, 'Not Found', 'Url inexistente');
            }
        } else {
            return create_response_message(404, 'Not Found', 'Url inexistente');
        }

    }

}

?>