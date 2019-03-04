<?php

class navigationModuleController
{
    public $html;
    public $navigation_model;
    public $categories;

    public function __constructor()
    {

    }

    public function buildCategoryTree()
    {
        $model = new Model();
        $this->navigation_model = $model->getModuleModel('navigation', 'navigation');
        $this->categories = $this->navigation_model->getNavigationCategories();

        if (!empty($this->categories) && count($this->categories) > 0) {
            $category_name = $this->navigation_model->getCategoryNameByController(Url::$url['controller']);
            $this->html = '<div id="navigation">';
            $this->html .= '<div class="panel panel-default">';
            if (!empty($category_name)) {
                $this->html .= '<div class="panel-heading">' . ucfirst($category_name[0]->categories_name) . '</div>';
            } else {
                $this->html .= '<div class="panel-heading"></div>';
            }
            $this->html .= '<div class="panel-body">';
            $this->html .= '<ul class="nav nav-pills nav-stacked">';
            $this->categoryLevelBuild(0);
            $this->html .= '</ul>';
            $this->html .= '</div>';
            $this->html .= '</div>';
            $this->html .= '</div>';
        }
        return $this->html;
    }

    public function categoryLevelBuild(int $parent_id = 0)
    {
        $categories = $this->navigation_model->getCategoryByParent($parent_id);
        foreach ($categories as $category) {
            $subcategories = $this->navigation_model->getCategoryByParent($category->id);
            if ($category->id != 1) {
                $active_category = ($category->categories_controller == Url::$url['controller']);
                $this->html .= '<li ' . ($active_category ? 'class="active"' : '');
                if ($active_category) {

                }
                $this->html .= ($subcategories ? ' class="parent"' : '');
                $this->html .= 'role="presentation"><a target="_self" href="' . $this->buildCategoryLink($category->categories_controller) . '">';
                if (!empty($category->categories_icon) && $category->categories_level == 1) {
                    $this->html .= '<span class="glyphicon ' . $category->categories_icon . '" aria-hidden="true"></span>';
                }
                $this->html .= '<span class="category-name">' . ucfirst($category->categories_name) . '</span>';
                $badges = Alerts::getAlertsCount($category->categories_controller);
                if ($badges[0]['count'] > 0) {
                    $this->html .= '<span class="badge">' . $badges[0]['count'] . '</span>';
                }
                if ($subcategories) {
                    $this->html .= '<span class="caret"></span>';
                    $this->html .= '</a>';
                    $this->html .= '<ul class="nav nav-pills nav-stacked subcategory-nav">';
                    $this->categoryLevelBuild($category->id);
                    $this->html .= '</ul>';
                } else {
                    $this->html .= '</a>';
                    $this->html .= '</li>';
                }
            } else {
                $this->categoryLevelBuild($category->id);
            }
        }
    }

    private function buildCategoryLink(string $categories_controller)
    {
        if (!empty($categories_controller)) {
            return $link = Url::$url['path'] . '?controller=' . $categories_controller . '&action=index';
        }
    }

}
