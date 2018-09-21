<?php
/*
* 2017 app
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@app.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade app to newer
* versions in the future. If you wish to customize app for your
* needs please refer to http://www.app.com for more information.
*
*  @author Jakub Bogacz SA <bogaczjakub@gmail.com>
*  @copyright  2017 app
*  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*/

function autoloadClass($class_name)
{
    includeFiles(checkPatches($class_name, 'class'));
}

function autoloadControllers($controller_name)
{
    includeFiles(checkPatches($controller_name, 'controller'));
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
    $real_files_array = Array();

    // get all included files with their paths
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

function checkPatches($type_name, $type)
{
    $results = Array();

        if ($type == 'class') {
//            Works only if namespaces are disabled
//            $class_file = CLASSES_DIR . $type_name . '.php';
//            if(file_exists($class_file)) {
//                array_push($results, $class_file);
//            } else {
//                $admin_class_file = ADMIN_CLASSES_DIR . $type_name . '.php';
//                if(file_exists($admin_class_file))
//                {
//                    array_push($results, $admin_class_file);
//                } else {
//                    throw new CustomException('Could not load ' . $admin_class_file .'.');
//                }
//            }
            $class_file = $type_name . '.php';
            $fix_path = str_replace('\\', '/', $class_file);
            if (file_exists($fix_path)) {
                array_push($results, $fix_path);
            } else {
                throw new CustomException('Could not load ' . $fix_path .' class file.');
            }
        } elseif ($type = 'controller') {
//            Works only if namespaces are disabled
//            $controller_file = CONTROLLERS_DIR . $type_name . '.php';
//            if(file_exists($controller_file)) {
//                array_push($results, $controller_file);
//
//            } else {
//                $admin_controller_file = ADMIN_CONTROLLERS_DIR . $type_name . '.php';
//                if(file_exists($admin_controller_file)) {
//                    array_push($results, $admin_controller_file);
//
//                } else {
//                    throw new CustomException('Could not load ' . $admin_controller_file . '.');
//                }
//            }
            $controller_file = $type_name . '.php';
            $fix_path = str_replace('\\', '/', $controller_file);
            if (file_exists($fix_path)) {
                array_push($results, $fix_path);
            } else {
                throw new CustomException('Could not load ' . $fix_path .' controller file.');
            }
        }

    return $results;
}

// Register autoload functions
spl_autoload_register('autoloadClass');
spl_autoload_register('autoloadControllers');