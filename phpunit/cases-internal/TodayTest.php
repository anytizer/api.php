<?php

namespace cases;

use PHPUnit\Framework\TestCase;
use anytizer\relay;

class TodayTest extends TestCase {

    public function testTodayDateApi() {
        // curl http://api.example.com:88/calendar/age/today
        $url = APIGATEWAY . "/calendar/age/today";

        $_GET = array();
        $_POST = array();

        $relay = new relay();
        $relay->headers(array(
            "X-Protection-Token" => "D1EC9E10-8E1A-4DAE-9104-17131522DD63",
        ));
        $data = $relay->fetch($url);

        $dates = json_decode($data, true);
        $remote = substr($dates[0]["date"], 0, 10); // date part only
        $local = date("Y-m-d");

        $this->assertEquals($remote, $local);
    }

    public function testPostTodayDateApi() {
        // curl http://api.example.com:88/calendar/age/today
        $url = APIGATEWAY . "/calendar/age/today";

        $_GET = array();
        $_POST = array();

        $relay = new relay();
        $relay->force_post();
        $relay->headers(array(
            "X-Protection-Token" => "D1EC9E10-8E1A-4DAE-9104-17131522DD63",
        ));
        $data = $relay->fetch($url);

        $dates = json_decode($data, true);
        $remote = substr($dates[0]["date"], 0, 10); // date part only
        $local = date("Y-m-d");

        $this->assertEquals($remote, $local);
    }

    public function testPostDataTodayDateApi() {
        // curl http://api.example.com:88/calendar/age/today
        $url = APIGATEWAY . "/calendar/age/today";

        $_GET = array();
        $_POST = array(
            0 => 0,
        );

        $relay = new relay();
        $relay->headers(array(
            "X-Protection-Token" => "D1EC9E10-8E1A-4DAE-9104-17131522DD63",
        ));
        $data = $relay->fetch($url);

        $dates = json_decode($data, true);
        $remote = substr($dates[0]["date"], 0, 10); // date part only
        $local = date("Y-m-d");

        $this->assertEquals($remote, $local);
    }

}
