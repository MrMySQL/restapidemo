<?php

namespace App\Service;

abstract class DataMapper
{
    /**
     * @var $databaseManager DatabaseManager
     */
    protected $databaseManager;

    /**
     * AbstractModel constructor.
     * @param DatabaseManager $databaseManager
     */
    public function __construct(DatabaseManager $databaseManager)
    {
        $this->databaseManager = $databaseManager;
    }
}