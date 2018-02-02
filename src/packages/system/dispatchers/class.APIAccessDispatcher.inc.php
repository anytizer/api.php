<?php
namespace system\dispatchers;

/**
 * Log access to our APIs
 * Class APIAccessDispatcher
 * @package system\dispatchers
 */
class APIAccessDispatcher implements dispatcher
{
    /**
     * @param string $event
     * @param string $message
     * @param array $data
     * @return bool
     */
    public function dispatch(string $event="something.happened", string $message="", array $data=array()): bool
    {
        $datetime = date("Y-m-d H:i:s");
        
        $user = "@todoAPI";
        
        $replaces = array(
            "/angular/libraries/awesome/awesome.licensing/server/" => "/",
        );
        /**
         * @see http://php.net/manual/en/function.array-walk.php
         */
        $finds = array_keys($replaces);
        array_walk($finds, function(&$item){
            $data = "/^".preg_quote($item, "/")."/is";
            $item = $data;
        });
        $url = preg_replace($finds, array_values($replaces), $_SERVER["REQUEST_URI"]);

        $method = $_SERVER["REQUEST_METHOD"];

        file_put_contents("d:/events.log", sprintf("\r\n%-10s %-10s %-25s %-10s %-50s %s", $datetime, $user, $event, $method, $url, $message), FILE_APPEND);
        file_put_contents("d:/events.log", "\r\n".print_r(array("get" => $_GET, "post" => $_POST), true), FILE_APPEND);
        return true;
    }
}
