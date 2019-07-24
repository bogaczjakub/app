<?php

class FooterConfiguration extends ModuleConfiguration
{

    public function __construct()
    { 
        $this->addCss(array('footer_configuration.css'));
        $this->addJs(array('footer_configuration.js'));
    }

    public function index($args)
    {
        $this->assignData(array('args' => $args));
        $this->render('footer_configuration.tpl');
    }
}
