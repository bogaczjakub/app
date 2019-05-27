<?php

class SettingsController extends Page
{
    public function __construct()
    {
    }

    public function index(array $args)
    {
        $this->display('settings');
    }
}