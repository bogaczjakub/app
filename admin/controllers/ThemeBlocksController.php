<?php

class ThemeBlocksController extends Page
{
    public function __construct()
    {}

    public function index(array $args)
    {
        $forms = new Forms();
        $forms->buildAdminForm('front_theme_blocks', 'table', array(
            'id',
            'theme_blocks_name',
            'theme_blocks_display_name',
            'theme_blocks_last_update',
            'theme_blocks_information',
            'theme_blocks_active',
            'theme_blocks_content',
            'theme_blocks_styles',
            'gaps_id',
        ), $args);
        $this->display('theme_blocks');
    }

    public function form(array $args)
    {
        if (isset($args['front_theme_blocks-form-action']) && !empty($args['front_theme_blocks-form-action'])) {
            switch ($args['front_theme_blocks-form-action'][0]) {
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
        $forms->buildAdminForm('front_theme_blocks', 'add', array(
            'theme_blocks_name',
            'theme_blocks_display_name',
            'theme_blocks_information',
            'theme_blocks_active',
            'theme_blocks_content',
            'theme_blocks_styles',
            'gaps_id',
        ), $args);
        $this->display('theme_blocks_add');
    }

    private function edit(array $args)
    {
        if (isset($args['front_theme_blocks-items']) && !empty($args['front_theme_blocks-items'])) {
            $forms = new Forms();
            $forms->buildAdminForm('front_theme_blocks', 'edit', array(
                'theme_blocks_name',
                'theme_blocks_display_name',
                'theme_blocks_last_update',
                'theme_blocks_information',
                'theme_blocks_active',
                'theme_blocks_content',
                'theme_blocks_styles',
                'gaps_id',
            ), $args);
            $this->display('theme_blocks_edit');
        } else {
            $alerts = new Alerts();
            $alerts->newAlert('warning', 'Front theme blocks', 'At least one element must be selected.', 'ThemeBlocks');
            Url::redirectUrl('ThemeBlocks', 'index', array());
        }
    }

    private function remove(array $args)
    {
        if (isset($args) && !empty($args)) {
            $forms = new Forms();
            $alerts = new Alerts();
            $raport = $forms->formHandler('front_theme_blocks', 'table', $args, 'remove');
            if ($raport['status']) {
                if ($raport['query_status']) {
                    $alerts->newPredefinedAlert('table', 'remove', 'success', 'ThemeBlocks');
                } else {
                    $alerts->newPredefinedAlert('table', 'remove', 'danger', 'ThemeBlocks');
                }
            } else {
                $alerts->newAlertFromRaport('ThemeBlocks', $raport);
            }
        }
        Url::redirectUrl('ThemeBlocks', 'index', array());
    }

    private function update(array $args)
    {
        if (isset($args) && !empty($args)) {
            $forms = new Forms();
            $alerts = new Alerts();
            $raport = $forms->formHandler('front_theme_blocks', 'table', $args, 'update');
            if ($raport['status']) {
                if ($raport['query_status']) {
                    $alerts->newPredefinedAlert('table', 'update', 'success', 'ThemeBlocks');
                } else {
                    $alerts->newPredefinedAlert('table', 'update', 'danger', 'ThemeBlocks');
                }
            } else {
                $alerts->newAlertFromRaport('ThemeBlocks', $raport);
            }
        }
        Url::redirectUrl('ThemeBlocks', 'index', array());
    }

    private function save(array $args)
    {
        if (isset($args) && !empty($args)) {
            $forms = new Forms();
            $alerts = new Alerts();
            $raport = $forms->formHandler('front_theme_blocks', 'table', $args, 'save');
            if ($raport['status']) {
                if ($raport['query_status']) {
                    $alerts->newPredefinedAlert('table', 'save', 'success', 'ThemeBlocks');
                } else {
                    $alerts->newPredefinedAlert('table', 'save', 'danger', 'ThemeBlocks');
                }
            } else {
                $alerts->newAlertFromRaport('ThemeBlocks', $raport);
            }
        }
        Url::redirectUrl('ThemeBlocks', 'index', array());
    }
}
