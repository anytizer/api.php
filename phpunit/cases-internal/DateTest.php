<?php
namespace cases;

use PHPUnit\Framework\TestCase;
use connections\relay;

class DateTest extends TestCase
{
	public function testDate()
	{
		// curl api.example.com:88/calendar/age/today
		$url = "http://api.example.com:88/calendar/age/today";

		$_GET = array();
		$_POST = array();
		$relay = new relay();
		$data = $relay->fetch($url);
		$dates = json_decode($data, true);
		#echo $data;
		#print_r($dates);
		$remote = substr($dates[0]["date"], 0, 10);
		$local = date("Y-m-d");

		echo "Remote, Local: {$remote}, {$local}.";

		$this->assertEquals($remote, $local);
	}
}