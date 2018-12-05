<?php

class Model
{
    private $controller;
    private $method;

    public function __construct()
    {

    }

    public function getPageModel($controller)
    {
        global $config;
        try {
            if (!empty($controller)) {
                $model_name = str_replace('Controller', '', $controller) . 'Model';
                $model_path = _ROOT_DIR_ . $config['page']['type'] . DS . 'models' . DS . $model_name . '.php';
                if (file_exists($model_path) && !empty($model_path)) {
                    return new $model_name;
                } else {
                    throw new CustomException('Could not find model file for this controller.');
                }
            }
        } catch (CustomException $e) {
            echo $e->getCustomMessage($e);
            exit();
        }
    }
}
