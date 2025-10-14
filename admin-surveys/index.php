<?php

/*=============================================
Mostrar errores
=============================================*/

ini_set('display_errors', 1);
ini_set("log_errors", 1);
ini_set("error_log",  "C:/xampp/htdocs/surveys/admin-surveys/php_error_log");

/*=============================================
CORS
=============================================*/

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE');

/*=============================================
Requerimientos
=============================================*/

require_once "config/config.php";
require_once "views/assets/custom/helpers/helpers.php";
require_once "controllers/template.controller.php";
require_once "controllers/curl.controller.php";

require "extensions/vendor/autoload.php";

$index = new TemplateController();
$index -> index();