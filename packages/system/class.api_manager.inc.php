<?php

namespace system;

/**
 * Extracts Endpoints and HTTP Headers
 * Determine methods and additional parameters from end points
 * GET and POST data and untouched
 */
class api_manager
{

    /**
     *
     * Avoid reserved word: default, using auto
     * Returns namespaced controller: controllers\controller UNDERSCORE CONTROLLER
     */
    public function default_controller($package = "", $controller = "")
    {
        #$controller = array_shift($params);

        $controller = !empty($controller) ? $this->word_only($controller) : "auto";
        $controller = "{$package}\\controllers\\controller_{$controller}";

        $controller = strtolower($controller);

        return $controller;
    }

    /**
     * For API safety reasons, do not allow random characters
     */
    private function word_only($resource = "")
    {
        $resource = preg_replace("/[^a-zA-Z0-9\_]+/is", "", $resource);
        return $resource;
    }

    /**
     * Look for methods like: get_index, post_index, ...
     * Returns HTTP_VERB underscore METHOD
     */
    public function default_method($method = "index")
    {
        $http_verb = $this->http_verb();
        $method = !empty($method) ? $this->word_only($method) : "index";
        $method = "{$http_verb}_{$method}";

        $method = strtolower($method);

        return $method;
    }

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
        $verb = !empty($_SERVER["REQUEST_METHOD"]) ? strtolower($_SERVER["REQUEST_METHOD"]) : null;
        $verb = in_array($verb, $verbs) ? $verb : "get";

        return $verb;
    }

    /**
     * Actual API Processor
     */
    public function output($package = "", $controller = "services\\api_auto", $method = "get_index", $params = array())
    {
        $output = array();
        if (class_exists($controller)) {
            $object = new $controller();

            if (!method_exists($object, $method)) {
                $output = array(
                    "success" => false,
                    "message" => "Missing http-verb method: {$method}()",
                );
            } else {
                // Grab the output
                try {
                    $output = $object->$method($params);
                } catch (Exception $ex) {
                    $output = $ex; // "Error!";
                }
            }
        } else {
            $output = array(
                "success" => false,
                "message" => "No such controller: {$controller}",
            );
        }

        return $output;
    }

    /**
     * Checks if a request to access API resource is pre-authorized
     * Validates X-Protection-Token header
     * @return bool
     * @todo Verify against database
     * @todo Verify that user can proceed based on the token key
     */
    public function authorized(): bool
    {
        $valid = array_key_exists("HTTP_X_PROTECTION_TOKEN", $_SERVER);
        return $valid;
    }

    /**
     * Event dispatcher
     */
    public function dispatch($dispatcher_name = "\system\dispatchers\APIAccessDispatcher", $event = "", $message = "", $data = array())
    {
        $dispatcher_class = "\\system\dispatchers\\{$dispatcher_name}";
        if (class_exists($dispatcher_class)) {
            $dispatcher = new $dispatcher_class();
            $dispatcher->dispatch($event, "", array());
        } else {
            // Dispatcher not found.
        }
    }

    /**
     * Log management
     */
    public function log_events($controller = "", $method = "", $data = array(), $output)
    {
        $date = date("YmdH");
        file_put_contents(__LOG_PATH__ . "/requests-{$date}.log", print_r($output, true), FILE_APPEND);

        #$ray = new ray();
        #$ray->request();
        #$ray->response();
    }

}
