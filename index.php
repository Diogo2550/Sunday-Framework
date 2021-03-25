<?php

require_once './vendor/autoload.php';
require_once './RestApi.php';

use Core\Builders\QueryBuilder\MySQLQueryBuilder;
use Core\Builders\ResponseBuilder\HttpResponseBuilder;
use Core\Database\MySQLDatabaseBuilder;
use Core\Repository\Repository;

$config = json_decode(file_get_contents("Settings.json"), true);
define("SETTINGS", $config);
define('BASE_PATH', getcwd());

$connectionSettings = SETTINGS['database_con'];
$con = new MySQLDatabaseBuilder($connectionSettings);
$con->createConnetion()->autoCreateDatabase()->autoCreateTables();

if(isset($_REQUEST) && $_REQUEST['url'] == "") {
    printMessage("Bem vindo ao Sunday Framework!");
    printMessage("Caso essa seja sua primeira vez utilizando nossa API, sinta-se livre para ver a documentação e aprender sobre como utiliza-la em nosso GitHub.");
    printMessage("<a href='https://github.com/Diogo2550/Sunday-Framework'>GitHub</a>");

} else {
    \Core\Requests\RequestConf::useDefaultRequestOptions();

    $repository = new Repository($con->getConnection());

    $app = new RestAPI($repository, new HttpResponseBuilder);
    $app->setQueryBuilder(new MySQLQueryBuilder);

    echo json_encode($app->route($_REQUEST));
}

function printMessage($message) {
    echo "<p>$message</p>";
}

?>