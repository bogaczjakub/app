<?php

class Core extends Controller
{
    private $main_controller;
    private $global_settings;

    public function __construct()
    {
        $this->Settings = new Settings();
        $this->global_settings = $this->Settings->getGlobalSettings();
        parent::$global_pages_details = $this->Settings->fillGlobalDetails($this->global_settings);
    }

    public function appInit($page)
    {
        global $config;
        try {
            if ($page == 'admin' && file_exists(GLOBAL_CONTROLLERS_DIR . ucfirst($page) . 'Controller.php') && !empty(GLOBAL_CONTROLLERS_DIR . ucfirst($page) . 'Controller.php')) {
                $config['current_inc_dir'] = 'admin';
                $this->main_controller = new AdminController($this->Settings->getAdminSettings(), $page);
            } elseif ($page == 'front' && file_exists(GLOBAL_CONTROLLERS_DIR . ucfirst($page) . 'Controller.php') && !empty(GLOBAL_CONTROLLERS_DIR . ucfirst($page) . 'Controller.php')) {
                $config['current_inc_dir'] = 'front';
                $this->main_controller = new FrontController($this->Settings->getFrontSettings(), $page);
            } else {
                throw new CustomException('Could not find ' . ucfirst($page) . ' controller.');
            }
        } catch (customException $e) {
            echo $e->getCustomMessage($e);
            exit();
        }
    }
}
