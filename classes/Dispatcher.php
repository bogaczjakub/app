<?php

class dispatcher
{
    private $settings;
    private $main_controller;

    public function __construct()
    {
        $this->settings = new Settings();
        Page::$global_pages_details = $this->settings->fillGlobalDetails($this->settings->getGlobalSettings());
    }

    public function appInit($page)
    {
        global $config;
        try {
            if ($page == 'admin' && file_exists(GLOBAL_CONTROLLERS_DIR . ucfirst($page) . 'Controller.php') && !empty(GLOBAL_CONTROLLERS_DIR . ucfirst($page) . 'Controller.php')) {
                $config['page']['type'] = 'admin';
                Page::$type = $page;
                $this->main_controller = new AdminController($this->settings->getAdminSettings());
            } elseif ($page == 'front' && file_exists(GLOBAL_CONTROLLERS_DIR . ucfirst($page) . 'Controller.php') && !empty(GLOBAL_CONTROLLERS_DIR . ucfirst($page) . 'Controller.php')) {
                $config['page']['type'] = 'front';
                Page::$type = $page;
                $this->main_controller = new FrontController($this->settings->getFrontSettings());
            } else {
                throw new CustomException('Could not find ' . ucfirst($page) . ' controller.');
            }
        } catch (customException $e) {
            echo $e->getCustomMessage($e);
            exit();
        }
    }
}
