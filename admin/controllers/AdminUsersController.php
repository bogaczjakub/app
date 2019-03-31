<?php

class AdminUsersController extends Page
{
    public function __construct()
    {

    }

    public function index(array $args)
    {
        $forms = new Forms();
        $forms->buildAdminForm('admin_users', 'table', array(
            'id',
            'users_login',
            'users_firstname',
            'users_lastname',
            'users_create_date',
            'users_birthday',
            'users_email',
            'users_info',
            'privileges_id',
            'privileges_enum',
            'settest',
            'tinyinttest',
        ), $args);
        $this->display('admin_users');
    }

    public function form(array $args)
    {
        if (isset($args['admin_users-form-action']) && !empty($args['admin_users-form-action'])) {
            switch ($args['admin_users-form-action'][0]) {
                case 'remove':
                    $this->remove($args);
                    break;
                case 'add':
                    $this->add($args);
                    break;
                case 'save':
                    $this->save($args);
                    break;
                case 'edit':
                    $this->edit($args);
                    break;
                case 'update':
                    $this->update($args);
                    break;
                default:
            }
        }
    }

    private function add(array $args)
    {
        $forms = new Forms();
        $forms->buildAdminForm('admin_users', 'add', array(
            'users_login',
            'users_firstname',
            'users_lastname',
            'users_birthday',
            'users_password',
            'users_email',
            'users_info',
            'privileges_id',
            'privileges_enum',
            'settest',
            'tinyinttest',
        ), $args);
        $this->display('user_add');
    }

    private function edit(array $args)
    {
        if (isset($args['admin_users-items']) && !empty($args['admin_users-items'])) {
            $forms = new Forms();
            $forms->buildAdminForm('admin_users', 'edit', array(
                'users_login',
                'users_firstname',
                'users_lastname',
                'users_create_date',
                'users_birthday',
                'users_password',
                'users_email',
                'users_info',
                'privileges_id',
                'privileges_enum',
                'settest',
                'tinyinttest',
            ), $args);
            $this->display('user_edit');
        } else {
            $alerts = new Alerts();
            $alerts->newAlert('warning', 'Admin users', 'At least one element must be selected.', 'AdminUsers');
            Url::redirectUrl('AdminUsers', 'index', array());
        }
    }

    private function remove(array $args)
    {
        if (isset($args) && !empty($args)) {
            $forms = new Forms();
            $alerts = new Alerts();
            $check = $forms->formHandler('admin_users', 'table', $args, 'remove');
            if ($check) {
                $alerts->newAlert('success', 'Admin users', 'Item(s) deleted successfully.', 'AdminUsers');
            } else {
                $alerts->newAlert('danger', 'Admin users', 'Could not delete selected item(s).', 'AdminUsers');
            }
        }
        Url::redirectUrl('AdminUsers', 'index', array());
    }

    private function update(array $args)
    {
        if (isset($args) && !empty($args)) {
            $forms = new Forms();
            $alerts = new Alerts();
            $check = $forms->formHandler('admin_users', 'table', $args, 'update');
            if ($check) {
                $alerts->newAlert('success', 'Admin users', 'Item(s) updated successfully.', 'AdminUsers');
            } else {
                $alerts->newAlert('danger', 'Admin users', 'Could not update selected item(s).', 'AdminUsers');
            }
        }
        Url::redirectUrl('AdminUsers', 'index', array());
    }

    private function save(array $args)
    {
        if (isset($args) && !empty($args)) {
            $forms = new Forms();
            $alerts = new Alerts();
            $check = $forms->formHandler('admin_users', 'table', $args, 'save');
            if ($check) {
                $alerts->newAlert('success', 'Admin users', 'Item(s) added successfully.', 'AdminUsers');
            } else {
                $alerts->newAlert('danger', 'Admin users', 'Could not update selected item(s).', 'AdminUsers');
            }
        }
        Url::redirectUrl('AdminUsers', 'index', array());
    }
}
