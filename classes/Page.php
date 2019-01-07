<?php

class Page
{
    public $page;
    public static $js = array();
    public static $css = array();
    public static $smarty;
    public static $collection;
    public static $module;
    public static $passive;

    public function __construct(array $collection = array(), bool $passive = true)
    {
        self::$collection = $collection;
        self::$passive = $passive;
        if (self::$passive) {
            $this->setTheme();
        }
    }

    public function setTheme(string $type = 'front', string $theme = 'default')
    {
        if (!self::$passive) {
            self::$collection['type'] = $type;
            self::$collection['theme'] = $theme;
        }
        self::$collection['theme_index'] = _ROOT_DIR_ . self::$collection['type'] . DS . 'themes' . DS . self::$collection['theme'] . DS . 'views' . DS . 'Index.tpl';
        if (self::$passive) {
            $this->setPage();
        }
    }

    public function setPage(string $controller = 'Index')
    {
        $settings = new Settings();
        if (!self::$passive) {
            self::$collection['request']['controller'] = $controller;
        }
        self::$collection['page_details'] = $settings->getPageDetails(self::$collection['request']['controller']);
        $page_controller = self::$collection['request']['controller'] . 'Controller';
        $this->page = new $page_controller();
        if (self::$passive) {
            $this->setAction();
        }
    }

    public function setAction(array $query = array(), string $action = 'index')
    {
        if (self::$passive) {
            array_key_exists('action', self::$collection['request']) && !empty(self::$collection['request']['action']) ? self::$collection['request']['action'] = self::$collection['request']['action'] : self::$collection['request']['action'] = 'index';
            array_key_exists('query', self::$collection['request']) && !empty(self::$collection['request']['query']) ? self::$collection['request']['query'] = self::$collection['request']['query'] : self::$collection['request']['query'] = array();
        } else {
            self::$collection['request']['action'] = $action;
            self::$collection['request']['query'] = $query;
        }
        $methods = get_class_methods(get_class($this->page));
        if (!empty(self::$collection['request']['action'])) {
            foreach ($methods as $method) {
                if ($method == self::$collection['request']['action']) {
                    $this->page->{$method}(self::$collection['request']['query']);
                    break;
                }
            }
        }
    }

    public function initSmarty()
    {
        self::$smarty = new SmartyApp(self::$collection['theme'], self::$collection['type']);
    }

    public function render(string $template = 'index')
    {
        $this->preRenderActions();
        self::$module = new Module(self::$collection);
        $tools = new Tools();
        try {
            if (file_exists(self::$collection['theme_index'])) {
                $template_path = _ROOT_DIR_ . self::$collection['type'] . DS . 'themes' . DS . self::$collection['theme'] . DS . 'views' . DS . $template . '.tpl';
                if (file_exists($template_path)) {
                    self::$smarty->assign('head_gap', self::$module->getModule($tools->getGapModules('head_gap')));
                    self::$smarty->assign('header_gap', self::$module->getModule($tools->getGapModules('header_gap')));
                    self::$smarty->assign('left_column_gap', self::$module->getModule($tools->getGapModules('left_column_gap')));
                    self::$smarty->assign('right_column_gap', self::$module->getModule($tools->getGapModules('right_column_gap')));
                    self::$smarty->assign('top_gap', self::$module->getModule($tools->getGapModules('top_gap')));
                    self::$smarty->assign('center_column_gap', self::$module->getModule($tools->getGapModules('center_column_gap')));
                    self::$smarty->assign('bottom_gap', self::$module->getModule($tools->getGapModules('bottom_gap')));
                    self::$smarty->assign('footer_gap', self::$module->getModule($tools->getGapModules('footer_gap')));
                    self::$smarty->assign('content', $template_path);
                    self::$smarty->assign('global_page_details', self::$collection['global_pages_details']);
                    self::$smarty->assign('page_details', self::$collection['page_details']);
                    self::$smarty->assign('template_data', self::$collection['template_data']);
                    self::$smarty->assign('head_links', self::$collection['head_links']);
                    self::$smarty->assign('alerts', self::$collection['alerts']);
                    self::$smarty->assign('breadcrumbs', self::$collection['breadcrumbs']);
                    self::$smarty->display(self::$collection['theme_index']);
                } else {
                    throw new CustomException('Could not find ' . $template . ' template.');
                }
            } else {
                throw new CustomException('Could not find index file of ' . self::$collection['theme'] . ' theme.');
            }
        } catch (CustomException $e) {
            echo $e->getCustomMessage($e);
        }
        $this->postRenderActions();
    }

    public function display(string $template = 'index')
    {
        if (self::$passive) {
            $this->render($template);
        }
    }

    public function preRenderActions()
    {
        $this->addHeadLinks();
        $this->initSmarty();
        $this->buildBreadcrumbs();
    }

    public function postRenderActions()
    {

    }

    public function assignData($data)
    {
        if (!empty($data)) {
            self::$collection['template_data'] = $data;
        }
    }

    public function assignAlert(string $type, string $title, string $message)
    {
        $alert_array = compact('type', 'title', 'message');
        if (!empty($alert_array['type']) && !empty($alert_array['message'])) {
            $alert = new Alert($alert_array);
            array_push(self::$collection['alerts'], $alert->getAlert());
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
        self::$collection['head_links'] = $tools->addLibraries(self::$collection['head_links'], self::$collection['type']);
        self::$collection['head_links'] = $tools->themeHeadLinks(self::$collection['head_links'], self::$collection['type'], self::$collection['theme']);
        self::$collection['head_links'] = $tools->templateHeadLinks(self::$collection['head_links'], self::$css, self::$collection['type'], self::$collection['theme'], 'css');
        self::$collection['head_links'] = $tools->templateHeadLinks(self::$collection['head_links'], self::$js, self::$collection['type'], self::$collection['theme'], 'js');
    }

    public function buildBreadcrumbs()
    {
        $breadcrumbs = new Breadcrumbs();
        self::$collection['breadcrumbs'] = $breadcrumbs->build(self::$collection['request']['controller'], self::$collection['type']);
    }
}
