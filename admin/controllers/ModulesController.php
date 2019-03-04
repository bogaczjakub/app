<?php

class ModulesController extends Page
{
    public function __construct()
    {
    }

    public function index(array $args)
    {
        $this->display('modules');
    }
}