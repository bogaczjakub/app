<?php

class Collector
{
    public $collection = array(
        'type' => '',
        'global_pages_details' => array(),
        'type_settings' => array(),
        'theme' => '',
        'request' => '',
        'theme_index' => '',
        'page_details' => array(),
        'template_data' => array(),
        'head_links' => array('js' => array(), 'css' => array()),
        'alerts' => array(),
        'breadcrumbs' => array()
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

    public function globalPageDetails(array $global_page_details = array()) {
        if (!empty($global_page_details)) {
            $this->collection['global_pages_details'] = $global_page_details;
        }
    }

    public function theme(string $theme = '') {
        if (!empty($theme)) {
            $this->collection['theme'] = $theme;
        }
    }
}
