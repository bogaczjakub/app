<?php

class loginModuleModel
{
    public function __constructor()
    {

    }

    public function login(array $args)
    {
        if (isset($args['login_form-login']) && isset($args['login_form-password'])) {
            $db = new Db();
            return $db->select("id")->
                from("admin_users")->
                where("login='{$db->escapeString($args['login_form-login'])}' AND password='{$db->escapeString(md5($args['login_form-password']))}'")->
                execute("object");
        }
    }

    public function getUserDetails(int $user_id)
    {
        if (!empty($user_id)) {
            $db = new Db();
            return $db->select("*")->
                from("admin_users")->
                where("id = '{$user_id}'")->
                execute("assoc");
        }
    }

    public function insertLoginStamp(array $user_array)
    {
        $db = new Db();
        $timestamp = date('Y-m-d H:i:s');
        $user_id = $user_array[0]['id'];
        return $db->insert("admin_logins")->
            values("'0','{$user_id}','{$timestamp}'")->
            execute('bool');
    }
}
