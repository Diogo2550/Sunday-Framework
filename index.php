<?php

require_once './_Core/Database/MySQLDatabaseBuilder.php';
require_once './_Core/Requests/ResquestConf.php';
require_once './RestApi.php';

$config = json_decode(file_get_contents("Settings.json"), true);
define("SETTINGS", $config);

if(isset($_REQUEST) && $_REQUEST['url'] == "") {
    printMessage("Bem vindo ao Sunday Framework!");
    printMessage("Caso essa seja sua primeira vez utilizando nossa API, sinta-se livre para ver a documentação e aprender sobre como utiliza-la em nosso GitHub.");
    printMessage("<a href='https://github.com/Diogo2550/Sunday-Framework'>GitHub</a>");

} else {
    useDefaultRequestOptions();

    $connectionSettings = SETTINGS['database_con'];
    $con = new MySQLDatabaseBuilder($connectionSettings);
    $con->createConnetion()->autoCreateDatabase()->autoCreateTables();

    $repository = new Repository($con->getConnection());

    $app = new RestAPI($repository, new HttpResponseBuilder);
    $app->setQueryBuilder(new MySQLQueryBuilder);

    echo json_encode($app->route($_REQUEST));
}

function printMessage($message) {
    echo "<p>$message</p>";
}

?>