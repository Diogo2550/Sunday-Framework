<?php

namespace Core\Requests;

class RequestConf {
    static function useDefaultRequestOptions() {
        $allowedHosts = SETTINGS['allowed_hosts'];
    
        header("Access-Control-Allow-Origin: $allowedHosts");
        header('Access-Control-Allow-Credentials: true');
        header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization, X-Auth-Token");
        header("Content-Type: application/json; charset=UTF-8");
    }
}
