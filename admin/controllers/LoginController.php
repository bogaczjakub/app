<?php

class LoginController extends Page
{
    public function __construct()
    {
        $this->addJs('login.js');
        $this->addCss('login.css');
    }

    public function index($args)
    {
        $this->render('login');
    }

    public function login($args)
    {
        $object = new Model();
        $model = $object->getPageModel(__CLASS__);
        $results = $model->login($args);
        if (!empty($results) && isset($results[0]->id)) {
            $this->assignVariables();
            header('Location: ?controller=Index&action=index');
            // $this->render('index');
        }
    }

    public function remindMePassword()
    {
        # code...
    }
}
