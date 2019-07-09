<?php

class Tools
{
    public function __construct()
    { }

    public static function checkExisting(string $file, string $type)
    {
        global $config;
        if (!empty($type) && !empty($file)) {
            switch ($type) {
                case 'controller':
                    if (file_exists($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . $config['page']['type'] . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . $file . 'Controller.php') || file_exists($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . $file . 'Controller.php')) {
                        return true;
                    } else {
                        return false;
                    }
                    break;
                case 'class':
                    if (file_exists($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . $config['page']['type'] . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . $file . 'Class.php') || file_exists($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . $file . '.php')) {
                        return true;
                    } else {
                        return false;
                    }
                    break;
                case 'module':
                    if (file_exists($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . $file . 'Module' . DIRECTORY_SEPARATOR . $config['page']['type'] . DIRECTORY_SEPARATOR . $file . 'Module.php')) {
                        return true;
                    } else {
                        return false;
                    }
                    break;
                case 'module_configuration':
                    if (file_exists($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . $file . 'Module' . DIRECTORY_SEPARATOR . 'configuration' . DIRECTORY_SEPARATOR . 'configuration.php')) {
                        return true;
                    } else {
                        return false;
                    }
                    break;
                default:
                    return false;
                    break;
            }
        }
    }

    public static function getConnectionData()
    {
        $xml_array = array();
        if (file_exists(CONNECTION_DATA) && !empty(CONNECTION_DATA)) {
            $xml = simplexml_load_file(CONNECTION_DATA);
            return $xml;
        }
    }

    public static function isLogged()
    {
        if (isset($_SESSION['logged_user']) && !empty($_SESSION['logged_user']['user_id'])) {
            return true;
        } else {
            return false;
        }
    }

    public function themeHeadLinks(array $head_links, string $type, string $theme)
    {
        if (!empty($type)) {
            $type_select = ($type == 'admin') ? ADMIN_THEMES : FRONT_THEMES;
            $styles_file = $type_select . $theme . DS . 'css' . DS . 'styles.css';
            $ajax_file = $type_select . $theme . DS . 'js' . DS . 'ajax.js';
            $scripts_file = $type_select . $theme . DS . 'js' . DS . 'scripts.js';
            $forms_file = $type_select . $theme . DS . 'js' . DS . 'forms.js';
            $validation_file = $type_select . $theme . DS . 'js' . DS . 'validation.js';
            if (file_exists($styles_file) && !empty($styles_file)) {
                array_push($head_links['css'], self::returnAbsolutePath($styles_file));
            }
            if (file_exists($scripts_file) && !empty($ajax_file)) {
                array_push($head_links['js'], self::returnAbsolutePath($ajax_file));
            }
            if (file_exists($scripts_file) && !empty($scripts_file)) {
                array_push($head_links['js'], self::returnAbsolutePath($scripts_file));
            }
            if (file_exists($forms_file) && !empty($forms_file)) {
                array_push($head_links['js'], self::returnAbsolutePath($forms_file));
            }
            if (file_exists($forms_file) && !empty($validation_file)) {
                array_push($head_links['js'], self::returnAbsolutePath($validation_file));
            }
            return $head_links;
        }
    }

    public function templateHeadLinks(array $head_links, array $links, string $type, string $theme, string $link_type)
    {
        if (!empty($links)) {
            $type_select = ($type == 'admin') ? ADMIN_THEMES : FRONT_THEMES;
            if (is_array($links)) {
                foreach ($links as $link) {
                    $link_source = $type_select . $theme . DS . $link_type . DS . $link;
                    if (file_exists($link_source)) {
                        array_push($head_links[$link_type], self::returnAbsolutePath($link_source));
                    }
                }
            } elseif (is_string($links)) {
                $link_source = $type_select . $theme . DS . $link_type . DS . $links;
                if (file_exists($link_source)) {
                    array_push($head_links[$link_type], self::returnAbsolutePath($link_source));
                }
            }
        }
        return $head_links;
    }

    public function blocksHeadStyles(string $block_styles)
    {
        if (!empty($block_styles)) {
            $styles = '<style type="text/css">';
            $styles .= $block_styles;
            $styles .= '</style>';
            return $styles;
        }
    }

    public function modulesHeadLinks(array $head_links, array $links, string $type, string $theme, string $link_type, string $module_name)
    {
        if (!empty($links)) {
            if (is_array($links)) {
                foreach ($links as $link) {
                    $link_source = MODULES_DIR . $module_name . DS . $type . DS . 'themes' . DS . $theme . DS . $link_type . DS . $link;
                    if (file_exists($link_source)) {
                        array_push($head_links[$link_type], self::returnAbsolutePath($link_source));
                    }
                }
            } elseif (is_string($links)) {
                $link_source = MODULES_DIR . $module_name . DS . $type . DS . 'themes' . DS . $theme . DS . $link_type . DS . $links;
                if (file_exists($link_source)) {
                    array_push($head_links[$link_type], self::returnAbsolutePath($link_source));
                }
            }
        }
        return $head_links;
    }

    public static function isModuleConfigurationExist(string $module_name)
    {
        if (!empty($module_name)) {
            return self::checkExisting($module_name, 'module_configuration');
        } else {
            return false;
        }
    }

    private function librarySelection(array $type, array $head_links)
    {
        foreach ($type as $key => $library) {
            foreach ($library as $value) {
                $exploded = explode(DS, $value);
                if (end($exploded) == '*') {
                    array_pop($exploded);
                    $imploded = implode(DS, $exploded);
                    $path = LIBS_DIR . $key . DS . $imploded . DS;
                    $files = scandir($path);
                    if (!empty($files)) {
                        foreach ($files as $file) {
                            $separated = explode('.', $file);
                            if (end($separated) == 'js') {
                                array_push($head_links['js'], self::returnAbsolutePath($path . $file));
                            } elseif (end($separated) == 'css') {
                                array_push($head_links['css'], self::returnAbsolutePath($path));
                            }
                        }
                    }
                } else {
                    $file = end($exploded);
                    $path = LIBS_DIR . $key . DS . $value;
                    if (file_exists($path) && !empty($path)) {
                        $separated = explode('.', $file);
                        if (end($separated) == 'js') {
                            array_push($head_links['js'], self::returnAbsolutePath($path));
                        } elseif (end($separated) == 'css') {
                            array_push($head_links['css'], self::returnAbsolutePath($path));
                        }
                    }
                }
            }
        }
        return $head_links;
    }

    public function addLibraries(array $head_links, string $for_type)
    {
        global $config;
        $library_array_all = $config['global_libs']['all'];
        $library_array_type = $config['global_libs'][$for_type];
        if (!empty($library_array_all)) {
            $head_links = $this->librarySelection($library_array_all, $head_links);
        }
        if (!empty($library_array_type)) {
            $head_links = $this->librarySelection($library_array_type, $head_links);
        }
        return $head_links;
    }

    public static function returnAbsolutePath(string $path)
    {
        if (!empty($path)) {
            $absolute_path = str_replace($_SERVER['DOCUMENT_ROOT'], '', $path);
            return $absolute_path;
        }
    }

    public function getModuleController(string $controller_name, string $module_name, string $type)
    {
        $controller_postfix = $controller_name . 'ModuleController';
        $controller_file = MODULES_DIR . $module_name . 'Module' . DS . $type . DS . 'controllers' . DS . $controller_postfix . '.php';
        if (file_exists($controller_file) && !empty($controller_file)) {
            return new $controller_postfix();
        }
    }

    public function getModuleClass(string $class_name, string $module_name, string $type)
    {
        $class_postfix = $class_name . 'ModuleClass';
        $class_file = MODULES_DIR . $module_name . 'Module' . DS . $type . DS . 'classes' . DS . $class_name . 'Class.php';
        if (file_exists($class_file) && !empty($class_file)) {
            require_once $class_postfix;
        }
    }

    public function getModuleModel(string $module_name, string $model_name)
    {
        $model = new Model();
        return $model->getModuleModel($module_name, $model_name);
    }

    public function getControllerClass(string $class_name)
    {
        if (!empty($class_name) && Tools::checkExisting($class_name, 'class')) {
            $real_class_name = $class_name . 'Class';
            return new $real_class_name();
        }
    }

    public function getClassModel(string $model_name)
    {
        $model = new Model();
        return $model->getClassModel($model_name);
    }

    public function getPageSilenceModules(string $controller, string $type)
    {
        if (!empty($controller)) {
            $db = new Db();
            return $db->select("global_modules.name")->from("global_modules")->innerJoin("{$type}_modules_silence_associations")->on("global_modules.id={$type}_modules_silence_associations.module_id")->innerJoin("{$type}_pages")->on("{$type}_modules_silence_associations.page_id={$type}_pages.id")->where("{$type}_pages.controller='{$controller}'")->execute("assoc");
        }
    }

    public function cleanInput(string $input, string $type)
    {
        if (!empty($input)) {
            if ($type == 'string' || $type == 'radio' || $type == 'select' || $type == 'checkbox') {
                $input = trim($input);
            }
            if ($type == 'text') {
                $input = htmlspecialchars($input);
            }
            if ($type == 'boolean') {
                if (gettype($input) == 'integer' && ($input == 1 || $input == 0)) {
                    $input = (string) $input;
                }
            }
        }
        return $input;
    }

    public static function getStaticMessage(string $message_name)
    {
        if (!empty($message_name)) {
            $db = new Db();
            $resutls = $db->select("message")->from("global_messages_static")->where("message='{$message_name}'")->execute("assoc");
            return $resutls[0]['message_static_message'];
        }
    }
}
