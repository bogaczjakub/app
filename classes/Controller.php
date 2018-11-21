<?php

abstract class Controller
{
    public static $url;
    public static $type;
    public static $theme;
    public static $action;
    public static $args;
    public static $page;
    public static $smarty;
    public static $tpl_vars;
    public static $head_links;
    public static $tpl_js;
    public static $tpl_css;
    public static $theme_index;
    public static $page_details;
    public static $head_links_page;
    public static $global_pages_details;

    public function __construct($theme)
    {
        self::$theme = $theme;
        self::$theme_index = _ROOT_DIR_ . self::$type . DS . 'themes' . DS . self::$theme . DS . 'views' . DS . 'index.tpl';
        self::$smarty = new SmartyApp($theme, self::$type);
    }

    public function pageController($url)
    {
        self::$url = $url;
        self::$page_details = Settings::getPageDetails($url['query']['controller']);
        $page_controller = $url['query']['controller'] . 'Controller';
        self::$page = new $page_controller();
        $this->action();
    }

    public function action()
    {
        array_key_exists('action', self::$url) ? self::$action = self::$url['action'] : self::$action = 'index';
        array_key_exists('args', self::$url) ? self::$args = self::$url['args'] : self::$args = '';
        $methods = get_class_methods(get_class(self::$page));
        if (!empty(self::$action)) {
            foreach ($methods as $method) {
                if ($method == self::$action) {
                    self::$page->$method(self::$args);
                    break;
                }
            }
        }
    }

    public function render($tpl = 'index')
    {
        try {
            if (file_exists(self::$theme_index)) {
                $tpl_path = _ROOT_DIR_ . self::$type . DS . 'themes' . DS . self::$theme . DS . 'views' . DS . $tpl . '.tpl';
                if (file_exists($tpl_path)) {
                    self::$smarty->assign('content', $tpl_path);
                    self::$smarty->assign('global_page_details', self::$global_pages_details);
                    self::$smarty->assign('page_details', self::$page_details);
                    self::$smarty->assign('tpl_vars', self::$tpl_vars);
                    self::$smarty->assign('head_links', self::$head_links);
                    self::$smarty->assign('tpl_js', self::$tpl_js);
                    self::$smarty->assign('tpl_css', self::$tpl_css);
                    self::$smarty->display('index.tpl');
                } else {
                    throw new CustomException('Could not find ' . $tpl . ' template.');
                }
            } else {
                throw new CustomException('Could not find index file of ' . self::$theme . ' theme.');
            }
        } catch (CustomException $e) {
            echo $e->getCustomMessage($e);
        }
    }

    public function assignVars($var_array = array())
    {
        self::$tpl_vars = $var_array;
    }

    public function addTemplateJs()
    {

    }

    public function addTemplateCss()
    {
        
    }
}
