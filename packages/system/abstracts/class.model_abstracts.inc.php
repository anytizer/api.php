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
             *
             */
            $options = array(
                PDO::ATTR_PERSISTENT => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4;",
                PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => false,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            );
            // @todo Read configs from outside public_html
            $this->pdo = new PDO("mysql:host=192.168.1.76;dbname=passport", "admin", "nimda", $options);
        } catch (Exception $ex) {
            die("Cannot connect to the database. See: " . basename(__FILE__));
        }
    }

    /**
     * Queries that should draw one row or the details.
     *
     * @param string $sql
     * @param array $parameters
     * @return array
     */
    protected function single($sql = "", $parameters = []): array
    {
        $this->_log_sql($sql, $parameters );

        $statement = $this->pdo->prepare($sql);
        $success = $statement->execute($parameters);
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    /**
     * Queries that should return a list of rows.
     *
     * Array of arrays.
     *
     * @param string $sql
     * @param array $parameters
     * @return array
     */
    protected function rows($sql = "", $parameters = []): array
    {
        $this->_log_sql($sql, $parameters );

        $statement = $this->pdo->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
        $success = $statement->execute($parameters);
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    /**
     * Queries that should return boolean. Eg. flag something, or insert/update a record.
     *
     * @param string $sql
     * @param array $parameters
     * @return bool
     */
    protected function query($sql = "", $parameters = []): bool
    {
        $this->_log_sql($sql, $parameters);

        $statement = $this->pdo->prepare($sql);
        $success = $statement->execute($parameters);
        return $success;
    }

    /**
     * Keep an eye on SQLs
     * @param string $sql
     * @param array $parameters
     */
    private function _log_sql($sql="", $parameters=[]): void
    {
        $log_file = "/tmp/sql.log";

        file_put_contents($log_file, $sql, FILE_APPEND);
        file_put_contents($log_file, print_r($parameters, true), FILE_APPEND);
    }
}
