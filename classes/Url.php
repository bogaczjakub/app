<?php

class Url
{
    protected $url = array('path', 'query');

    public function __construct()
    {

    }

    public static function requestToArray()
    {
        $query_array = array();
        $url = array('path' => '', 'query' => array(), 'controller' => '', 'action' => '');
        extract(parse_url($_SERVER['REQUEST_URI']));
        $url['path'] = $path;
        if (isset($query)) {
            parse_str($query, $query_array);
        }
        if (isset($query_array['controller'])) {
            $url['controller'] = $query_array['controller'];
            unset($query_array['controller']);
        } else {
            $url['controller'] = '';
        }
        if (isset($query_array['action'])) {
            $url['action'] = $query_array['action'];
            unset($query_array['action']);
        } else {
            $url['action'] = '';
        }
        if (!empty($query_array)) {
            $url['query'] = $query_array;
        }
        return $url;
    }

    public function redirectUrl()
    {
        
    }

}
