<?php

class FrontSettingsController extends Page
{
    public function __construct()
    {
    }

    public function index(array $args)
    {
        $forms = new Forms();
        $forms->buildAdminForm('front_settings', 'settings', array(), $args);
        $this->display('front_settings');
    }

    public function form(array $args)
    {
        if (isset($args['front_settings-form-action']) && !empty($args['front_settings-form-action'])) {
            switch ($args['front_settings-form-action'][0]) {
                case 'save':
                    $this->save($args);
                    break;
                default:
            }
        }
    }

    public function save(array $args)
    {
        $forms = new Forms();
        $check = $forms->formHandler('front_settings', 'settings', $args, 'update');
        if ($check) {
            $alerts = new Alerts();
            $alerts->newAlert('success', 'Front settings', 'Table updated successfully.', 'FrontSettings');
        }
        Url::redirectUrl('FrontSettings', 'index', array());

    }
}