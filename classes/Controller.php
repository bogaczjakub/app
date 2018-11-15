<?php

abstract class Controller
{
    public static $theme;
    public static $type;
    private static $smarty;

    public function __construct($_theme, $_type)
    {
        self::$smarty = new SmartyApp($_theme, $_type);  
    }

    public function controller($controller)
    {

    }

    public function action($action)
    {

    }

    public function render($tpl = 'index')
    {
        self::$smarty->display($tpl . '.tpl');
        
    }

    public function getPageInfo($page)
    {

    }
}
