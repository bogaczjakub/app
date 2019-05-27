<?php

class Validation
{
    public $validation_raport = array(
        'action_type' => '',
        'status' => 1,
        'failures' => 0,
        'query_status' => '',
        'failure_fields' => array(),
        'failure_action_message' => '',
        'validated_data' => array(),
    );

    public function __construct()
    { }

    public function validateForm(string $table_name, array $form_fields = null, array $ids = null, string $form_action = null)
    {
        $this->validation_raport['action_type'] = $form_action;
        if ($form_action == 'update' || $form_action == 'save') {
            $form_fields = $this->passwordFieldCorrector($table_name, $form_fields, $ids, $form_action);
            if (!empty($table_name) && !empty($form_fields)) {
                foreach ($form_fields as $field_key => $field) {
                    if ($field_key != $this->getTablePasswordField($table_name)) {
                        $field_rules = $this->getFieldRules($field_key, $table_name);
                        $field_name = Tables::createColumnName($table_name, $field_key);
                        if (isset($field_rules[0]['id'])) {
                            if ($field_rules[0]['forms_rules_required'] == 1 && empty($field['value'])) {
                                $this->validation_raport['failure_fields'][$field_name] = array('requirement_failure' => 1, 'failure_message' => $field_rules[0]['forms_rules_requirement_failure_message']);
                                $this->validation_raport['failures'] += 1;
                            }
                            if (!empty($field_rules[0]['forms_rules_validation_pattern']) && !empty($field['value']) && !preg_match($field_rules[0]['forms_rules_validation_pattern'], $field['value'])) {
                                $this->validation_raport['failure_fields'][$field_name] = array('pattern_failure' => 1, 'failure_message' => $field_rules[0]['forms_rules_pattern_failure_message']);
                                $this->validation_raport['failures'] += 1;
                            }
                        }
                    }
                }
            }
        } else if ($form_action == 'remove') {
            if (isset($ids) && !empty($ids)) {
                $remove_protected = self::protector($table_name, $form_action, $ids);
                if (!empty($remove_protected)) {
                    $form_fields = $remove_protected;
                } else {
                    $static_alert = Alerts::getstaticAlert('table', 'remove', 'danger');
                    $this->validation_raport['failure_action_message'] = $static_alert[0]['alerts_static_message'];
                    $this->validation_raport['failures'] += 1;
                }
            } else {
                $static_alert = Alerts::getstaticAlert('table', 'remove', 'danger');
                $this->validation_raport['failure_action_message'] = $static_alert[0]['alerts_static_message'];
                $this->validation_raport['failures'] += 1;
            }
        }
        if ($this->validation_raport['failures'] > 0) {
            $this->validation_raport['status'] = 0;
        }
        $this->validation_raport['validated_data'] = $form_fields;
        return $this->validation_raport;
    }

    private function passwordFieldCorrector(string $table_name, array $fields_array, array $id = null, $form_action)
    {
        $password_field = $this->getTablePasswordField($table_name);
        if ($password_field) {
            $password_field_real_name = Tables::createColumnName($table_name, $password_field);
            $field_rules = $this->getFieldRules($password_field, $table_name);
        }
        if (
            isset($fields_array[$password_field . '-change']['value']) &&
            $fields_array[$password_field . '-change']['value'] == 1 &&
            !empty($fields_array[$password_field . '-old']['value']) &&
            !empty($fields_array[$password_field . '-new']['value']) &&
            !empty($fields_array[$password_field . '-repeat']['value'])
        ) {
            if (array_sum($this->confirmPassword($fields_array[$password_field . '-old']['value'], $id, $table_name))) {
                if ($fields_array[$password_field . '-new']['value'] === $fields_array[$password_field . '-repeat']['value']) {
                    if (!empty($field_rules[0]['forms_rules_validation_pattern']) && !empty($fields_array[$password_field . '-new']['value']) && !preg_match($field_rules[0]['forms_rules_validation_pattern'], $fields_array[$password_field . '-new']['value'])) {
                        $this->validation_raport['failure_fields'][$password_field] = array('requirement_failure' => 1, 'failure_message' => $field_rules[0]['forms_rules_requirement_failure_message']);
                    } else {
                        $fields_array[$password_field] = array('value' => md5($fields_array[$password_field . '-new']['value']), 'type' => 'varchar');
                    }
                } else {
                    $static_alert = Alerts::getstaticAlert('password', 'typing', 'danger');
                    $this->validation_raport['failure_fields'][$password_field_real_name] = array('pattern_failure' => 1, 'failure_message' => $static_alert[0]['alerts_static_message']);
                    $this->validation_raport['failures'] += 1;
                }
            } else {
                $static_alert = Alerts::getstaticAlert('password', 'match', 'danger');
                $this->validation_raport['failure_fields'][$password_field_real_name] = array('pattern_failure' => 1, 'failure_message' => $static_alert[0]['alerts_static_message']);
                $this->validation_raport['failures'] += 1;
            }
        } else if (
            !empty($fields_array[$password_field . '-new']['value']) &&
            !empty($fields_array[$password_field . '-repeat']['value'])
        ) {
            if ($fields_array[$password_field . '-new']['value'] === $fields_array[$password_field . '-repeat']['value']) {
                if (!empty($field_rules[0]['forms_rules_validation_pattern']) && !empty($fields_array[$password_field . '-new']['value']) && !preg_match($field_rules[0]['forms_rules_validation_pattern'], $fields_array[$password_field . '-new']['value'])) {
                    $this->validation_raport['failure_fields'][$password_field] = array('requirement_failure' => 1, 'failure_message' => $field_rules[0]['forms_rules_requirement_failure_message']);
                } else {
                    $fields_array[$password_field] = array('value' => md5($fields_array[$password_field . '-new']['value']), 'type' => 'varchar');
                }
                unset($fields_array[$password_field . '-repeat']);
                unset($fields_array[$password_field . '-new']);
            } else {
                $this->validation_raport['failure_fields'][$password_field_real_name] = array('pattern_failure' => 1, 'failure_message' => 'New password and repeat password does not match.');
                $this->validation_raport['failures'] += 1;
            }
        } else if (!empty($field_rules[0]['forms_rules_required']) && $field_rules[0]['forms_rules_required'] == 1 && $form_action != 'update') {
            $this->validation_raport['failure_fields'][$password_field_real_name] = array('requirement_failure' => 1, 'failure_message' => $field_rules[0]['forms_rules_requirement_failure_message']);
            $this->validation_raport['failures'] += 1;
        }
        unset($fields_array[$password_field . '-old']);
        unset($fields_array[$password_field . '-new']);
        unset($fields_array[$password_field . '-repeat']);
        unset($fields_array[$password_field . '-change']);
        return $fields_array;
    }

    private function confirmPassword(string $password, array $id, string $table_name)
    {
        $db = new Db();
        $table_all_field = Tables::getTableColumnNames($table_name);
        $password_field = $this->getTablePasswordField($table_name);
        if ($password_field) {
            $results = [];
            $current_password = $db->select("{$password_field}")->from("{$table_name}")->where("id='{$id[0]}'")->execute("assoc");
            if (md5($password) === $current_password[0][$password_field]) {
                $results = array($id[0] => true);
            }
            return $results;
        }
    }

    private function getTablePasswordField(string $table_name)
    {
        $db = new Db();
        $table_all_field = Tables::getTableColumnNames($table_name);
        $password_field = '';
        if (!empty($table_all_field)) {
            $table_password_field = preg_grep('/_password$/', array_column($table_all_field, 'Field'));
            $first_password_field = reset($table_password_field);
            if (!empty($first_password_field)) {
                $password_field = $first_password_field;
            }
        }
        if (!empty($password_field)) {
            return $password_field;
        }
    }

    public static function protector(string $table_name, string $forbidden_action, array $ids)
    {
        if (!empty($table_name) && !empty($forbidden_action)) {
            $db = new Db();
            $protected = $db->select("protected_data_row_id")->from("system_protected_data")->where("protected_data_forbidden_action='{$forbidden_action}' AND protected_data_table_name='{$table_name}'")->execute("assoc");
            if (!empty($protected) && !empty($ids)) {
                return array_diff($ids, array_column($protected, 'protected_data_row_id'));
            } else if (!empty($ids)) {
                return $ids;
            }
        }
    }

    public static function getFieldRules(string $field_name, string $table_name)
    {
        $db = new Db();
        return $db->select("*")->from("system_forms_rules")->where("forms_rules_table_name='{$table_name}' AND forms_rules_field_name='{$field_name}'")->execute("assoc");
    }
}
