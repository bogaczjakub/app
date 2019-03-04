<?php

class loginModuleController
{
    public function __constructor()
    {

    }

    public function login(array $args)
    {
        if (!empty($args)) {
            $model = new Model();
            $login_model = $model->getModuleModel('login', 'login');
            return $login_model->login($args);
        }
    }

    public function createUserSession(int $user_id)
    {
        $model = new Model();
        $login_model = $model->getModuleModel('login', 'login');
        $user_details = $login_model->getUserDetails($user_id);
        $results = $login_model->insertLoginStamp($user_details);
        if ($results) {
            $_SESSION['logged_user']['user_id'] = $user_details[0]['id'];
            $_SESSION['logged_user']['user_name'] = $user_details[0]['users_firstname'];
            $_SESSION['logged_user']['user_login'] = $user_details[0]['users_login'];
            return true;
        }
    }

    public function destroyUserSession(int $user_id)
    {
        if (isset($_SESSION['logged_user']['user_id']) && $_SESSION['logged_user']['user_id'] == $user_id) {
            session_destroy();
            return true;
        }
    }
}
