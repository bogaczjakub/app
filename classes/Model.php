<?php

class Model
{
    private $controller;
    private $method;

    public function __construct()
    {

    }

    public function getPageModel(string $controller)
    {
        global $config;
        try {
            if (!empty($controller)) {
                if (preg_match('/.+Controller$/', $controller)) {
                    $model_name = str_replace('Controller', '', $controller) . 'Model';
                }
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

    public function getModuleModel(string $module, string $model)
    {
        global $config;
        try {
            if (!empty($module) && !empty($model)) {
                $model_name = $model . 'ModuleModel';
                $module_path_name = $module . 'Module';
                $model_path = MODULES_DIR . $module_path_name . DS . $config['page']['type'] . DS . 'models' . DS . $model_name . '.php';
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
