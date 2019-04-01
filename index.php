<?php

use App\Service\Request;
use App\Service\Router;

require_once('config/config.php');
require_once('Autoloader.php');

$router = new Router(new Request());

$router->post('/signup', function (Request $request) {

});