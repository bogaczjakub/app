<?php

class Module
{
    public $module;
    public static $js = array();
    public static $css = array();
    public static $module_args;
    public static $module_smarty;
    public static $modules_array = array();
    public static $current_module;
    public static $silence_flag = 0;

    public function __construct()
    { }

    public function setModule(string $module_name = '')
    {
        $module_name_posfix = $module_name . 'Module';
        try {
            if (Tools::checkExisting($module_name, 'module')) {
                $this->module = new $module_name_posfix();
                self::$current_module = $module_name_posfix;
            } else {
                throw new CustomException('Could not find ' . $module_name_posfix . ' file.');
            }
        } catch (customException $e) {
            $e->getCustomMessage($e);
        }
    }

    public function setTheme()
    {
        self::$module_smarty = $this->initSmarty();
    }

    public function setAction(array $query = array(), string $action = 'index')
    {
        $methods = get_class_methods($this->module);
        if (!empty(Page::$collection['request']['module']['action']) && !empty(Page::$collection['request']['module']['name'])) {
            if (Page::$collection['request']['module']['name'] == strtolower(str_replace('Module', '', self::$current_module))) {
                foreach ($methods as $method) {
                    if ($method == Page::$collection['request']['module']['action']) {
                        $this->module->{$method}(isset(Page::$collection['request']['module']['args']) && (!empty(Page::$collection['request']['module']['args'])) ? Page::$collection['request']['module']['args'] : Page::$collection['request']['query']);
                        break;
                    }
                }
            } else {
                $this->module->index(Page::$collection['request']['query']);
            }
        } else {
            if (array_search('index', $methods)) {
                $this->module->index(Page::$collection['request']['query']);
            }
        }
    }

    public function getGapModules(string $gap)
    {
        if (!empty($gap)) {
            $type = Page::$collection['type'];
            $controller = Page::$collection['request']['controller'];
            $db = new Db();
            return $db->select("global_modules.name")->from("global_modules")->innerJoin("{$type}_modules_associations")->on("global_modules.id={$type}_modules_associations.module_id")->innerJoin("system_gaps")->on("{$type}_modules_associations.gap_id=system_gaps.id")->innerJoin("{$type}_pages")->on("{$type}_modules_associations.page_id={$type}_pages.id")->where("system_gaps.name='{$gap}' AND {$type}_pages.controller='{$controller}'")->execute('assoc');
        }
    }

    public function getModule(array $modules)
    {
        global $config;
        self::$modules_array = array();
        foreach ($modules as $module) {
            if (isset($module['name']) && !empty($module['name'])) {
                if (in_array($module['name'], $config['modules'])) {
                    self::$silence_flag = 0;
                    $this->setModule($module['name']);
                    $this->setTheme();
                    $this->setAction();
                }
            }
        }
        if (!empty($modules)) {
            return self::$modules_array;
        }
    }

    public function initSmarty()
    {
        return new SmartyApp(Page::$collection['theme'], Page::$collection['type'], true, false, self::$current_module);
    }

    public function render(string $template = 'index')
    {
        $this->preRenderActions();
        if (!self::$silence_flag) {
            $fetch = self::$module_smarty->fetch($template);
            self::$modules_array[self::$current_module] = $fetch;
        }
        $this->postRenderActions();
    }

    public function preRenderActions()
    {
        if (!self::$silence_flag) {
            $this->addHeadLinks();
        }
    }

    public function postRenderActions()
    { }

    public function assignData($data)
    {
        if (!empty($data)) {
            self::$module_smarty->assign('template_data', $data);
        }
    }

    public function assignAlert(string $type, string $title, string $message)
    {
        $alert_array = compact('type', 'title', 'message');
        if (!empty($alert_array['type']) && !empty($alert_array['message'])) {
            $alerts = new Alerts($alert_array);
            array_push(Page::$collection['alerts'], $alerts->getAlert());
        }
    }

    public function addJs($js)
    {
        if (!empty($js)) {
            if (is_string($js)) {
                array_push(self::$js, $js);
            } elseif (is_array($js)) {
                foreach ($js as $element) {
                    if (preg_match('/^.+\.js$/', $element, $matches)) {
                        array_push(self::$js, $element);
                    }
                }
            } else {
                return false;
            }
        }
    }

    public function addCss($css)
    {
        if (!empty($css)) {
            if (is_string($css)) {
                array_push(self::$css, $css);
            } elseif (is_array($css)) {
                foreach ($css as $element) {
                    if (preg_match('/^.+\.css$/', $element)) {
                        array_push(self::$css, $element);
                    }
                }
            } else {
                return false;
            }
        }
    }

    private function addHeadLinks()
    {
        $tools = new Tools();
        Page::$collection['head_links'] = $tools->modulesHeadLinks(Page::$collection['head_links'], self::$css, Page::$collection['type'], Page::$collection['theme'], 'css', self::$current_module);
        Page::$collection['head_links'] = $tools->modulesHeadLinks(Page::$collection['head_links'], self::$js, Page::$collection['type'], Page::$collection['theme'], 'js', self::$current_module);
    }

    public function getSilenceModulesForPage(string $controller)
    {
        if (!empty($controller)) {
            $tools = new Tools();
            $type = Page::$collection['type'];
            return $tools->getPageSilenceModules($controller, $type);
        }
    }

    public function runSilenceModules()
    {
        $controller = Page::$collection['request']['controller'];
        $silence_modules =  $this->getSilenceModulesForPage($controller);
        if (!empty($silence_modules)) {
            foreach ($silence_modules as $module) {
                self::$silence_flag = 1;
                $this->setModule($module['name']);
                $this->setTheme();
                $this->setAction();
            }
        }
    }

    public function getModuleSettingsTable(string $table_name, array $columns, array $args)
    {
        $db = new Db();
        $new_table[$table_name] = array();
        if (!empty($columns)) {
            $select = implode(',', $columns);
        } else {
            $select = 'modules_settings_values.pointer_id,data_type,name,display_name,setting_value,is_active,information,is_active,last_update';
        }
        $require_table = $db->select("{$select}")->from("{$table_name}")->innerJoin("modules_settings_pointers")->on("{$table_name}.pointer_id=modules_settings_pointers.id")->innerJoin("modules_settings_values")->on("modules_settings_pointers.id=modules_settings_values.pointer_id")->orderBy("pointer_id,data_type")->execute("assoc");
        if (!empty($require_table)) {
            foreach ($require_table as $key => $row) {
                $pointer = $row['pointer_id'];
                if (array_key_exists($pointer, $new_table[$table_name]) && ($row['data_type'] == 'select' || $row['data_type'] == 'radio')) {
                    if (!isset($new_table[$table_name][$pointer]['multiple_fields'])) {
                        $new_table[$table_name][$pointer]['multiple_fields'] = array();
                    }
                    if ($row['is_active'] == 1) {
                        $new_table[$table_name][$pointer]['setting_value'] = $row['setting_value'];
                    }
                    array_push($new_table[$table_name][$pointer]['multiple_fields'], $row['setting_value']);
                } elseif (array_key_exists($pointer, $new_table[$table_name]) && ($row['data_type'] == 'multiple' || $row['data_type'] == 'checkbox')) {
                    if (!isset($new_table[$table_name][$pointer]['multiple_fields'])) {
                        $new_table[$table_name][$pointer]['multiple_fields'] = array();
                    }
                    if ($row['is_active'] == 1) {
                        array_push($new_table[$table_name][$pointer]['setting_value'], $row['setting_value']);
                    }
                    array_push($new_table[$table_name][$pointer]['multiple_fields'], $row['setting_value']);
                } else {
                    if ($row['data_type'] == 'multiple' || $row['data_type'] == 'checkbox' || $row['data_type'] == 'select' || $row['data_type'] == 'radio') {
                        if ($row['data_type'] != 'radio') {
                            $new_table[$table_name][$pointer]['setting_value'] = array();
                        } else {
                            $new_table[$table_name][$pointer]['setting_value'] = '';
                        }
                        if ($row['is_active'] == 1 && $row['data_type'] != 'radio') {
                            array_push($new_table[$table_name][$pointer]['setting_value'], $row['setting_value']);
                        } elseif ($row['data_type'] == 'radio') {
                            $new_table[$table_name][$pointer]['setting_value'] = $row['setting_value'];
                        }
                        if (!isset($new_table[$table_name][$pointer]['multiple_fields'])) {
                            $new_table[$table_name][$pointer]['multiple_fields'] = array();
                        }
                        array_push($new_table[$table_name][$pointer]['multiple_fields'], $row['setting_value']);
                    } else {
                        $new_table[$table_name][$pointer]['setting_value'] = ($row['is_active'] == 1) ? $row['setting_value'] : '';
                    }
                    $new_table[$table_name][$pointer]['setting_name'] = $row['name'];
                    $new_table[$table_name][$pointer]['setting_display_name'] = $row['display_name'];
                    $new_table[$table_name][$pointer]['setting_type'] = $row['data_type'];
                    $new_table[$table_name][$pointer]['setting_information'] = $row['information'];
                    $new_table[$table_name][$pointer]['setting_last_update'] = $row['last_update'];
                }
            }
        }
        return $new_table;
    }

    public function changeSettings(string $form_name, array $form_args)
    {
        $db = new Db();
        $tools = new Tools();
        $check_results = array();
        if (!empty($form_args) && is_array($form_args) && !empty($form_name)) {
            foreach ($form_args as $setting_name => $values) {
                $input_components = explode('-', $setting_name);
                $check_results[$input_components[1]]['update_status'] = 0;
                if ($input_components[2] == 'multiple' || $input_components[2] == 'checkbox') {
                    $in_values = '';
                    foreach ($values as $key => $value) {
                        $value = $tools->cleanInput($value, $input_components[2]);
                        $in_values .= "'$value'";
                        if ($value != end($values)) {
                            $in_values .= ',';
                        }
                    }
                    $results = $db->update("modules_settings_values")->innerJoin("modules_settings_pointers")->on("modules_settings_values.pointer_id=modules_settings_pointers.id")->innerJoin("{$form_name}")->on("modules_settings_pointers.id={$form_name}.pointer_id")->set("is_active=CASE WHEN setting_value IN({$in_values}) THEN 1 ELSE 0 END")->where("{$input_components[0]}.name='{$input_components[1]}'")->execute("bool");
                    $check_results[$input_components[1]]['update_status'] = $results[0];
                } elseif ($input_components[2] == 'text' || $input_components[2] == 'string') {
                    $values = $tools->cleanInput($values, $input_components[2]);
                    $results = $db->update("modules_settings_values")->innerJoin("modules_settings_pointers")->on("modules_settings_values.pointer_id=modules_settings_pointers.id")->innerJoin("{$form_name}")->on("modules_settings_pointers.id={$form_name}.pointer_id")->set("is_active=CASE WHEN {$form_name}.name = '{$input_components[1]}' THEN 1 ELSE 0 END,setting_value='$values'")->where("{$input_components[0]}.name='{$input_components[1]}'")->execute("bool");
                    $check_results[$input_components[1]]['update_status'] = $results[0];
                } elseif ($input_components[2] == 'boolean') {
                    $values = $tools->cleanInput($values, $input_components[2]);
                    $results = $db->update("modules_settings_values")->innerJoin("modules_settings_pointers")->on("modules_settings_values.pointer_id=modules_settings_pointers.id")->innerJoin("{$form_name}")->on("modules_settings_pointers.id={$form_name}.pointer_id")->set("is_active='1',setting_value='{$values}'")->where("{$input_components[0]}.name='{$input_components[1]}'")->execute("bool");
                    $check_results[$input_components[1]]['update_status'] = $results[0];
                } elseif ($input_components[2] == 'select' || $input_components[2] == 'radio') {
                    $values = $tools->cleanInput($values, $input_components[2]);
                    $results = $db->update("modules_settings_values")->innerJoin("modules_settings_pointers")->on("modules_settings_values.pointer_id=modules_settings_pointers.id")->innerJoin("{$form_name}")->on("modules_settings_pointers.id={$form_name}.pointer_id")->set("is_active=CASE WHEN setting_value='{$values}' THEN 1 ELSE 0 END")->where("{$input_components[0]}.name='{$input_components[1]}'")->execute("bool");
                    $check_results[$input_components[1]]['update_status'] = $results[0];
                }
            }
            return $check_results;
        }
    }
}
