<?php

class footerModule extends Module
{
    public function __construct()
    {
        $this->addCss(array('footer.css'));
        $this->addJs(array('footer.js'));
    }

    public function index()
    {
        global $config;
        $tools = new Tools();
        $module_controller = $tools->getModuleController('footer', 'footer', $config['page']['type']);
        $this->render('footer.tpl');
    }
}
