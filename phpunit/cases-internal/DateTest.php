<?php
namespace cases;

use PHPUnit\Framework\TestCase;
use anytizer\connections\relay;
use \DateTime;
use \DateInterval;

class DateTest extends TestCase
{
	public function testTodayDateApi()
	{
		// curl api.example.com:88/calendar/age/today
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

	public function testFutureDateApi()
	{
		$date = new DateTime(date("Y-m-d"));
		$date->add(new DateInterval('P1D'));
		$tomorrow = $date->format('Y-m-d');

		// curl api.example.com:88/calendar/age/tomorrow
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
}