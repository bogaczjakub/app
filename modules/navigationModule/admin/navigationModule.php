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
        $module_controller = $this->loadModuleController('navigation');
        $category_tree = $module_controller->buildCategoryTree();
        $this->assignData($category_tree);
        $this->render('navigation.tpl');
    }
}