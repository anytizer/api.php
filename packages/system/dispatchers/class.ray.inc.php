<?php

namespace system\dispatchers;

use anytizer\guid;

class ray
{

    private $ray_id;

    public function __construct()
    {
        $this->ray_id = (new guid())->NewGuid();
    }

    public function request()
    {
        /*
          $_GET,
          $_POST,
          $_FILE,
          $_ENV,
          $_COOKIE,
          $_SESSION; */
    }

    public function response($output, $code)
    {
        // update ray
    }

}

/**
 * Include once only
 */
$ray = new ray();
$ray->request($_GET, $_POST, $cookie, $_SESSION);
$ray->response($ray_id, $output);
