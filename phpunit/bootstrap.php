<?php
/**
 * Some API gateways need specific headers to be present
 */
$_SERVER["HTTP_USER_AGENT"] = "Minimal API Unit Test";
$_SERVER["REQUEST_URI"] = "";

error_reporting(E_ALL|E_STRICT);

if(!function_exists("curl_init"))
{
	die("cURL library is not initialized.");
}

if(!function_exists("http_build_query"))
{
	die("http_build_query not initialized.");
}

/**
 * Often XDebug is NOT necessary.
 * @see https://xdebug.org/docs/all_functions
 */
$xdebug_disable = "xdebug_disable";
if(function_exists($xdebug_disable)) {
	$xdebug_disable();
}

require_once("vendor/autoload.php");
use anytizer\includer;
#spl_autoload_register(array(new includer("../classes"), "namespaced_inc_dot"));

#define("APIGATEWAY", "http://api.example.com:88"); // Without trailing /
define("APIGATEWAY", "http://localhost/angular/libraries/unittesting/api.php/api.php/src"); // Without trailing /
