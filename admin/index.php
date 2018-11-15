<?php

try {
    if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/config/preload.inc.php')) {
        include $_SERVER['DOCUMENT_ROOT'] . '/config/preload.inc.php';
        $Core = new Core($config);
        $Core->appInit('Admin');
    } else {
        throw new Exception('Could not find preload.inc.php file');
    }
} catch (Exception $e) {
    die($e->getMessage());
}
