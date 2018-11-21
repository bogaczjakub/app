<?php

$config = array(
    'current_inc_dir' => '',
    'reserved_classes' => array(
        'Db',
        'Helper',
        'Settings',
        'Tools',
        'CustomException',
        'Core',
        'Controller',
        'Url',
        'SmartyApp',
        'AdminController',
        'FrontController'
    ),
    'include_paths' => array(
        'admin' => array(
            $_SERVER['DOCUMENT_ROOT'] . '/admin' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR,
            $_SERVER['DOCUMENT_ROOT'] . '/admin' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR),
        'front' => array(
            $_SERVER['DOCUMENT_ROOT'] . '/front' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR,
            $_SERVER['DOCUMENT_ROOT'] . '/front' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR),
        'global' => array(
            $_SERVER['DOCUMENT_ROOT'] . '/classes' . DIRECTORY_SEPARATOR,
            $_SERVER['DOCUMENT_ROOT'] . '/controllers' . DIRECTORY_SEPARATOR,
            $_SERVER['DOCUMENT_ROOT'] . '/libs' . DIRECTORY_SEPARATOR,
            $_SERVER['DOCUMENT_ROOT'] . '/libs/smarty' . DIRECTORY_SEPARATOR),
    ),
    'global_libs' => array(
        'jquery',
        'bootstrap'
    )
);
