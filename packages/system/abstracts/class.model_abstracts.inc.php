<?php

namespace system\abstracts;

use Exception;
use PDO;

abstract class model_abstracts
{

    protected $pdo;

    public function __construct()
    {
        try {
            /**
             * Connects to the database server
             * This is the only configuration for your database, through out the entire application
             */
            $this->pdo = new PDO("mysql:host=localhost;dbname=inventory", "root", "");
        } catch (Exception $ex) {
            die("Cannot connect to the database. See: " . basename(__FILE__));
        }
    }

}
