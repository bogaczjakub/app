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

// define('SMARTY_FRONT_VIEWS', _ROOT_DIR_ . 'front' . DS . 'themes' . DS . 'views' .DS);
// define('SMARTY_FRONT_VIEWS', _ROOT_DIR_ . 'front' . DS . 'themes' . DS . 'cache' .DS);
// define('SMARTY_FRONT_VIEWS', _ROOT_DIR_ . 'front' . DS . 'themes' . DS . 'compile' .DS);
// define('SMARTY_FRONT_VIEWS', _ROOT_DIR_ . 'front' . DS . 'themes' . DS . 'configs' .DS);
// define('SMARTY_ADMIN_VIEWS', _ROOT_DIR_ . 'admin' . DS . 'themes' . DS . 'views' .DS);
// define('SMARTY_ADMIN_VIEWS', _ROOT_DIR_ . 'admin' . DS . 'themes' . DS . 'cache' .DS);
// define('SMARTY_ADMIN_VIEWS', _ROOT_DIR_ . 'admin' . DS . 'themes' . DS . 'compile' .DS);
// define('SMARTY_ADMIN_VIEWS', _ROOT_DIR_ . 'admin' . DS . 'themes' . DS . 'configs' .DS);