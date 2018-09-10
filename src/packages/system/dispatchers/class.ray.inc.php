<?php
namespace system\dispatchers;

class ray
{
	private $ray_id;
	public function __construct()
	{
		$this->ray_id = (new Guid())->NewGuid();
	}

	public function request()
	{
		/*
		$_GET,
		$_POST,
		$_FILE,
		$_ENV,
		$_COOKIE,
		$_SESSION;*/
	}

	public function response($output, $code)
	{
		// update ray
	}
}

$ray = new ray();
$ray->request($_GET, $_POST, $cookie, $_SESSION);
$ray->response($ray_id, $output);
