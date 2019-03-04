<?php

class PagesController extends Page
{
    public function __construct()
    {

    }

    public function index($args)
    {
        $this->display('pages');
    }
}