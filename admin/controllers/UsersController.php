<?php

class UsersController extends Page
{
    public function __construct()
    {
    }

    public function index($args)
    {
        $this->assignData($args);
        $this->display('test');
    }
}