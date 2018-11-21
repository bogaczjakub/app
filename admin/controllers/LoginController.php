<?php

class LoginController extends AdminController
{
    public function __construct()
    {

    }

    public function index($args)
    {
        $this->assignVars(array('var_1' => 'aaaaaaaa', 'var_2' => 'bbbbbbbbbbbbb'));
        $this->render('login');
    }

    public function signIn()
    {
        # code...
    }

    public function signUp()
    {
        # code...
    }
}
