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
            $options = array(
                PDO::ATTR_PERSISTENT => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4;",
                PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => false,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            );
            $this->pdo = new PDO("mysql:host=localhost;dbname=inventory", "root", "", $options);
        } catch (Exception $ex) {
            die("Cannot connect to the database. See: " . basename(__FILE__));
        }
    }

}
