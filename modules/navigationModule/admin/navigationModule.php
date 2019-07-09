<?php

class navigationModule extends Module
{
    public function __construct()
    {
        $this->addCss(array('navigation.css'));
        $this->addJs(array('navigation.js'));
    }

    public function index()
    {
        global $config;
        $tools = new Tools();
        $navigation_controller = $tools->getModuleController('navigation', 'navigation', $config['page']['type']);
        $category_tree = $navigation_controller->buildCategoryTree();
        $this->assignData($category_tree);
        $this->render('navigation.tpl');
    }
}