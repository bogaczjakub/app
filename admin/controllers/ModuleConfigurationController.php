<?php

class ModuleConfigurationController extends Page
{
    public function __construct()
    { }

    public function index(array $args)
    {
        $module_configuration = new ModuleConfiguration();
        $configuration = $module_configuration->getModuleConfiguration($args['module_name']);
        $this->assignData(array('configuration' => $configuration, 'module_name' => $args['module_name']));
        $this->display('module_configuration');
    }
}
