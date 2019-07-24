<?php

class FrontController
{
    private $request;
    public $settings;
    public $url;

    public function __construct($collector)
    {
        $this->settings = new Settings();
        $this->url = new Url();
        $collector->collection['type_settings'] = $this->settings->getFrontSettings();
        $collector->collection['theme'] = $config['page']['theme'] = $this->settings->getSettingValue($collector->collection['type_settings'], 'theme');
        $collector->collection['request'] = $this->url->requestToArray();
        
        $disable_front = $this->settings->getSettingValue($collector->collection['type_settings'], 'disable_front');
        if ($disable_front) {
            $disable_front_message = $this->settings->getSettingValue($collector->collection['type_settings'], 'disable_front_message');
            die($disable_front_message);
        } else {
            $this->pageConstructor($collector);
        }
    }
    
    private function pageConstructor($collector)
    {
        try {
            if (!empty($collector->collection['request'])) {
                (isset($collector->collection['request']['controller']) && (!empty($collector->collection['request']['controller'])) ? $collector->collection['request']['controller'] : $collector->collection['request']['controller'] = 'Index');
                $controller_path = _ROOT_DIR_ . $collector->collection['type'] . DS . 'controllers' . DS . $collector->collection['request']['controller'] . 'Controller.php';
                if (file_exists($controller_path) && !empty($controller_path)) {
                    $page = new Page($collector->collection, true);
                } else {
                    throw new CustomException('Can not find controller file or it is empty.');
                }
            } else {
                throw new CustomException('Wrong URL or empty parameters.');
            }
        } catch (CustomException $e) {
            echo $e->getCustomMessage($e);
            exit();
        }
    }
}

