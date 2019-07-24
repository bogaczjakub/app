<?php

class ModuleSettingsController extends Page
{
    private $table_name = '';
    public function __construct()
    { }

    public function index(array $args)
    {
        $forms = new Forms();
        $this->table_name = 'module_' . $args['module_name'] . '_settings';
        $forms->buildAdminForm($this->table_name, 'module_settings', array(), $args);
        $this->assignData(array('module_key' => $this->table_name));
        $this->display('module_settings');
    }

    public function form(array $args)
    {
        $form_action = $this->table_name . '-form-action';
        if (isset($args[$form_action]) && !empty($args[$form_action])) {
            switch ($args[$form_action][0]) {
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
