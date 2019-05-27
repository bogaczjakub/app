<?php

class Module
{
    public $module;
    public static $js = array();
    public static $css = array();
    public static $collection;
    public static $module_args;
    public static $module_smarty;
    public static $modules_array = array();
    public static $current_module;
    public static $silence_flag = 0;

    public function __construct(array $collection)
    {
        self::$collection = $collection;
    }

    public function setModule(string $module_name = '')
    {
        $module_name_posfix = $module_name . 'Module';
        try {
            if (Tools::checkExisting($module_name_posfix, 'module')) {
                $this->module = new $module_name_posfix();
                self::$current_module = $module_name_posfix;
            } else {
                throw new CustomException('Could not find ' . $module_name_posfix . ' file.');
            }
        } catch (customException $e) {
            $e->getCustomMessage($e);
        }
    }

    public function setTheme(string $type = 'front', string $theme = 'default')
    {
        self::$collection['type'] = $type;
        self::$collection['theme'] = $theme;
        self::$module_smarty = $this->initSmarty(self::$current_module);
    }

    public function setAction(array $query = array(), string $action = 'index')
    {
        $methods = get_class_methods($this->module);
        if (!empty(self::$collection['request']['module']['action']) && !empty(self::$collection['request']['module']['name'])) {
            if (self::$collection['request']['module']['name'] == strtolower(str_replace('Module', '', self::$current_module))) {
                foreach ($methods as $method) {
                    if ($method == self::$collection['request']['module']['action']) {
                        $this->module->{$method}(isset(self::$collection['request']['module']['args']) && (!empty(self::$collection['request']['module']['args'])) ? self::$collection['request']['module']['args'] : self::$collection['request']['query']);
                        break;
                    }
                }
            } else {
                $this->module->index(self::$collection['request']['query']);
            }
        } else {
            if (array_search('index', $methods)) {
                $this->module->index(self::$collection['request']['query']);
            }
        }
    }

    public function getModule(array $modules)
    {
        global $config;
        self::$modules_array = array();
        foreach ($modules as $module) {
            if (isset($module['modules_name']) && !empty($module['modules_name'])) {
                if (in_array($module['modules_name'], $config['modules']) && $this->moduleAllowedForPage(Url::$url['controller'], $module['modules_name'])) {
                    self::$silence_flag = 0;
                    $this->setModule($module['modules_name']);
                    $this->setTheme(self::$collection['type']);
                    $this->setAction();
                } elseif (in_array($module['modules_name'], $config['modules']) && $this->moduleSilenceMode($module['modules_name'])) {
                    self::$silence_flag = 1;
                    $this->setModule($module['modules_name']);
                    $this->setTheme(self::$collection['type']);
                    $this->setAction();
                }
            }
        }
        if (!empty($modules)) {
            return self::$modules_array;
        }
    }

    public function initSmarty(string $module_name)
    {
        return new SmartyApp(self::$collection['theme'], self::$collection['type'], true, $module_name);
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
        Page::$collection['head_links'] = $tools->modulesHeadLinks(Page::$collection['head_links'], self::$css, self::$collection['type'], self::$collection['theme'], 'css', self::$current_module);
        Page::$collection['head_links'] = $tools->modulesHeadLinks(Page::$collection['head_links'], self::$js, self::$collection['type'], self::$collection['theme'], 'js', self::$current_module);
    }

    public function loadModuleController($controller_name)
    {
        if (!empty($controller_name)) {
            $tools = new Tools();
            return $tools->getModuleController($controller_name, self::$current_module, self::$collection['type']);
        }
    }

    public function loadModuleClass($class_name)
    {
        if (!empty($class_name)) {
            $tools = new Tools();
            $tools->getModuleClass($class_name, self::$current_module, self::$collection['type']);
        }
    }

    public function getGapModules(string $gap)
    {
        if (!empty($gap)) {
            $type = self::$collection['type'];
            $db = new Db();
            return $db->select("modules_name")->from("global_modules")->innerJoin("system_modules_gaps")->on("global_modules.id=system_modules_gaps.modules_id")->innerJoin("system_gaps")->on("system_modules_gaps.gaps_id=system_gaps.id")->where("system_gaps.gaps_name='{$gap}' AND global_modules.modules_type_allowed IN ('{$type}','all')")->execute('assoc');
        }
    }

    public function moduleAllowedForPage(string $controller, string $module_name)
    {
        $tools = new Tools();
        $allowed = $tools->getModuleAllowedPages($module_name);
        if (!empty($allowed) && $allowed[0]['modules_pages_allowed'] != 'All') {
            $pages = explode(',', $allowed[0]['modules_pages_allowed']);
            foreach ($pages as $key => $value) {
                if (strtolower($value) === strtolower($controller)) {
                    return true;
                }
            }
        } elseif ($allowed[0]['modules_pages_allowed'] == 'All') {
            return true;
        }
    }

    public function moduleSilenceMode($module_name)
    {
        if (!empty($module_name) && !empty(Url::$url['controller'])) {
            $tools = new Tools();
            $silence_pages = $tools->getModuleSilencePages($module_name);
            if (!empty($silence_pages) && $silence_pages[0]['modules_silence_pages_allowed'] != 'All') {
                $exploded = explode(',', $silence_pages[0]['modules_silence_pages_allowed']);
                foreach ($exploded as $page) {
                    if ($page === Url::$url['controller']) {
                        return true;
                    }
                }
            } elseif ($silence_pages[0]['modules_silence_pages_allowed'] == 'All') {
                return true;
            }
        }
    }
}
