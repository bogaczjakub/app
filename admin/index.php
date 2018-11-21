<?php

try {
    if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/config/setup.inc.php')) {
        include $_SERVER['DOCUMENT_ROOT'] . '/config/setup.inc.php';
        $Core = new Core($config);
        $Core->appInit('admin');
    } else {
        throw new Exception('Could not find setup.inc.php file');
    }
} catch (Exception $e) {
    die($e->getMessage());
}
