<?php
namespace cases;

use PHPUnit\Framework\TestCase;
use anytizer\relay;
use \DateTime;
use \DateInterval;

class TomorrowTest extends TestCase
{
	public function testFutureDateApi()
	{
		$date = new DateTime(date("Y-m-d"));
		$date->add(new DateInterval('P1D'));
		$tomorrow = $date->format('Y-m-d');

		// curl http://api.example.com:88/calendar/age/tomorrow
		$url = APIGATEWAY."/calendar/age/tomorrow";

		$_GET = array();
		$_POST = array();
		
		$relay = new relay();
		$data = $relay->fetch($url);
		
		$dates = json_decode($data, true);
		$remote = substr($dates[0]["date"], 0, 10);

		#echo $data;
		#print_r($dates);
		#echo "Remote, Local: {$remote}, {$tomorrow}.";

		$this->assertEquals($remote, $tomorrow);
	}
	
	public function testPostFutureDateApi()
	{
		$date = new DateTime(date("Y-m-d"));
		$date->add(new DateInterval('P1D'));
		$tomorrow = $date->format('Y-m-d');

		// curl http://api.example.com:88/calendar/age/tomorrow
		$url = APIGATEWAY."/calendar/age/tomorrow";

		$_GET = array();
		$_POST = array();
		
		$relay = new relay();
		$relay->force_post(); // calls different/post api
		$data = $relay->fetch($url);
		
		$dates = json_decode($data, true);
		$remote = substr($dates[0]["date"], 0, 10);

		#echo $data;
		#print_r($dates);
		#echo "Remote, Local: {$remote}, {$tomorrow}.";

		$this->assertEquals($remote, $tomorrow);
	}
	
	public function testPostDataFutureDateApi()
	{
		$date = new DateTime(date("Y-m-d"));
		#$date->setTimezone(new DateTimeZone("Europe/London"));
		$date->add(new DateInterval("P1D"));
		$tomorrow = $date->format("Y-m-d");

		// curl http://api.example.com:88/calendar/age/tomorrow
		$url = APIGATEWAY."/calendar/age/tomorrow";

		$_GET = array();
		$_POST = array(
			"0" => "10",
		);
		
		$relay = new relay();
		$data = $relay->fetch($url);
		
		$dates = json_decode($data, true);
		$remote = substr($dates[0]["date"], 0, 10);

		#echo $data;
		#print_r($dates);
		#echo "Remote, Local: {$remote}, {$tomorrow}.";

		$this->assertEquals($remote, $tomorrow);
	}
}