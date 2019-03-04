<?php

class loginModule extends Module
{
    public function __construct()
    {
        $this->addCss(array('login.css'));
        $this->addJs(array('login.js'));
    }

    public function index()
    {
        $this->render('login.tpl');
    }

    public function login(array $args)
    {
        if (!empty($args['login-form_login']) && !empty($args['login-form_password'])) {
            $module_controller = $this->loadModuleController('login');
            $results = $module_controller->login($args);
            if (!empty($results) && isset($results[0]->id)) {
                $session_status = $module_controller->createUserSession($results[0]->id);
                if ($session_status) {
                    Url::redirectUrl('Index', 'index', array());
                }
            } else {
                // $this->assignAlert('danger', 'Login', 'Wrong user name or password.');
                $this->render('login.tpl');
            }
        } else {
            // $this->assignAlert('warning', 'Login', 'You must enter user login and password.');
            $this->render('login.tpl');
        }
    }

    public function remindMePassword(array $args)
    {

    }

    public function logout($args)
    {
        if (isset($_SESSION['logged_user']['user_id'])) {
            $module_controller = $this->loadModuleController('login');
            $results = $module_controller->destroyUserSession($_SESSION['logged_user']['user_id']);
            if ($results) {
                Url::redirectUrl('Login', 'index', array());
            }
        }
    }
}
