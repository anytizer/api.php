<?php
namespace cases;

use PHPUnit\Framework\TestCase;
use anytizer\relay;
use \DateTime;
use \DateInterval;

class TodayTest extends TestCase
{
	public function testTodayDateApi()
	{
		// curl http://api.example.com:88/calendar/age/today
		$url = APIGATEWAY."/calendar/age/today";

		$_GET = array();
		$_POST = array();
		
		$relay = new relay();
		$data = $relay->fetch($url);
		
		$dates = json_decode($data, true);
		$remote = substr($dates[0]["date"], 0, 10);
		$local = date("Y-m-d");

		#echo $data;
		#print_r($dates);
		#echo "Remote, Local: {$remote}, {$local}.";

		$this->assertEquals($remote, $local);
	}
}