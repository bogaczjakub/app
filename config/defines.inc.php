<?php

define('DS', DIRECTORY_SEPARATOR);
define('_ROOT_DIR_', $_SERVER['DOCUMENT_ROOT'] . DS);
define('CONFIG_DIR', _ROOT_DIR_ . '/config/');
define('GLOBAL_CLASSES_DIR', _ROOT_DIR_ . '/classes/');
define('GLOBAL_CONTROLLERS_DIR', _ROOT_DIR_ . '/controllers/');
define('ADMIN_CONTROLLERS', _ROOT_DIR_ . '/admin/controllers/');
define('FRONT_CONTROLLERS', _ROOT_DIR_ . '/front/controllers/');
define('ADMIN_CLASSES', _ROOT_DIR_ . '/admin/classes/');
define('FRONT_CLASSES', _ROOT_DIR_ . '/front/classes/');
define('CONNECTION_DATA', _ROOT_DIR_ . '/config/xml/connection.xml');
define('ADMIN_THEMES', _ROOT_DIR_ . 'admin' . DS . 'themes' . DS);
define('FRONT_THEMES', _ROOT_DIR_ . 'front' . DS . 'themes' . DS);
define('LIBS_DIR', _ROOT_DIR_ . 'libs' . DS);
