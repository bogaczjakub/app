<?php

class AdminUsersController extends Page
{
    public function __construct()
    { }

    public function index(array $args)
    {
        $forms = new Forms();
        $forms->buildAdminForm('admin_users', 'table', array(
            'id',
            'login',
            'firstname',
            'lastname',
            'create_date',
            'birthday',
            'email',
            'info',
            'privilege_id',
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
            'login',
            'firstname',
            'lastname',
            'birthday',
            'password',
            'email',
            'info',
            'privilege_id',
        ), $args);
        $this->display('user_add');
    }

    private function edit(array $args)
    {
        if (isset($args['admin_users-items']) && !empty($args['admin_users-items'])) {
            $forms = new Forms();
            $forms->buildAdminForm('admin_users', 'edit', array(
                'login',
                'firstname',
                'lastname',
                'birthday',
                'password',
                'email',
                'info',
                'privilege_id',
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
        if (isset($args['admin_users-items']) && !empty($args['admin_users-items'])) {
            $forms = new Forms();
            $alerts = new Alerts();
            $raport = $forms->formHandler('admin_users', 'table', $args, 'remove');
            if ($raport['status']) {
                if ($raport['query_status']) {
                    $alerts->newPredefinedAlert('table', 'remove', 'success', 'AdminUsers');
                } else {
                    $alerts->newPredefinedAlert('table', 'remove', 'danger', 'AdminUsers');
                }
            } else {
                $alerts->newAlertFromRaport('AdminUsers', $raport);
            }
        } else {
            $alerts = new Alerts();
            $alerts->newAlert('warning', 'Admin users', 'At least one element must be selected.', 'AdminUsers');
            Url::redirectUrl('AdminUsers', 'index', array());
        }
        Url::redirectUrl('AdminUsers', 'index', array());
    }

    private function update(array $args)
    {
        if (isset($args) && !empty($args)) {
            $forms = new Forms();
            $alerts = new Alerts();
            $raport = $forms->formHandler('admin_users', 'table', $args, 'update');
            if ($raport['status']) {
                if ($raport['query_status']) {
                    $alerts->newPredefinedAlert('table', 'update', 'success', 'AdminUsers');
                } else {
                    $alerts->newPredefinedAlert('table', 'update', 'danger', 'AdminUsers');
                }
            } else {
                $alerts->newAlertFromRaport('AdminUsers', $raport);
            }
        }
        Url::redirectUrl('AdminUsers', 'index', array());
    }

    private function save(array $args)
    {
        if (isset($args) && !empty($args)) {
            $forms = new Forms();
            $alerts = new Alerts();
            $raport = $forms->formHandler('admin_users', 'table', $args, 'save');
            if ($raport['status']) {
                if ($raport['query_status']) {
                    $alerts->newPredefinedAlert('table', 'save', 'success', 'AdminUsers');
                } else {
                    $alerts->newPredefinedAlert('table', 'save', 'danger', 'AdminUsers');
                }
            } else {
                $alerts->newAlertFromRaport('AdminUsers', $raport);
            }
        }
        Url::redirectUrl('AdminUsers', 'index', array());
    }
}
