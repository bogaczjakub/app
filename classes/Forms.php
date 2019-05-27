<?php

class Forms
{

    public function __construct()
    { }

    public function buildAdminForm(string $table_name, string $form_type, array $columns, array $args)
    {
        if (!empty($table_name) && $form_type == 'settings') {
            array_push(Page::$collection['page_forms'], $this->settingsForm($table_name, $columns, $args));
        }
        if (!empty($table_name) && $form_type == 'table') {
            array_push(Page::$collection['page_forms'], $this->tableForm($table_name, $columns, $args));
        }
        if (!empty($table_name) && $form_type == 'edit') {
            array_push(Page::$collection['page_forms'], $this->editForm($table_name, $columns, $args));
        }
        if (!empty($table_name) && $form_type == 'add') {
            array_push(Page::$collection['page_forms'], $this->addForm($table_name, $columns, $args));
        }
    }

    private function settingsForm(string $table_name, array $columns, array $args)
    {
        $settings = new Settings();
        $table = $settings->getSettingsTable($table_name, $columns, $args);
        $form_action = Url::buildPageUrl('this', 'form');
        if (!empty($table) && !empty($form_action)) {
            $table_name_split = preg_replace('/_/', ' ', $table_name);
            $form_html[key($table)] = '<div class="panel panel-default form-panel">';
            $form_html[key($table)] .= '<div class="panel-heading"><div class="table-name">' . ucfirst($table_name_split) . '</div><div class="window-buttons"><button type="button" class="btn btn-success panel-hide-toggle"><span class="glyphicon glyphicon-minus"></span></button><button type="button" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span></button></div></div>';
            $form_html[key($table)] .= '<div class="panel-body">';
            foreach ($table as $table_key => $forms) {
                $form_html[key($table)] .= '<form id="' . $table_key . '" class="form-horizontal admin-form" action="' . $form_action . '" method="POST" target="_self">';
                foreach ($forms as $setting) {
                    $form_html[key($table)] .= '<div class="form-group">';
                    $form_html[key($table)] .= '<div class="col-xs-12 col-sm-3 admin-form-left"><label for="' . $setting['setting_name'] . '" class="control-label">' . $setting['setting_display_name'] . '</label>';
                    if (!empty($setting['setting_last_update'])) {
                        $form_html[key($table)] .= '<p class="update-info">last update: ' . $setting['setting_last_update'] . '</p>';
                    }
                    $form_html[key($table)] .= '</div>';
                    $form_html[key($table)] .= '<div class="col-xs-12 col-sm-9 admin-form-right">';
                    switch ($setting['setting_type']) {
                        case 'string':
                            $form_html[key($table)] .= '<input type="text" name="' . $table_key . '-' . $setting['setting_name'] . '-' . $setting['setting_type'] . '" value="' . $setting['setting_value'] . '" class="form-control" id="' . $table_key . '-' . $setting['setting_name'] . '" data-input-default="' . $setting['setting_value'] . '">';
                            break;
                        case 'boolean':
                            $form_html[key($table)] .= '<div class="btn-group btn-switch ' . ($setting['setting_value'] == 1 ? 'switch-on' : 'switch-off') . '" role="group" aria-label="">';
                            $form_html[key($table)] .= '<button type="button" class="btn btn-switch-on">On</button>';
                            $form_html[key($table)] .= '<input type="hidden" name="' . $table_key . '-' . $setting['setting_name'] . '-' . $setting['setting_type'] . '" value="' . $setting['setting_value'] . '" class="form-control btn-switch-input" id="' . $table_key . '-' . $setting['setting_name'] . '" data-input-default="' . $setting['setting_value'] . '">';
                            $form_html[key($table)] .= '<button type="button" class="btn btn-switch-off">Off</button>';
                            $form_html[key($table)] .= '</div>';
                            break;
                        case 'select':
                            $form_html[key($table)] .= '<select name="' . $table_key . '-' . $setting['setting_name'] . '-' . $setting['setting_type'] . '" class="form-control" id="' . $table_key . '-' . $setting['setting_name'] . '">';
                            if (!empty($setting['multiple_fields'])) {
                                foreach ($setting['multiple_fields'] as $option) {
                                    $selected = ($option == $setting['setting_value']) ? 'selected' : '';
                                    $form_html[key($table)] .= '<option value="' . $option . '"' . $selected . '>' . $option . '</option>';
                                }
                            }
                            $form_html[key($table)] .= '</select>';
                            break;
                        case 'multiple':
                            $form_html[key($table)] .= '<select multiple name="' . $table_key . '-' . $setting['setting_name'] . '-' . $setting['setting_type'] . '[]" class="form-control" id="' . $table_key . '-' . $setting['setting_name'] . '">';
                            if (!empty($setting['multiple_fields'])) {
                                foreach ($setting['multiple_fields'] as $option) {
                                    $selected = (in_array($option, $setting['setting_value'])) ? 'selected' : '';
                                    $form_html[key($table)] .= '<option value="' . $option . '"' . $selected . '>' . $option . '</option>';
                                }
                            }
                            $form_html[key($table)] .= '</select>';
                            break;
                        case 'checkbox':
                            if (!empty($setting['multiple_fields'])) {
                                $checkbox_array = $table_key . '-checkbox' . '-' . $setting['setting_type'] . '[]';
                                foreach ($setting['multiple_fields'] as $checkbox) {
                                    $checked = (in_array($checkbox, $setting['setting_value'])) ? 'checked' : '';
                                    $form_html[key($table)] .= '<div class="checkbox"><label><input type="checkbox" name="' . $checkbox_array . '" value="' . $checkbox . '"' . $checked . ' id="' . $table_key . '-' . $setting['setting_name'] . '">' . $checkbox . '</label></div>';
                                }
                            }
                            break;
                        case 'radio':
                            if (!empty($setting['multiple_fields'])) {
                                foreach ($setting['multiple_fields'] as $radio) {
                                    $checked = ($radio == $setting['setting_value']) ? 'checked' : '';
                                    $form_html[key($table)] .= '<div class="radio"><label><input type="radio" name="' . $table_key . '-' . $setting['setting_name'] . '-' . $setting['setting_type'] . '" value="' . $radio . '"' . $checked . ' id="' . $table_key . '-' . $setting['setting_name'] . '">' . $radio . '</label></div>';
                                }
                            }
                            break;
                        case 'text':
                            $form_html[key($table)] .= '<textarea name="' . $table_key . '-' . $setting['setting_name'] . '-' . $setting['setting_type'] . '" id="' . $table_key . '-' . $setting['setting_name'] . '" class="form-control extented-textarea" id="' . $table_key . '-' . $setting['setting_name'] . '">' . $setting['setting_value'] . '</textarea>';
                            $form_html[key($table)] .= "<script>CKEDITOR.replace('" . $table_key . '-' . $setting['setting_name'] . "');</script>";
                            break;
                    }
                    if (!empty($setting['setting_information'])) {
                        $form_html[key($table)] .= '<div class="alert alert-info" role="alert"><span class="glyphicon glyphicon-info-sign"></span>' . $setting['setting_information'] . '</div>';
                    }
                    $form_html[key($table)] .= '</div>';
                    $form_html[key($table)] .= '</div>';
                }
            }
            $form_html[key($table)] .= '</div>';
            $form_html[key($table)] .= '<div class="panel-footer clearfix">';
            $form_html[key($table)] .= '<button type="submit" name="' . $table_name . '-form-action[]" value="save" class="btn btn-success form-button-save pull-right"><span class="glyphicon glyphicon-floppy-disk"></span>Save</button>';
            $form_html[key($table)] .= '<button type="submit" name="' . $table_name . '-form-action[]" value="reset" class="btn btn-primary form-button-reset pull-right"><span class="glyphicon glyphicon-repeat"></span>Reset</button>';
            $form_html[key($table)] .= '</form>';
            $form_html[key($table)] .= '</div>';
            $form_html[key($table)] .= '</div>';
            if (!empty($form_html)) {
                return $form_html;
            }
        }
    }

    private function tableForm(string $table_name, array $columns, array $args)
    {
        $tables = new Tables();
        $table = $tables->getTable($table_name, $columns, $args);
        $form_action = Url::buildPageUrl('this', 'form');
        if (!empty($table) && !empty($form_action)) {
            $table_name_split = preg_replace('/_/', ' ', $table_name);
            $form_html[key($table)] = '<div class="panel panel-default form-panel">';
            $form_html[key($table)] .= '<div class="panel-heading"><div class="table-name">' . ucfirst($table_name_split) . '</div><div class="window-buttons"><button type="button" class="btn btn-success panel-hide-toggle"><span class="glyphicon glyphicon-minus"></span></button><button type="button" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span></button></div></div>';
            $form_html[key($table)] .= '<div class="panel-body">';
            foreach ($table as $table_key => $row) {
                $form_html[key($table)] .= '<form id="' . $table_key . '" class="form-horizontal admin-form" action="' . $form_action . '" method="POST" target="_self">';
                $form_html[key($table)] .= '<table class="table table-striped">';
                $form_html[key($table)] .= '<thead><tr>';
                $form_html[key($table)] .= '<th></th>';
                foreach ($row['columns_keys'] as $key) {
                    $real_column_name = Tables::createColumnName($table_name, $key);
                    if (isset($args['sort'][$key]) && !empty($args['sort'][$key])) {
                        if ($args['sort'][$key] == 'asc') {
                            $sort_class = 'asc';
                            $sort_link = Url::buildPageUrl('this', 'index', array('sort' => array($key => 'desc')), true);
                        } elseif ($args['sort'][$key] == 'desc') {
                            $sort_class = 'desc';
                            $sort_link = Url::buildPageUrl('this', 'index', array('sort' => array($key => 'asc')), true);
                        }
                        $form_html[key($table)] .= '<th>' . $real_column_name . '<a href="' . $sort_link . '" target="_self"><div class="sort ' . $sort_class . '"><span class="caret caret-asc"></span><span class="caret caret-desc"></span></div></a></th>';
                    } else {
                        $sort_link = Url::buildPageUrl('this', 'index', array('sort' => array($key => 'desc')), true);
                        $form_html[key($table)] .= '<th>' . $real_column_name . '<a href="' . $sort_link . '" target="_self"><div class="sort"><span class="caret caret-asc"></span><span class="caret caret-desc"></span></div></a></th>';
                    }
                }
                $form_html[key($table)] .= '</tr></thead>';
                $form_html[key($table)] .= '<tbody>';
                foreach ($row['rows'] as $column_name => $column_value) {
                    $form_html[key($table)] .= '<tr>';
                    $form_html[key($table)] .= '<td><div class="checkbox"><label><input type="checkbox" name="' . key($table) . '-items[]" value="' . $column_value['id'] . '" id="' . key($table) . '-item-' . $column_value['id'] . '"></label></div></td>';
                    foreach ($column_value as $value_name => $value) {
                        $is_primary = $tables->getPrimaryKeyName($value_name, $value, $table_name);
                        $form_html[key($table)] .= '<td>' . $value . ($is_primary ? ' - ' . $is_primary : '') . '</td>';
                    }
                    $form_html[key($table)] .= '<tr>';
                }
                $form_html[key($table)] .= '<tbody>';
                $form_html[key($table)] .= '</table>';
            }
            $form_html[key($table)] .= $tables->buildTablePagination($table_name, $args);
            $form_html[key($table)] .= '</div>';
            $form_html[key($table)] .= '<div class="panel-footer clearfix">';
            $form_html[key($table)] .= '<button type="submit" name="' . key($table) . '-form-action[]" value="add" class="btn btn-success form-button-add pull-right"><span class="glyphicon glyphicon-plus"></span>Add</button>';
            $form_html[key($table)] .= '<button type="submit" name="' . key($table) . '-form-action[]" value="edit" class="btn btn-primary form-button-edit pull-right"><span class="glyphicon glyphicon-pencil"></span>Edit</button>';
            $form_html[key($table)] .= '<button type="submit" name="' . key($table) . '-form-action[]" value="remove" class="btn btn-danger form-button-remove pull-right"><span class="glyphicon glyphicon-remove"></span>Remove</button>';
            $form_html[key($table)] .= '</form>';
            $form_html[key($table)] .= '</div>';
        } else {
            $table_name_split = preg_replace('/_/', ' ', $table_name);
            $form_html[$table_name] = '<div class="panel panel-default form-panel">';
            $form_html[$table_name] .= '<div class="panel-heading"><div class="table-name">' . ucfirst($table_name_split) . '</div><div class="window-buttons"><button type="button" class="btn btn-success panel-hide-toggle"><span class="glyphicon glyphicon-minus"></span></button><button type="button" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span></button></div></div>';
            $form_html[$table_name] .= '<div class="panel-body">';
            $form_html[$table_name] .= '<form id="' . $table_name . '" class="form-horizontal admin-form" action="' . $form_action . '" method="POST" target="_self">';
            $form_html[$table_name] .= '<table class="table table-striped">';
            $form_html[$table_name] .= '<thead>';
            $form_html[$table_name] .= '<tr><th>Table is empty</th></tr>';
            $form_html[$table_name] .= '</thead>';
            $form_html[$table_name] .= '<tbody>';
            $form_html[$table_name] .= '<tr><td><h2 class="text-center">Table is empty.</h2></td></tr>';
            $form_html[$table_name] .= '</tbody>';
            $form_html[$table_name] .= '</table>';
            $form_html[$table_name] .= '</div>';
            $form_html[$table_name] .= '<div class="panel-footer clearfix">';
            $form_html[$table_name] .= '<button type="submit" name="' . $table_name . '-form-action[]" value="add" class="btn btn-success form-button-add pull-right"><span class="glyphicon glyphicon-plus"></span>Add</button>';
            $form_html[$table_name] .= '<button type="submit" name="' . $table_name . '-form-action[]" value="refresh" class="btn btn-primary form-button-edit pull-right"><span class="glyphicon glyphicon-refresh"></span>Refresh</button>';
            $form_html[$table_name] .= '</form>';
            $form_html[$table_name] .= '</div>';
        }
        return $form_html;
    }

    private function editForm(string $table_name, array $columns, array $args)
    {
        $tables = new Tables();
        $table = $tables->getTableForEdit($table_name, $columns, $args);
        $form_action = Url::buildPageUrl('this', 'form');
        if (!empty($table) && !empty($form_action)) {
            foreach ($table as $table_key => $forms) {
                foreach ($forms as $form_key => $form) {
                    $form_html[$form_key] = '<div class="panel panel-default form-panel">';
                    $form_html[$form_key] .= '<div class="panel-heading"><div class="table-name">' . $form_key . '</div><div class="window-buttons"><button type="button" class="btn btn-success panel-hide-toggle"><span class="glyphicon glyphicon-minus"></span></button><button type="button" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span></button></div></div>';
                    $form_html[$form_key] .= '<div class="panel-body">';
                    $form_html[$form_key] .= '<form id="' . $table_name . '-item-' . $form_key . '" class="form-horizontal admin-form" action="' . $form_action . '" method="POST" target="_self">';
                    $form_html[$form_key] .= '<input type="hidden" name="' . $table_name . '-item-int" value="' . $form_key . '" class="form-control" id="' . $table_name . '-item-int">';
                    foreach ($form as $setting_key => $setting) {
                        $field_rules = Validation::getFieldRules($setting['setting_name'], $table_name);
                        $is_required = (isset($field_rules[0]['forms_rules_required']) ? $field_rules[0]['forms_rules_required'] : false);
                        $form_html[$form_key] .= '<div class="form-group">';
                        $form_html[$form_key] .= '<div class="col-xs-12 col-sm-3 admin-form-left"><label for="' . $setting['setting_name'] . '" class="control-label">' . Tables::createColumnName($table_name, $setting['setting_name']) . '</label>';
                        if ($is_required) {
                            $form_html[$form_key] .= '<p class="required-field">Required field</p>';
                        }
                        $form_html[$form_key] .= '</div>';
                        $form_html[$form_key] .= '<div class="col-xs-12 col-sm-9 admin-form-right">';
                        switch ($setting['setting_type']) {
                            case 'varchar':
                            case 'smallint':
                            case 'mediumint':
                            case 'int':
                            case 'bigint':
                            case 'float':
                            case 'double':
                            case 'decimal':
                                if ($this->passwordInput($setting['setting_name'], $setting['setting_type'])) {
                                    $form_html[$form_key] .= '<input type="password" name="' . $table_key . '-' . $setting['setting_name'] . '-' . $setting['setting_type'] . '" value="password" class="form-control" id="' . $table_key . '-' . $setting['setting_name'] . '" id="' . $table_key . '-' . $setting['setting_name'] . '-' . $setting['setting_type'] . '" disabled="disabled">';
                                    $form_html[$form_key] .= '<input type="hidden" name="' . $table_key . '-' . $setting['setting_name'] . '-change-' . $setting['setting_type'] . '" value="0" class="form-control change-password" id="' . $table_key . '-' . $setting['setting_name'] . '-change-' . $setting['setting_type'] . '" data-input-default="0">';
                                    $form_html[$form_key] .= '<button type="button" name="' . $table_key . '-' . $setting['setting_name'] . '-button-' . $setting['setting_type'] . '" value="0" class="btn btn-success change-password-button" id="' . $table_key . '-' . $setting['setting_name'] . '-button-' . $setting['setting_type'] . '">Change Password</button>';
                                    $form_html[$form_key] .= '<div class="change-password-container" style="display: none">';
                                    $form_html[$form_key] .= '<div class="input-group"><input type="password" name="' . $table_key . '-' . $setting['setting_name'] . '-old-' . $setting['setting_type'] . '" value="" placeholder="old password" class="form-control" id="' . $table_key . '-' . $setting['setting_name'] . '-old-' . $setting['setting_type'] . '"><div class="input-group-addon"><span class="glyphicon glyphicon-eye-open"></span></div></div>';
                                    $form_html[$form_key] .= '<div class="input-group"><input type="password" name="' . $table_key . '-' . $setting['setting_name'] . '-new-' . $setting['setting_type'] . '" value="" placeholder="new password" class="form-control" id="' . $table_key . '-' . $setting['setting_name'] . '-new-' . $setting['setting_type'] . '"><div class="input-group-addon"><span class="glyphicon glyphicon-eye-open"></span></div></div>';
                                    $form_html[$form_key] .= '<div class="input-group"><input type="password" name="' . $table_key . '-' . $setting['setting_name'] . '-repeat-' . $setting['setting_type'] . '" value="" placeholder="repeat new password" class="form-control" id="' . $table_key . '-' . $setting['setting_name'] . '-repeat-' . $setting['setting_type'] . '"><div class="input-group-addon"><span class="glyphicon glyphicon-eye-open"></span></div></div>';
                                    $form_html[$form_key] .= '</div>';
                                } else {
                                    $form_html[$form_key] .= '<input type="text" name="' . $table_key . '-' . $setting['setting_name'] . '-' . $setting['setting_type'] . '" value="' . $setting['setting_value'] . '" class="form-control" id="' . $table_key . '-' . $setting['setting_name'] . '" data-input-default="' . $setting['setting_value'] . '">';
                                }
                                break;
                            case 'tinyint':
                                $form_html[$form_key] .= '<div class="btn-group btn-switch ' . ($setting['setting_value'] == 1 ? 'switch-on' : 'switch-off') . '" role="group" aria-label="">';
                                $form_html[$form_key] .= '<button type="button" class="btn btn-switch-on">On</button>';
                                $form_html[$form_key] .= '<input type="hidden" name="' . $table_key . '-' . $setting['setting_name'] . '-' . $setting['setting_type'] . '" value="' . $setting['setting_value'] . '" class="form-control btn-switch-input" id="' . $table_key . '-' . $setting['setting_name'] . '-' . $setting['setting_type'] . '" data-input-default="' . $setting['setting_value'] . '">';
                                $form_html[$form_key] .= '<button type="button" class="btn btn-switch-off">Off</button>';
                                $form_html[$form_key] .= '</div>';
                                break;
                            case 'enum':
                                $form_html[$form_key] .= '<select name="' . $table_key . '-' . $setting['setting_name'] . '-' . $setting['setting_type'] . '" class="form-control" id="' . $table_key . '-' . $setting['setting_name'] . '-' . $setting['setting_type'] . '" data-default="' . $setting['setting_value'] . '">';
                                if (!empty($setting['multiple_fields'])) {
                                    foreach ($setting['multiple_fields'] as $option) {
                                        $selected = ($option == $setting['setting_value']) ? 'selected' : '';
                                        $is_primary = $tables->getPrimaryKeyName($setting['setting_name'], $option, $table_name);
                                        $form_html[$form_key] .= '<option value="' . $option . '"' . $selected . '>' . $option . ($is_primary ? ' - ' . $is_primary : '') . '</option>';
                                    }
                                }
                                $form_html[$form_key] .= '</select>';
                                break;
                            case 'set':
                                $form_html[$form_key] .= '<select multiple name="' . $table_key . '-' . $setting['setting_name'] . '-' . $setting['setting_type'] . '[]" class="form-control" id="' . $table_key . '-' . $setting['setting_name'] . '-' . $setting['setting_type'] . '" data-default="' . $imploded_multiple = implode(',', $setting['setting_value']) . '">';
                                if (!empty($setting['multiple_fields'])) {
                                    foreach ($setting['multiple_fields'] as $option) {
                                        $selected = (in_array($option, $setting['setting_value'])) ? 'selected' : '';
                                        $form_html[$form_key] .= '<option value="' . $option . '"' . $selected . '>' . $option . '</option>';
                                    }
                                }
                                $form_html[$form_key] .= '</select>';
                                break;
                            case 'date':
                                $form_html[$form_key] .= '<div class="input-group date datepicker-date" data-provide="datepicker">';
                                $form_html[$form_key] .= '<input type="text" name="' . $table_key . '-' . $setting['setting_name'] . '-' . $setting['setting_type'] . '" value="' . $setting['setting_value'] . '" class="form-control" id="' . $table_key . '-' . $setting['setting_name'] . '-' . $setting['setting_type'] . '" data-input-default="' . $setting['setting_value'] . '">';
                                $form_html[$form_key] .= '<div class="input-group-addon">';
                                $form_html[$form_key] .= '<span class="glyphicon glyphicon-th"></span>';
                                $form_html[$form_key] .= '</div>';
                                $form_html[$form_key] .= '</div>';
                                break;
                            case 'datetime':
                            case 'time':
                            case 'timestamp':
                                $form_html[$form_key] .= '<input type="text" name="' . $table_key . '-' . $setting['setting_name'] . '-' . $setting['setting_type'] . '" value="' . $setting['setting_value'] . '" class="form-control" id="' . $table_key . '-' . $setting['setting_name'] . '" data-input-default="' . $setting['setting_value'] . '" disabled="disabled">';
                                break;
                            case 'tinytext':
                            case 'text':
                            case 'mediumtext':
                            case 'longtext':
                                $form_html[$form_key] .= '<textarea name="' . $table_key . '-' . $setting['setting_name'] . '-' . $setting['setting_type'] . '" id="' . $table_key . '-' . $setting['setting_name'] . '" class="form-control extented-textarea" id="' . $table_key . '-' . $setting['setting_name'] . '">' . $setting['setting_value'] . '</textarea>';
                                if ($setting['setting_type'] == 'longtext') {
                                    $form_html[$form_key] .= "<script>CKEDITOR.replace('" . $table_key . '-' . $setting['setting_name'] . "');</script>";
                                }
                                break;
                            default:
                                break;
                        }
                        if (!empty($setting['setting_information'])) {
                            $form_html[$form_key] .= '<div class="alert alert-info" role="alert"><span class="glyphicon glyphicon-info-sign"></span>' . $setting['setting_information'] . '</div>';
                        }
                        $form_html[$form_key] .= '</div>';
                        $form_html[$form_key] .= '</div>';
                    }
                    $form_html[$form_key] .= '</div>';
                    $form_html[$form_key] .= '<div class="panel-footer clearfix">';
                    $form_html[$form_key] .= '<button type="submit" name="' . $table_name . '-form-action[]" value="update" class="btn btn-success form-button-update pull-right"><span class="glyphicon glyphicon-floppy-disk"></span>Update</button>';
                    $form_html[$form_key] .= '<button type="button" name="' . $table_name . '-form-action[]" class="btn btn-primary form-button-reset pull-right"><span class="glyphicon glyphicon-repeat"></span>Reset</button>';
                    $form_html[$form_key] .= '</form>';
                    $form_html[$form_key] .= '</div>';
                    $form_html[$form_key] .= '</div>';
                }
                if (!empty($form_html)) {
                    return $form_html;
                }
            }
        }
    }

    private function addForm(string $table_name, array $columns, array $args)
    {
        $tables = new Tables();
        $table = $tables->getTableForAdd($table_name, $columns, $args);
        $form_action = Url::buildPageUrl('this', 'form');
        if (!empty($table) && !empty($form_action)) {
            foreach ($table as $table_key => $forms) {
                $form_html['new'] = '<div class="panel panel-default form-panel">';
                $form_html['new'] .= '<div class="panel-heading"><div class="table-name">New</div><div class="window-buttons"><button type="button" class="btn btn-success panel-hide-toggle"><span class="glyphicon glyphicon-minus"></span></button><button type="button" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span></button></div></div>';
                $form_html['new'] .= '<div class="panel-body">';
                $form_html['new'] .= '<form id="' . $table_name . '-add" class="form-horizontal admin-form" action="' . $form_action . '" method="POST" target="_self">';
                foreach ($forms as $setting_key => $setting) {
                    $field_rules = Validation::getFieldRules($setting['setting_name'], $table_name);
                    $is_required = (isset($field_rules[0]['forms_rules_required']) ? $field_rules[0]['forms_rules_required'] : false);
                    $form_html['new'] .= '<div class="form-group">';
                    $form_html['new'] .= '<div class="col-xs-12 col-sm-3 admin-form-left"><label for="' . $setting['setting_name'] . '" class="control-label">' . Tables::createColumnName($table_name, $setting['setting_name']) . '</label>';
                    if ($is_required) {
                        $form_html['new'] .= '<p class="required-field">Required field</p>';
                    }
                    $form_html['new'] .= '</div>';
                    $form_html['new'] .= '<div class="col-xs-12 col-sm-9 admin-form-right">';
                    switch ($setting['setting_type']) {
                        case 'varchar':
                        case 'smallint':
                        case 'mediumint':
                        case 'int':
                        case 'bigint':
                        case 'float':
                        case 'double':
                        case 'decimal':
                            if ($this->passwordInput($setting['setting_name'], $setting['setting_type'])) {
                                $form_html['new'] .= '<div class="input-group"><input type="password" name="' . $table_key . '-' . $setting['setting_name'] . '-new-' . $setting['setting_type'] . '" value="" placeholder="password" class="form-control ' . ($is_required ? 'required-field' : '') . '" id="' . $table_key . '-' . $setting['setting_name'] . '-new-' . $setting['setting_type'] . '"><div class="input-group-addon"><span class="glyphicon glyphicon-eye-open"></span></div></div>';
                                $form_html['new'] .= '<div class="input-group"><input type="password" name="' . $table_key . '-' . $setting['setting_name'] . '-repeat-' . $setting['setting_type'] . '" value="" placeholder="repeat password" class="form-control ' . ($is_required ? 'required-field' : '') . '" id="' . $table_key . '-' . $setting['setting_name'] . '-repeat-' . $setting['setting_type'] . '"><div class="input-group-addon"><span class="glyphicon glyphicon-eye-open"></span></div></div>';
                            } else {
                                $form_html['new'] .= '<input type="text" name="' . $table_key . '-' . $setting['setting_name'] . '-' . $setting['setting_type'] . '" value="" class="form-control ' . ($is_required ? 'required-field' : '') . '" id="' . $table_key . '-' . $setting['setting_name'] . '-' . $setting['setting_type'] . '">';
                            }
                            break;
                        case 'tinyint':
                            $form_html['new'] .= '<div class="btn-group btn-switch switch-off" role="group" aria-label="">';
                            $form_html['new'] .= '<button type="button" class="btn btn-switch-on">On</button>';
                            $form_html['new'] .= '<input type="hidden" name="' . $table_key . '-' . $setting['setting_name'] . '-' . $setting['setting_type'] . '" value="0" class="form-control btn-switch-input ' . ($is_required ? 'required-field' : '') . '" id="' . $table_key . '-' . $setting['setting_name'] . '-' . $setting['setting_type'] . '">';
                            $form_html['new'] .= '<button type="button" class="btn btn-switch-off">Off</button>';
                            $form_html['new'] .= '</div>';
                            break;
                        case 'enum':
                            $form_html['new'] .= '<select name="' . $table_key . '-' . $setting['setting_name'] . '-' . $setting['setting_type'] . '" class="form-control ' . ($is_required ? 'required-field' : '') . '" id="' . $table_key . '-' . $setting['setting_name'] . '-' . $setting['setting_type'] . '">';
                            if (!empty($setting['multiple_fields'])) {
                                foreach ($setting['multiple_fields'] as $option) {
                                    $is_primary = $tables->getPrimaryKeyName($setting['setting_name'], $option, $table_name);
                                    $form_html['new'] .= '<option value="' . $option . '">' . $option . ($is_primary ? ' - ' . $is_primary : '') . '</option>';
                                }
                            }
                            $form_html['new'] .= '</select>';
                            break;
                        case 'set':
                            $form_html['new'] .= '<select multiple name="' . $table_key . '-' . $setting['setting_name'] . '-' . $setting['setting_type'] . '[]" class="form-control ' . ($is_required ? 'required-field' : '') . '" id="' . $table_key . '-' . $setting['setting_name'] . '-' . $setting['setting_type'] . '">';
                            if (!empty($setting['multiple_fields'])) {
                                foreach ($setting['multiple_fields'] as $option) {
                                    $form_html['new'] .= '<option value="' . $option . '">' . $option . '</option>';
                                }
                            }
                            $form_html['new'] .= '</select>';
                            break;
                        case 'date':
                            $form_html['new'] .= '<div class="input-group date datepicker-date" data-provide="datepicker">';
                            $form_html['new'] .= '<input type="text" name="' . $table_key . '-' . $setting['setting_name'] . '-' . $setting['setting_type'] . '" value="" class="form-control ' . ($is_required ? 'required-field' : '') . '" id="' . $table_key . '-' . $setting['setting_name'] . '-' . $setting['setting_type'] . '">';
                            $form_html['new'] .= '<div class="input-group-addon">';
                            $form_html['new'] .= '<span class="glyphicon glyphicon-th"></span>';
                            $form_html['new'] .= '</div>';
                            $form_html['new'] .= '</div>';
                            break;
                        case 'datetime':
                        case 'time':
                        case 'timestamp':
                            $form_html['new'] .= '<input type="text" name="' . $table_key . '-' . $setting['setting_name'] . '-' . $setting['setting_type'] . '" value="" class="form-control ' . ($is_required ? 'required-field' : '') . '" id="' . $table_key . '-' . $setting['setting_name'] . '-' . $setting['setting_type'] . '" disabled="disabled">';
                            break;
                        case 'tinytext':
                        case 'text':
                        case 'mediumtext':
                        case 'longtext':
                            $form_html['new'] .= '<textarea name="' . $table_key . '-' . $setting['setting_name'] . '-' . $setting['setting_type'] . '" id="' . $table_key . '-' . $setting['setting_name'] . '" class="form-control extented-textarea" id="' . $table_key . '-' . $setting['setting_name'] . '"></textarea>';
                            if ($setting['setting_type'] == 'longtext') {
                                $form_html['new'] .= "<script>CKEDITOR.replace('" . $table_key . '-' . $setting['setting_name'] . "');</script>";
                            }
                            break;
                        default:
                            break;
                    }
                    if (!empty($setting['setting_information'])) {
                        $form_html['new'] .= '<div class="alert alert-info" role="alert"><span class="glyphicon glyphicon-info-sign"></span>' . $setting['setting_information'] . '</div>';
                    }
                    $form_html['new'] .= '</div>';
                    $form_html['new'] .= '</div>';
                }
                $form_html['new'] .= '</div>';
                $form_html['new'] .= '<div class="panel-footer clearfix">';
                $form_html['new'] .= '<button type="submit" name="' . $table_name . '-form-action[]" value="save" class="btn btn-success form-button-save pull-right"><span class="glyphicon glyphicon-floppy-disk"></span>Save</button>';
                $form_html['new'] .= '<button type="button" name="' . $table_name . '-form-action[]" class="btn btn-primary form-button-reset pull-right"><span class="glyphicon glyphicon-repeat"></span>Reset</button>';
                $form_html['new'] .= '</form>';
                $form_html['new'] .= '</div>';
                $form_html['new'] .= '</div>';
                if (!empty($form_html)) {
                    return $form_html;
                }
            }
        }
    }

    public function formHandler(string $form_name, string $form_type, array $form_args, string $form_action)
    {
        if (!empty($form_name) && !empty($form_args) && $form_type == 'settings') {
            $settings = new Settings();
            return $settings->changeSettings($form_name, $form_args);
        }
        if (!empty($form_name) && !empty($form_args) && $form_type == 'table') {
            $tables = new Tables();
            return $tables->changeTable($form_name, $form_args, $form_action);
        }
    }

    private function passwordInput(string $input_name, string $input_type)
    {
        if (!empty($input_name) && !empty($input_type)) {
            $split_name = explode('_', $input_name);
            if (end($split_name) == 'password' && $input_type == 'varchar') {
                return true;
            }
        }
        return false;
    }
}
