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
            'privileges_enum'
        ), $args);
        $this->display('admin_users');
    }

    public function form(array $args)
    {
        print_r($args);

    }
    public function edit(array $args)
    {

    }

    public function delete(array $args)
    {

    }
}