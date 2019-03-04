<?php

class Tables
{

    public function __construct()
    {

    }

    public function getTable(string $table_name, array $columns, array $args)
    {
        $db = new Db();
        $new_table[$table_name] = array();
        if (!empty($columns)) {
            $select = implode(',', $columns);
        } else {
            $select = '*';
        }
        $require_table = $db->select($select)->
            from($table_name)->
            execute("assoc");
        if (!empty($require_table)) {
            $index = 0;
            foreach ($require_table as $key => $row) {
                $index_increase = $index++;
                foreach ($row as $column_name => $value) {
                    $exploded_name = explode('_', $column_name);
                    $exploded_table_name = explode('_', $table_name);
                    $name_differences = array_diff($exploded_name, $exploded_table_name);
                    $new_column_name = implode(' ', $name_differences);
                    $new_table[$table_name]['rows'][$index_increase][$new_column_name] = $value;
                }
            }
        }
        if (!empty($new_table)) {
            $array_keys = array_keys($new_table[$table_name]['rows'][0]);
            $new_table[$table_name]['columns_keys'] = $array_keys;
        }
        return $new_table;
    }

    public function buildTablePagination(array $args)
    {
        $settings = new Settings();
        if (isset($args['page']) && empty($args['page'])) {

        }
        // $row_limit = $settings->getSettingValue();
        // print_r($args);
        $pagination_html = '';
        $pagination_html .= '<nav aria-label=""><ul class="pagination pagination-md">';
        $pagination_html .= '<li><a href="" aria-label="Previous"><span aria-hidden="true">«</span></a></li>';
        $pagination_html .= '<li><a href="">1</a></li>';
        $pagination_html .= '<li class="active"><a href="">2 <span class="sr-only">(current)</span></a></li>';
        $pagination_html .= '<li><a href="">3</a></li>';
        $pagination_html .= '<li><a href="">4</a></li>';
        $pagination_html .= '<li><a href="" aria-label="Next"><span aria-hidden="true">»</span></a></li>';
        $pagination_html .= '</ul></nav>';
        return $pagination_html;
    }

    public function changeTable()
    {

    }
}
