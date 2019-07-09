<?php

class BlocksAssociationsController extends Page
{
    public function __construct()
    { }

    public function index(array $args)
    {
        $forms = new Forms();
        $forms->buildAdminForm('front_blocks_associations', 'table', array(
            'id',
            'page_id',
            'block_id',
            'gap_id'
        ), $args);
        $this->display('blocks_associations');
    }

    public function form(array $args)
    {
        if (isset($args['front_blocks_associations-form-action']) && !empty($args['front_blocks_associations-form-action'])) {
            switch ($args['front_blocks_associations-form-action'][0]) {
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
        $forms->buildAdminForm('front_blocks_associations', 'add', array(
            'page_id',
            'block_id',
            'gap_id'
        ), $args);
        $this->display('blocks_associations_add');
    }

    private function edit(array $args)
    {
        if (isset($args['front_blocks_associations-items']) && !empty($args['front_blocks_associations-items'])) {
            $forms = new Forms();
            $forms->buildAdminForm('front_blocks_associations', 'edit', array(
                'page_id',
                'block_id',
                'gap_id'
            ), $args);
            $this->display('blocks_associations_add');
        } else {
            $alerts = new Alerts();
            $alerts->newAlert('warning', 'Front blocks associations', 'At least one element must be selected.', 'BlocksAssociations');
            Url::redirectUrl('BlocksAssociations', 'index', array());
        }
    }

    private function remove(array $args)
    {
        if (isset($args['front_blocks_associations-items']) && !empty($args['front_blocks_associations-items'])) {
            $forms = new Forms();
            $alerts = new Alerts();
            $raport = $forms->formHandler('front_blocks_associations', 'table', $args, 'remove');
            if ($raport['status']) {
                if ($raport['query_status']) {
                    $alerts->newPredefinedAlert('table', 'remove', 'success', 'BlocksAssociations');
                } else {
                    $alerts->newPredefinedAlert('table', 'remove', 'danger', 'BlocksAssociations');
                }
            } else {
                $alerts->newAlertFromRaport('BlocksAssociations', $raport);
            }
        } else {
            $alerts = new Alerts();
            $alerts->newAlert('warning', 'Front blocks associations', 'At least one element must be selected.', 'BlocksAssociations');
            Url::redirectUrl('BlocksAssociations', 'index', array());
        }
        Url::redirectUrl('BlocksAssociations', 'index', array());
    }

    private function update(array $args)
    {
        if (isset($args) && !empty($args)) {
            $forms = new Forms();
            $alerts = new Alerts();
            $raport = $forms->formHandler('front_blocks_associations', 'table', $args, 'update');
            if ($raport['status']) {
                if ($raport['query_status']) {
                    $alerts->newPredefinedAlert('table', 'update', 'success', 'BlocksAssociations');
                } else {
                    $alerts->newPredefinedAlert('table', 'update', 'danger', 'BlocksAssociations');
                }
            } else {
                $alerts->newAlertFromRaport('BlocksAssociations', $raport);
            }
        }
        Url::redirectUrl('BlocksAssociations', 'index', array());
    }

    private function save(array $args)
    {
        if (isset($args) && !empty($args)) {
            $forms = new Forms();
            $alerts = new Alerts();
            $raport = $forms->formHandler('front_blocks_associations', 'table', $args, 'save');
            if ($raport['status']) {
                if ($raport['query_status']) {
                    $alerts->newPredefinedAlert('table', 'save', 'success', 'BlocksAssociations');
                } else {
                    $alerts->newPredefinedAlert('table', 'save', 'danger', 'BlocksAssociations');
                }
            } else {
                $alerts->newAlertFromRaport('BlocksAssociations', $raport);
            }
        }
        Url::redirectUrl('BlocksAssociations', 'index', array());
    }
}
