<?php

class navigationModel
{
    public function __constructor()
    {

    }

    public function getNavigationCategories()
    {
        $db = new Db();
        return $db->select('*')->
            from('admin_categories')->
            orderBy('category_parent_id', 'ASC')->
            execute('object');
    }

    public function getCategoryByParent(int $parent_id)
    {
        $db = new Db();
        return $db->select("*")->
            from("admin_categories")->
            where("category_parent_id = $parent_id")->
            orderBy("category_parent_id", "ASC")->
            execute("object");
    }
}
