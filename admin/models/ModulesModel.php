<?php

class ModulesModel
{
    public $module_buttons = array('configuration' => '', 'settings' => '');
    public function __construct()
    { }

    public function getAllModules()
    {
        $db = new Db();
        return $db->select("global_modules.name, global_modules.display_name, global_modules.status, global_modules.description, global_modules.author, system_modules_categories.name as category_name")->from("global_modules")->innerJoin("system_modules_categories")->on("global_modules.category=system_modules_categories.id")->execute("assoc");
    }

    public function getModuleButtons(string $module_name)
    {
        $db = new Db();
        if (!empty($module_name)) {
            $module_settings_table = 'module_' . $module_name . '_settings';
            $settings_table = $db->select("*")->from("information_schema.tables")->where("table_schema='app' AND table_name='{$module_settings_table}'")->execute("assoc");
            $module_configuration = Tools::isModuleConfigurationExist($module_name);
            if (!empty($settings_table)) {
                $this->module_buttons['settings'] = Url::buildPageUrl("ModuleSettings", "index", array('module_name' => $module_name));
            }
            if (!empty($module_configuration)) {
                $this->module_buttons['configuration'] = Url::buildPageUrl("ModuleConfiguration", "index", array('module_name' => $module_name));
            }
        }
        return $this->module_buttons;
    }
}
