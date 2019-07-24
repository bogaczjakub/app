<?php

class SmartyApp extends Smarty
{
    public function __construct(string $theme = 'default', string $type = 'front', bool $is_module = false, bool $is_module_configuration = false, string $module_name = '')
    {
        parent::__construct();
        if (!$is_module && !$is_module_configuration) {
            $this->template_dir = _ROOT_DIR_ . $type . DS . 'themes' . DS . $theme . DS . 'views' . DS;
            $this->compile_dir = _ROOT_DIR_ . $type . DS . 'themes' . DS . $theme . DS . 'compile' . DS;
            $this->config_dir = _ROOT_DIR_ . $type . DS . 'themes' . DS . $theme . DS . 'configs' . DS;
            $this->cache_dir = _ROOT_DIR_ . $type . DS . 'themes' . DS . $theme . DS . 'cache' . DS;
        } elseif ($is_module && !$is_module_configuration) {
            $this->template_dir = _ROOT_DIR_ . 'modules' . DS . $module_name . DS . $type . DS . 'themes' . DS . $theme . DS . 'views' . DS;
            $this->compile_dir = _ROOT_DIR_ . 'modules' . DS . $module_name . DS . $type . DS . 'themes' . DS . $theme . DS . 'compile' . DS;
            $this->config_dir = _ROOT_DIR_ . 'modules' . DS . $module_name . DS . $type . DS . 'themes' . DS . $theme . DS . 'configs' . DS;
            $this->cache_dir = _ROOT_DIR_ . 'modules' . DS . $module_name . DS . $type . DS . 'themes' . DS . $theme . DS . 'cache' . DS;
        } elseif ($is_module_configuration && !$is_module) {
            $this->template_dir = _ROOT_DIR_ . 'modules' . DS . $module_name . 'Module' . DS . 'configuration' . DS . 'themes' . DS . $theme . DS . 'views' . DS;
            $this->compile_dir = _ROOT_DIR_ . 'modules' . DS . $module_name . 'Module'  . DS . 'configuration' . DS . 'themes' . DS . $theme . DS . 'compile' . DS;
            $this->config_dir = _ROOT_DIR_ . 'modules' . DS . $module_name . 'Module'  . DS . 'configuration' . DS . 'themes' . DS . $theme . DS . 'configs' . DS;
            $this->cache_dir = _ROOT_DIR_ . 'modules' . DS . $module_name . 'Module'  . DS . 'configuration' . DS . 'themes' . DS . $theme . DS . 'cache' . DS;
        }
        // $this->caching = true;
    }
}
