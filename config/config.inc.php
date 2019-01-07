<?php

$config = array(
    'page' => array('theme' => '', 'type' => ''),
    'reserved_classes' => array(
        'Db',
        'Helper',
        'Settings',
        'Tools',
        'Dispatcher',
        'Page',
        'Url',
        'CustomException',
        'Model',
        'SmartyApp',
        'AdminController',
        'FrontController',
        'Collector',
        'Module',
        'Alert',
        'Breadcrumbs'
    ),
    'modules' => array(
        'navigation',
    ),
    'include_paths' => array(
        'admin' => array(
            $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR,
            $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR,
            $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR),
        'front' => array(
            $_SERVER['DOCUMENT_ROOT'] . '/front' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR,
            $_SERVER['DOCUMENT_ROOT'] . '/front' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR,
            $_SERVER['DOCUMENT_ROOT'] . '/front' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR),
        'global' => array(
            $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR,
            $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR,
            $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'libs' . DIRECTORY_SEPARATOR,
            $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'libs/smarty' . DIRECTORY_SEPARATOR),
        'modules' => array(
            $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR
        )
    ),
    'global_libs' => array(
        'front' => array(),
        'admin' => array(),
        'all' => array(
            'jquery' => array(
                'dist/jquery.min.js'
            ),
            'bootstrap' => array(
                // 'assets' . DIRECTORY_SEPARATOR . 'javascripts' . DIRECTORY_SEPARATOR . 'bootstrap.min.js',
                'assets' . DIRECTORY_SEPARATOR . 'javascripts' . DIRECTORY_SEPARATOR . 'bootstrap' . DIRECTORY_SEPARATOR . '*',
            ),
        ),
    ),
);
