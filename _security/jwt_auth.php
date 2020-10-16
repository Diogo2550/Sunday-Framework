<?php

require "../vendor/autoload.php";
use \Firebase\JWT\JWT;

require_once ROOT_DIR . '\\interfaces\\authenticate.php';

class JWTAuth implements IAuthenticate {

    private $secret_key;
    private $token;
    private $algorithm = 'HS256';
    
    public function create_token($array_params, $array_values) {
        $params = array();
        for($i = 0; $i < count($array_params); $i++) {
            $params[$array_params[$i]] = $array_values[$i];
        }

        $this->token = JWT::encode($params, $this->secret_key, $this->algorithm);
        return $this->token;
    }

    public function check_token() {
        try {
            $decoded = JWT::decode($this->token, $this->secret_key, array($this->algorithm));
            
            return true;
        } catch (Exception $e){
    
            http_response_code(401);
    
            echo json_encode(array(
                "message" => "Access denied.",
                "error" => $e->getMessage()
            ));

            return false;
        }
    }

    public function set_token($token) {
        $this->token = $token;
    }

    public function set_key($key) {
        $this->secret_key = $key;
    }

    public function has_token():bool {
        return !($this->token == null || $this->token == "");
    }

}

/*
$secret_key = "test";
$jwt = null;

$authHeader = $_SERVER['HTTP_AUTHORIZATION'];

$arr = explode(" ", $authHeader);

$jwt = $arr[1];
*/

?>