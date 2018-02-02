<?php
/**
 * Some API gateways need specific headers
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

// namespace/class.{name}.inc.php
spl_autoload_register(function(string $class_name){
	$chunks = explode("\\", $class_name);
	$class_name = array_pop($chunks);
	$namespace = implode("/", $chunks);
	if(!$namespace) $namespace = ".";

	$file = "classes/{$namespace}/class.{$class_name}.inc.php";
	if(is_file($file))
	{
		require_once($file);
	}
});