<?php

class Model
{
    private $model_name;
    private $method;

    public function __construct()
    { }

    public function getClassModel(string $model_name)
    {
        global $config;
        try {
            if (!empty($model_name)) {
                if (preg_match('/.+Controller$/', $model_name)) {
                    $model_name = str_replace('Controller', '', $model_name) . 'Model';
                }
                $model_path = _ROOT_DIR_ . $config['page']['type'] . DS . 'models' . DS . $model_name . 'Model.php';
                if (file_exists($model_path) && !empty($model_path)) {
                    $real_model_name = $model_name . 'Model';
                    return new $real_model_name;
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
