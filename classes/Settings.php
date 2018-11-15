<?php

class Settings
{
    private $Db;

    public function __construct()
    {
        $this->Db = new Db();
    }

    public function getGlobalSettings()
    {
        // return $this->Db->customQuery('select * from global_settings')->execute('assoc');
    }

    public function getAdminSettings()
    {
        return $this->Db->customQuery('select * from admin_settings')->execute('assoc');
    }

    public function getFrontSettings()
    {
        return $this->Db->customQuery('select * from front_settings')->execute('assoc');
    }

    public static function getSettingValue($array, $setting_name)
    {
        $key = array_search($setting_name, array_column($array, 'setting_name'));
        return $array[$key]['setting_value'];
    }

}
