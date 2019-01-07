<?php

class Url
{
    public static $url = array();
    public function __construct()
    {

    }

    public static function requestToArray()
    {
        $query_array = array();
        self::$url = array('path' => '', 'query' => array(), 'controller' => '', 'action' => '', 'module' => array());
        extract(parse_url($_SERVER['REQUEST_URI']));
        self::$url['path'] = $path;
        if (isset($query)) {
            parse_str($query, $query_array);
        }
        if (isset($query_array['controller'])) {
            self::$url['controller'] = $query_array['controller'];
            unset($query_array['controller']);
        } else {
            self::$url['controller'] = '';
        }
        if (isset($query_array['action'])) {
            self::$url['action'] = $query_array['action'];
            unset($query_array['action']);
        } else {
            self::$url['action'] = '';
        }
        if (isset($query_array['module']['name'])) {
            self::$url['module']['name'] = $query_array['module']['name'];
            unset($query_array['module']['name']);
        } else {
            self::$url['module']['name'] = '';
        }
        if (isset($query_array['module']['action'])) {
            self::$url['module']['action'] = $query_array['module']['action'];
            unset($query_array['module']['action']);
        } else {
            self::$url['module']['action'] = '';
        }
        if (isset($query_array['module']['args'])) {
            self::$url['module']['args'] = $query_array['module']['args'];
            unset($query_array['module']['args']);
        } else {
            self::$url['module']['args'] = '';
        }
        if (!empty($query_array)) {
            self::$url['query'] = $query_array;
        }
        return self::$url;
    }

    public static function redirectUrl($controller, $action, $args = array())
    {
        if (Tools::checkExisting($controller, 'controller')) {
            $class_methods = get_class_methods($controller . 'Controller');
            if (array_search($action, $class_methods)) {
                $args_query = http_build_query($args);
                $location = 'Location: ?controller=' . $controller . '&action=' . $action;
                if (!empty($args_query)) {
                    $location .= '&' . $args_query;
                }
                header($location);
            }
        }

    }

}
