<?php

class IndexController extends Page
{
    public function __construct()
    {
        $this->addCss(array('test.css', 'index.css'));

    }

    public function index($args)
    {
        $this->assignAlert('success', 'It is alert!', 'Thank You for submitted alert message.');
        $this->assignAlert('danger', 'It is alert danger!', 'Thank You for submitted alert message.');
        $this->assignData($args);
        $this->display('index');
    }
}
