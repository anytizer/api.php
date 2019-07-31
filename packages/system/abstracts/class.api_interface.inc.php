<?php

namespace system\abstracts;

interface api_interface {

    function get_index($data = array());

    // include function names like:
    // function get_xxx($params=array());
    // function post_xxx($params=array());
    // function put_xxx($params=array());
}
