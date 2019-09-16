<?php

namespace auto\controllers;

use system\abstracts\api_abstracts;
use system\abstracts\api_interface;

/**
 * Fail-back API
 * Duplicate this class and make your own API Processors
 */
class controller_auto extends api_abstracts implements api_interface
{

    /**
     * Default POST index
     * @param type $data
     * @return type
     */
    public function post_index($data = array())
    {
        return $this->_index();
    }

    /**
     * Interface to get/post for index
     * @return type
     */
    private function _index()
    {
        /**
         * Web Server's local time
         */
        $response = date("Y-m-d H:i:s");
        return $response;
    }

    /**
     * Default GET index
     */
    public function get_index($data = array())
    {
        return $this->_index();
    }
}
