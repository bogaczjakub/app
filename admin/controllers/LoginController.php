<?php

class LoginController extends Page
{
    public function __construct()
    {
        $this->addJs('login.js');
        $this->addCss('login.css');
    }

    public function index(array $args)
    {
        $this->render('login');
    }

    public function login(array $args)
    {
        if (!empty($args)) {
            $model = new Model();
            $login_model = $model->getPageModel(__CLASS__);
            $results = $login_model->login($args);
            if (!empty($results) && isset($results[0]->id)) {
                Url::redirectUrl('Index', 'index', array());
            }
        } else {
            Url::redirectUrl('Login', 'index');
        }
    }

    public function remindMePassword()
    {
        # code...
    }
}
