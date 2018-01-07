<?php
namespace controllers;

use abstracts\api_abstracts;
use abstracts\api_interface;
use abstracts\model_abstracts;
use models\model_age;
use \PDO;

/**
 * The Age API
 */
class controller_age extends api_abstracts implements api_interface
{
	private function calculate($data=array())
	{
		$age = new model_age();
		$dates = $age->calculate($data);
		
		return $dates;
	}

	public function get_index($data=array())
	{
		# Web Server's local time test
		#return date("Y-m-d H:i:s"); // local time test
		
		# Database server time test
		return $this->calculate(array(0));
	}
	
	/**
	 * Returns date in the past before N days
	 * 
	 * @see /age/old
	 * @see /age/old/50
	 */
	public function get_old($data=array())
	{
		$data[0] = (int)($data[0]??1);
		return $this->calculate($data);
	}	

	/**
	 * Returns yesterday's date
	 * 
	 * @see /age/yesterday
	 */
	public function get_yesterday($data=array())
	{
		return $this->calculate(array(1));
	}

	/**
	 * Returns current date
	 * 
	 * @see /age/today
	 */
	public function get_today($data=array())
	{
		return $this->calculate(array(0));
	}

	/**
	 * Returns tomorrow's date
	 * 
	 * @see /age/tomorrow
	 */
	public function get_tomorrow($data=array())
	{
		return $this->calculate(array(-1));
	}

	/**
	 * Returns future date after N days
	 * 
	 * @see /age/future
	 * @see /age/future/80
	 */
	public function get_future($data=array())
	{
		$data[0] = -(int)($data[0]??1);
		return $this->calculate($data);
	}
}
