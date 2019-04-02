<?php

require_once('autoloader.php');
require_once('config/config.php');

use App\Service\DatabaseConfiguration;
use App\Service\DatabaseManager;

$dbc = new DatabaseConfiguration(
    getenv('CONFIG_DB_HOST'),
    getenv('CONFIG_DB_NAME'),
    getenv('CONFIG_DB_USER'),
    getenv('CONFIG_DB_PASS'),
    getenv('CONFIG_DB_TYPE')
);

DatabaseManager::install($dbc);