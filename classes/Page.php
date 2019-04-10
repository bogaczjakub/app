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
        self::$collection['page_details'] = $settings->getPageDetails(self::$collection['request']['controller'], self::$collection['type']);
        $page_controller = self::$collection['request']['controller'] . 'Controller';
        $this->page = new $page_controller();
        if (self::$passive) {
            $this->setAction();
        }
    }
    
    public function setAction(array $query = array(), string $action = 'index')
    {
        if (self::$passive) {
            array_key_exists('action', self::$collection['request']) && (!empty(self::$collection['request']['action'])) ? self::$collection['request']['action'] = self::$collection['request']['action'] : self::$collection['request']['action'] = 'index';
            array_key_exists('query', self::$collection['request']) && (!empty(self::$collection['request']['query'])) ? self::$collection['request']['query'] = self::$collection['request']['query'] : self::$collection['request']['query'] = array();
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
        $tools = new Tools();
        self::$module = new Module(self::$collection);
        try {
            if (file_exists(self::$collection['theme_index'])) {
                $template_path = _ROOT_DIR_ . self::$collection['type'] . DS . 'themes' . DS . self::$collection['theme'] . DS . 'views' . DS . $template . '.tpl';
                if (file_exists($template_path)) {
                    self::$smarty->assign('head_gap', self::$module->getModule(self::$module->getGapModules('head_gap')));
                    self::$smarty->assign('header_gap', self::$module->getModule(self::$module->getGapModules('header_gap')));
                    self::$smarty->assign('left_column_gap', self::$module->getModule(self::$module->getGapModules('left_column_gap')));
                    self::$smarty->assign('right_column_gap', self::$module->getModule(self::$module->getGapModules('right_column_gap')));
                    self::$smarty->assign('top_gap', self::$module->getModule(self::$module->getGapModules('top_gap')));
                    self::$smarty->assign('center_column_gap', self::$module->getModule(self::$module->getGapModules('center_column_gap')));
                    self::$smarty->assign('bottom_gap', self::$module->getModule(self::$module->getGapModules('bottom_gap')));
                    self::$smarty->assign('footer_gap', self::$module->getModule(self::$module->getGapModules('footer_gap')));
                    self::$smarty->assign('content', $template_path);
                    self::$smarty->assign('global_details', self::$collection['global_details']);
                    self::$smarty->assign('page_details', self::$collection['page_details']);
                    self::$smarty->assign('pages_gaps_allowed', $this->getPageGapsAllowed(self::$collection['page_details']));
                    self::$smarty->assign('template_data', self::$collection['template_data']);
                    self::$smarty->assign('head_links', self::$collection['head_links']);
                    self::$smarty->assign('alerts', self::$collection['alerts']);
                    self::$smarty->assign('breadcrumbs', self::$collection['breadcrumbs']);
                    self::$smarty->assign('page_forms', self::$collection['page_forms']);
                    self::$smarty->assign('page_url', Url::$page_url);
                    self::$smarty->assign('session', $_SESSION);
                    if (isset(self::$collection['request']['ajax']) && self::$collection['request']['ajax'] == true) {
                        self::$smarty->display($template_path);
                    }else {
                        self::$smarty->display(self::$collection['theme_index']);
                    }
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

    public function display(string $template)
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
        $this->getAlerts();
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

    private function getAlerts()
    {
        $alerts = new Alerts();
        $page_alerts = $alerts->getAlerts(self::$collection['request']['controller']);
        if (!empty($page_alerts)) {
            foreach ($page_alerts as $alert_key =>$alert) {
                $page_alerts[$alert_key]['alerts_message'] = htmlspecialchars_decode($alert['alerts_message']);
            }
        }
        array_push(self::$collection['alerts'], $page_alerts);
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

    private function getPageGapsAllowed(array $page_details)
    {
        if (!empty($page_details)) {
            $return_array = array();
            $page_gaps = explode(',', $page_details[0]['pages_gaps_allowed']);
            foreach ($page_gaps as $key => $value) {
                $return_array[$value] = 1;
            }
            return $return_array;
        }
    }

    public function buildBreadcrumbs()
    {
        $breadcrumbs = new Breadcrumbs();
        self::$collection['breadcrumbs'] = $breadcrumbs->build(self::$collection['request']['controller'], self::$collection['type']);
    }
}
