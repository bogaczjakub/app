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

    private function pageConstructor($url, $type)
    {
        try {
            if (!empty($url)) {
                isset($url['query']['controller']) ? $url['query']['controller'] : $url['query']['controller'] = 'Index';
                $controller_path = _ROOT_DIR_ . $type . DS . 'controllers' . DS . $url['query']['controller'] . 'Controller.php';
                if (file_exists($controller_path) && !empty($controller_path)) {
                    $controller_full = $url['query']['controller'] . 'Controller';
                    $page_controller = new $controller_full($url['query']);
                } else {
                    throw new CustomException('Can not find controller file or it is empty.');
                }
            } else {
                
                // throw new CustomException('Wrong URL or empty parameters.');
            }
        } catch (CustomException $e) {
            echo $e->getCustomMessage($e);
            exit();
        }
    }
}
