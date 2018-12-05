<?php

class LoginModel extends Model
{
    public function __construct()
    {
    }

    public function login($args)
    {
        $db = new Db();
        if (isset($args['login-form_login']) && isset($args['login-form_password'])) {
            $results = $db->select('id')->
                from('admin_users')->
                where("login = '" . $db->cleanInput($args['login-form_login']) . "' AND password = '" . $db->cleanInput(md5($args['login-form_password'])) . "'")->
                execute('object');
            return $results;
        }
        return NULL;
    }

    public function remindMePassword()
    {

    }
}
