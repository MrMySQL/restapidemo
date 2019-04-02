<?php

use App\Controller\TaskController;
use App\Controller\UserController;
use App\Model\TaskModel;
use App\Model\UserModel;
use App\Service\DatabaseConfiguration;
use App\Service\DatabaseManager;
use App\Service\Request;
use App\Service\Router;
use App\Service\TaskMapper;
use App\Service\UserMapper;

require_once('config/config.php');
require_once('autoloader.php');

$dbc = new DatabaseConfiguration(
    CONFIG_DB_HOST,
    CONFIG_DB_NAME,
    CONFIG_DB_USER,
    CONFIG_DB_PASS,
    CONFIG_DB_TYPE
);
$databaseManager = new DatabaseManager($dbc);

$router = new Router(new Request(), $databaseManager);

$router->post('/auth/signup', function (Request $request, DatabaseManager $databaseManager) {
    $controller = new UserController($databaseManager, new UserModel(new UserMapper($databaseManager)));
    $response = $controller->signUpAction($request);
    echo json_encode($response);
});

$router->post('/auth/signin', function (Request $request, DatabaseManager $databaseManager) {
    $controller = new UserController($databaseManager, new UserModel(new UserMapper($databaseManager)));
    $response = $controller->signInAction($request);
    echo json_encode($response);
});

$router->post('/tasks/new', function (Request $request, DatabaseManager $databaseManager) {
    $controller = new TaskController($databaseManager, new TaskModel(new TaskMapper($databaseManager)));
    $response = $controller->newAction($request);
    echo json_encode($response);
});

$router->get('/tasks/', function (Request $request, DatabaseManager $databaseManager) {
    $controller = new TaskController($databaseManager, new TaskModel(new TaskMapper($databaseManager)));
    $response = $controller->getTasksAction($request);
    echo json_encode($response);
});

$router->post('/tasks/done', function (Request $request, DatabaseManager $databaseManager) {
    $controller = new TaskController($databaseManager, new TaskModel(new TaskMapper($databaseManager)));
    $response = $controller->markDoneAction($request);
    echo json_encode($response);
});

$router->post('/tasks/delete', function (Request $request, DatabaseManager $databaseManager) {
    $controller = new TaskController($databaseManager, new TaskModel(new TaskMapper($databaseManager)));
    $response = $controller->deleteAction($request);
    echo json_encode($response);
});