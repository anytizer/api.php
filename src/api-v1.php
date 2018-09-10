<?php
/**
 * an offset from your root path to this api
 * No need, if you installed it on the root of a subdomain.
 */
# http://api.example.com:88/path/module/controller/action/data/value
# http://api.example.com:88
$offset_path = "/"; # fix it

error_reporting(E_ALL);
ini_set("display_errors", 1);
ignore_user_abort(true);
ini_set("error_log", "logs/php-errors.log");

/**
 * Do not edit anything below.
 */
$_SERVER["REDIRECT_URL"] = $_SERVER["REDIRECT_URL"]??"/";
$path = preg_replace("/^".preg_quote($offset_path, "/")."/is", "", $_SERVER["REDIRECT_URL"]);
$path = trim($path, "/");
$params = explode("/", $path);
if(empty($params[0])) $params[0] = "auto";
if(empty($params[1])) $params[1] = "index";
if(count($params)<3)
{
	die("/package/controller/method/data format required.");
}

/**
 * In cases of JSON posts via javascripts like AngularJS
 */
$fi = file_get_contents("php://input");
if(empty($_POST) && $fi)
{
	$_POST = json_decode($fi, true);
}

/**
 * Includes class files automatically
 * File name pattern: classes/{sub}/{name}/{space}/class.{class_name}.inc.php
 */
spl_autoload_register(function(string $class_name){
	// for name-space based class access
	$chunks = explode("\\", $class_name);
	$class_name = array_pop($chunks); // from the last word
	$namespace = implode("/", $chunks);
	if(!$namespace) $namespace = ".";

	$file = "packages/{$namespace}/class.{$class_name}.inc.php";
	if(is_file($file))
	{
		require_once($file);
	}
	else
	{
		#echo "\r\nNot found: {$file}";
	}
});

$manager = new system\api_manager();
$package = strtolower($params[0]);
$controller = $manager->default_controller($package, $params[1]);
$method = $manager->default_method($params[2]);
//$APIAccessDispatcher = new \system\dispatchers\APIAccessDispatcher();

/**
 * Remaining will the the data only
 */
array_shift($params); // throw out: package
array_shift($params); // throw out: controller
array_shift($params); // throw out: method

/**
 * Core calculations
 */
$output = null;
if($manager->authorized())
{
	$manager->dispatch("APIAccessDispatcher", "authorization.successful", "", array());
	$output = $manager->output($package, $controller, $method, $params);
	$manager->dispatch("APIAccessDispatcher", "output.generated", "", array());
}
else
{
	$manager->dispatch("APIAccessDispatcher", "authorization.failed", "", array());
}
/**
 * Do some access logs
 */
$manager->log_events($package, $controller, $method, $params, $output);


/**
 * Output
 */
header("Content-Type: application/json");
echo json_encode($output);
