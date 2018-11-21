<?php

class Url
{
    protected $url = array('path', 'query');

    public function __construct()
    {
        
    }

    public static function requestToArray()
    {
        extract(parse_url($_SERVER['REQUEST_URI']));
        $url['path'] = $path;
        if (isset($query)) {
            parse_str($query, $url['query']);
        }
        return $url;
    }

}
