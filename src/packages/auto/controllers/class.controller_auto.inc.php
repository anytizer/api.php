<?php
namespace auto\controllers;

use system\abstracts\api_abstracts;
use system\abstracts\api_interface;
use \PDO;

/**
 * Failback API
 * Duplicate this class and make your own API Processors
 */
class controller_auto extends api_abstracts implements api_interface
{
	/**
	 * Default index
	 */
	public function get_index($data=array())
	{
		/**
		 * Web Server's local time
		 */
		$response = date("Y-m-d H:i:s");
		return $response;
	}

	/**
	 * Respond with - pong
	 */
	public function get_ping($data=array())
	{
		$response = "pong";
		return $response;
	}
}
