<?php

namespace cases;

use anytizer\relay;
use PHPUnit\Framework\TestCase;

class AutoTest extends TestCase
{

    public function testAutoGet()
    {
        // http://localhost/tutor/api.php/src/
        // http://localhost/tutor/api.php/src/auto
        // http://localhost/tutor/api.php/src/auto/auto
        // http://localhost/tutor/api.php/src/auto/auto/index

        $url = APIGATEWAY . "/auto/auto/index";

        $_GET = array();
        $_POST = array();

        $relay = new relay();
        $relay->headers(array(
            "X-Protection-Token" => "D1EC9E10-8E1A-4DAE-9104-17131522DD63",
        ));
        $data = $relay->fetch($url);

        $this->assertEquals($data, "");
    }

}
