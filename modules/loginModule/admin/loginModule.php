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
        global $config;
        $tools = new Tools();
        $login_module = $tools->getModuleController('login', 'login', $config['page']['type']);
        $login_form_action = $login_module->createLoginFormAction();
        $this->assignData(array('login_form_action' => $login_form_action));
        $this->render('login.tpl');
    }

    public function login(array $args)
    {
        if (!empty($args['login_form-login']) && !empty($args['login_form-password'])) {
            global $config;
            $tools = new Tools();
            $login_module = $tools->getModuleController('login', 'login', $config['page']['type']);
            $results = $login_module->login($args);
            if (!empty($results) && isset($results[0]->id)) {
                $session_status = $login_module->createUserSession($results[0]->id);
                if ($session_status) {
                    Url::redirectUrl('Index', 'index', array());
                }
            } else {
                $alerts = new Alerts();
                $alerts->newAlert('danger', 'Login', 'Wrong user name or password.', 'Login');
                Url::redirectUrl('Login', 'index', array());
            }
        } else {
            $alerts = new Alerts();
            $alerts->newAlert('warning', 'Login', 'You must enter user login and password.', 'Login');
            Url::redirectUrl('Login', 'index', array());
        }
    }

    public function remindMePassword(array $args)
    { }

    public function logout($args)
    {
        if (isset($_SESSION['logged_user']['user_id'])) {
            global $config;
            $tools = new Tools();
            $login_controller = $tools->getModuleController('login', 'login', $config['page']['type']);
            $results = $login_controller->destroyUserSession($_SESSION['logged_user']['user_id']);
            if ($results) {
                Url::redirectUrl('Login', 'index', array());
            }
        }
    }
}
