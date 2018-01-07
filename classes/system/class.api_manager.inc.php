<?php
namespace system;

/**
 * Extracts Endpoints, determine methods and get parameters ready
 */
class api_manager
{
	/**
	 * How the API was requested?
	 * @see https://www.w3.org/Protocols/rfc2616/rfc2616-sec9.html
	 * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Methods
	 * @see https://en.wikipedia.org/wiki/Hypertext_Transfer_Protocol
	 * @see http://www.restapitutorial.com/lessons/httpmethods.html
	 * @see https://restfulapi.net/http-methods/
	 */
	private function http_verb()
	{
		$verbs = array("get", "post", "put", "patch", "delete");
		$verb = !empty($_SERVER["REQUEST_METHOD"])?strtolower($_SERVER["REQUEST_METHOD"]):null;
		$verb = in_array($verb, $verbs)?$verb:"get";
		
		return $verb;
	}

	/**
	 * For API safety reasons
	 */
	private function word_only($resource="")
	{
		$resource = preg_replace("/[^a-zA-Z0-9\_]+/is", "", $resource);
		return $resource;
	}
	
	/**
	 * 
	 * Avoid reserved word: default, using auto
	 * Returns namespaced controller: controllers\controller UNDERSCORE CONTROLLER
	 */
	public function default_controller($controller="")
	{
		#$controller = array_shift($params);
		$controller = !empty($controller)?$this->word_only($controller):"auto";
		$controller = "controllers\\controller_{$controller}";
		
		return $controller;
	}
	
	/**
	 * Look for methods like: get_index, post_index, ...
	 * Returns HTTP_VERB underscore METHOD
	 */
	public function default_method($method="index")
	{
		$http_verb = $this->http_verb();
		$method = !empty($method)?$this->word_only($method):"index";
		$method = "{$http_verb}_{$method}";
		
		return $method;
	}
	
	/**
	 * Actual API Processor
	 */
	public function output($controller="services\\api_auto", $method="get_index", $params=array())
	{
		$output = array();
		if(class_exists($controller))
		{
			$object = new $controller();
			
			if(!method_exists($object, $method))
			{
				$output = array(
					"success" => false,
					"message" => "Missing http-verb method: ".$method,
				);
			}
			else
			{
				// Grab the output
				$output = $object->$method($params);
			}
		}
		else
		{
			$output = array(
				"success" => false,
				"message" => "No such controller: {$controller}",
			);
		}
		
		return $output;
	}

	/**
	 * Log management
	 */
	public function log_events($controller="", $method="", $data=array(), $output)
	{
		$date = date("YmdH");
		file_put_contents("logs/requests-{$date}.log", print_r($output, true), FILE_APPEND);
	}
}
