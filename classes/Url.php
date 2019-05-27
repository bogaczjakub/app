<?php

class Url
{
    public static $url = array();
    public static $page_url;

    public function __construct()
    {

    }

    public function requestToArray()
    {
        $query_array = array();
        self::$url = array('path' => '', 'query' => array(), 'controller' => '', 'action' => '', 'module' => array(), 'ajax' => false);
        extract(parse_url($_SERVER['REQUEST_URI']));
        self::$url['path'] = $path;
        if (isset($query)) {
            parse_str($query, $query_array);
        }
        if (isset($query_array['ajax'])) {
            self::$url['ajax'] = $query_array['ajax'];
            unset($query_array['ajax']);
        }
        if (isset($query_array['controller'])) {
            self::$url['controller'] = $query_array['controller'];
            unset($query_array['controller']);
        } else {
            self::$url['controller'] = 'Index';
        }
        if (isset($query_array['action'])) {
            self::$url['action'] = $query_array['action'];
            unset($query_array['action']);
        } else {
            self::$url['action'] = 'index';
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
        unset($query_array['module']);
        if (!empty($query_array)) {
            foreach ($query_array as $key => $value) {
                self::$url['query'][$key] = $value;
            }
        }
        if (!empty($_POST)) {
            foreach ($_POST as $key => $value) {
                self::$url['query'][$key] = $value;
            }
        }
        self::$page_url = $this->buildPageUrl(self::$url['controller'], self::$url['action']);
        return self::$url;
    }

    public static function redirectUrl(string $controller, string $action, array $args = array())
    {
        try {
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
            } else {
                throw new Exception('Could not find ' . $controller . ' file.');
            }
        } catch (CustomException $e) {
            echo $e->getCustomMessage($e);
            exit();
        }
    }

    public static function buildPageUrl(string $page_controller, string $page_action, array $params = null, bool $table = false)
    {
        $page_controller = (empty($page_controller) || $page_controller = 'this') ? self::$url['controller'] : $page_controller;
        $page_action = (empty($page_controller)) ? self::$url['action'] : $page_action;
        $query_params = '&';
        if ($table) {
            $table_query_array = array(
                'page' => '',
                'sort' => array(),
            );
            if (isset($params['page']) && !empty($params['page'])) {
                $table_query_array['page'] = $params['page'];
                if (isset(self::$url['query']['sort']) && !empty(self::$url['query']['sort'])) {
                    $table_query_array['sort'] = self::$url['query']['sort'];
                } else {
                    $table_query_array['sort'] = '';
                }
            } else {
                $table_query_array['page'] = 1;
            }
            if (isset($params['sort']) && !empty($params['sort'])) {
                if (isset(self::$url['query']['sort']) && !empty(self::$url['query']['sort'])) {
                    $merge_sort = array_merge(self::$url['query']['sort'], $params['sort']);
                    $table_query_array['sort'] = $merge_sort;
                    if (isset(self::$url['query']['page']) && !empty(self::$url['query']['page'])) {
                        $table_query_array['page'] = self::$url['query']['page'];
                    } else {
                        $table_query_array['page'] = 1;
                    }
                } else {
                    $table_query_array['sort'] = $params['sort'];
                    if (isset(self::$url['query']['page']) && !empty(self::$url['query']['page'])) {
                        $table_query_array['page'] = self::$url['query']['page'];
                    } else {
                        $table_query_array['page'] = 1;
                    }
                }
            }
            $query_params .= http_build_query($table_query_array);
        } else {
            if (!empty($params)) {
                $query_params .= http_build_query($params);
            } else {
                $query_params = '';
            }
        }
        return $_SERVER['PHP_SELF'] . '?controller=' . $page_controller . '&action=' . $page_action . $query_params;
    }

}
