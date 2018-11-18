<?php

class Settings
{
    public static $Db;

    public function __construct()
    {
        self::$Db = new Db();
    }

    public function getGlobalSettings()
    {
        // return $this->Db->customQuery('select * from global_settings')->execute('assoc');
    }

    public function getAdminSettings()
    {
        return self::$Db->select('*')->from('admin_settings')->execute('assoc');
    }

    public function getFrontSettings()
    {
        return self::$Db->select('*')->from('front_settings')->execute('assoc');
    }

    public static function getSettingValue($array, $setting_name)
    {
        $key = array_search($setting_name, array_column($array, 'setting_name'));
        return $array[$key]['setting_value'];
    }

    public static function getPageDetails($page)
    {
        return self::$Db->select('*')->from('admin_pages')->where("title = '" . $page . "'")->execute('row');
    }

}
