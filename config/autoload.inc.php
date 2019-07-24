<?php

define('INCLUDE_PATHS_ADMIN', $config['include_paths']['admin']);
define('INCLUDE_PATHS_FRONT', $config['include_paths']['front']);
define('INCLUDE_PATHS_AJAX', $config['include_paths']['ajax']);
define('INCLUDE_PATHS_GLOBAL', $config['include_paths']['global']);
define('INCLUDE_PATHS_MODULES', $config['include_paths']['modules']);

function autoload($class_name)
{
    includeFiles(checkPatches($class_name));
}

function includeFiles($files_array)
{
    $to_include = checkIfFileIncluded($files_array);
    if (!empty($to_include)) {
        foreach ($to_include as $file) {
            include $file;
        }
    }
}

function checkIfFileIncluded($to_include_array)
{
    $real_files_array = array();
    $included = get_included_files();
    foreach ($to_include_array as $path) {
        $real_path = realpath($path);
        if (array_search($real_path, $included)) {
            continue;
        } else {
            array_push($real_files_array, $real_path);
        }
    }
    return $real_files_array;
}

function checkPatches($type_name)
{
    global $config;
    $results = array();
    $class_file = $type_name . '.php';
    if (isReserved($type_name)) {
        foreach (INCLUDE_PATHS_GLOBAL as $path) {
            $joined_paths = $path . $class_file;
            if (file_exists($joined_paths) && !empty($joined_paths)) {
                array_push($results, $joined_paths);
            }
        }
    } elseif (isModule($type_name)) {
        foreach (INCLUDE_PATHS_MODULES as $path) {
            $module_file = $path . $type_name . DS . $config['page']['type'] . DS . $class_file;
            if (file_exists($module_file) && !empty($module_file)) {
                array_push($results, $module_file);
            }
        }
    } elseif (isModuleController($type_name)) {
        $module_controller_file = MODULES_DIR . str_replace('ModuleController', 'Module', $type_name) . DS . $config['page']['type'] . DS . 'controllers' . DS . $class_file;
        if (file_exists($module_controller_file) && !empty($module_controller_file)) {
            array_push($results, $module_controller_file);
        }
    } elseif (isModuleModel($type_name)) {
        $module_model_file = MODULES_DIR . str_replace('ModuleModel', 'Module', $type_name) . DS . $config['page']['type'] . DS . 'models' . DS . $class_file;
        if (file_exists($module_model_file) && !empty($module_model_file)) {
            array_push($results, $module_model_file);
        }
    } elseif (isModuleClass($type_name)) {
        $module_class_file = MODULES_DIR . str_replace('ModuleClass', 'Module', $type_name) . DS . $config['page']['type'] . DS . 'class' . DS . $class_file;
        if (file_exists($module_class_file) && !empty($module_class_file)) {
            array_push($results, $module_class_file);
        }
    } elseif (isModuleConfiguration($type_name)) {
        $module_configuration_class_file = MODULES_DIR . str_replace('Configuration', 'Module', $type_name) . DS . 'Configuration' . DS . $class_file;
        if (file_exists($module_configuration_class_file) && !empty($module_configuration_class_file)) {
            array_push($results, $module_configuration_class_file);
        }
    } else {
        if (!empty($config['page']['type']) && $config['page']['type'] == 'front') {
            foreach (INCLUDE_PATHS_FRONT as $path) {
                $joined_paths = $path . $class_file;
                if (file_exists($joined_paths) && !empty($joined_paths)) {
                    array_push($results, $joined_paths);
                }
            }
        } elseif (!empty($config['page']['type']) && $config['page']['type'] == 'admin') {
            foreach (INCLUDE_PATHS_ADMIN as $path) {
                $joined_paths = $path . $class_file;
                if (file_exists($joined_paths) && !empty($joined_paths)) {
                    array_push($results, $joined_paths);
                }
            }
        } elseif (!empty($config['page']['type']) && $config['page']['type'] == 'ajax') {
            foreach (INCLUDE_PATHS_AJAX as $path) {
                $joined_paths = $path . $class_file;
                if (file_exists($joined_paths) && !empty($joined_paths)) {
                    array_push($results, $joined_paths);
                }
            }
        }
    }
    if (count($results) > 0) {
        return $results;
    } else {
        throw new CustomException('Could not include "' . $class_file . '" file.');
    }
}

function isReserved($name)
{
    global $config;
    $is = 0;
    foreach ($config['reserved_classes'] as $reserved) {
        if (strtolower($reserved) === strtolower($name)) {
            $is++;
        }
    }
    return $is;
}

function isModule($name)
{
    global $config;
    $is = 0;
    $replaced = str_replace('Module', '', $name);
    foreach ($config['modules'] as $module) {
        if (strtolower($module) === str_replace('Module', '', strtolower($replaced))) {
            $is++;
        }
    }
    return $is;
}

function isModuleController($name)
{
    global $config;
    $is = 0;
    if (preg_match('/.+ModuleController$/', $name)) {
        $clean_name = str_replace('ModuleController', '', $name);
        $controller_file = MODULES_DIR . $clean_name . 'Module' . DS . $config['page']['type'] . DS . 'controllers' . DS . $name . '.php';
        if (file_exists($controller_file)) {
            $is++;
        }
    }
    return $is;
}

function isModuleModel($name)
{
    global $config;
    $is = 0;
    if (preg_match('/.+ModuleModel$/', $name)) {
        $clean_name = str_replace('ModuleModel', '', $name);
        $model_file = MODULES_DIR . $clean_name . 'Module' . DS . $config['page']['type'] . DS . 'models' . DS . $name . '.php';
        if (file_exists($model_file)) {
            $is++;
        }
    }
    return $is;
}

function isModuleClass($name)
{
    global $config;
    $is = 0;
    if (preg_match('/.+ModuleClass$/', $name)) {
        $clean_name = str_replace('ModuleClass', '', $name);
        $class_file = MODULES_DIR . $clean_name . 'Module' . DS . $config['page']['type'] . DS . 'classes' . DS . $name . '.php';
        if (file_exists($class_file)) {
            $is++;
        }
    }
    return $is;
}

function isModuleConfiguration($name)
{
    global $config;
    $is = 0;
    if (preg_match('/.+Configuration$/', $name)) {
        $clean_name = str_replace('Configuration', '', $name);
        $class_file = MODULES_DIR . $clean_name . 'Module' . DS . 'Configuration' . DS . $name . '.php';
        if (file_exists($class_file)) {
            $is++;
        }
    }
    return $is;
}

spl_autoload_register('autoload');
