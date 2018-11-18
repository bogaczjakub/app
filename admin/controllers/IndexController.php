<?php

class IndexController extends AdminController
{
    private $args;
    private $action;

    public function __construct($_query)
    {
        array_key_exists('action', $_query) ? $this->action = $_query['action'] : $this->action = 'index';
        array_key_exists('args', $_query) ? $this->args = $_query['args'] : $this->args = '';
        $methods = get_class_methods(__CLASS__);
        if (!empty($this->action)) {
            foreach ($methods as $method) {
                if ($method == $this->action) {
                    self::$method($this->args);
                    break;
                }
            }
        }
    }

    public function index($_args)
    {
        $this->assingVars();
        $this->render('index');
    }
}
