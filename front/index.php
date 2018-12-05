<?php

try {
    if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/config/setup.inc.php')) {
        include $_SERVER['DOCUMENT_ROOT'] . '/config/setup.inc.php';
        $dispatcher = new Dispatcher();
        $dispatcher->appInit('Front');
    } else {
        throw new Exception('Could not find preload.inc.php file');
    }
} catch (Exception $e) {
    die($e->getMessage());
}
