<?php

class Url
{
    protected $url = array('path', 'query');

    public function __construct()
    {
        // echo 'Hello from ' . __CLASS__ . ' class.';
    }

    public static function urlToArray()
    {
        extract(parse_url($_SERVER['REQUEST_URI']));
        $url['path'] = $path;
        parse_str($query, $url['query']);
        return $url;
    }

}