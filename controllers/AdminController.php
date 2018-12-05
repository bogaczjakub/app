<?php

class AdminController
{
    private $request;
    protected $login_required;

    public function __construct($settings)
    {
        $this->request = Url::requestToArray();
        $this->login_required = Settings::getSettingValue($settings, 'login_required');
        Page::$theme = $config['page']['theme'] =  Settings::getSettingValue($settings, 'theme');
        Page::$url = $this->request;

        if (($this->login_required && Tools::isLogged()) or (!$this->login_required)) {
            $this->pageConstructor();
        } elseif ($this->login_required && !Tools::isLogged()) {
            $this->request['controller'] = 'Login';
            $this->pageConstructor();
        }
    }

    private function pageConstructor()
    {
        try {
            if (!empty($this->request)) {
                (isset($this->request['controller']) && !empty($this->request['controller'])) ? $this->request['controller'] : $this->request['controller'] = 'Index';
                $controller_path = _ROOT_DIR_ . Page::$type . DS . 'controllers' . DS . $this->request['controller'] . 'Controller.php';
                if (file_exists($controller_path) && !empty($controller_path)) {
                    $page = new Page();
                    $page->init();
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
