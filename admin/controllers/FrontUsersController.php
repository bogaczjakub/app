<?php

class FrontUsersController extends Page
{
    public function __construct()
    {
    }

    public function index(array $args)
    {
        $this->display('front_users');
    }
}