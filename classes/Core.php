<?php

class Core
{
    private $main_controller;

    public function __construct()
    {
        $this->Settings = new Settings();
        $this->global_settings = $this->Settings->getGlobalSettings();
    }

    public function appInit($page)
    {
        global $config;
        try {
            if($page == 'Admin' && file_exists(ADMIN_CONTROLLERS . $page . 'Controller.php') && !empty(ADMIN_CONTROLLERS . $page . 'Controller.php')) {
                $config['current_inc_dir'] = 'admin';
                $this->main_controller = new AdminController($this->Settings->getAdminSettings());
            } elseif ($page == 'Front' && file_exists(FRONT_CONTROLLERS . $page . 'Controller.php') && !empty(FRONT_CONTROLLERS . $page . 'Controller.php')) {
                $config['current_inc_dir'] = 'front';
                $this->main_controller = new FrontController($this->Settings->getFrontSettings());
            } else {
                throw new CustomException('Could not find ' . $page . ' controller.');
            }
        } catch (customException $e) {
            echo $e->getCustomMessage($e);
            exit(); 
        }
    }
}
