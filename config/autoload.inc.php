<?php

define('INCLUDE_PATHS_ADMIN', $config['include_paths']['admin']);
define('INCLUDE_PATHS_FRONT', $config['include_paths']['front']);
define('INCLUDE_PATHS_GLOBAL', $config['include_paths']['global']);

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
    } else {
        if (!empty($config['current_inc_dir']) && $config['current_inc_dir'] == 'front') {
            foreach (INCLUDE_PATHS_FRONT as $path) {
                $joined_paths = $path . $class_file;
                if (file_exists($joined_paths) && !empty($joined_paths)) {
                    array_push($results, $joined_paths);
                }
            }
        } elseif (!empty($config['current_inc_dir']) && $config['current_inc_dir'] == 'admin') {
            foreach (INCLUDE_PATHS_ADMIN as $path) {
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
        if (strtolower($reserved) == strtolower($name)) {
            $is++;
        }
    }
    return $is;
}

// Register autoload functions
spl_autoload_register('autoload');
