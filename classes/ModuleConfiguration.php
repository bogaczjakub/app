<?php

class ModuleConfiguration
{

    public $configuration;
    public static $js = array();
    public static $css = array();
    public static $configuration_smarty;
    public static $module_configuration;
    public static $current_configuration;

    public function __construct()
    { }

    public function setModuleConfiguration(string $module_name = '')
    {
        $module_configuration_posfix = $module_name . 'Configuration';
        try {
            if (Tools::checkExisting($module_name, 'module_configuration')) {
                $this->configuration = new $module_configuration_posfix();
                self::$current_configuration = $module_name;
            } else {
                throw new CustomException('Could not find ' . $module_configuration_posfix . ' file.');
            }
        } catch (customException $e) {
            $e->getCustomMessage($e);
        }
    }

    public function setConfigurationTheme()
    {
        self::$configuration_smarty = $this->initSmarty();
    }

    public function setConfigurationAction(array $query = array(), string $action = 'index')
    {
        $methods = get_class_methods($this->configuration);
        if (!empty(Page::$collection['request']['module']['action']) && !empty(Page::$collection['request']['module']['name'])) {
            if (Page::$collection['request']['module']['name'] == strtolower(str_replace('Module', '', self::$current_configuration))) {
                foreach ($methods as $method) {
                    if ($method == Page::$collection['request']['module']['action']) {
                        $this->configuration->{$method}(isset(Page::$collection['request']['module']['args']) && (!empty(Page::$collection['request']['module']['args'])) ? Page::$collection['request']['module']['args'] : Page::$collection['request']['query']);
                        break;
                    }
                }
            } else {
                $this->configuration->index(Page::$collection['request']['query']);
            }
        } else {
            if (array_search('index', $methods)) {
                $this->configuration->index(Page::$collection['request']['query']);
            }
        }
    }

    public function getModuleConfiguration(string $module_name)
    {
        $is_configuration_exist = Tools::checkExisting($module_name, 'module_configuration');
        if ($is_configuration_exist) {
            $this->setModuleConfiguration($module_name);
            $this->setConfigurationTheme();
            $this->setConfigurationAction();
        }
        return self::$module_configuration;
    }

    public function initSmarty()
    {
        return new SmartyApp(Page::$collection['theme'], '', false, true, self::$current_configuration);
    }

    public function render(string $template = 'index')
    {
        $this->preRenderActions();
        $fetch = self::$configuration_smarty->fetch($template);
        self::$module_configuration[self::$current_configuration] = $fetch;
        $this->postRenderActions();
    }

    public function preRenderActions()
    {
        $this->addHeadLinks();
    }

    public function postRenderActions()
    { }

    public function assignData($data)
    {
        if (!empty($data)) {
            self::$configuration_smarty->assign('template_data', $data);
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
        Page::$collection['head_links'] = $tools->modulesConfigurationHeadLinks(Page::$collection['head_links'], self::$css, Page::$collection['type'], Page::$collection['theme'], 'css', self::$current_configuration);
        Page::$collection['head_links'] = $tools->modulesConfigurationHeadLinks(Page::$collection['head_links'], self::$js, Page::$collection['type'], Page::$collection['theme'], 'js', self::$current_configuration);
    }
}
