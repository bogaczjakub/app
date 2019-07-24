<?php

if (isset($_REQUEST['ajax']) && $_REQUEST['ajax'] == true) {
    try {
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/config/setup.inc.php')) {
            include $_SERVER['DOCUMENT_ROOT'] . '/config/setup.inc.php';
            $collector = new Collector();
            $collector->type('ajax');
            $dispatcher = new Dispatcher($collector);
        } else {
            throw new Exception('Could not find setup.inc.php file');
        }
    } catch (Exception $e) {
        die($e->getMessage());
    }
} else {
    header('Location: ' . DIRECTORY_SEPARATOR . 'front');
}
