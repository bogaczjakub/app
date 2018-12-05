<?php

class IndexController extends Page
{
    public function __construct()
    {
        $this->addCss('index.css');

    }

    public function index($args)
    {
        $this->render('index');
    }
}
