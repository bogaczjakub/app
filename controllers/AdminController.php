<?php

class AdminController
{
    private $request;
    protected $login_required;
    public $settings;
    public $url;

    public function __construct($collector)
    {
        $this->settings = new Settings();
        $this->url = new Url();
        $collector->collection['type_settings'] = $this->settings->getAdminSettings();
        $this->login_required = $this->settings->getSettingValue($collector->collection['type_settings'], 'login_required');
        $collector->collection['theme'] = $config['page']['theme'] = $this->settings->getSettingValue($collector->collection['type_settings'], 'theme');
        $collector->collection['request'] = $this->url->requestToArray();

        if ($this->login_required && !Tools::isLogged() && $collector->collection['request']['controller'] == 'Login') {
            $this->pageConstructor($collector);
        }
        if ($this->login_required && !Tools::isLogged() && $collector->collection['request']['controller'] != 'Login') {
            Url::redirectUrl('Login', 'index', array());
        }
        if ($this->login_required && Tools::isLogged() && $collector->collection['request']['controller'] != 'Login') {
            $this->pageConstructor($collector);
        }
        if ($this->login_required && Tools::isLogged() && $collector->collection['request']['controller'] == 'Login') {
            Url::redirectUrl('Index', 'index', array());
        }
        if (!$this->login_required) {
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
