<?php

class ModulesClass
{
    public $modules_list = array();
    public function __construct()
    { }

    public function getModulesList()
    {
        $tools = new Tools();
        $modules_model = $tools->getClassModel('Modules');
        $modules = $modules_model->getAllModules();
        foreach ($modules as $module) {
            $this->modules_list[$module['name']] = array('name' => $module['name'], 'display_name' => $module['display_name'], 'description' => $module['description'], 'author' => $module['author'], 'category_name' => $module['category_name'], 'buttons' => $this->getModuleButtonsList($module['name']));
        }
        return $this->modules_list;
    }

    public function getModuleButtonsList(string $module_name)
    {
        $tools = new Tools();
        $modules_model = $tools->getClassModel('Modules');
        $module_buttons = $modules_model->getModuleButtons($module_name);
        return $module_buttons;
    }
}
