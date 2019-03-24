<?php

class Tables
{

    public function __construct()
    {

    }

    public function getTable(string $table_name, array $columns, array $args)
    {
        $settings = new Settings();
        $new_table[$table_name] = array();
        $pages = $this->countTablePages($settings->getSettingValue($settings->getAdminSettings(), 'table_row_limit'), self::getTableCount($table_name));
        $limit = $this->getQueryLimit($args, $settings->getSettingValue($settings->getAdminSettings(), 'table_row_limit'), $pages);
        $order = $this->getQueryOrder($args);
        $required_table = $this->prepareTableUpdateQuery($table_name, $limit, $order, $this->getQuerySelect($columns));
        if (!empty($required_table)) {
            $index = 0;
            foreach ($required_table as $key => $row) {
                $index_increase = $index++;
                foreach ($row as $column_name => $value) {
                    $new_table[$table_name]['rows'][$index_increase][$column_name] = $value;
                }
            }
        }
        if (!empty($new_table)) {
            $array_keys = array_keys($new_table[$table_name]['rows'][0]);
            $new_table[$table_name]['columns_keys'] = $array_keys;
        }
        return $new_table;
    }

    public function getTableForEdit(string $table_name, array $columns, array $args)
    {
        $table_columns = self::getTableColumnNames($table_name);
        $selected = $this->getTableRows($table_name, $args);
        $new_table = array();
        foreach ($selected as $select) {
            foreach ($select as $field_name => $value) {
                $check = array_search(strtolower($field_name), $columns);
                if (is_numeric($check)) {
                    $column_key = array_search($field_name, array_column($table_columns, 'Field'));
                    $single_type = ($table_columns[$column_key]['Type'] == 'datetime' || $table_columns[$column_key]['Type'] == 'timestamp' || $table_columns[$column_key]['Type'] == 'time' || $table_columns[$column_key]['Type'] == 'date') ? 1 : 0;
                    if (!$single_type) {
                        $column_type = substr($table_columns[$column_key]['Type'], 0, strpos($table_columns[$column_key]['Type'], '('));
                    }
                    $new_table[$table_name][$select['id']][$field_name]['setting_name'] = $table_columns[$column_key]['Field'];
                    if ($single_type) {
                        $new_table[$table_name][$select['id']][$field_name]['setting_type'] = $table_columns[$column_key]['Type'];
                    } else {
                        $new_table[$table_name][$select['id']][$field_name]['setting_type'] = $column_type;
                        if ($column_type == 'enum' || $column_type == 'set') {
                            if ($column_type == 'enum') {
                                $values = preg_match('/^enum\(\'(.*)\'\)$/', $table_columns[$column_key]['Type'], $matches);
                            } else if ($column_type == 'set') {
                                $values = preg_match('/^set\(\'(.*)\'\)$/', $table_columns[$column_key]['Type'], $matches);
                            }
                            if ($values) {
                                $exploded = explode("','", $matches[1]);
                                $new_table[$table_name][$select['id']][$field_name]['multiple_fields'] = $exploded;
                            }
                        }
                    }
                    if ($column_type != 'set') {
                        $new_table[$table_name][$select['id']][$field_name]['setting_value'] = $value;
                    } else {
                        $exploded = explode(',', $value);
                        if (!empty($exploded)) {
                            $new_table[$table_name][$select['id']][$field_name]['setting_value'] = $exploded;
                        }
                    }
                    $new_table[$table_name][$select['id']][$field_name]['setting_information'] = $table_columns[$column_key]['Comment'];
                }
            }
        }
        return $new_table;
    }

    public function getTableForAdd()
    {

    }

    public function buildTablePagination(string $table_name, array $args)
    {
        $settings = new Settings();
        $row_limit = $settings->getSettingValue($settings->getAdminSettings(), 'table_row_limit');
        $row_total = self::getTableCount('admin_users');
        $pages = ceil((int) $row_total[0]['count'] / (int) $row_limit);
        $pagination_html = '';
        $pagination_html .= '<nav aria-label="table pagination"><ul class="pagination pagination-md">';
        if ((isset($args['page']) && !empty($args['page']) && $args['page'] == 1) || (isset($args['page']) && !empty($args['page']) && $args['page'] == 0) || (!isset($args['page']) || empty($args['page']) || $pages == 1)) {
            $pagination_html .= '<li class="disabled"><span aria-hidden="true">«</span></li>';
        } else {
            if (isset($args['page']) && !empty($args['page']) && ($args['page'] - 1 > 0)) {
                $offset = (isset($args['page']) && !empty($args['page']) && $args['page'] > $pages) ? ($pages - 1) : ($args['page'] - 1);
                $previous_link = Url::buildPageUrl('this', 'index', array('page' => $offset), true);
                $pagination_html .= '<li><a href="' . $previous_link . '" aria-label="Previous" target="_self"><span aria-hidden="true">«</span></a></li>';
            }
        }
        for ($i = 1; $i < (int) $pages + 1; $i++) {
            $page_link = Url::buildPageUrl('this', 'index', array('page' => $i), true);
            if ((empty($args['page']) || !isset($args['page']) || $args['page'] == 0) && ($i == 1 && $pages >= 1) || (isset($args['page']) && !empty($args['page']) && $args['page'] == $i) || (isset($args['page']) && !empty($args['page']) && $args['page'] > $pages && $i == $pages)) {
                $pagination_html .= '<li class="active"><a href="' . $page_link . '" target="_self">' . $i . '<span class="sr-only"></span></a></li>';
            } else {
                $pagination_html .= '<li><a href="' . $page_link . '" target="_self">' . $i . '</a></li>';
            }
        }
        if ((isset($args['page']) && !empty($args['page']) && $args['page'] == $pages) || $pages <= 1 || (isset($args['page']) && !empty($args['page']) && $args['page'] > $pages)) {
            $pagination_html .= '<li class="disabled"><span aria-hidden="true">»</span></li>';
        } else {
            if (((isset($args['page']) && !empty($args['page']) && $args['page'] + 1 <= $pages)) || (!isset($args['page']) || empty($args['page']) || $args['page'] == 0) && $pages > 1) {
                $offset = ($pages > 1 && (!isset($args['page']) || $args['page'] == 0)) ? 2 : ($args['page'] + 1);
                $next_link = Url::buildPageUrl('this', 'index', array('page' => $offset), true);
                $pagination_html .= '<li><a href="' . $next_link . '" aria-label="Next" target="_self"><span aria-hidden="true">»</span></a></li>';
            }
        }
        $pagination_html .= '</ul></nav>';
        return $pagination_html;
    }

    public static function getTableCount(string $table_name)
    {
        if (!empty($table_name)) {
            $db = new Db();
            return $db->select("COUNT(*) as count")->
                from("{$table_name}")->
                execute("assoc");
        }
    }

    public static function getTableColumnNames(string $table_name)
    {
        if (!empty($table_name)) {
            $db = new Db();
            return $db->customQuery("SHOW FULL COLUMNS FROM {$table_name}")->
                execute("assoc");
        }
    }

    public static function createColumnName(string $table_name, string $column_name)
    {
        $exploded_name = explode('_', $column_name);
        $exploded_table_name = explode('_', $table_name);
        $name_differences = array_diff($exploded_name, $exploded_table_name);
        $new_column_name = implode(' ', $name_differences);
        return $new_column_name;
    }

    private function prepareTableUpdateQuery(string $table_name, string $limit, string $order, string $select)
    {
        $db = new Db();
        return $db->select("{$select}")->
            from("{$table_name}")->
            orderby("{$order}")->
            limit("{$limit}")->
            execute("assoc");
    }

    private function prepareTableRemoveQuery(string $table_name, string $where_query)
    {
        $db = new Db();
        return $db->delete()->
            from("{$table_name}")->
            where("{$where_query}")->
            execute('bool');
    }

    private function prepareUpdateQuery(string $table_name, string $where, string $set)
    {
        $db = new Db();
        return $db->update("{$table_name}")->
            set("{$set}")->
            where("{$where}")->
            execute("print");
    }

    private function getQuerySelect(array $columns)
    {
        $select = '';
        if (!empty($columns)) {
            $select = implode(',', $columns);
        } else {
            $select = '*';
        }
        return $select;
    }

    private function getQueryLimit(array $args, string $row_limit, $pages)
    {
        $limit = '0, ' . $row_limit;
        if (isset($args['page']) && !empty($args['page']) && ($args['page'] <= $pages) && ($args['page'] > 1)) {
            $limit = ($args['page'] - 1) * $row_limit . ', ' . $row_limit;
        } elseif (isset($args['page']) && !empty($args['page']) && $args['page'] > $pages) {
            $limit = ($pages - 1) * $row_limit . ', ' . $row_limit;
        }
        return $limit;
    }

    private function getQuerySet(array $columns, string $table_name, array $ids)
    {
        if (isset($columns['change-password']['value']) &&
            !empty($columns['change-password']['value']) &&
            $columns['change-password']['value'] == 1 &&
            isset($columns['change-password-old']['value']) &&
            !empty($columns['change-password-old']['value']) &&
            isset($columns['change-password-new']['value']) &&
            !empty($columns['change-password-new']['value']) &&
            isset($columns['change-password-repeat']['value']) &&
            !empty($columns['change-password-repeat']['value'])) {
            if (array_sum($this->confirmPassword($columns['change-password-old']['value'], $ids, $table_name))) {
                if ($columns['change-password-new'] === $columns['change-password-repeat']) {
                    echo 'ok!';
                }
            }
        }
        $set_string = '';
        foreach ($columns as $column_name => $column) {
            if ($column['type'] != 'set') {

                $set_string .= $column_name . "='" . $column['value'] . "' ";
            } else {
                $imploded = implode(',', $column['value']);
                $set_string .= $column_name . "='" . $imploded . "' ";
            }
        }
        return $set_string;
    }

    private function getQueryOrder(array $args)
    {
        $orderby = '';
        if (isset($args['sort']) && !empty($args['sort'])) {
            $counter = 0;
            $sort_array_count = count($args['sort']);
            foreach ($args['sort'] as $sort => $direction) {
                $orderby .= $sort . ' ' . strtoupper($direction);
                if (++$counter != $sort_array_count) {
                    $orderby .= ',';
                }
            }
        } else {
            $orderby = 'id ASC';
        }
        return $orderby;
    }

    private function getQueryWhere(array $ids, string $counts)
    {
        if (is_array($ids)) {
            $where_query = 'id IN (';
            $counter = 0;
            if (!empty($ids)) {
                foreach ($ids as $id) {
                    $where_query .= $id;
                    if ($counter != $counts) {
                        $where_query .= ', ';
                    } else {
                        $where_query .= ')';
                    }
                    $counter++;
                }
                return $where_query;
            } else {
                return false;
            }
        } else {
            return $where_query = "id='" . $ids . "'";
        }
    }

    private function getTableRows(string $table_name, array $args)
    {
        $items_name = $table_name . '-items';
        if (isset($args[$items_name]) && !empty($args[$items_name])) {
            $count = (count($args[$items_name]) - 1);
            $where = $this->getQueryWhere($args[$items_name], $count);
            if ($where) {
                $db = new Db();
                return $db->select("*")->
                    from("{$table_name}")->
                    where("{$where}")->
                    execute("assoc");
            }
        }
    }

    private function countTablePages(string $row_limit, array $row_total)
    {
        $pages = ceil((int) $row_total[0]['count'] / (int) $row_limit);
        return $pages;
    }

    private function confirmPassword(string $password, array $ids, string $table_name)
    {
        $db = new Db();
        $table_all_field = $this->getTableColumnNames($table_name);
        $password_field = '';
        if (!empty($table_all_field)) {
            $table_password_field = preg_grep('/_password$/', array_column($table_all_field, 'Field'));
            $first_password_field = reset($table_password_field);
            if ($first_password_field) {
                $password_field = $first_password_field;
            }
        }
        $results = [];
        foreach ($ids as $id) {
            $current_password = $db->select("{$password_field}")->
                from("{$table_name}")->
                where("id='{$id}'")->
                execute("assoc");
            if (md5($password) === $current_password[0][$password_field]) {
                $results =array($id => true);
            }
        }
        return $results;
    }

    public function changeTable(string $table_name, array $args, string $form_action)
    {
        if (!empty($form_action)) {
            switch ($form_action) {
                case 'remove':
                    $items_name = $table_name . '-items';
                    if (isset($args[$items_name]) && !empty($args[$items_name])) {
                        $count = (count($args[$items_name]) - 1);
                        $where = $this->getQueryWhere(Tools::protector($table_name, 'remove', $args[$items_name]), $count);
                        if ($where) {
                            return $this->prepareTableRemoveQuery($table_name, $where);
                        } else {
                            return array();
                        }
                    }
                    break;
                case 'edit':
                    if (isset($args[$items_name]) && !empty($args[$items_name])) {

                    }
                    break;
                case 'add':
                    break;
                case 'save':
                    $new_named_array = array();
                    $items = '';
                    foreach ($args as $key => $value) {
                        $field_name_cut = str_replace($table_name . '-', '', $key);
                        $exploded_field_name = explode('-', $field_name_cut);
                        $field_type = end($exploded_field_name);
                        $real_field_name = preg_replace('/(-varchar|-int|-tinyint|-smallint|-mediumint|-bigint|-double|-decimal|-tinytext|-text|-mediumtext|-longtext|-enum|-set|-date|-time|-timestamp|-datetime)/', '', $field_name_cut);
                        if ($real_field_name != 'form-action') {
                            $new_named_array[$real_field_name]['value'] = $value;
                            $new_named_array[$real_field_name]['type'] = $field_type;
                        }
                    }
                    if (isset($new_named_array['item']) && !empty($new_named_array['item'])) {
                        $items = [];
                        array_push($items, $new_named_array['item']['value']);
                        unset($new_named_array['item']);
                    }
                    if (!empty($items)) {
                        $count = (count($items) - 1);
                        $where = $this->getQueryWhere(Tools::protector($table_name, 'update', $items), $count);
                        $set = $this->getQuerySet($new_named_array, $table_name, $items);
                        return $this->prepareUpdateQuery($table_name, $where, $set);
                    }
                    break;
                default:
            }
        }
    }
}
