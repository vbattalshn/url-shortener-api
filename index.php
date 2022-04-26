<?php
$include = true;
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header('Content-Type: application/json; charset=utf-8" == "application/json');

date_default_timezone_set('Europe/Istanbul');
require("./settings/config.php");
require("./inc/dbh.inc.php");
require("./inc/functions.inc.php");
require("./inc/data.inc.php");

$data = new Data;
$dataPost = json_decode(file_get_contents("php://input"), true);

function writeData($result){
    echo(json_encode($result));
};

switch (isset($_GET["action"]) ? $_GET["action"] : null) {
    case 'short': writeData($data->shorUrl(isset($dataPost["url"]) ? $dataPost["url"] : null)); break;
    case 'getUrl': writeData($data->getUrl(isset($dataPost["token"]) ? $dataPost["token"] : null)); break;
    default: echo("👍👌"); break;
}

?>