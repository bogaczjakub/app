<?php

class navigationModuleModel
{
    public function __constructor()
    { }

    public function getNavigationCategories()
    {
        $db = new Db();
        return $db->select('*')->from("front_categories")->orderBy("parent_id", "ASC")->execute("object");
    }

    public function getCategoryByParent(int $parent_id)
    {
        $db = new Db();
        return $db->select("*")->from("front_categories")->where("parent_id={$parent_id}")->orderBy("parent_id", "ASC")->execute("object");
    }

    public function getCategoryParent(string $child_id)
    {
        $db = new Db();
        return $db->select("parent_id")->from("front_categories")->where("id={$child_id}")->execute("assoc");
    }

    public function getCategoryNameByController(string $controller_name)
    {
        if (!empty($controller_name)) {
            $db = new Db();
            return $db->select("name")->from("front_categories")->where("controller='{$controller_name}'")->execute("object");
        }
    }

    public function categoryAllowedToDisplay(string $category_id)
    {
        $db = new Db();
        return $db->select("display")->from("front_categories")->where("id={$category_id}")->execute("assoc");
    }
}
