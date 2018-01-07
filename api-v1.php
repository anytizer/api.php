<?php
# an offset from your root path to this api
$api_path = "/angular/libraries/api.php/"; # /
#print_r($_SERVER);
$_SERVER["REDIRECT_URL"] = $_SERVER["REDIRECT_URL"]??"/";
$path = preg_replace("/^".preg_quote($api_path, "/")."/is", "", $_SERVER["REDIRECT_URL"]);
$path = trim($path, "/");
$params = explode("/", $path);
if(empty($params[0])) $params[0] = "auto";
if(empty($params[1])) $params[1] = "index";

/**
 * In cases of JSON posts via javascripts like AngularJS
 */
if(empty($_POST))
{
	$_POST = json_decode(file_get_contents("php://input"), true);
}

/**
 * Includes class files automatically
 * File name pattern: classes/{namespace}/class.{class_name}.inc.php
 */
spl_autoload_register(function(string $class_name){
	// for name-space based class access
	$chunks = explode("\\", $class_name);
	$class_name = array_pop($chunks); // from the last word
	$namespace = implode("/", $chunks);
	if(!$namespace) $namespace = ".";

	$file = "classes/{$namespace}/class.{$class_name}.inc.php";
	if(is_file($file))
	{
		require_once($file);
	}
});

$manager = new system\api_manager();
$controller = $manager->default_controller($params[0]);
$method = $manager->default_method($params[1]);

/**
 * remaining will the the data only
 */
array_shift($params); // throw out: controller
array_shift($params); // throw out: method

/**
 * Core calculations
 */
$output = $manager->output($controller, $method, $params);

/**
 * Do some access logs
 */
$manager->log_events($controller, $method, $params, $output);

/**
 * Output
 */
header("Content-Type: application/json");
echo json_encode($output);
