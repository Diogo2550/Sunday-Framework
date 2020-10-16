<?php 

function create_response_message($statusCode, $statusMessage, $message) {
    return json_encode(array(
        'status' => $statusMessage,
        'statusCode' => $statusCode,
        'message' => $message
    ));
}

?>