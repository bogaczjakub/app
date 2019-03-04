<?php

class LoginController extends Page
{
    public function __construct()
    {
    }

    public function index(array $args)
    {
        $this->display('login');
    }
}
