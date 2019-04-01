<?php

namespace App\Controller;

use App\Service\DatabaseManager;

abstract class AbstractController
{
    /**
     * @var $databaseManager DatabaseManager
     */
    protected $databaseManager;

    /**
     * AbstractController constructor.
     * @param DatabaseManager $databaseManager
     */
    public function __construct(DatabaseManager $databaseManager)
    {
        $this->databaseManager = $databaseManager;
    }

    /**
     * @param array $errorMessages
     * @return array
     */
    protected function error(array $errorMessages)
    {
        return [
            'status' => 'error',
            'messages' => $errorMessages
        ];
    }
}