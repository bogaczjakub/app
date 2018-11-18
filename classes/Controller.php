<?php

abstract class Controller
{
    public static $type;
    private static $smarty;
    public static $page_details;

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
        var_dump(self::$page_details);
        self::$smarty->display($tpl . '.tpl');
    }

    public function assingVars()
    {

    }

    public function templateScheme($page)
    {

    }
}
