<?php

class Collector
{
    public $collection = array(
        'type' => '',
        'global_details' => array(),
        'type_settings' => array(),
        'theme' => '',
        'request' => '',
        'theme_index' => '',
        'page_details' => array(),
        'template_data' => array(),
        'head_links' => array('js' => array(), 'css' => array()),
        'alerts' => array(),
        'breadcrumbs' => array(),
        'page_forms' => array()
    );

    public function __construct()
    {

    }

    public function type(string $type = '')
    {
        if (!empty($type)) {
            $this->collection['type'] = $type;
        }
    }

    public function globalDetails(array $global_details = array()) {
        if (!empty($global_details)) {
            $this->collection['global_details'] = $global_details;
        }
    }

    public function theme(string $theme = '') {
        if (!empty($theme)) {
            $this->collection['theme'] = $theme;
        }
    }
}
