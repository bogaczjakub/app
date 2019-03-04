<?php

class IndexController extends Page
{
    public function __construct()
    {
        $this->addCss(array('test.css', 'index.css'));

    }

    public function index($args)
    {
        $alerts = new Alerts();
        $alerts->newAlert('danger', 'Dashboard', 'It is alert danger!', 'Index');
        $this->assignData($args);
        $this->display('index');
    }
}
