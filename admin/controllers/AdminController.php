<?php

class AdminController extends Controller
{
    protected $admin_settings;
    protected $request;
    protected $login_required;

    public function __construct($settings)
    {
        global $config;
        $this->admin_settings = $settings;
        $this->request = Url::requestToArray();
        $this->login_required = Settings::getSettingValue($this->admin_settings, 'login_required');
        parent::__construct(Settings::getSettingValue($this->admin_settings, 'theme'), 'admin');
        if (($this->login_required && Tools::isLogged()) OR (!$this->login_required)) {
            $this->pageConstructor($this->request, 'admin');
        } elseif ($this->login_required && !Tools::isLogged()) {
            $this->request['query']['controller'] = 'Login';
            $this->pageConstructor($this->request, 'admin');
        }
    }

    private function pageConstructor($url, $type)
    {
        try {
            if (!empty($url)) {
                isset($url['query']['controller']) ? $url['query']['controller'] : $url['query']['controller'] = 'Index';
                $controller_path = _ROOT_DIR_ . $type . DS . 'controllers' . DS . $url['query']['controller'] . 'Controller.php';
                if (file_exists($controller_path) && !empty($controller_path)) {
                    parent::$page_details = Settings::getPageDetails($url['query']['controller']);
                    $controller_full = $url['query']['controller'] . 'Controller';
                    $page_controller = new $controller_full($url['query']);
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
