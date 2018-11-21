<?php

class AdminController extends Controller
{
    protected $admin_settings;
    protected $request;
    protected $login_required;

    public function __construct($settings, $type)
    {
        global $config;
        $this->admin_settings = $settings;
        $this->request = Url::requestToArray();
        $this->login_required = Settings::getSettingValue($this->admin_settings, 'login_required');
       
        parent::$type = $type;
        parent::__construct(Settings::getSettingValue($this->admin_settings, 'theme'));

        if (($this->login_required && Tools::isLogged()) or (!$this->login_required)) {
            $this->pageConstructor($this->request);
        } elseif ($this->login_required && !Tools::isLogged()) {
            $this->request['query']['controller'] = 'Login';
            $this->pageConstructor($this->request);
        }
    }

    private function pageConstructor($url)
    {
        try {
            if (!empty($url)) {
                isset($url['query']['controller']) ? $url['query']['controller'] : $url['query']['controller'] = 'Index';
                $controller_path = _ROOT_DIR_ . parent::$type . DS . 'controllers' . DS . $url['query']['controller'] . 'Controller.php';
                if (file_exists($controller_path) && !empty($controller_path)) {
                    parent::pageController($url);
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
