<?php

include dirname(__FILE__) . '/phpSettings.inc.php';
try {
    if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/classes/CustomException.php') && !empty($_SERVER['DOCUMENT_ROOT'] . '/classes/CustomException.php')) {
        include $_SERVER['DOCUMENT_ROOT'] . '/classes/CustomException.php';
        if (file_exists(dirname(__FILE__) . '/config.inc.php') && !empty(dirname(__FILE__) . '/config.inc.php')) {
            include dirname(__FILE__) . '/config.inc.php';
            if (file_exists(dirname(__FILE__) . '/defines.inc.php') && !empty(dirname(__FILE__) . '/defines.inc.php')) {
                include dirname(__FILE__) . '/defines.inc.php';
            } else {
                throw new CustomException('Could not include config file.');
            }
            if (file_exists(_ROOT_DIR_ . 'libs/Smarty.class.php') && !empty(_ROOT_DIR_ . 'libs/Smarty.class.php')) {
                include _ROOT_DIR_ . 'libs/Smarty.class.php';
            } else {
                throw new CustomException('Could not include Smarty file.');
            }
            if (file_exists(dirname(__FILE__) . '/autoload.inc.php') && !empty(dirname(__FILE__) . '/autoload.inc.php')) {
                include dirname(__FILE__) . '/autoload.inc.php';
            } else {
                throw new CustomException('Could not include autoload file.');
            }
            if (file_exists(dirname(__FILE__) . '/settings.inc.php') && !empty(dirname(__FILE__) . '/settings.inc.php')) {
                include dirname(__FILE__) . '/settings.inc.php';
            } else {
                throw new CustomException('Could not include settings file.');
            }
        } else {
            throw new CustomException('Could not include defines file.');
        }
    } else {
        throw new CustomException('Could not include CustomException file.');
    }
} catch (CustomException $e) {
    echo $e->getCustomMessage($e);
    exit();
}
