<?php
if($_SERVER["REQUEST_METHOD"] === "OPTIONS")
{
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: GET, POST");
	header("Access-Control-Allow-Headers: X-Protection-Token");
	
	die();
}

/**
 * an offset from your root path to this API
 * No need, if you installed it on the root of a subdomain.
 */
# http://api.example.com:88/path/module/controller/action/data/value
# http://api.example.com:88
require_once "inc.settings.php";

define("__LOG_PATH__", realpath("../logs"));

error_reporting(E_ALL);
ini_set("error_log", __LOG_PATH__."/php-errors.log");
ini_set("display_errors", 1);

/**
 * API Call needs to complete
 */
ignore_user_abort(true);

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
	die("<strong>/package/controller/method/data</strong> format required in the URL. eg. <em>/calendar/age/tomorrow<em>.");
}

/**
 * In cases of JSON posts via javascripts like AngularJS
 */
$fi = file_get_contents("php://input");
if(empty($_POST) && $fi!="")
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

	$file = "../packages/{$namespace}/class.{$class_name}.inc.php";
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
 * Remaining will be the the data portions only
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
#$manager->log_events($package, $controller, $method, $params, $output);


/**
 * Output
 */
header("Content-Type: application/json");
echo json_encode($output);
