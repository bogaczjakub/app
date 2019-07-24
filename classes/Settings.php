<?php

class Settings
{
    public static $db;

    public function __construct()
    { }

    public function getGlobalSettings()
    {
        $db = new Db();
        return $db->select("system_settings_values.pointer_id,data_type,name,display_name,setting_value,is_active,information,is_active,last_update")->from("global_settings")->innerJoin("system_settings_pointers")->on("global_settings.pointer_id=system_settings_pointers.id")->innerJoin("system_settings_values")->on("system_settings_pointers.id=system_settings_values.pointer_id")->where("system_settings_values.is_active='1'")->orderBy("pointer_id,data_type")->execute("assoc");
    }

    public function getAdminSettings()
    {
        $db = new Db();
        return $db->select("system_settings_values.pointer_id,data_type,name,display_name,setting_value,is_active,information,is_active,last_update")->from("admin_settings")->innerJoin("system_settings_pointers")->on("admin_settings.pointer_id=system_settings_pointers.id")->innerJoin("system_settings_values")->on("system_settings_pointers.id=system_settings_values.pointer_id")->where("system_settings_values.is_active='1'")->orderBy("pointer_id,data_type")->execute("assoc");
    }

    public function getFrontSettings()
    {
        $db = new Db();
        return $db->select("system_settings_values.pointer_id,data_type,name,display_name,setting_value,is_active,information,is_active,last_update")->from("front_settings")->innerJoin("system_settings_pointers")->on("front_settings.pointer_id=system_settings_pointers.id")->innerJoin("system_settings_values")->on("system_settings_pointers.id=system_settings_values.pointer_id")->where("system_settings_values.is_active='1'")->orderBy("pointer_id,data_type")->execute("assoc");
    }

    public function getSettingValue(array $settings_array, string $setting_name)
    {
        $key = array_search($setting_name, array_column($settings_array, 'name'));
        return $settings_array[$key]['setting_value'];
    }

    public function getPageDetails(string $page, string $type)
    {
        $db = new Db();
        return $db->select("*")->from("{$type}_pages")->where("controller='{$page}'")->execute("assoc");
    }

    public function fillGlobalDetails(array $settings_array)
    {
        $array = array();
        $array['site_language'] = $this->getSettingValue($settings_array, 'site_language');
        $array['site_author'] = $this->getSettingValue($settings_array, 'site_author');
        $array['base_url'] = $this->getSettingValue($settings_array, 'base_url');
        $array['images_directory'] = $this->getSettingValue($settings_array, 'images_directory');
        return $array;
    }

    public function getSettingsTable(string $table_name, array $columns, array $args)
    {
        $db = new Db();
        $new_table[$table_name] = array();
        if (!empty($columns)) {
            $select = implode(',', $columns);
        } else {
            $select = 'system_settings_values.pointer_id,data_type,name,display_name,setting_value,is_active,information,is_active,last_update';
        }
        $require_table = $db->select("{$select}")->from("{$table_name}")->innerJoin("system_settings_pointers")->on("{$table_name}.pointer_id=system_settings_pointers.id")->innerJoin("system_settings_values")->on("system_settings_pointers.id=system_settings_values.pointer_id")->orderBy("pointer_id,data_type")->execute("assoc");
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
                    $results = $db->update("system_settings_values")->innerJoin("system_settings_pointers")->on("system_settings_values.pointer_id=system_settings_pointers.id")->innerJoin("{$form_name}")->on("system_settings_pointers.id={$form_name}.pointer_id")->set("is_active=CASE WHEN setting_value IN({$in_values}) THEN 1 ELSE 0 END")->where("{$input_components[0]}.name='{$input_components[1]}'")->execute("bool");
                    $check_results[$input_components[1]]['update_status'] = $results[0];
                } elseif ($input_components[2] == 'text' || $input_components[2] == 'string') {
                    $values = $tools->cleanInput($values, $input_components[2]);
                    $results = $db->update("system_settings_values")->innerJoin("system_settings_pointers")->on("system_settings_values.pointer_id=system_settings_pointers.id")->innerJoin("{$form_name}")->on("system_settings_pointers.id={$form_name}.pointer_id")->set("is_active=CASE WHEN {$form_name}.name = '{$input_components[1]}' THEN 1 ELSE 0 END,setting_value='$values'")->where("{$input_components[0]}.name='{$input_components[1]}'")->execute("bool");
                    $check_results[$input_components[1]]['update_status'] = $results[0];
                } elseif ($input_components[2] == 'boolean') {
                    $values = $tools->cleanInput($values, $input_components[2]);
                    $results = $db->update("system_settings_values")->innerJoin("system_settings_pointers")->on("system_settings_values.pointer_id=system_settings_pointers.id")->innerJoin("{$form_name}")->on("system_settings_pointers.id={$form_name}.pointer_id")->set("is_active='1',setting_value='{$values}'")->where("{$input_components[0]}.name='{$input_components[1]}'")->execute("bool");
                    $check_results[$input_components[1]]['update_status'] = $results[0];
                } elseif ($input_components[2] == 'select' || $input_components[2] == 'radio') {
                    $values = $tools->cleanInput($values, $input_components[2]);
                    $results = $db->update("system_settings_values")->innerJoin("system_settings_pointers")->on("system_settings_values.pointer_id=system_settings_pointers.id")->innerJoin("{$form_name}")->on("system_settings_pointers.id={$form_name}.pointer_id")->set("is_active=CASE WHEN setting_value='{$values}' THEN 1 ELSE 0 END")->where("{$input_components[0]}.name='{$input_components[1]}'")->execute("bool");
                    $check_results[$input_components[1]]['update_status'] = $results[0];
                }
            }
            return $check_results;
        }
    }
}
