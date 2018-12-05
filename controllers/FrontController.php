<?php

class FrontController extends Controller
{
    private $front_settings;
    private $request;

    public function __construct($settings)
    {
        global $config;
        $this->front_settings = $settings;
        $this->request = Url::requestToArray();
        parent::__construct(Settings::getSettingValue($this->front_settings, 'theme'), 'front');
        $this->pageConstructor($this->request, 'front');
    }

    private function pageConstructor()
    {
        try {
            if (!empty($this->request)) {
                isset($this->request['query']['controller']) ? $this->request['query']['controller'] : $this->request['query']['controller'] = 'Index';
                $controller_path = _ROOT_DIR_ . Page::$type . DS . 'controllers' . DS . $this->request['query']['controller'] . 'Controller.php';
                if (file_exists($controller_path) && !empty($controller_path)) {
                    parent::Page($this->request);
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
