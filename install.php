<?php

require_once('autoloader.php');
require_once('config/config.php');

use App\Service\DatabaseConfiguration;
use App\Service\DatabaseManager;

$dbc = new DatabaseConfiguration(
    CONFIG_DB_HOST,
    CONFIG_DB_NAME,
    CONFIG_DB_USER,
    CONFIG_DB_PASS,
    CONFIG_DB_TYPE
);

DatabaseManager::install($dbc);