<?php

class Tools
{
    public function __construct()
    {

    }

    public static function getConnectionData()
    {
        $xml_array = array();
        if (file_exists(CONNECTION_DATA) && !empty(CONNECTION_DATA)) {
            $xml = simplexml_load_file(CONNECTION_DATA);
            return $xml;
        }
    }
}
