<?php

class ModulesController extends Page
{
    public function __construct()
    { }

    public function index(array $args)
    {
        $tools = new Tools();
        $modules_class = $tools->getControllerClass('Modules');
        $modules_list = $modules_class->getModulesList();
        $this->assignData(array('modules_list' => $modules_list));
        $this->display('modules');
    }
}
