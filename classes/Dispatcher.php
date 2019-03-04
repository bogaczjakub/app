<?php

class dispatcher
{
    public $settings;
    private $main_controller;

    public function __construct(object $collector)
    {
        $this->settings = new Settings();
        $collector->globalDetails($this->settings->fillGlobalDetails($this->settings->getGlobalSettings()));
        $this->appInit($collector);
    }

    public function appInit(object $collector)
    {
        global $config;
        try {
            if ($collector->collection['type'] == 'admin' && file_exists(GLOBAL_CONTROLLERS_DIR . ucfirst($collector->collection['type']) . 'Controller.php') && !empty(GLOBAL_CONTROLLERS_DIR . ucfirst($collector->collection['type']) . 'Controller.php')) {
                $config['page']['type'] = $collector->collection['type'];
                $this->main_controller = new AdminController($collector);
            } elseif ($collector->collection['type'] == 'front' && file_exists(GLOBAL_CONTROLLERS_DIR . ucfirst($collector->collection['type']) . 'Controller.php') && !empty(GLOBAL_CONTROLLERS_DIR . ucfirst($collector->collection['type']) . 'Controller.php')) {
                $config['page']['type'] = $collector->collection['type'];
                $this->main_controller = new FrontController($collector);
            } else {
                throw new CustomException('Could not find ' . ucfirst($collector->collection['type']) . ' controller.');
            }
        } catch (customException $e) {
            echo $e->getCustomMessage($e);
            exit();
        }
    }
}
