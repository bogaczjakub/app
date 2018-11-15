<?php

try {
    if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/config/setup.inc.php')) {
        include $_SERVER['DOCUMENT_ROOT'] . '/config/setup.inc.php';
        $Core = new Core();
        $Core->appInit('Front');
    } else {
        throw new Exception('Could not find preload.inc.php file');
    }
} catch (Exception $e) {
    die($e->getMessage());
}
