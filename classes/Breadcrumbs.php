<?php

class Breadcrumbs
{

    public $breadcrumbs_temporary_array = array();
    public $breadcrumbs_array = array();

    public function __constructor()
    { }

    public function build(string $controller, string $type)
    {
        $type_select = (!empty($type) && $type == 'front') ? 'front_categories' : 'admin_categories';
        if (!empty($controller)) {
            $db = new Db();
            $results = $db->select("*")->from("{$type_select}")->where("controller='{$controller}'")->execute("object");
            if (!empty($results)) {
                array_push($this->breadcrumbs_temporary_array, $results[0]);
                $this->collectBreadcrumbs($results[0]->parent_id);
                if (!empty($this->breadcrumbs_temporary_array)) {
                    return $this->buildBreadcrumbsArray($this->breadcrumbs_temporary_array);
                }
            }
        }
    }

    public function collectBreadcrumbs(int $parent_id)
    {
        $results = $this->getCategoryById($parent_id);
        if (!empty($results)) {
            if ($results[0]->parent_id != 0) {
                array_push($this->breadcrumbs_temporary_array, $results[0]);
                $this->collectBreadcrumbs($results[0]->parent_id);
            } else {
                return false;
            }
        }
    }

    private function getCategoryById(int $parent_id)
    {
        $type_select = (!empty($type) && $type == 'front') ? 'front_categories' : 'admin_categories';
        $db = new Db();
        return $db->select("*")->from("{$type_select}")->where("id='{$parent_id}'")->execute("object");
    }

    private function buildBreadcrumbsArray($collection)
    {
        if (!empty($collection)) {
            $home = array(
                'category_id' => '1',
                'category_name' => 'home',
                'category_controller' => 'Index',
                'category_uri' => $_SERVER['REQUEST_URI'] . '?controller=Index' . '&action=index'
            );
            foreach ($collection as $pack) {
                $temporary_array['category_id'] = (int)$pack->id;
                $temporary_array['category_name'] = $pack->name;
                $temporary_array['category_controller'] = $pack->controller;
                $temporary_array['category_uri'] = $_SERVER['REQUEST_URI'] . '?controller=' . $pack->controller . '&action=index';
                array_push($this->breadcrumbs_array, $temporary_array);
            }
            usort($this->breadcrumbs_array, function ($a, $b) {
                return $a['category_id'] <=> $a['category_id'];
            });
            array_push($this->breadcrumbs_array, $home);
            return array_reverse($this->breadcrumbs_array);
        }
    }
}
