<?php

class navigationModuleModel
{
    public function __constructor()
    {

    }

    public function getNavigationCategories()
    {
        $db = new Db();
        return $db->select('*')->
            from('admin_categories')->
            orderBy('categories_parent_id', 'ASC')->
            execute('object');
    }

    public function getCategoryByParent(int $parent_id)
    {
        $db = new Db();
        return $db->select("*")->
            from("admin_categories")->
            where("categories_parent_id = $parent_id")->
            orderBy("categories_parent_id", "ASC")->
            execute("object");
    }

    public function getCategoryNameByController(string $controller_name)
    {
        if (!empty($controller_name)) {
            $db = new Db();
            return $db->select("categories_name")->
                from("admin_categories")->
                where("categories_controller = '$controller_name'")->
                execute("object");
        }
    }
}
