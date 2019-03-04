<?php

class FrontSettingsController extends Page
{
    public function __construct()
    {
    }

    public function index(array $args)
    {
        $forms = new Forms();
        $forms->buildAdminForm('front_settings', 'settings', array());
        $this->display('front_settings');
    }

    public function form(array $args)
    {
        $forms = new Forms();
        $check = $forms->formHandler('front_settings', 'settings', $args);
        if (empty($check)) {
            $alerts = new Alerts();
            $alerts->newAlert('success', 'Front settings', 'Table updated successfully.', 'FrontSettings');
        }
        Url::redirectUrl('FrontSettings', 'index', array());
    }
}