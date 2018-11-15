<?php

class SmartyApp extends Smarty
{

    public function __construct($theme = 'default', $type = 'front')
    {
        parent::__construct();
        $this->template_dir = _ROOT_DIR_ . $type . DS . 'themes' . DS . $theme . DS . 'views' . DS;
        $this->compile_dir = _ROOT_DIR_ . $type . DS . 'themes' . DS . $theme . DS . 'compile' . DS;
        $this->config_dir = _ROOT_DIR_ . $type . DS . 'themes' . DS . $theme . DS . 'configs' . DS;
        $this->cache_dir = _ROOT_DIR_ . $type . DS . 'themes' . DS . $theme . DS . 'cache' . DS;
        $this->caching = true; 
    }
}
