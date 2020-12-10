<?php

define("ROOT_DIR", (__DIR__));

if(empty($_REQUEST)) {
    printMessage("Bem vindo(a) à api do site Gio Biquinis. Se você não tiver autorização para entrar, por favor, redicione-se à página principal do site clicando no botão abaixo");
    printMessage("<a href='http://localhost:4200'>página principal</a>");
} else {
    include_once 'rest_api.php';

    if(isset($_REQUEST) && !empty($_REQUEST)) {

        $config = json_decode(file_get_contents("settings.json"), true);
        define("SETTINGS", $config);

        $app = new RestAPI($mysqli, new HttpResponseBuilder);

        echo json_encode($app->route($_REQUEST));
        
    }
}

function printMessage($message) {
    echo "<p>$message</p>";
}

?>