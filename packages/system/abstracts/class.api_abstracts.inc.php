<?php

namespace system\abstracts;

use passport\models\model_roles;

class APIUser
{
    protected $user_id = "";

    public function can($package="", $class="", $method=""): bool
    {
        $can = !false;

        $user = "";
        $group = "";
        //$acl = new model_roles();
        //$can = $acl->valid($user, $group, $package, $class, $method);
        return $can;
    }

}

abstract class api_abstracts
{

    /**
     * Failback method
     * @param array $data
     */
    public function get_index($data = array())
    {
        //
    }

    public function APIUser(): APIUser
    {
        // read token
        // get the model
        // find the user if valid
        // operate
        // make necessary api access logs

        $apiuser = new APIUser();
        return $apiuser;
    }

}

