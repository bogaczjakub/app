<?php

try {
    if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/config/setup.inc.php')) {
        include $_SERVER['DOCUMENT_ROOT'] . '/config/setup.inc.php';
        $dispatcher = new Dispatcher();
        $dispatcher->appInit('admin');
    } else {
        throw new Exception('Could not find setup.inc.php file');
    }
} catch (Exception $e) {
    die($e->getMessage());
}
