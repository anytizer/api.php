<?php
namespace abstracts;
use \Exception;
use \PDO;

abstract class model_abstracts
{
	protected $pdo;
	
	public function __construct()
	{
		try
		{
			/**
			 * Connects to the database server
			 */
			$this->pdo = new PDO("mysql:host=localhost;dbname=test", "root", "");
		}
		catch(Exception $ex)
		{
			die("Cannot connect to the database. See: ".basename(__FILE__));
		}
	}
}