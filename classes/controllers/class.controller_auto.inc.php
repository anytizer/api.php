<?php
namespace controllers;

use abstracts\api_abstracts;
use abstracts\api_interface;
use \PDO;

/**
 * Failback API
 * Duplicate this class and make your own API Processors
 */
class controller_auto extends api_abstracts implements api_interface
{
	public function get_index($data=array())
	{
		# To debug headers
		#return $_SERVER;
		
		# Web Server's local time
		return date("Y-m-d H:i:s");
	}
}
