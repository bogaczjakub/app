<?php

class TestController extends Page
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