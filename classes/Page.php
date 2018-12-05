<?php

class Page
{
    private $settings;
    private $css;
    private $js;
    private $model;
    public static $url;
    public static $type;
    public static $theme;
    public static $action;
    public static $args;
    public static $page;
    public static $smarty;
    public static $template_vars;
    public static $template_js;
    public static $template_css;
    public static $theme_index;
    public static $page_details;
    public static $page_model;
    public static $global_pages_details;
    public static $head_links = array('js' => array(), 'css' => array());

    public function __construct($global_pages_details = array(), $type = '', $theme = '', $url = array())
    {
        if (empty(self::$global_pages_details)) {
            self::$global_pages_details = $global_pages_details;
        }
        if (empty(self::$type)) {
            self::$type = $type;
        }
        if (empty(self::$theme)) {
            self::$theme = $theme;
        }
        if (empty(self::$url)) {
            self::$url = $url;
        }

    }

    public function init()
    {
        self::$theme_index = _ROOT_DIR_ . self::$type . DS . 'themes' . DS . self::$theme . DS . 'views' . DS . 'Index.tpl';
        self::$smarty = new SmartyApp(self::$theme, self::$type);
        $this->getPage();
    }

    public function getPage()
    {
        $this->settings = new Settings();
        self::$page_details = $this->settings->getPageDetails(self::$url['controller']);
        $page_controller = self::$url['controller'] . 'Controller';
        self::$page = new $page_controller();
        $this->action();
    }

    public function action()
    {
        array_key_exists('action', self::$url) && !empty(self::$url['action']) ? self::$action = self::$url['action'] : self::$action = 'index';
        array_key_exists('query', self::$url) && !empty(self::$url['query'])? self::$args = self::$url['query'] : self::$args = array();
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

    public function render($template = 'index')
    {
        try {
            if (file_exists(self::$theme_index)) {
                $template_path = _ROOT_DIR_ . self::$type . DS . 'themes' . DS . self::$theme . DS . 'views' . DS . $template . '.tpl';
                if (file_exists($template_path)) {
                    $this->addHeadLinks();
                    self::$smarty->assign('content', $template_path);
                    self::$smarty->assign('global_page_details', self::$global_pages_details);
                    self::$smarty->assign('page_details', self::$page_details);
                    self::$smarty->assign('template_vars', self::$template_vars);
                    self::$smarty->assign('head_links', self::$head_links);
                    self::$smarty->display(self::$theme_index);
                } else {
                    throw new CustomException('Could not find ' . $template . ' template.');
                }
            } else {
                throw new CustomException('Could not find index file of ' . self::$theme . ' theme.');
            }
        } catch (CustomException $e) {
            echo $e->getCustomMessage($e);
        }
    }

    public function assignVariables($variables_array = array())
    {
        self::$template_vars = $variables_array;
    }

    public function addJs($js)
    {
        if (!empty($js)) {
            $this->js = $js;
        }
    }

    public function addCss($css)
    {
        if (!empty($css)) {
            $this->css = $css;
        }
    }

    private function addHeadLinks()
    {
        $tools = new Tools();
        self::$head_links = $tools->addLibraries(self::$head_links, self::$type);
        self::$head_links = $tools->themeHeadLinks(self::$head_links, self::$type, self::$theme);
        self::$head_links = $tools->templateHeadLinks(self::$head_links, $this->css, self::$type, self::$theme, 'css');
        self::$head_links = $tools->templateHeadLinks(self::$head_links, $this->js, self::$type, self::$theme, 'js');
    }
}
