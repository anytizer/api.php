<?php

namespace calendar\controllers;

use system\abstracts\api_abstracts;
use system\abstracts\api_interface;
use system\abstracts\model_abstracts;
use calendar\models\model_age;

/**
 * The Age API
 */
class controller_age extends api_abstracts implements api_interface {

    private function calculate($data = array()) {
        $age = new model_age();
        $dates = $age->calculate($data);

        return $dates;
    }

    public function get_index($data = array()) {
        # Database server time test
        $response = $this->get_today($data); // calling another API

        return $response;
    }

    /**
     * Returns date in the past before N days
     * 
     * @see /age/old
     * @see /age/old/50
     */
    public function get_old($data = array()) {
        $data[0] = (int) ($data[0] ?? 1);
        $response = $this->calculate($data);

        return $response;
    }

    /**
     * Returns yesterday's date
     * 
     * @see /age/yesterday
     */
    public function get_yesterday($data = array()) {
        $data[0] = 1;
        $response = $this->calculate($data);

        return $response;
    }

    /**
     * Returns current date
     * 
     * @see /age/today
     */
    public function get_today($data = array()) {
        $data[0] = 0;
        $response = $this->calculate($data);

        return $response;
    }

    /**
     * Returns tomorrow's date
     * 
     * @see /age/tomorrow
     */
    public function get_tomorrow($data = array()) {
        $data[0] = -1;
        $response = $this->calculate($data);

        return $response;
    }

    /**
     * Returns future date after N days
     * 
     * @see /age/future
     * @see /age/future/80
     * @see /calendar/age/future/80
     */
    public function get_future($data = array()) {
        $data[0] = -(int) ($data[0] ?? 1);
        $response = $this->calculate($data);

        return $response;
    }

    public function post_old($data = array()) {
        $response = $this->get_old($data);
        return $response;
    }

    public function post_yesterday($data = array()) {
        $response = $this->get_yesterday($data);
        return $response;
    }

    public function post_today($data = array()) {
        $response = $this->get_today($data);
        return $response;
    }

    public function post_tomorrow($data = array()) {
        $response = $this->get_tomorrow($data);
        return $response;
    }

    public function post_future($data = array()) {
        $response = $this->get_future($data);
        return $response;
    }

    /**
     * For documentation purpose only
     */
    public function delete_event($data = array()) {
        // do nothing
    }

    /**
     * For documentation purpose only
     */
    public function post_event($data = array()) {
        // do nothing
    }

    /**
     * For documentation purpose only
     */
    public function put_event($data = array()) {
        // do nothing
    }

    /**
     * For documentation purpose only
     */
    public function patch_event($data = array()) {
        // do nothing
    }

}
