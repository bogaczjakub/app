<?php

class ModuleSettingsController extends Page
{
    public function __construct()
    { }

    public function index(array $args)
    {
        $forms = new Forms();
        $table_name = 'module_' . $args['module_name'] . '_settings';
        $forms->buildAdminForm($table_name, 'module_settings', array(), $args);
        $this->display('module_settings');
    }

    public function form(array $args)
    {
        if (isset($args['module_footer_settings-form-action']) && !empty($args['module_footer_settings-form-action'])) {
            switch ($args['module_footer_settings-form-action'][0]) {
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
        $check = $forms->formHandler('module_footer_settings', 'module_settings', $args, 'update');
        if ($check) {
            $alerts = new Alerts();
            $alerts->newAlert('success', 'Footer settings', 'Table updated successfully.', 'ModuleSettings');
        }
        Url::redirectUrl('ModuleSettings', 'index', array('module_name' => 'footer'));
    }
}
