<?php
// подключаем autoloader
use App\Core\Router;
require_once('vendor/autoload.php');
session_start();

$router = new Router();
$router->run();